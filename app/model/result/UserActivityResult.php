<?php

/**
 * Description of ActivityReportResult
 *
 * @author luis
 * @since Jan 14, 2014
 */
class UserActivityResult {

    private $userName;
    private $userId;
    private $userAllocatedHours;
    private $userTotalHours;
    private $date;
    private $percentFinished;
    /**
     *
     * @var DateTime
     */
    private $extra;
    private $avaliable;

    public function getUserName() {
        return $this->userName;
    }

    public function getUserId() {
        return $this->userId;
    }

    /**
     * 
     * @return DateTime
     */
    public function getUserAllocatedHours() {
        return $this->userAllocatedHours;
    }

    /**
     * 
     * @return DateTime
     */
    public function getUserTotalHours() {
        return $this->userTotalHours;
    }

    public function setUserName($userName) {
        $this->userName = $userName;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setUserAllocatedHours($userAllocatedHours) {
        $this->userAllocatedHours = $userAllocatedHours;
    }

    public function setUserTotalHours($userTotalHours) {
        $this->userTotalHours = $userTotalHours;
    }
    
    public function getDate() {
        return $this->date;
    }

    public function setDate($date) {
        $this->date = $date;
    }
    /**
     * 
     * @return float
     */
    public function getPercentFinished() {
        return $this->percentFinished;
    }

    /**
     * 
     * @param float $percentFinished
     */
    public function setPercentFinished($percentFinished) {
        $this->percentFinished = $percentFinished;
    }
    
    public function getAvaliable() {
        return $this->avaliable;
    }

    public function setAvaliable($avaliable) {
        $this->avaliable = $avaliable;
    }
    
    /**
     * 
     * @return DateTime
     */
    public function getExtra() {
        return $this->extra;
    }
    /**
     * 
     * @param DateTime $extra
     */
    public function setExtra($extra) {
        $this->extra = $extra;
    }


}
