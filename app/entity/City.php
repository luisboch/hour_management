<?php

/**
 * Description of City
 *
 * @author luis
 * @since Jan 10, 2014
 * @Entity @Table(name="city")
 */
class City {

    /** 
     * @Id @Column(type="integer") @GeneratedValue 
     */
    private $id;

    /**
     *  @Column(type="string") 
     */
    private $name;

    /**
     *  @ManyToOne(targetEntity="State")
     *  @JoinColumn(name="state_id", referencedColumnName="id")
     *  @var State
     */
    private $state;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getState() {
        return $this->state;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setState($state) {
        $this->state = $state;
    }

}
