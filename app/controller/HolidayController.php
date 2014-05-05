<?php

require_once 'CrudBase.php';
require_once SERVICE_DIR . 'HolidayService.php';

/**
 * Description of HolidayService
 *
 * @author luis
 * @property HolidayService $service
 */
class HolidayController extends CrudBase {

    public function initialize() {

        parent::initialize(new HolidayService());

        // Set default active of value
        $this->view->active = true;
        $this->setTitle('Feriados');
    }

    /**
     * @param Holiday $holiday
     */
    protected function populatePostData($holiday) {
        $holiday->setDescription($this->request->getPost("description"));
        
        $date = $this->request->getPost('date');
        
        if ($date != '') {
            $date = DateTime::createFromFormat('d/m/y', $date);
            $holiday->setDate($date);
        }
        
        
        $active = $this->request->getPost("active");
        $holiday->setActive($active === "on");
    }

    
    /**
     * @param Holiday $holiday
     * @return boolean
     */
    protected function isValid($holiday) {
        return TRUE;
    }

    /**
     * @return \Holiday
     */
    protected function createNewInstance() {
        $h = new Holiday();
        $h->setDate(new DateTime());
        return $h;
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
