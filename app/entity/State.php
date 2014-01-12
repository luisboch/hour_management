<?php

/**
 * Description of State
 *
 * @author luis
 * @since Jan 10, 2014
 * @Entity @Table(name="state")
 */
class State {
    
    /**
     *  @Id @Column(type="integer") @GeneratedValue
     */
    private $id;
    
    /** 
     * @Column(type="string")
     */
    private $name;
    
    /**
     * @Column(type="string")
     */
    private $code;
            
    
    public  function getId() {
        return $this->id;
    }

    /** @Column(type="string") * */
    public function getName() {
        return $this->name;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }
    public function getCode() {
        return $this->code;
    }

    public function setCode($code) {
        $this->code = $code;
    }
    
}