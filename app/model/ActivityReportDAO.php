<?php

require_once APP_DIR . 'model/result/UserActivityResult.php';
require_once APP_DIR . 'model/result/ActivityReportResult.php';

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
                       to_char(sum(ai.allocated_time),\'HH24:MI\') as allocated,
                       to_char(ai.creation_date, \'YYYY-MM-DD\') as wday
                  from users u
                  join activity_interaction ai on ai.user_id = u.id
                  join activity a on ai.activity_id = a.id
                  join user_work_day uw on uw.user_id = u.id and uw."date" =  ai.creation_date::date
                 where ai.creation_date between :startDate and :endDate
                   and u.active = true
                   and a.active = true
              group by u.id, wday, uw.id';

        $q = $this->em->createNativeQuery($sql, $rsm);

        $q->setParameter('startDate', $startDate, 'datetime');
        $q->setParameter('endDate', $endDate, 'datetime');

        $result = $q->getResult();

        $data = array();

        foreach ($result as $v) {

            $val = new UserActivityResult();

            $val->setUserId($v['userId']);
            $val->setUserName($v['userName']);
            $val->setUserAllocatedHours($v['allocated']);
            $val->setUserTotalHours($v['day_active_hour']);
            $val->setDate($v['day']);
            $data[] = $val;
        }

        return $data;
    }

    /**
     * 
     * @param array $filters
     * @param integer $limit
     * @param integer $offset
     * @return ActivityReportResult
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
                       to_char(sum(ai.allocated_time),\'HH24:MI\') as allocated,
                       to_char(ai.creation_date, \'YYYY-MM-DD\') as wday
                  from activity a
                  join activity_interaction ai on ai.activity_id = a.id
                 where ai.creation_date between :startDate and :endDate
                   and a.active = true
              group by a.id, wday';

        $q = $this->em->createNativeQuery($sql, $rsm);

        $q->setParameter('startDate', $startDate, 'datetime');
        $q->setParameter('endDate', $endDate, 'datetime');

        $dbResult = $q->getResult();

        $result = new \report\result\ActivityReportResult();

        foreach ($dbResult as $v) {
            $r = new report\result\ResultData(
                    $v['activityId'], $v['activityName'], $v['allocated'], $v['finished']);
            $result->add($r);
        }

        return $result;
    }

}