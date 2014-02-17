<?php

namespace report\result;

/**
 * Description of ActivityReportResult
 *
 * @author luis
 * @since Jan 14, 2014
 */
class ActivityReportTypeResult {

    private $activityType;
    private $activityUser;
    private $finished;
    private $allocated;
    private $date;

    function __construct($activityType, $activityUser, $finished, $allocated, $date) {
        $this->activityType = $activityType;
        $this->activityUser = $activityUser;
        $this->finished = $finished;
        $this->allocated = $allocated;
        $this->date = $date;
    }

    public function getActivityType() {
        return $this->activityType;
    }

    public function getActivityUser() {
        return $this->activityUser;
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
        $this->activityType = $activityName;
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