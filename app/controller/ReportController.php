<?php

require_once SERVICE_DIR . 'report/ActivityReportService.php';
require_once SERVICE_DIR . 'report/UserReport.php';
require_once APP_DIR . 'service/UserService.php';

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

    /**
     *
     * @var UserService
     */
    private $userService;

    public function initialize() {
        parent::initialize();
        $this->service = new ActivityReportService();
        $this->userService = new UserService();
        $this->setTitle('Relatórios');
    }

    public function indexAction() {
        $this->view->users = $this->userService->search(array(), true);
    }

    public function userAction() {
        $results = $this->service->getUserActiveReport($this->getParams());
        $this->view->results = $results;
    }

    public function activityAction() {
        $results = $this->service->getActivityReport($this->getParams());
        $this->view->results = $results;
    }

    public function typeAction() {
        $results = $this->service->getActivityTypeReport($this->getParams());
        $this->view->result = $results;
    }

    public function workAction() {

        $params = $this->getParams();

        if ($params['user'] == null) {
            $this->warn("Selecione o usuário");
            $this->response->redirect('report/index');

            $this->view->disable();
            return false;
        } else {
            $results = $this->service->getWorkReport($this->getParams());
            $this->view->results = $results['results'];
            $this->view->total = $results['total'];
            $this->view->user = $params['user'];
        }
    }

    /**
     * @return array
     */
    private function getParams() {
        $startDate = $this->request->getQuery('startDate');
        $endDate = $this->request->getQuery('endDate');

        $userId = $this->request->getQuery('user_id');

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
        $user = null;

        if ($userId != '') {
            $user = $this->userService->findById($userId);
        }
        return array('startDate' => $startDate, 'endDate' => $endDate, 'user' => $user);
    }
    
    public function work_detailAction(){
        $params = $this->getParams();
        $service = new UserReport();
        $this->view->userResult = $params['user'];
        $this->view->results = $service->getUserActivityReport($params);
    }

}
