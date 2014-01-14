<?php

require_once 'BasicEntity.php';

/**
 * Description of HourType
 *
 * @author luis
 * @since Jan 13, 2014
 * 
 * @Entity @Table(name="activity_type")
 */
class ActivityType implements BasicEntity {

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
     * @Column(type="datetime", name="creation_date") 
     * @var DateTime
     */
    private $creationDate;

    /**
     *  @Column(type="datetime", name="last_update")
     * @var DateTime
     */
    private $lastUpdate;

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

    public function getActive() {
        return $this->active;
    }

    public function setActive($active) {
        $this->active = $active;
    }

    /**
     * 
     * @param ActivityType $activityType
     * @return boolean
     */
    public function equals($activityType) {
        if ($activityType !== null) {
            if (is_object($activityType) && $activityType instanceof ActivityType) {
                return $this->getId() === $activityType->getId();
            }
        }
        return false;
    }

    public function __toString() {
        return 'ActivityType@{id: ' . $this->id . ', name: ' . $this->name . '}';
    }

}
