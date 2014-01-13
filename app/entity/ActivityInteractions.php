<?php

/**
 * Description of ActivityInteractions
 *
 * @author luis
 * @since Jan 13, 2014
 * @Entity @Table(name="activity_interaction")
 */
class ActivityInteractions {

    /**
     * @Id 
     * @Column(type="integer") 
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="string")
     */
    private $description;

    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     * @var User 
     */
    private $user;

    /**
     * @ManyToOne(targetEntity="Activity")
     * @JoinColumn(name="activity_id", referencedColumnName="id")
     * @var Activity 
     */
    private $activity;

    /**
     * @var DateTime 
     * @Column(type="datetime", name="creation_date")
     */
    private $creationDate;
    
    /**
     * @var DateTime 
     * @Column(type="datetime", name="allocated_time")
     */
    private $allocatedTime;
    
    public function getId() {
        return $this->id;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getUser() {
        return $this->user;
    }

    public function getActivity() {
        return $this->activity;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getAllocatedTime() {
        return $this->allocatedTime;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setUser(User $user) {
        $this->user = $user;
    }

    public function setActivity(Activity $activity) {
        $this->activity = $activity;
    }

    public function setCreationDate(DateTime $creationDate) {
        $this->creationDate = $creationDate;
    }

    public function setAllocatedTime(DateTime $allocatedTime) {
        $this->allocatedTime = $allocatedTime;
    }

}
