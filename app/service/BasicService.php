<?php

require_once 'SessionManager.php';
require_once SERVICE_DIR . 'exceptions/ValidationException.php';

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

    /**
     * @var SessionManager
     */
    protected $session;

    function __construct(BasicDAO $dao) {
        $this->dao = $dao;
        $this->session = SessionManager::getInstance();
    }

    /**
     * 
     * @param BasicEntity entity
     * @throws Exception
     */
    function save(BasicEntity $entity) {
        $this->validate($entity);

        // Set creation date and last upadte values
        $date = new DateTime();
        $entity->setCreationDate($date);
        $entity->setLastUpdate($date);

        try {
            // Begin Transaction
            $this->saveRelations($entity);
            $this->dao->save($entity);
            $this->dao->getEntityManager()->flush();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    protected function saveRelations(BasicEntity $entity) {
        // nothing to do at default ( @see ActivityService )
    }

    /**
     * 
     * @param User $entity
     * @throws Exception
     */
    function update(BasicEntity $entity) {

        $this->validate($entity, false);

        $entity->setLastUpdate(new DateTime());

        try {
            // Begin Transaction
            $this->saveRelations($entity);
            $this->dao->update($entity);
            $this->dao->getEntityManager()->flush();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public abstract function validate($entity, $newObject = true);

    /**
     * 
     * @param integer $id
     * @return BasicEntity
     */
    public function findById($id) {
        return $this->dao->findById($id);
    }

    /**
     * 
     * @param array $filter
     * Can user to search for all properties
     * 
     * example:
     * [
     *      "name" => "My Name",
     *      "description" => "myemail.com"
     * ]
     * 
     * will generate filter like:
     *  where lower(name) like lower('%My%Name%') 
     *    and lower(email) like lower('%myemail.com%')
     * 
     * another way is use the generic term "search"
     * example: 
     * 
     * [
     *      "search" => "MySearch"
     * ]
     * 
     * will generate filter like:
     *  where lower(name) like lower('%MySearch%') 
     *    and lower(email) like lower('%MySearch%')
     *
     * Note: Do not use both option a the same time.
     * 
     * @param boolean $activeOnly
     * @param int $limit
     * @param int $offset
     * @return User[]
     */
    public function search($filters = array(), $activeOnly = NULL, $limit = NULL, $offset = NULL) {
        return $this->dao->search($filters, $activeOnly, $limit, $offset);
    }

    /**
     * @param array $filters
     * Can user to search for all properties
     * 
     * example:
     * [
     *      "name" => "My Name",
     *      "email" => "myemail.com"
     * ]
     * 
     * will generate filter like:
     *  where lower(name) like lower('%My%Name%') 
     *    and lower(email) like lower('%myemail.com%')
     * 
     * another way is use the generic term "search"
     * example: 
     * 
     * [
     *      "search" => "MySearch"
     * ]
     * 
     * will generate filter like:
     *  where lower(name) like lower('%MySearch%') 
     *    and lower(email) like lower('%MySearch%')
     *
     * Note: Do not use both option a the same time.
     * 
     * 
     * @param boolean $activeOnly
     * @return int
     */
    public function searchCount($filters = array(), $activeOnly = NULL) {
        return $this->dao->searchCount($filters, $activeOnly);
    }
    
    
    /**
     * @param DateTime $date
     * @return DateTime
     */
    public function getNextWorkDate(DateTime $date = null) {
        return $this->dao->getNextWorkDate($date);
    }
    
    /**
     * @param DateTime $date
     * @return type
     */
    public function getPreviousWorkDate(DateTime $date = null) {
        return $this->dao->getPreviousWorkDate($date);
    }

}
