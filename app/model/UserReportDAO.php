<?php

require_once APP_DIR . 'model/BasicDAO.php';
require_once APP_DIR . 'model/result/UserDayActivityResult.php';
require_once SERVICE_DIR.'utils/DateUtils.php';

/**
 * Description of ActivityReport
 *
 * @author luis
 * @since Jan 14, 2014
 */
class UserReportDAO extends BasicDAO {

    public function __construct() {
        parent::__construct('null');
    }

    public function findById($id) {
        throw new Exception("Not implemented yet");
    }

    public function save($obj) {
        throw new Exception("Not implemented yet");
    }

    public function update($obj) {
        throw new Exception("Not implemented yet");
    }

    /**
     * @param array $filters
     * @param integer $limit
     * @param integer $offset
     * @return UserActivityDetailResult[]

     */
    public function getUserActivityReport($filters = array(), $limit = NULL, $offset = NULL) {

        $startDate = $filters['startDate'];
        $endDate = $filters['endDate'];
        $user = $filters['user'];
        
        $userId = null;
        
        if($user == ''){
            throw new InvalidArgumentException("'user' param can't be null");
        } else if (is_object ($user) && $user instanceof User){
            $userId = $user->getId();
        } else if (is_numeric($user)){
            $userId = $user;
        } else {
            throw new InvalidArgumentException("expecting an instance of user or id from 'user' param");
        }

        if ($startDate === null) {
            $startDate = new DateTime('00:00:00');
        }

        if ($endDate === null) {
            $endDate = new DateTime('23:59:59');
        }

        $rsm = new \Doctrine\ORM\Query\ResultSetMappingBuilder($this->em);
        
        $rsm->addScalarResult('activity_id', 'activity_id', 'integer');
        $rsm->addScalarResult('name', 'name', 'string');
        $rsm->addScalarResult('start_date', 'start_date', 'datetime');
        $rsm->addScalarResult('end_date', 'end_date', 'datetime');
        $rsm->addScalarResult('allocated', 'allocated', 'string');
        $rsm->addScalarResult('day', 'day', 'string');

        $sql = 'select a.id as activity_id,
                       a.name,
                       ai.start_date, 
                       ai.end_date, 
                       (case when ai.end_date is not null then ai.end_date - ai.start_date else null end) as allocated, 
                       to_char(ai.start_date,\'DD/Mon\') as "day"
                  from activity a 
                  join activity_interaction ai on ai.activity_id = a.id 
                 where ai.start_date between :startDate and :endDate 
                   and a.active = true 
                   and ai.user_id = :userId
              order by "day", ai.start_date, a.name';

        $q = $this->em->createNativeQuery($sql, $rsm);

        $q->setParameter('startDate', $startDate, 'datetime');
        $q->setParameter('endDate', $endDate, 'datetime');
        $q->setParameter('userId', $userId, 'integer');
        
        $result = $q->getResult();

        $data = array();

        foreach ($result as $v) {
            
            $val = new UserDayActivityResult();
            
            $val->setActivityId($v['activity_id']);
            $val->setActivityName($v['name']);
            $val->setStartDate($v['start_date']);
            $val->setEndDate($v['end_date']);
            $val->setAllocated($v['allocated']);
            $val->setDate($v['day']);
            
            $data[] = $val;
        }

        return $data;
    }

}
