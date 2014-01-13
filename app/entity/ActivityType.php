<?php

/**
 * Description of HourType
 *
 * @author luis
 * @since Jan 13, 2014
 * 
 * @Entity @Table(name="activity_type")
 */
class ActivityType {

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
     * @Column(type="string")
     */
    private $description;
    
    /** 
     * @Column(type="time", name="creation_date) 
     * @var DateTime
     */
    private $creationDate;
    
    /**
     *  @Column(type="datetime", name="creation_date)
     * @var DateTime
     */
    private $lastUpdate;
    /*
    /**
     * @ManyToOne(targetEntity="Activity")
     * @var Activity
     * /
    private $activity;
    */
    
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getLastUpdate() {
        return $this->lastUpdate;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setCreationDate(DateTime $creationDate) {
        $this->creationDate = $creationDate;
    }

    public function setLastUpdate(DateTime $lastUpdate) {
        $this->lastUpdate = $lastUpdate;
    }

}
