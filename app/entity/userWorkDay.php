<?php

/**
 * @author luis
 * @since Jan 13, 2014
 * @BasicEntity
 * @Table(name="user_work_day")
 */
class userWorkDay {
    /**
     *
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
    
    public function getDate() {
        return $this->date;
    }

    public function getUser() {
        return $this->user;
    }

    public function getDayActiveHour() {
        return $this->dayActiveHour;
    }

    public function setDate(DateTime $date) {
        $this->date = $date;
    }

    public function setUser(User $user) {
        $this->user = $user;
    }

    public function setDayActiveHour($dayActiveHour) {
        $this->dayActiveHour = $dayActiveHour;
    }


}
