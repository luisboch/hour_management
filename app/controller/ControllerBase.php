<?php

require_once SERVICE_DIR . 'SessionManager.php';

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
    

    public function initialize() {

        // Start session
        $this->session = SessionManager::getInstance();

        $this->view->title = 'Pizzaria Fornalha Vinhedo';
        
        $this->view->_session = $this->session;
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
