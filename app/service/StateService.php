<?php
require_once 'BasicService.php';
require_once APP_DIR . 'model/StateDAO.php';
/**
 * Description of StateService
 *
 * @author luis
 * @since Jan 10, 2014
 * @property StateDAO $dao
 */
class StateService extends BasicService {
    
    public function __construct() {
        parent::__construct(new StateDAO());
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
     * @return State[]
     */
    public function findAll() {
        return $this->dao->findAll();
    }
}
