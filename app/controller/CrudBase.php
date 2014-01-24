<?php

require_once 'AdminBase.php';
require_once APP_DIR . 'components/custom/Pagination.php';
require_once 'utils/CrudStates.php';

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
    protected $controllerName;
    protected $state;

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
        $this->state= CrudStates::INITIALIZING;
        parent::initialize();

        $this->service = $service;
        $this->_initialized = true;
        $this->controllerName = $this->getControllerName();
    }

    /* --- ACTIONS --- */

    public function indexAction() {

        $this->state= CrudStates::INDEX_ACTION;
        try {
            $params = $this->getSearchParams();

            $this->view->results = $this->service->search(
                    $params, true, $this->config['pagination']['registers_limit_per_page'], 0);

            $totalResults = $this->service->searchCount($params, true);
            $this->builPagination(1, $totalResults, true);

            $this->view->pick($this->controllerName . "/search");
        } catch (Exception $ex) {
            $this->showError($ex);
        }
    }

    public function searchAction($page = 1) {

        $this->state= CrudStates::SEARCH_ACTION;
        try {

            $params = $this->getSearchParams();

            if ($this->beforeSearch()) {
                $limitPerPage = $this->config['pagination']['registers_limit_per_page'];
                $this->view->results = $this->service->search(
                        $params, $this->showActiveResults, $limitPerPage, $page * $limitPerPage - $limitPerPage);

                $totalResults = $this->service->searchCount($params, $this->showActiveResults);
                $this->builPagination($page, $totalResults);

                $this->afterSearch();
                $this->view->active = $this->showActiveResults;
            }
        } catch (Exception $ex) {
            $this->showError($ex);
        }
    }
    
    /**
     * Alias to #viewAction
     */
    public function newAction() {
        $this->state= CrudStates::NEW_ACTION;
        
        $this->dispatcher->forward(array('action' => 'view'));
    } 
    
    public function viewAction($id) {
        
        $this->state= CrudStates::VIEW_ACTION;
        
        $this->checkInitialization();

        $this->resolveInstance($id);

        $this->view->instance = $this->instance;
    }

    public function saveAction() {

        $this->state= CrudStates::SAVE_ACTION;
        $this->checkInitialization();

        if ($this->request->isPost()) {

            $this->resolveInstance($this->request->getPost("id"));

            $this->populatePostData($this->instance);

            if ($this->isValid($this->instance)) {
                $this->saveOrUpdate($this->instance);
            } else {
                $this->dispatcher->setParams(array('instance' => $this->instance));
                $this->dispatcher->forward(array('action' => 'view'));
                return false;
            }
        } else {
            throw new LogicException("For security reasons GET method is not allowed");
        }
    }

    private function builPagination($page, $total,  $forceFilterActiveTrue = NULL) {
        $pagination = new Pagination();
        $pagination->setAmountLinkShow($this->config['pagination']['number_of_links_displayed']);
        $pagination->setCurrentPage($page);
        $pagination->setAmountPerPage($this->config['pagination']['registers_limit_per_page']);
        $pagination->setTargetUrl($this->url->get($this->controllerName . '/search'));
        $pagination->setAmountRegisters($total);
        $pagination->setQueryString($this->getQueryString(NULL, $forceFilterActiveTrue));
        $this->view->pagination = $pagination;
    }

    protected function getQueryString($ignore = NULL, $forceActiveTrue = NULL) {
        $query = "";
        $get = isset($_GET) ? $_GET : array();
        $foundActive = false;
        foreach ($get as $k => $v) {
            if ($k != '_url'&& $k != $ignore) {
                $query .= $k . '=' . urlencode($v) . '&';
            }
            if ($k === 'active') {
                $foundActive = true;
            }
        }
        
        if(!$foundActive && $forceActiveTrue === true){
            $query.='active=on';
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
