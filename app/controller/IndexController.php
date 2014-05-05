<?php

require_once 'ControllerBase.php';
require_once SERVICE_DIR . 'report/ActivityReportService.php';
require_once SERVICE_DIR . 'ActivityService.php';
require_once SERVICE_DIR . 'utils/DateUtils.php';

/**
 * Description of DefaultController
 *
 * @author luis
 */
class IndexController extends AdminBase {

    /**
     * @var ActivityReportService
     */
    private $reportService;
    /**
     *
     * @var ActivityService
     */
    private $activityService;
    
    public function initialize() {
        parent::initialize();
        $this->reportService = new ActivityReportService();
        $this->activityService = new ActivityService();
        $this->setTitle("Home");
    }

    public function indexAction() {

        $this->view->action = 'Home';
        
        
        $viewDate = $this->request->getQuery('d');

        if ($viewDate != '') {
            $viewDate = DateTime::createFromFormat('d/m/y', $viewDate);
            $viewDate->setTime(00, 00, 00);
        } else {
            $viewDate = new DateTime('00:00:00');
        }
        
        $endDate = clone $viewDate;
        DateUtils::addTimeToDate($endDate, '23:59:59');
        
        $filters = array();
        
        $filters['startDate'] = $viewDate;
        $filters['endDate'] = $endDate;
        
        $userResults = $this->reportService->getUserActiveReport($filters, null, null);

        $userResult = null;
        $prodTotal = 0;
        
        foreach ($userResults as $v) {
            if ($v->getUserId() == $this->session->getUser()->getId()) {
                $userResult = $v;
            }

            $prodTotal += $this->reportService->getFloat($v->getUserAllocatedHours());
        }

        $prodTotal = $this->reportService->getHour($prodTotal);

        $activityResult = $this->reportService->getActivityReport(array(), null, null);

        $this->view->userResults = $userResults;
        $this->view->userResult = $userResult;
        $this->view->activityResult = $activityResult;
        $this->view->prodTotal = $prodTotal;
        
        $this->view->previousDate = $this->reportService->getPreviousWorkDate($viewDate);
        $nextDate = $this->reportService->getNextWorkDate($viewDate);
        
        // Tomorrow
        $tomorrow = $this->reportService->getNextWorkDate(new DateTime());
        
        // Equals?
        if($tomorrow->format('Y-m-d') === $nextDate->format("Y-m-d")){
            
            // Dont show tomorrow
            $nextDate = null;
        }
        
        
        $this->view->currentDate = $viewDate;
        $this->view->nextDate = $nextDate;
        
        $this->view->openActivities = $this->activityService->search(array('status' => 0), TRUE);
    }

}
