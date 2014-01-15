<?php

require_once SERVICE_DIR . 'report/ActivityReportService.php';

/**
 * Description of ReportController
 *
 * @author luis
 * @since Jan 15, 2014
 */
class ReportController extends AdminBase {

    /**
     *
     * @var ActivityReportService
     */
    private $service;

    public function initialize() {
        parent::initialize();
        $this->service = new ActivityReportService();
    }

    public function indexAction() {
    }

    public function userAction() {
        $results = $this->service->getUserActiveReport($this->getParams());
        $this->view->results = $results;
    }

    public function activityAction() {
        $results = $this->service->getActivityReport($this->getParams());
        $this->view->results = $results;
    }

    /**
     * @return array
     */
    private function getParams() {
        $startDate = $this->request->getQuery('startDate');
        $endDate = $this->request->getQuery('endDate');

        if ($startDate != '') {
            $startDate = DateTime::createFromFormat('d/m/y', $startDate);
            
            $startDate->setTime(00, 00, 00);
        } else {
            $startDate = new DateTime('00:00:00');
        }
        if ($endDate != '') { 
            $endDate = DateTime::createFromFormat('d/m/y', $endDate);
            /* @var DateTime $endDate */
            $endDate->setTime(23, 59, 59);
        } else {
            $endDate = new DateTime('23:59:59');
        }
        return array('startDate' => $startDate, 'endDate' => $endDate);
    }

}
