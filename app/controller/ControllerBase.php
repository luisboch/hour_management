<?php

require_once SERVICE_DIR . 'SessionManager.php';
require_once SERVICE_DIR . 'Config.php';
require_once APP_DIR . 'model/BasicDAO.php';

// Include helpers
require_once APP_DIR . 'components/volt/helpers.php';

/**
 * Description of BaseController
 *
 * @author luis
 */
class ControllerBase extends \Phalcon\Mvc\Controller {

    /**
     * @var SessionManager
     */
    protected $session;
    
    /**
     * @var Config
     */
    protected $config;

    public function initialize() {

        // Start session
        $this->session = SessionManager::getInstance();

        $this->view->title = "";
        
        $this->view->_session = $this->session;
        
        $this->config = Config::getInstance();
    }
    
    protected function setTitle($title) {
        $this->view->title = $title;
    }

    protected function showError($ex) {
        $this->dispatcher->setParams(array('exception' => $ex));
        $this->dispatcher->forward(array(
            'controller' => 'error',
            'action' => 'exception'));
    }
    
    protected function info($message){
        $this->session->getMessage()->info($message);
    }
    
    protected function warn($message){
        $this->session->getMessage()->warn($message);
    }
    
    protected function success($message){
        $this->session->getMessage()->success($message);
    }
    
    protected function error($message){
        $this->session->getMessage()->error($message);
    }

}