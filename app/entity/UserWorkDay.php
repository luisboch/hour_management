<?php

require_once 'BasicEntity.php';

/**
 * @author luis
 * @since Jan 13, 2014
 * @Entity
 * @Table(name="user_work_day")
 */
class UserWorkDay {
    
    /**
     * @Id 
     * @Column(type="integer") 
     * @GeneratedValue
     */
    private $id;
    
    /**
     * @Column(type="date")
     * @var DateTime
     */
    private $date;
    
    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     * @var User 
     */
    private $user;
    
    /** 
     * @Column(type="time", name="day_active_hour")
     */
    private $dayActiveHour;
    
    /**
     * @Column(type="datetime", name="start_work")
     * @var DateTime
     */
    private $startWork;
    
    /**
     * @Column(type="datetime", name="end_work")
     * @var DateTime
     */
    private $endWork;
    
    public function getId() {
        return $this->id;
    }
    
    /**
     * @return DateTime
     */
    public function getDate() {
        return $this->date;
    }
    
    /**
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    public function getDayActiveHour() {
        return $this->dayActiveHour;
    }
    
    /**
     * @return DateTime
     */
    public function getStartWork() {
        return $this->startWork;
    }

    /**
     * @return DateTime
     */
    public function getEndWork() {
        return $this->endWork;
    }

    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @param DateTime $date
     */
    public function setDate($date) {
        $this->date = $date;
    }

    /**
     * @param User $user
     */
    public function setUser($user) {
        $this->user = $user;
    }
    
    /**
     * @param DateTime $dayActiveHour
     */
    public function setDayActiveHour($dayActiveHour) {
        $this->dayActiveHour = $dayActiveHour;
    }
    
    /**
     * @param DateTime $startWork
     */
    public function setStartWork($startWork) {
        $this->startWork = $startWork;
    }

    /**
     * @param DateTime $endWork
     */
    public function setEndWork($endWork) {
        $this->endWork = $endWork;
    }


}