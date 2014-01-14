<?php

require_once SERVICE_DIR . 'ActivityService.php';
require_once SERVICE_DIR . 'ActivityTypeService.php';
require_once SERVICE_DIR . 'UserService.php';

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
     * @var UserService
     */
    private $userService;

    public function initialize() {
        parent::initialize(new ActivityService());
        $this->activityTypeService = new ActivityTypeService();
        $this->userService = new UserService();
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

        $limitPerPage = $this->config['pagination']['registers_limit_per_page'];

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

    private function createPagination($page, $total, $pageParamName = 'page') {
        $pagination = new Pagination();
        $pagination->setAmountLinkShow($this->config['pagination']['number_of_links_displayed']);
        $pagination->setCurrentPage($page);
        $pagination->setAmountPerPage($this->config['pagination']['registers_limit_per_page']);
        $pagination->setTargetUrl($this->url->get($this->controllerName . '/index'));
        $pagination->setAmountRegisters($total);
        $pagination->setQueryString($this->getQueryString());
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
            /* @var Activity $activity */
            $activity->removeInteraction($actionId);
            $this->service->save($activity);
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
            $filters['user'] = $this->userService->findById($userId);
        } else {
            $filters['user'] = null;
        }

        $this->view->user_id = $userId;

        // Type filter
        $typeId = $this->request->getQuery('type');
        if ($typeId != '') {
            $filters['type'] = $typeId;
            $this->view->type = $typeId;
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
        $instance->setStatus($this->request->getPost('status'));

        $description = $this->request->getPost('action_description');
        $time = $this->request->getPost('action_time');

        if ($description != '' || $time != '') {
            $action = new ActivityInteraction();
            $action->setUser($this->userService->findById($this->session->getUser()->getId()));
            $action->setCreationDate(new DateTime());
            $action->setDescription($description);
            $action->setAllocatedTime(new DateTime($time));
            $instance->getInteractions()->add($action);
        }
    }

}
