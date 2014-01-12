<?php
require_once 'BasicDAO.php';
require_once APP_DIR . 'entity/City.php';

/**
 * Description of CityDAO
 *
 * @author luis
 * @since Jan 10, 2014
 */
class CityDAO extends BasicDAO{
    
    function __construct() {
        parent::__construct('City');
    }
    
    public function findCities($stateId){
        $dql = ""
                . "  select c "
                . "    from City c"
                . "    join c.state s "
                . "   where s.id = ?1 "
                . "order by c.name";
        
        $q = $this->em->createQuery($dql);
        
        $q->setParameter(1, $stateId);
        
        return $q->getResult();
    }
}
