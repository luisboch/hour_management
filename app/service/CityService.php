<?php

require_once 'BasicService.php';
require_once APP_DIR . 'model/CityDAO.php';

/**
 * Description of StateService
 *
 * @author luis
 * @since Jan 10, 2014
 * @property CityDAO $dao
 */
class CityService extends BasicService {

    public function __construct() {
        parent::__construct(new CityDAO());
    }

    public function searchCount($filters = array(), $activeOnly = NULL) {
        throw new LogicException("Not implemented Yet!");
    }

    public function search($filters = array(), $activeOnly = NULL, $limit = NULL, $offset = NULL) {
        throw new LogicException("Not implemented Yet!");
    }

    public function validate($entity, $save = true) {
        throw new LogicException("Not implemented Yet!");
    }
    
    /**
     * 
     * @param State $state
     * @return City[]
     */
    public function findCitiesByState($stateId) {
        return $this->dao->findCities($stateId);
    }

}
