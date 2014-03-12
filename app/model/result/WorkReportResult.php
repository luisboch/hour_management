<?php

namespace report\result;

/**
 * Description of ActivityReportResult
 *
 * @author luis
 * @since Jan 14, 2014
 */
class WorkReportResult {

    private $userName;
    private $userId;
    private $startWork;
    private $endWork;
    private $date;
    private $userAvaliable;
    private $extra;
    private $negativeExtra;

    function __construct($userName, $userId, $startWork, $endWork, $date, $userAvaliable) {
        $this->userName = $userName;
        $this->userId = $userId;
        $this->startWork = $startWork;
        $this->endWork = $endWork;
        $this->date = $date;
        $this->userAvaliable = $userAvaliable;
    }

    public function getUserName() {
        return $this->userName;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getStartWork() {
        return $this->startWork;
    }

    public function getEndWork() {
        return $this->endWork;
    }

    public function getDate() {
        return $this->date;
    }

    public function setUserName($userName) {
        $this->userName = $userName;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setStartWork($startWork) {
        $this->startWork = $startWork;
    }

    public function setEndWork($endWork) {
        $this->endWork = $endWork;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function getUserAvaliable() {
        return $this->userAvaliable;
    }

    public function getExtra() {
        return $this->extra;
    }

    public function setUserAvaliable($userAvaliable) {
        $this->userAvaliable = $userAvaliable;
    }

    public function setExtra($extra) {
        $this->extra = $extra;
    }

    public function getNegativeExtra() {
        return $this->negativeExtra;
    }

    public function isNegativeExtra() {
        return $this->negativeExtra;
    }

    public function setNegativeExtra($negativeExtra) {
        $this->negativeExtra = $negativeExtra;
    }

    public function calculateExtra() {
        if ($this->startWork != null && $this->endWork != null && $this->userAvaliable != null) {
            $diff = $this->startWork->diff($this->endWork);

            $config = \Config::getInstance();
            if ($config['report']['discount_lunchtime']) {
                if ($diff->h > 8 || ($diff->h == 8 && $diff->i > 0 )) {
                    $diff->h--;
                }
            }

            /* @var DateInterval $diff */
            $workTime = new \DateTime($diff->format('%H:%I:00'));
            $normalTime = new \DateTime($this->userAvaliable);

            // Has extra?
            $this->setNegativeExtra(!($workTime->getTimestamp() > $normalTime->getTimestamp()));

            $diff = $workTime->diff($normalTime);
            /* @var DateInterval $diff */
            $this->setExtra(new \DateTime($diff->format('%H:%I:00')));
        }
    }

}
