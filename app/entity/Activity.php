<?php

/**
 * Description of Activity
 *
 * @author luis
 * @since Jan 13, 2014
 * @Entity @Table(name="activity")
 */
class Activity {

    /** 
     * @Id 
     * @Column(type="integer") 
     * @GeneratedValue
     */
    private $id;

    /** 
     * @Column(type="string")
     */
    private $name;
    
    /** 
     * @Column(type="integer")
     */
    private $priority;
    
    /** 
     * @Column(type="string")
     */
    private $description;
    
    /**
     * @ManyToOne(targetEntity="ActivityType")
     * @JoinColumn(name="type_id", referencedColumnName="id")
     * @var ActivityType 
     */
    private $activityType;
    
    /**
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     * @var User 
     */
    private $user;
    
    /**
     *
     * @var DateTime 
     * @Column(type="datetime", name="creation_date")
     */
    private $creationDate;
    
    /**
     *
     * @var DateTime 
     * @Column(type="datetime", name="last_update")
     */
    private $lastUpdate;
    
    /**
     * @OneToMany(targetEntity="ActivityInteractions", mappedBy="activity")
     * @var ActivityInteractions[]
     */
    private $interactions = array();
    
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getPriority() {
        return $this->priority;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getActivityType() {
        return $this->activityType;
    }

    public function getUser() {
        return $this->user;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getLastUpdate() {
        return $this->lastUpdate;
    }

    public function getInteractions() {
        return $this->interactions;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setPriority($priority) {
        $this->priority = $priority;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setActivityType(ActivityType $activityType) {
        $this->activityType = $activityType;
    }

    public function setUser(User $user) {
        $this->user = $user;
    }

    public function setCreationDate(DateTime $creationDate) {
        $this->creationDate = $creationDate;
    }

    public function setLastUpdate(DateTime $lastUpdate) {
        $this->lastUpdate = $lastUpdate;
    }

    public function setInteractions(ActivityInteractions $interactions) {
        $this->interactions = $interactions;
    }
    
    /**
     * @param ActivityInteractions $interaction
     */
    public function addInteraction(ActivityInteractions $interaction) {
        $this->interactions[] = $interaction;
    }
    


    
}
