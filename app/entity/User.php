<?php

/**
 * Description of User
 *
 * @author luis
 * @since Jan 7, 2014
 * @Entity @Table(name="users")
 */
class User {

    /** @Id @Column(type="integer") @GeneratedValue * */
    private $id;

    /** @Column(type="string") * */
    private $name;

    /** @Column(type="string") * */
    private $cpf;

    /** @Column(type="string") * */
    private $email;

    /** @Column(type="string") * */
    private $password;

    /**
     * @var boolean
     * @Column(type="boolean")
     */
    private $active = true;

    /**
     *
     * @var type 
     * @Column(type="datetime", name="creation_date")
     */
    private $creationDate;
    
    /**
     *
     * @var type 
     * @Column(type="datetime", name="last_access")
     */
    private $lastAccess;
    
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

    public function getActive() {
        return $this->active;
    }

    public function setActive($active) {
        $this->active = $active;
    }
    
    public function getCreationDate() {
        return $this->creationDate;
    }

    public function setCreationDate(DateTime $creationDate) {
        $this->creationDate = $creationDate;
    }
    
    public function getLastAccess() {
        return $this->lastAccess;
    }

    public function setLastAccess(DateTime $lastAccess) {
        $this->lastAccess = $lastAccess;
    }


}
