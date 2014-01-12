<?php

require_once 'ControllerBase.php';

/**
 * Description of MenuController
 *
 * @author luis
 */
class MenuController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->view->action = "Cardápio";
    }

    public function indexAction() {
        
    }

    public function categoryAction($id) {
        $this->view->catId = $id;
    }

}
