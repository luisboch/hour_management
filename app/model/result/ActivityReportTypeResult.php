<?php

namespace report\result;

class ActivityReportTypeTotal{
    /**
     * @var ActivityReportTypeIndicator
     */
    private $indicators;
    
    /**
     * @var ActivityReportTypeUserTotal[]
     */
    private $userIndicators;
    /**
     *
     * @var ActivityReportTypeGroupedResult[] 
     */
    private $typeIndicators;    
    /**
     *
     * @var ActivityReportTypeDetailResult[]
     */
    private $typesDetails;
    
    
    /**
     * 
     * @return ActivityReportTypeDetailResult[]
     */

    public function getTypesDetails() {
        return $this->typesDetails;
    }    /**
     * 
     * @param ActivityReportTypeDetailResult[] $typeDetails
     */
    public function setTypesDetails($typesDetails) {
        $this->typesDetails = $typesDetails;
    }
    
    /**
     * @return ActivityReportTypeIndicator
     */
    public function getIndicators() {
        return $this->indicators;
    }

    public function setIndicators(ActivityReportTypeIndicator $indicators) {
        $this->indicators = $indicators;
    }
    
    public function getUserIndicators() {
        return $this->userIndicators;
    }

    /**
     * 
     * @param ActivityReportTypeUserTotal[] $userIndicators
     */
    public function setUserIndicators( $userIndicators) {
        $this->userIndicators = $userIndicators;
    }
    
    /**
     * 
     * @return ActivityReportTypeGroupedResult[]
     */
    public function getTypeIndicators() {
        return $this->typeIndicators;
    }

    /**
     * 
     * @param ActivityReportTypeGroupedResult[] $typeIndicators
     */
    public function setTypeIndicators($typeIndicators) {
        $this->typeIndicators = $typeIndicators;
    }
}

/**
 * Description of ActivityReportResult
 *
 * @author luis
 * @since Jan 14, 2014
 */
class ActivityReportTypeDetailResult {

    private $activityType;
    private $activityUser;
    private $allocated;
    private $date;

    function __construct($activityType, $activityUser, $allocated, $date) {
        $this->activityType = $activityType;
        $this->activityUser = $activityUser;
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
    
    public function setDate($date) {
        $this->date = $date;
    }

}


class ActivityReportTypeIndicator{
    
    private $avaliableTime;
    private $allocatedNormalTime;
    private $remainingTime;
    
    public function getAvaliableTime() {
        return $this->avaliableTime;
    }

    public function getAllocatedNormalTime() {
        return $this->allocatedNormalTime;
    }

    public function getRemainingTime() {
        return $this->remainingTime;
    }

    public function setAvaliableTime($avaliableTime) {
        $this->avaliableTime = $avaliableTime;
    }

    public function setAllocatedNormalTime($allocatedNormalTime) {
        $this->allocatedNormalTime = $allocatedNormalTime;
    }
    public function setRemainingTime($remainingTime) {
        $this->remainingTime = $remainingTime;
    }
    
}

class ActivityReportTypeUserTotal{
    
    private $userName;
    private $avaliableTime;
    private $remainingTime;
    private $allocatedTime;
    
    public function getUserName() {
        return $this->userName;
    }

    public function getAvaliableTime() {
        return $this->avaliableTime;
    }

    public function getRemainingTime() {
        return $this->remainingTime;
    }

    public function setUserName($userName) {
        $this->userName = $userName;
    }

    public function setAvaliableTime($avaliableTime) {
        $this->avaliableTime = $avaliableTime;
    }

    public function setRemainingTime($remainingTime) {
        $this->remainingTime = $remainingTime;
    }
    
    public function getAllocatedTime() {
        return $this->allocatedTime;
    }

    public function setAllocatedTime($allocatedTime) {
        $this->allocatedTime = $allocatedTime;
    }
}

class ActivityReportTypeGroupedResult{
    
    private $activityType;
    private $allocatedTime;
    
    public function getActivityType() {
        return $this->activityType;
    }

    public function getAllocatedTime() {
        return $this->allocatedTime;
    }

    public function setActivityType($activityType) {
        $this->activityType = $activityType;
    }

    public function setAllocatedTime($allocatedTime) {
        $this->allocatedTime = $allocatedTime;
    }
}