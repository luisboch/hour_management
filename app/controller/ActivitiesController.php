<?php

require_once SERVICE_DIR . 'ActivityService.php';
require_once SERVICE_DIR . 'CustomerService.php';
require_once SERVICE_DIR . 'ActivityTypeService.php';
require_once SERVICE_DIR . 'UserService.php';
require_once SERVICE_DIR . 'utils/DateUtils.php';

/**
 * Description of ActivitiesController
 *
 * @author luis
 * @since Jan 13, 2014
 * @property ActivityService $service
 */
class ActivitiesController extends CrudBase {

    /**
     * @var ActivityTypeService
     */
    private $activityTypeService;

    /**
     *
     * @var CustomerService
     */
    private $customerService;

    /**
     *
     * @var UserService
     */
    private $userService;

    /**
     *
     * @var Customer[]
     */
    private $customers;
    private $status;

    public function initialize() {
        parent::initialize(new ActivityService());
        $this->activityTypeService = new ActivityTypeService();
        $this->userService = new UserService();
        $this->customerService = new CustomerService();

        $this->customers = $this->customerService->search();
        $this->view->customers = $this->customers;
        $status = 'open';
        $this->setTitle('Atividades');
    }

    public function viewAction($id) {
        parent::viewAction($id);
        $this->view->types = $this->activityTypeService->search(array(), true, null, null);
        $this->view->users = $this->userService->search(array(), true, null, null);
    }

    public function indexAction() {

        $page1 = $this->request->getQuery("page1");

        $page1 = $page1 === null ? 1 : $page1;

        $page2 = $this->request->getQuery("page2");

        $page2 = $page2 === null ? 1 : $page2;


        $params1 = array('user' => $this->session->getUser());

        $limitPerPage = 10;

        $this->view->results1 = $this->service->search(
                $params1, true, $limitPerPage, $page1 * $limitPerPage - $limitPerPage);

        $totalResults1 = $this->service->searchCount($params1, true);

        $params2 = array('user' => null);

        $this->view->results2 = $this->service->search(
                $params2, true, $limitPerPage, $page2 * $limitPerPage - $limitPerPage);

        $totalResults2 = $this->service->searchCount($params2, true);

        $this->view->pagination1 = $this->createPagination($page1, $totalResults1, 'page1');
        $this->view->pagination2 = $this->createPagination($page2, $totalResults2, 'page2');
    }

    private function createPagination($page1, $total, $pageParamName) {
        $pagination = new Pagination();
        $pagination->setAmountLinkShow($this->config['pagination']['number_of_links_displayed']);
        $pagination->setCurrentPage($page1);
        $pagination->setAmountPerPage(10);
        $pagination->setTargetUrl($this->url->get($this->controllerName . '/index'));
        $pagination->setAmountRegisters($total);
        $pagination->setQueryString($this->getQueryString($pageParamName));
        $pagination->setPageParamName($pageParamName);
        return $pagination;
    }

    public function searchAction($page = 1) {
        parent::searchAction($page);
        $this->view->types = $this->activityTypeService->search(array(), true, null, null);
        $this->view->users = $this->userService->search(array(), true, null, null);
    }

    public function removeAction($activityId, $actionId) {
        try {
            $activity = $this->service->findById($activityId);

            $ia = $activity->getInteractionById($actionId);
            if ($ia->getUser()->getId() === $this->session->getUser()->getId()) {
                $activity->removeInteraction($actionId);
                $this->service->save($activity);
                $this->success("Ação removida");
            } else {
                $this->warn("Somente o próprio usuário pode excluir a ação!");
            }

            $this->response->redirect('activities/view/' . $activityId);
        } catch (Exception $ex) {
            $this->showError($ex);
        }
    }

    public function finishAction($activityId, $actionId) {
        try {
            if ($actionId == '') {
                $actionId = $this->request->getPost('action_id');
            }

            if ($actionId == null) {
                $this->warn("Não é possível finalizar a atividade sem referencia!");
                $this->response->redirect('activities/view/' . $activityId);
                return;
            }

            $activity = $this->service->findById($activityId);

            $endDate = $this->request->getPost('finish_end_time');
            if ($endDate == '') {
                $endDate = new DateTime();
            } else {
                $endDate = new DateTime($endDate);
            }

            /* @var Activity $activity */
            $action = $activity->getInteractionById($actionId);
            if ($action->getUser()->getId() === $this->session->getUser()->getId()) {
                $action->setEndDate($endDate);
                $this->service->save($activity);

                $this->success("Ação concluída");
            } else {
                $this->warn("Somente o próprio usuário pode alterar a ação!");
            }
            $this->response->redirect('activities/view/' . $activityId);
        } catch (Exception $ex) {
            $this->showError($ex);
        }
    }

    public function startAction($activityId) {
        $activity = $this->service->findById($activityId);
        $user = $this->userService->findById($this->session->getUser()->getId());

        try {
            $this->service->startInteraction($activity, $user);
            $this->success("Atividade iniciada");
            $this->response->redirect('activities/view/' . $activityId);
        } catch (ValidationException $ex) {
            $this->warn($ex->getMessage());
            $this->response->redirect('activities/view/' . $activityId);
        } catch (Exception $ex) {
            $this->showError($ex);
        }
    }

    protected function createNewInstance() {
        return new Activity();
    }

    protected function getSearchParams() {

        $filters = array();

        // User filter
        $userId = $this->request->getQuery('user');

        // Is searching?
        if ($userId !== NULL) {
            // Yes, then get active status
            $active = $this->request->getQuery('active');
            $this->showActiveResults = $active === 'on';
            $this->view->active = $this->showActiveResults;
        }

        if ($userId != '') {
            
            if ($userId === 'none') {
                $filters['user'] = '';
            } else if ($userId === 'all') {
                $filters['user'] = $userId;
            } else {
                $filters['user'] = $this->userService->findById($userId);
            }
            
            $this->view->user_id = $userId;
        }

        // Type filter
        $typeId = $this->request->getQuery('type');
        if ($typeId != '') {
            $filters['type'] = $typeId;
            $this->view->type = $typeId;
        }

        $customerId = $this->request->getQuery('customer_id');

        if ($customerId != '') {
            $filters['customer'] = $customerId;
            $this->view->customer_id = $customerId;
        }

        // Status filter
        $status = $this->request->getQuery('status');
        if ($status != '') {
            $filters['status'] = $status;
            $this->view->status = $status;
        }

        // Name filter
        $search = $this->request->getQuery('search');
        if ($search != '') {
            $filters['search'] = $search;
            $this->view->search = $search;
        }
        return $filters;
    }

    protected function isValid($instance) {
        return true;
    }

    /**
     * 
     * @param Activity $instance
     */
    protected function populatePostData($instance) {
        $instance->setActive($this->request->getPost('active') === 'on');
        $instance->setName($this->request->getPost('name'));
        $instance->setDescription($this->request->getPost('description'));
        $type = $this->request->getPost('type');

        if ($type != null) {
            $instance->setActivityType($this->activityTypeService->findById($type));
        } else {
            $instance->setActivityType(null);
        }
        $instance->setPriority($this->request->getPost('priority'));

        $userId = $this->request->getPost('user');

        if ($userId != '') {
            $instance->setUser($this->userService->findById($userId));
        } else {
            $instance->setUser(null);
        }

        $customerId = $this->request->getPost('customer_id');

        if ($customerId != null) {
            $this->instance->setCustomer($this->customerService->findById($customerId));
        } else {
            $this->instance->setCustomer(null);
        }

        $instance->setStatus($this->request->getPost('status'));

        $date = $this->request->getPost('action_date');
        $startTime = $this->request->getPost('action_start_time');
        $endTime = $this->request->getPost('action_end_time');

        if (trim($startTime) != '' && trim($date) != '') {

            if (!$instance->isFinished()) {
                /* @var DateTime $today */
                $startDate = DateTime::createFromFormat('d/m/y', $date);
                DateUtils::addTimeToDate($startDate, $startTime);

                $user = $this->userService->findById($this->session->getUser()->getId());

                $action = new ActivityInteraction();

                $action->setUser($user);

                $action->setCreationDate(new DateTime());

                $action->setStartDate($startDate);

                // Call userService to create work date if it not created before
                $this->userService->getWorkDate($user, $startDate);

                if (trim($endTime) != '') {

                    $endDate = DateTime::createFromFormat('d/m/y', $date);
                    DateUtils::addTimeToDate($endDate, $endTime);

                    $action->setEndDate($endDate);
                }

                $instance->getInteractions()->add($action);
            } else {
                $this->warn("Não é possível iniciar uma ação em uma atividade fechada!");
            }
        }
    }

}
