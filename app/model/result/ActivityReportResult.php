<?php

namespace report\result;
/**
 * Description of ActivityReportResult
 *
 * @author luis
 * @since Jan 14, 2014
 */
class ActivityReportResult {

    private $total = 0;
    private $finished = 0;
    private $idsFinished = array();
    /**
     *
     * @var ResultData[] 
     */
    private $data = array();

    public function getTotal() {
        return $this->total;
    }

    public function getFinished() {
        return $this->finished;
    }

    /**
     * 
     * @return ResultData[]
     */
    public function getData() {
        return $this->data;
    }
    
    /**
     * @param \report\result\ResultData $data
     */
    public function add(ResultData $data) {
        $this->data[] = $data;
        
        $this->total++;
     
        if ($data->isFinished()) {
            if(!in_array($data->getActivityId(), $this->idsFinished)){
                $this->finished++;
                $this->idsFinished[] = $data->getActivityId();
            }
        }
    }

}


class ResultData {

    private $activityId;
    private $activityName;
    private $allocated;
    private $finished;
    private $date;

    function __construct($activityId, $activityName, $allocated, $finished, $date) {
        $this->activityId = $activityId;
        $this->activityName = $activityName;
        $this->allocated = $allocated;
        $this->finished = $finished;
        $this->date = $date;
    }
    
    public function getActivityId() {
        return $this->activityId;
    }

    public function getActivityName() {
        return $this->activityName;
    }

    public function getAllocated() {
        return $this->allocated;
    }

    public function getFinished() {
        return $this->finished;
    }
    
    public function isFinished() {
        return $this->finished;
    }

    public function getDate() {
        return $this->date;
    }

    public function setActivityId($activityId) {
        $this->activityId = $activityId;
    }

    public function setActivityName($activityName) {
        $this->activityName = $activityName;
    }

    public function setAllocated($allocated) {
        $this->allocated = $allocated;
    }

    public function setFinished($finished) {
        $this->finished = $finished;
    }

    public function setDate($date) {
        $this->date = $date;
    }


}
