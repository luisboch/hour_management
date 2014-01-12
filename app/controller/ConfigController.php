<?php

require_once 'AdminBase.php';
require_once APP_DIR . 'service/CityService.php';
require_once APP_DIR . 'service/StateService.php';

/**
 * Description of AdminClass
 *
 * @author luis
 */
class ConfigController extends AdminBase {

    /**
     *
     * @var CityService
     */
    private $cityService;

    /**
     *
     * @var StateService
     */
    private $stateService;

    /**
     *
     * @var States[]
     */
    private $states;

    /**
     *
     * @var City[]
     */
    private $cities;

    public function initialize() {
        parent::initialize();
        $this->initializeServices();
        $this->states = $this->stateService->findAll();
        $this->cities = array();
        $this->view->action = "Configuração";
    }

    private function initializeServices() {
        $this->cityService = new CityService();
        $this->stateService = new StateService();
    }

    public function indexAction() {
        $this->view->states = $this->states;
    }

    public function loadCitiesAction($stateId) {

        $this->view->cities = $this->cityService->findCitiesByState($stateId);

        $this->view->setViewsDir('../app/view/admin/ajax/');
    }

}
