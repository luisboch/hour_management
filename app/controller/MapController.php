<?php
require_once 'ControllerBase.php';

/**
 * Description of MapController
 *
 * @author luis
 */
class MapController extends ControllerBase {
    public function initialize() {
        parent::initialize();
        $this->view->action = "Mapa";
    }
    public function indexAction() {
    }
}
