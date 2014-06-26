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
    private $total;
    private $discount;
    private $balance;
    
    function __construct($userName, $userId, $startWork, $endWork, $date, $userAvaliable, $total, $discount, $balance) {
        $this->userName = $userName;
        $this->userId = $userId;
        $this->startWork = $startWork;
        $this->endWork = $endWork;
        $this->date = $date;
        $this->userAvaliable = $userAvaliable;
        $this->total = $total;
        $this->discount = $discount;
        $this->balance = $balance;
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

    public function getUserAvaliable() {
        return $this->userAvaliable;
    }

    public function getTotal() {
        return $this->total;
    }

    public function getDiscount() {
        return $this->discount;
    }

    public function getBalance() {
        return $this->balance;
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

    public function setUserAvaliable($userAvaliable) {
        $this->userAvaliable = $userAvaliable;
    }

    public function setTotal($total) {
        $this->total = $total;
    }

    public function setDiscount($discount) {
        $this->discount = $discount;
    }

    public function setBalance($balance) {
        $this->balance = $balance;
    }
}
