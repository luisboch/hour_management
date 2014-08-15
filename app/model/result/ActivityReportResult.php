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
    private $idsTotal = array();
    
    private $totalHour;

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
        if (!in_array($data->getActivityId(), $this->idsTotal)) {
            $this->total++;
            $this->idsTotal[] = $data->getActivityId();
        }

        if ($data->isFinished()) {
            if (!in_array($data->getActivityId(), $this->idsFinished)) {
                $this->finished++;
                $this->idsFinished[] = $data->getActivityId();
            }
        }
    }

    public function getTotalHour() {
        return $this->totalHour;
    }

    public function setTotalHour($totalHour) {
        $this->totalHour = $totalHour;
    }
}

class ResultData {

    private $activityId;
    private $activityName;
    private $allocated;
    private $finished;
    private $date;
    private $customerId;
    private $customerName;

    function __construct($activityId, $activityName, $allocated, $finished, $date, $customerId, $customerName) {
        $this->activityId = $activityId;
        $this->activityName = $activityName;
        $this->allocated = $allocated;
        $this->finished = $finished;
        $this->date = $date;
        $this->customerId = $customerId;
        $this->customerName = $customerName;
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
    
    public function getCustomerId() {
        return $this->customerId;
    }

    public function getCustomerName() {
        return $this->customerName;
    }

    public function setCustomerId($customerId) {
        $this->customerId = $customerId;
    }

    public function setCustomerName($customerName) {
        $this->customerName = $customerName;
    }

    
    
}
