<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Holiday
 *
 * @author luis
 * @since May 05, 2014
 * @Entity @Table(name="holiday")
 */
class Holiday implements BasicEntity {
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
     * @var boolean
     * @Column(type="boolean")
     */
    private $active = true;
    
    
    /**
     * @var boolean
     * @Column(type="date")
     */
    private $date;
    
    public function getId() {
        return $this->id;
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

    public function getActive() {
        return $this->active;
    }

    public function getDate() {
        return $this->date;
    }

    public function setId($id) {
        $this->id = $id;
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

    public function setActive($active) {
        $this->active = $active;
    }

    public function setDate($date) {
        $this->date = $date;
    }
}
