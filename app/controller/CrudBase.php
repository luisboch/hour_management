<?php

require_once 'AdminBase.php';
require_once APP_DIR . 'components/custom/Pagination.php';

/**
 * Description of CrudBase
 *
 * @author luis
 * @since Jan 8, 2014
 */
abstract class CrudBase extends AdminBase {

    private $_initialized = false;
    protected $instance;
    protected $results = array();
    private $controllerName;

    /**
     *
     * @var boolean Used to configure search
     * possible values 
     * NULL - Show all results
     * FALSE - Show only inactive results
     * TRUE - Show only active results (default)
     */
    protected $showActiveResults = true;

    /**
     *
     * @var BasicService
     */
    protected $service;

    public function initialize(BasicService $service) {
        parent::initialize();

        $this->service = $service;
        $this->_initialized = true;
        $this->controllerName = $this->getControllerName();
    }

    /* --- ACTIONS --- */

    public function indexAction() {

        try {
            $params = $this->getSearchParams();


            $this->view->results = $this->service->search(
                    $params, true, DEFAULT_LIMITS_PER_PAGE, 0);

            $totalResults = $this->service->searchCount($params, true);
            $this->builPagination(1, $totalResults);

            $this->view->pick($this->controllerName . "/search");
        } catch (Exception $ex) {
            $this->showError($ex);
        }
    }

    public function searchAction($page = 1) {

        try {

            $params = $this->getSearchParams();

            if ($this->beforeSearch()) {

                $this->view->results = $this->service->search(
                        $params, $this->showActiveResults, DEFAULT_LIMITS_PER_PAGE, $page * DEFAULT_LIMITS_PER_PAGE - DEFAULT_LIMITS_PER_PAGE);

                $totalResults = $this->service->searchCount($params, $this->showActiveResults);
                $this->builPagination($page, $totalResults);

                $this->afterSearch();
            }
        } catch (Exception $ex) {
            $this->showError($ex);
        }
    }

    public function viewAction($id) {
        $this->checkInitialization();

        $this->resolveInstance($id);

        $this->view->instance = $this->instance;
    }

    public function saveAction() {

        $this->checkInitialization();

        if ($this->request->isPost()) {

            $this->resolveInstance($this->request->getPost("id"));

            $this->populatePostData($this->instance);

            if ($this->isValid($this->instance)) {
                $this->saveOrUpdate($this->instance);
            } else {
                $this->dispatcher->setParams(array('instance' => $this->instance));
                $this->dispatcher->forward(array('action' => 'view'));

                //Disable the view to avoid rendering
                $this->view->disable();
                return false;
            }
        } else {
            throw new LogicException("For security reasons GET method is not allowed");
        }
    }

    private function builPagination($page, $total) {
        $pagination = new Pagination();
        $pagination->setAmountLinkShow(9);
        $pagination->setCurrentPage($page);
        $pagination->setAmountPerPage(DEFAULT_LIMITS_PER_PAGE);
        $pagination->setTargetUrl($this->url->get($this->controllerName . '/search'));
        $pagination->setAmountRegisters($total);
        $pagination->setQueryString($this->getQueryString());
        $this->view->pagination = $pagination;
    }

    protected function getQueryString() {
        $query = "";
        $get = isset($_GET) ? $_GET : array();
        foreach ($get as $k => $v) {
            if ($k != '_url') {
                $query .= $k . '=' . urlencode($v) . '&';
            }
        }
        return $query;
    }

    private function getControllerName() {
        return strtolower(str_replace('Controller', '', get_class($this)));
    }

    private function resolveInstance($id) {
        if ($id != '' && !is_object($id)) {
            $this->instance = $this->service->findById($id);
        } else {
            $params = $this->dispatcher->getParams();

            if (isset($params['instance']) && $params['instance'] != null) {
                $this->instance = $params['instance'];
            } else {
                $this->instance = $this->getNewInstance();
            }
        }

        if ($this->instance == null) {
            $this->response->redirect('error/show404');
        }
    }

    protected function saveOrUpdate($instance) {

        $this->view->instance = $instance;

        try {
            if ($instance->getId() == '') {
                $this->service->save($instance);
                $this->success("Registro salvo com sucesso");
                $this->response->redirect($this->controllerName . '/view/' . $instance->getId());

                //Disable the view to avoid rendering
                $this->view->disable();
                return false;
            } else {
                $this->service->update($instance);
                $this->success("Registro atualizado com sucesso");
                $this->response->redirect($this->controllerName . '/view/' . $instance->getId());

                //Disable the view to avoid rendering
                $this->view->disable();
            }
        } catch (ValidationException $ex) {

            foreach ($ex->getErrors() as $err) {
                $this->error($err->getMessage());
            }

            $this->dispatcher->setParams(array('instance' => $this->instance));
            $this->dispatcher->forward(array('action' => 'view'));

            //Disable the view to avoid rendering
            $this->view->disable();
        } catch (Exception $ex) {
            $this->showError($ex);
        }
    }

    private function getNewInstance() {
        $instance = $this->createNewInstance();
        if ($instance == null) {
            throw new LogicException("Method #createNewInstance must return a new instance of currente managed class");
        }
        return $instance;
    }

    /**
     * @throws LogicException
     */
    private function checkInitialization() {
        if (!$this->_initialized) {
            throw new LogicException("Method called before #initialize(BasicService)");
        }
    }

    /* ---- ABSTRACT METHODS ---- */

    protected abstract function createNewInstance();

    protected abstract function populatePostData($instance);

    protected abstract function isValid($instance);

    protected abstract function getSearchParams();

    /* ---- LISTENER METHODS --- */

    protected function beforeSave($instance) {
        
    }

    protected function afterSave($instance) {
        
    }

    /**
     * @return boolean
     */
    protected function beforeSearch() {
        return true;
    }

    protected function afterSearch() {
        
    }

}
