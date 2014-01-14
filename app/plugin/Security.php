<?php
require_once SERVICE_DIR.'SessionManager.php';
use Phalcon\Events\Event,
    Phalcon\Mvc\User\Plugin,
    Phalcon\Mvc\Dispatcher;

/**
 * Description of Security
 *
 * @author luis
 * @since Jan 14, 2014
 */
class Security extends Plugin {

    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher) {

        //Check whether the "auth" variable exists in session to define the active role
        $session = SessionManager::getInstance();
        
        if ($session->getUser() !== null) {
            return true;
        }
        
        //Take the active controller/action from the dispatcher
        $controller = $dispatcher->getControllerName();
        
        $class=  ucfirst($controller.'Controller');
        
        if((new $class) instanceof AdminBase){ // Need to be autenticated.
            $dispatcher->forward(
                    array(
                        'controller' => 'security',
                        'action' => 'login'
                    )
            );
            return false;
        }
        
        return true;
    }

}
