<?php
require_once 'ControllerBase.php';

/**
 * Description of DefaultController
 *
 * @author luis
 */
class IndexController extends AdminBase {

    public function indexAction() {
        $this->view->action = 'Home';
    }

}
