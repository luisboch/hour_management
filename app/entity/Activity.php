<?php

require_once 'ActivityType.php';
require_once 'ActivityInteraction.php';
require_once 'User.php';
require_once 'BasicEntity.php';

/**
 * Description of Activity
 *
 * @author luis
 * @since Jan 13, 2014
 * @Entity @Table(name="activity")
 */
class Activity implements BasicEntity{

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
    private $priority = 70;

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
     * @OneToMany(targetEntity="ActivityInteraction", mappedBy="activity")
     * @var ActivityInteraction[]
     */
    private $interactions = array();

    /**
     * @var boolean
     * @Column(type="boolean")
     */
    private $active = true;

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

    /**
     * 
     * @return ActivityInteraction[]
     */
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

    /**
     * 
     * @param ActivityType $activityType
     */
    public function setActivityType( $activityType) {
        $this->activityType = $activityType;
    }

    /**
     * @param User $user
     */
    public function setUser($user) {
        $this->user = $user;
    }

    public function setCreationDate(DateTime $creationDate) {
        $this->creationDate = $creationDate;
    }

    public function setLastUpdate(DateTime $lastUpdate) {
        $this->lastUpdate = $lastUpdate;
    }

    /**
     * @param ActivityInteraction[] $interactions
     */
    public function setInteractions( $interactions) {
        $this->interactions = $interactions;
    }

    /**
     * @param ActivityInteraction $interaction
     */
    public function addInteraction(ActivityInteraction $interaction) {
        $this->interactions[] = $interaction;
    }

    public function getActive() {
        return $this->active;
    }

    public function setActive($active) {
        $this->active = $active;
    }

}
