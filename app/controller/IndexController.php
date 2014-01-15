<?php
require_once 'ControllerBase.php';
require_once SERVICE_DIR.'report/ActivityReportService.php';

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
    
    public function initialize() {
        $this->reportService = new ActivityReportService();
        parent::initialize();
    }
    
    public function indexAction() {
        
        $this->view->action = 'Home';
        $userResults = $this->reportService->getUserActiveReport(array(), null, null);
        
        $userResult = null;
        $prodTotal = 0;
        foreach($userResults as $v){
            if($v->getUserId() == $this->session->getUser()->getId()){
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
        
    }
    
    
}
