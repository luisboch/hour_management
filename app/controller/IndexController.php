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
        $userResults = $this->reportService->getUserActiveReport(array(), 0, 0);
        
        $userResult = null;
        
        foreach($userResults as $v){
            if($v->getUserId() == $this->session->getUser()->getId()){
                $userResult = $v;
            }
        }
        
        $this->view->userResults = $userResults;
        $this->view->userResult = $userResult;
        
    }
    
    
}
