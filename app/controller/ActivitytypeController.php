<?php

require_once 'CrudBase.php';
require_once APP_DIR . 'service/ActivityTypeService.php';

/**
 * Description of UserCrudController
 *
 * @author luis
 * @property UserService $service 
 */
class ActivitytypeController extends CrudBase {

    public function initialize() {

        parent::initialize(new ActivityTypeService());
        
        // Set default active of value
        $this->view->active = true;
        $this->setTitle('Tipo de atividade');
    }

    /**
     * 
     * @param ActivityType $activity
     */
    protected function populatePostData($activity) {

        $activity->setName($this->request->getPost("name"));
        $activity->setDescription($this->request->getPost("description"));
        $active = $this->request->getPost("active");
        $activity->setActive($active === "on");
        
    }

    /**
     * 
     * @param ActivityType $activityType
     */
    protected function isValid($activityType) {
        return TRUE;
    }

    protected function createNewInstance() {
        return new ActivityType();
    }

    protected function getSearchParams() {
        return array('search' => $this->request->getQuery('search'));
    }

    protected function beforeSearch() {
        $active = $this->request->getQuery('active');
        $this->showActiveResults = $active === 'on';
        $this->view->active = $this->showActiveResults;
        return true;
    }

}
