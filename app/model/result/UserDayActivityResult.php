<?php

class UserDayActivityResult{
    
    private $date;
    
    private $activityName;
    private $activityId;
    private $startDate;
    private $endDate;
    private $allocated;
    

    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }
    
    public function getActivityName() {
        return $this->activityName;
    }

    public function getActivityId() {
        return $this->activityId;
    }

    public function getStartDate() {
        return $this->startDate;
    }

    public function getEndDate() {
        return $this->endDate;
    }

    public function getAllocated() {
        return $this->allocated;
    }

    public function setActivityName($activityName) {
        $this->activityName = $activityName;
    }

    public function setActivityId($activityId) {
        $this->activityId = $activityId;
    }

    public function setStartDate($startDate) {
        $this->startDate = $startDate;
    }

    public function setEndDate($endDate) {
        $this->endDate = $endDate;
    }

    public function setAllocated($allocated) {
        $this->allocated = $allocated;
    }

}