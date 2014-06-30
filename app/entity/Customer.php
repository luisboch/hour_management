<?php

/**
 * Description of Customer
 *
 * @author luis
 * @since Jun 30, 2014
 * 
 * @Entity @Table(name="customer")
 */
class Customer implements BasicEntity {

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

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getLastUpdate() {
        return $this->lastUpdate;
    }

    public function getActive() {
        return $this->active;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setCreationDate(DateTime $creationDate) {
        $this->creationDate = $creationDate;
    }

    public function setLastUpdate(DateTime $lastUpdate) {
        $this->lastUpdate = $lastUpdate;
    }

    public function setActive($active) {
        $this->active = $active;
    }

    public function __toString() {
        return "Customer:{name: " . $this->name . ", id: " . $this->id . ", active: " . $this->active ? 'true' : 'false' . "}";
    }

}
