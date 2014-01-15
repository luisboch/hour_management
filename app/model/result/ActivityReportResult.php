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

    /**
     *
     * @var \ActivityReportResult\Data\ResultData[] 
     */
    private $data = array();

    public function getTotal() {
        return $this->total;
    }

    public function getFinished() {
        return $this->finished;
    }

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
            $this->finished++;
        }
    }

}


class ResultData {

    private $activityId;
    private $activityName;
    private $allocated;
    private $finished;

    function __construct($activityId, $activityName, $allocated, $finished) {
        $this->activityId = $activityId;
        $this->activityName = $activityName;
        $this->allocated = $allocated;
        $this->finished = $finished;
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

    public function setActivityId($activityId) {
        $this->activityId = $activityId;
    }

    public function setActivityName($activityName) {
        $this->activityName = $activityName;
    }

    public function setAllocated($allocated) {
        $this->allocated = $allocated;
    }

    public function isFinished() {
        return $this->finished;
    }

    public function setFinished($finished) {
        $this->finished = $finished;
    }

}
