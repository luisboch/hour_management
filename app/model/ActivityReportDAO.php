<?php

require_once APP_DIR . 'model/BasicDAO.php';
require_once APP_DIR . 'model/result/UserActivityResult.php';
require_once APP_DIR . 'model/result/ActivityReportResult.php';
require_once APP_DIR . 'model/result/ActivityReportTypeResult.php';
require_once APP_DIR . 'model/result/WorkReportResult.php';

/**
 * Description of ActivityReport
 *
 * @author luis
 * @since Jan 14, 2014
 */
class ActivityReportDAO extends BasicDAO {

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
     * @return UserActivityResult[]
     */
    public function getUserActiveReport($filters = array(), $limit = NULL, $offset = NULL) {

        $startDate = $filters['startDate'];
        $endDate = $filters['endDate'];

        if ($startDate === null) {
            $startDate = new DateTime('00:00:00');
        }

        if ($endDate === null) {
            $endDate = new DateTime('23:59:59');
        }

        $rsm = new \Doctrine\ORM\Query\ResultSetMappingBuilder($this->em);

        $rsm->addScalarResult('id', 'userId', 'integer');
        $rsm->addScalarResult('name', 'userName', 'string');
        $rsm->addScalarResult('wd', 'day_active_hour', 'time');
        $rsm->addScalarResult('allocated', 'allocated', 'string');
        $rsm->addScalarResult('wday', 'day', 'date');

        $sql = 'select u.id, 
                       u.name, 
                       uw.day_active_hour as wd,
                       to_char(sum((ai.end_date - ai.start_date)::time),\'HH24:MI\') as allocated,
                       to_char(ai.start_date, \'YYYY-MM-DD\') as wday
                  from users u
                  join activity_interaction ai on ai.user_id = u.id
                  join activity a on ai.activity_id = a.id
                  join user_work_day uw on uw.user_id = u.id and uw."date" =  ai.start_date::date
                 where ai.start_date between :startDate and :endDate
                   and a.active = true
                   and ai.end_date is not null
              group by u.id, wday, uw.id
              order by wday, u.name';

        $q = $this->em->createNativeQuery($sql, $rsm);

        $q->setParameter('startDate', $startDate, 'datetime');
        $q->setParameter('endDate', $endDate, 'datetime');

        $result = $q->getResult();

        $data = array();

        foreach ($result as $v) {

            $val = new UserActivityResult();

            $val->setUserId($v['userId']);
            $val->setUserName($v['userName']);
            $val->setUserAllocatedHours(new DateTime($v['allocated']));
            $val->setUserTotalHours($v['day_active_hour']);
            $val->setDate($v['day']);

            if ($val->getUserTotalHours()->getTimestamp() < $val->getUserAllocatedHours()->getTimestamp()) {
                $diff = $val->getUserTotalHours()->diff($val->getUserAllocatedHours());
                /* @var DateInterval $diff */
                $val->setExtra(new DateTime($diff->format('%H:%I:%S')));
            }
            $data[] = $val;
        }

        return $data;
    }

    /**
     * 
     * @param array $filters
     * @param integer $limit
     * @param integer $offset
     * @return \report\result\ActivityReportResult
     */
    public function getActivityReport($filters = array(), $limit = NULL, $offset = NULL) {

        $startDate = $filters['startDate'];
        $endDate = $filters['endDate'];

        if ($startDate === null) {
            $startDate = new DateTime('00:00:00');
        }

        if ($endDate === null) {
            $endDate = new DateTime('23:59:59');
        }

        $rsm = new \Doctrine\ORM\Query\ResultSetMappingBuilder($this->em);

        $rsm->addScalarResult('id', 'activityId', 'integer');
        $rsm->addScalarResult('name', 'activityName', 'string');
        $rsm->addScalarResult('allocated', 'allocated', 'string');
        $rsm->addScalarResult('finished', 'finished', 'boolean');
        $rsm->addScalarResult('wday', 'day', 'date');

        $sql = 'select a.id,
                       a.name, 
                       a.status = 1 as finished,
                       to_char(sum((ai.end_date - ai.start_date)::time),\'HH24:MI\') as allocated,
                       to_char(ai.start_date, \'YYYY-MM-DD\') as wday
                  from activity a
                  join activity_interaction ai on ai.activity_id = a.id
                 where ai.start_date between :startDate and :endDate
                   and a.active = true
                   and ai.end_date is not null
              group by a.id, wday
              order by wday, a.name';

        $q = $this->em->createNativeQuery($sql, $rsm);

        $q->setParameter('startDate', $startDate, 'datetime');
        $q->setParameter('endDate', $endDate, 'datetime');

        $dbResult = $q->getResult();

        $result = new \report\result\ActivityReportResult();

        foreach ($dbResult as $v) {
            $r = new report\result\ResultData(
                    $v['activityId'], $v['activityName'], $v['allocated'], $v['finished'], $v['day']);
            $result->add($r);
        }

        return $result;
    }

    /**
     * 
     * @param array $filters
     * @param integer $limit
     * @param integer $offset
     * @return \report\result\ActivityReportTypeResult[]
     */
    public function getActivityTypeReport($filters = array(), $limit = NULL, $offset = NULL) {

        $startDate = $filters['startDate'];
        $endDate = $filters['endDate'];

        if ($startDate === null) {
            $startDate = new DateTime('00:00:00');
        }

        if ($endDate === null) {
            $endDate = new DateTime('23:59:59');
        }

        $rsm = new \Doctrine\ORM\Query\ResultSetMappingBuilder($this->em);

        $rsm->addScalarResult('username', 'userName', 'string');
        $rsm->addScalarResult('activitytype', 'activityType', 'string');
        $rsm->addScalarResult('finished', 'finished', 'boolean');
        $rsm->addScalarResult('allocated', 'allocated', 'string');
        $rsm->addScalarResult('wday', 'day', 'date');

        $sql = "select u.name as username,
                       t.name as activitytype, 
                       a.status = 1 as finished,
                       to_char(sum((ai.end_date - ai.start_date)::time),'HH24:MI') as allocated,
                       to_char(ai.start_date, 'YYYY-MM-DD') as wday
                  from activity a
                  join activity_interaction ai on ai.activity_id = a.id
                  join activity_type t on a.type_id = t.id
                  join users u on u.id = ai.user_id
                 where ai.start_date between :startDate and :endDate
                   and a.active = true
                   and ai.end_date is not null
              group by userName, activityType, finished, wday
              order by wday, userName, activityType, finished";

        $q = $this->em->createNativeQuery($sql, $rsm);

        $q->setParameter('startDate', $startDate, 'datetime');
        $q->setParameter('endDate', $endDate, 'datetime');

        $dbResult = $q->getResult();
        $results = array();

        foreach ($dbResult as $v) {
            $results[] = new \report\result\ActivityReportTypeResult(
                    $v['activityType'], $v['userName'], $v['finished'], $v['allocated'], $v['day']);
        }

        return $results;
    }

    public function getWorkReport($filters = array(), $limit = NULL, $offset = NULL) {

        $startDate = $filters['startDate'];
        $endDate = $filters['endDate'];
        $user = $filters['user'];
        
        if ($startDate === null) {
            $startDate = new DateTime('00:00:00');
        }

        if ($endDate === null) {
            $endDate = new DateTime('23:59:59');
        }
        
        $rsm = new \Doctrine\ORM\Query\ResultSetMappingBuilder($this->em);

        $rsm->addScalarResult('username', 'userName', 'string');
        $rsm->addScalarResult('userid', 'userId', 'integer');
        $rsm->addScalarResult('start_work', 'startWork', 'datetime');
        $rsm->addScalarResult('end_work', 'endWork', 'datetime');
        $rsm->addScalarResult('active_hour', 'activeHour', 'string');
        $rsm->addScalarResult('wday', 'day', 'date');

        $sql = "select u.name as username,
                       u.id as userid, 
                       w.start_work,
                       w.end_work,
                       w.day_active_hour as active_hour,
                       to_char(w.\"date\", 'YYYY-MM-DD') as wday
                  from user_work_day w
                  join users u on w.user_id = u.id
                 where w.\"date\" between :startDate and :endDate\n";
        
        if($user != null){
            $sql .= "and u.id = :user\n";
        }
        
        $sql .= "order by wday, userName";

        $q = $this->em->createNativeQuery($sql, $rsm);

        $q->setParameter('startDate', $startDate, 'datetime');
        $q->setParameter('endDate', $endDate, 'datetime');
        
        if($user != null){
            $q->setParameter('user', $user->getId(), 'integer');
        }
        
        $dbResult = $q->getResult();
        $results = array();

        foreach ($dbResult as $v) {
            $r = new \report\result\WorkReportResult(
                    $v['userName'], $v['userId'], $v['startWork'], $v['endWork'], $v['day'], $v['activeHour']);
            
            $r->calculateExtra();
            
            $results[] = $r;
        }

        return $results;
    }

}
