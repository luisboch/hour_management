<?php

require_once 'BasicEntity.php';

/**
 * Description of User
 *
 * @author luis
 * @since Jan 7, 2014
 * @Entity @Table(name="users")
 */
class User implements BasicEntity {

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
    private $cpf;

    /**
     * @Column(type="string")
     */
    private $email;

    /**
     * @Column(type="string")
     */
    private $password;

    /** @Column(type="time", name="day_active_hour") * */
    private $dayActiveHour;

    /**
     * @var boolean
     * @Column(type="boolean")
     */
    private $active = true;

    /**
     *
     * @var DateTime 
     * @Column(type="datetime", name="creation_date")
     */
    private $creationDate;

    /**
     *
     * @var DateTime 
     * @Column(type="datetime", name="last_access")
     */
    private $lastAccess;

    /**
     *
     * @var DateTime 
     * @Column(type="datetime", name="last_update")
     */
    private $lastUpdate;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getDayActiveHour() {
        return $this->dayActiveHour;
    }

    public function getActive() {
        return $this->active;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getLastAccess() {
        return $this->lastAccess;
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

    public function setCpf($cpf) {
        $this->cpf = $cpf;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setDayActiveHour(DateTime $dayActiveHour) {
        $this->dayActiveHour = $dayActiveHour;
    }

    public function setActive($active) {
        $this->active = $active;
    }

    public function setCreationDate(DateTime $creationDate) {
        $this->creationDate = $creationDate;
    }

    public function setLastAccess(DateTime $lastAccess) {
        $this->lastAccess = $lastAccess;
    }

    public function setLastUpdate(DateTime $date) {
        $this->lastUpdate = $date;
    }

}