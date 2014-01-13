<?php

/**
 *
 * @author luis
 * @since Jan 13, 2014
 */
interface BasicEntity {
    /**
     * @return int
     */
    function getId();
    /**
     * @return DateTime
     */
    function getCreationDate();
    
    /**
     * @param DateTime $date
     */
    function setCreationDate(DateTime $date);
    /**
     * @param DateTime $date
     */
    function setLastUpdate(DateTime $date);
}
