<?php

require_once 'ControllerBase.php';

/**
 * Description of AdminBase
 *
 * @author luis
 */
class AdminBase extends ControllerBase {

    public function initialize() {

        parent::initialize();
        $this->title = "Administrativo";

        $this->view->setViewsDir('../app/view/admin/');
    }

}
