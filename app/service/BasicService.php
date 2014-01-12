<?php

/**
 * Description of BasicService
 *
 * @author luis
 * @since Jan 8, 2014
 */
abstract class BasicService {

    /**
     * @var BasicDAO
     */
    protected $dao;

    function __construct(BasicDAO $dao) {
        $this->dao = $dao;
    }

    function save($entity) {
        $this->validate($entity);
        try {
            // Begin Transaction
            $this->dao->save($entity);
            $this->dao->getEntityManager()->flush();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * 
     * @param User $entity
     * @throws Exception
     */
    function update($entity) {
        $this->validate($entity, false);
        try {
            // Begin Transaction
            $this->dao->update($entity);
            $this->dao->getEntityManager()->flush();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    
    public abstract function validate($entity, $save = true);
    
    public function findById($id) {
        return $this->dao->findById($id);
    }

    public abstract function search($filters = array(), $activeOnly = NULL, $limit = NULL, $offset = NULL);
    public abstract function searchCount($filters = array(), $activeOnly = NULL);
}
