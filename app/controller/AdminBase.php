<?php
require_once 'ControllerBase.php';

/**
 * Description of AdminBase
 *
 * @author luis
 */
class AdminBase extends ControllerBase{
    
    public function initialize() {
        
        parent::initialize();
        $this->title = "Administrativo";
        
        if (!$this->session->isLogged()) {
            $this->dispatcher->forward(array(
                'controller' => 'security',
                'action' => 'login'
            ));
        } else {
            $this->view->setViewsDir('../app/view/admin/');
        }
        
        
    }
    
}
