<?php

require_once APP_DIR . 'model/BasicDAO.php';
require_once APP_DIR . 'model/result/UserActivityResult.php';
require_once APP_DIR . 'model/result/ActivityReportResult.php';
require_once APP_DIR . 'model/result/ActivityReportTypeResult.php';
require_once APP_DIR . 'model/result/WorkReportResult.php';
require_once SERVICE_DIR . 'utils/DateUtils.php';

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
        $rsm->addScalarResult('start_work', 'start_work', 'datetime');
        $rsm->addScalarResult('end_work', 'end_work', 'datetime');
        $rsm->addScalarResult('allocated', 'allocated', 'string');
        $rsm->addScalarResult('wday', 'day', 'date');

        $sql = 'select u.id, 
                       u.name, 
                       uw.day_active_hour as wd,
                       uw.start_work,
                       uw.end_work,
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
            $val->setStartWork($v['start_work']);
            $val->setEndWork($v['end_work']);

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
        $customer = $filters['customer'];

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
        $rsm->addScalarResult('customerid', 'customerId', 'integer');
        $rsm->addScalarResult('customername', 'customerName', 'string');
        $rsm->addScalarResult('userid', 'userId', 'integer');
        $rsm->addScalarResult('username', 'userName', 'string');
        $rsm->addScalarResult('typename', 'typeName', 'string');
        $rsm->addScalarResult('wday', 'day', 'date');

        $sql = 'select a.id,
                       a.name, 
                       a.status = 1 as finished,
                       c.id as customerId,
                       c.name as customerName,
                       u.name as userName,
                       u.id as userId, 
                       t.name as typeName,
                       to_char(sum((ai.end_date - ai.start_date)::time),\'HH24:MI:SS\') as allocated,
                       to_char(ai.start_date, \'YYYY-MM-DD\') as wday
                  from activity a
                  join customer c on c.id = a.customer_id
                  join activity_interaction ai on ai.activity_id = a.id
                  join users u on u.id = ai.user_id
                  join activity_type t on a.type_id = t.id
                 where ai.start_date between :startDate and :endDate
                   and a.active = true
                   and ai.end_date is not null
                   ';

        if ($customer != '') {
            $sql .= "and c.id = :customer \n";
        }

        $sql .='
              group by a.id, wday, c.id, u.id, t.id
              order by wday, customerName, u.name, t.name, a.name';

        $q = $this->em->createNativeQuery($sql, $rsm);

        $q->setParameter('startDate', $startDate, 'datetime');
        $q->setParameter('endDate', $endDate, 'datetime');

        if ($customer != '') {
            $q->setParameter("customer", $customer);
        }
        
        $dbResult = $q->getResult();

        $result = new \report\result\ActivityReportResult();
        
        $totalAllocated = 0;
        
        foreach ($dbResult as $v) {
            
            $r = new report\result\ResultData(
                    $v['activityId'], $v['activityName'], $v['allocated'], 
                    $v['finished'], $v['day'], $v['customerId'], $v['customerName'],
                    $v['userId'], $v['userName'], $v['typeName']);
            $totalAllocated += DateUtils::getFloat($r->getAllocated());
            $result->add($r);
            
        }
        
        $result->setTotalHour(DateUtils::getHour($totalAllocated));

        return $result;
    }

    /**
     * 
     * @param array $filters
     * @param integer $limit
     * @param integer $offset
     * @return \report\result\ActivityReportTypeTotal
     */
    public function getActivityTypeReport($filters = array()) {

        $startDate = $filters['startDate'];
        $endDate = $filters['endDate'];

        if ($startDate === null) {
            $startDate = new DateTime('00:00:00');
        }

        if ($endDate === null) {
            $endDate = new DateTime('23:59:59');
        }

        $customer = $filters['customer'];
        $user = $filters['user'];
        $type = $filters['type'];

        $report = new report\result\ActivityReportTypeTotal();

        // Retrieve details 
        $report->setTypesDetails(
                $this->getTypeReportDetails($startDate, $endDate, $customer, $type, $user));

        // Retrieve Indicators
        $report->setIndicators(
                $this->getTypeReportIndicators($startDate, $endDate, $customer, $type, $user));
        // Retrieve Indicators by Type
        $report->setTypeIndicators(
                $this->getTypeDetailedReportIndicators($startDate, $endDate, $customer, $type, $user));

        // Retrieve Indicators by User
        $report->setUserIndicators($this->getTypeUserReportIndicators($startDate, $endDate, $customer, $type, $user));

        require_once APP_DIR . 'model/HolidayDAO.php';
        $holidayDAO = new HolidayDAO();

        $report->setHolidays($holidayDAO->getHolidays($startDate, $endDate));

        return $report;
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param Customer $customer
     * @param ActivityType $type
     * @param User $user
     * @return \report\result\ActivityReportTypeResult[]
     */
    private function getTypeUserReportIndicators(DateTime $startDate, DateTime $endDate, $customer, $type, $user) {

        $rsm = new \Doctrine\ORM\Query\ResultSetMappingBuilder($this->em);

        $rsm->addScalarResult('user_name', 'user_name', 'string');
        $rsm->addScalarResult('remaining_time', 'remaining_time', 'string');
        $rsm->addScalarResult('avaliable_time', 'avaliable_time', 'string');
        $rsm->addScalarResult('allocated_time', 'allocated_time', 'string');

        $sql = "select user_name, allocated_time, avaliable_time, avaliable_time - allocated_time as remaining_time
                  from (select user_name, sum(allocated_time) as allocated_time, sum(avaliable_time) as avaliable_time
                          from (select u.name as user_name, sum(coalesce( ai.end_date - ai.start_date, '00:00:00'::interval)) as allocated_time, getAvaliableTime(dates.dt, u.id) as avaliable_time,  dates.dt
                                  from (select CURRENT_DATE + i as dt from generate_series(date '" . DateUtils::toDataBaseDate($startDate) . "' - CURRENT_DATE, date '" . DateUtils::toDataBaseDate($endDate) . "' - CURRENT_DATE ) i) as dates
                             left join users u on u.active = true and u.id != 1 -- It ignore administrator.
                             left join activity_interaction ai on (ai.user_id = u.id and ai.start_date::date = dates.dt and ai.end_date is not null)
                             left join activity a on (ai.activity_id = a.id and a.active = true)
                                 where extract (DOW from dates.dt) in (1,2,3,4,5)
                                   and dates.dt not in (select h.date from holiday h where h.active = true)
                                   ";

        if ($customer != null) {
            $sql .= 'and a.customer_id = ' . $customer->getId() . "\n";
        }

        if ($type != null) {
            $sql .= 'and a.type_id = ' . $type->getId() . "\n";
        }

        if ($user != null) {
            $sql .= 'and ai.user_id = ' . $user->getId() . "\n";
        }

        $sql .= "
                              group by u.id , dates.dt)
                            as tmp
                      group by tmp.user_name) as tmp2
              order by user_name";

        $q = $this->em->createNativeQuery($sql, $rsm);

        $q->setParameter('startDate', $startDate, 'date');
        $q->setParameter('endDate', $endDate, 'date');

        $dbResult = $q->getResult();

        $results = array();

        foreach ($dbResult as $v) {

            $r = new \report\result\ActivityReportTypeUserTotal();

            $r->setUserName($v['user_name']);
            $r->setRemainingTime($v['remaining_time']);
            $r->setAvaliableTime($v['avaliable_time']);
            $r->setAllocatedTime($v['allocated_time']);

            $results[] = $r;
        }

        return $results;
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param Customer $customer
     * @param ActivityType $type
     * @param User $user
     * @return \report\result\ActivityReportTypeResult[]
     */
    private function getTypeDetailedReportIndicators(DateTime $startDate, DateTime $endDate, $customer, $type, $user) {

        $rsm = new \Doctrine\ORM\Query\ResultSetMappingBuilder($this->em);

        $rsm->addScalarResult('activitytype', 'activityType', 'string');
        $rsm->addScalarResult('allocated', 'allocated', 'string');

        $sql = "select t.name as activitytype, 
                       coalesce(sum(ai.end_date - ai.start_date),'00:00:00'::interval) as allocated
                  from activity a
                  join activity_interaction ai on ai.activity_id = a.id
                  join activity_type t on a.type_id = t.id
                  join users u on u.id = ai.user_id and u.active = true
                 where ai.start_date between :startDate and :endDate
                   and a.active = true
                   and ai.end_date is not null\n";

        if ($customer != null) {
            $sql .= "and a.customer_id = " . $customer->getId() . "\n";
        }

        if ($user != null) {
            $sql .= "and ai.user_id = " . $user->getId() . "\n";
        }

        if ($type != null) {
            $sql .= "and a.type_id = " . $type->getId() . "\n";
        }

        $sql .= "group by activityType
                 order by activityType";

        $q = $this->em->createNativeQuery($sql, $rsm);

        $q->setParameter('startDate', $startDate, 'datetime');
        $q->setParameter('endDate', $endDate, 'datetime');

        $dbResult = $q->getResult();
        $results = array();

        foreach ($dbResult as $v) {
            $r = new \report\result\ActivityReportTypeGroupedResult();
            $r->setActivityType($v['activityType']);
            $r->setAllocatedTime($v['allocated']);
            $results[] = $r;
        }

        return $results;
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param Customer $customer
     * @param ActivityType $type
     * @param User $user
     * @return \report\result\ActivityReportTypeDetailResult[]
     */
    private function getTypeReportDetails(DateTime $startDate, DateTime $endDate, $customer, $type, $user) {

        $rsm = new \Doctrine\ORM\Query\ResultSetMappingBuilder($this->em);

        $rsm->addScalarResult('username', 'userName', 'string');
        $rsm->addScalarResult('activitytype', 'activityType', 'string');
        $rsm->addScalarResult('allocated', 'allocated', 'string');
        $rsm->addScalarResult('wday', 'day', 'date');
        $rsm->addScalarResult('customer', 'customer', 'string');

        $sql = "select u.name as username,
                       t.name as activitytype, 
                       c.name as customer,
                       coalesce(sum(ai.end_date - ai.start_date),'00:00:00'::interval) as allocated,
                       to_char(ai.start_date, 'YYYY-MM-DD') as wday
                  from activity a
                  join activity_interaction ai on ai.activity_id = a.id
                  join activity_type t on a.type_id = t.id
                  join customer c on c.id = a.customer_id
                  join users u on u.id = ai.user_id and u.active = true
                 where ai.start_date::date between 
                         '" . DateUtils::toDataBaseDate($startDate) . "' and '" . DateUtils::toDataBaseDate($endDate) . "'\n";

        if ($customer != null) {
            $sql .= "and a.customer_id = " . $customer->getId() . "\n";
        }

        if ($type != null) {
            $sql .= "and a.type_id = " . $type->getId() . "\n";
        }

        if ($user != null) {
            $sql .= "and ai.user_id = " . $user->getId() . "\n";
        }

        $sql .="
                   and a.active = true
                   and ai.end_date is not null
              group by userName, activityType, wday, c.id
              order by wday, userName, activityType";

        $q = $this->em->createNativeQuery($sql, $rsm);

        $q->setParameter('startDate', $startDate, 'datetime');
        $q->setParameter('endDate', $endDate, 'datetime');

        $dbResult = $q->getResult();
        $results = array();

        foreach ($dbResult as $v) {
            $results[] = new \report\result\ActivityReportTypeDetailResult(
                    $v['activityType'], $v['userName'], $v['allocated'], $v['day'], $v['customer']);
        }

        return $results;
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param Customer $customer
     * @param ActivityType $type
     * @param User $user
     * @return \report\result\ActivityReportTypeIndicator
     */
    private function getTypeReportIndicators(DateTime $startDate, DateTime $endDate, $customer, $type, $user) {

        $rsm = new \Doctrine\ORM\Query\ResultSetMappingBuilder($this->em);

        $rsm->addScalarResult('avaliable_time', 'avaliable_time', 'string');
        $rsm->addScalarResult('allocated_time', 'allocated_time', 'string');
        $rsm->addScalarResult('remaining_time', 'remaining_time', 'string');

        $sql = 'select avaliable_time, allocated_time, avaliable_time - allocated_time as remaining_time
                       from (select sum(avaliable_time) as avaliable_time, sum(allocated_time) as allocated_time
                                     from (
                                     select dates.dt,getAvaliableTime(dates.dt) as avaliable_time ,
                                                 sum(
                                                 case when w.id is null
                                                            then \'00:00:00\'::interval
                                                  else (select COALESCE(sum(ai_inner.end_date - ai_inner.start_date), \'00:00:00\'::interval) as allocated_time
                                                            from activity_interaction ai_inner 
                                                            join activity a_inner on (a_inner.id = ai_inner.activity_id and a_inner.active = true)
                                                           where ai_inner.user_id = u.id 
                                                             and ai_inner.start_date::date = dates.dt               
                                                             and ai_inner.end_date is not null
                                                             ';

        if ($type != null) {
            $sql .= '                                       and a_inner.type_id = ' . $type->getId();
        }

        if ($customer != null) {
            $sql .= '                                       and a_inner.customer_id = ' . $customer->getId();
        }

        if ($user != null) {
            $sql .= '                                       and ai_inner.user_id = ' . $user->getId();
        }

        $sql .= ') 
                                                             
                
                                                     end
                                             ) as allocated_time
                                      from (select CURRENT_DATE + i as dt from generate_series(date \'' . DateUtils::toDataBaseDate($startDate) . '\' - CURRENT_DATE, date \'' . DateUtils::toDataBaseDate($endDate) . '\' - CURRENT_DATE ) i) as dates
                                 left join user_work_day w on w."date" = dates.dt
                                 left join users u on u.id = w.user_id and u.active = true ' . ($user != null ? 'and u.id = ' . $user->getId() : '') . '
                                     where extract (DOW from dates.dt) in (1,2,3,4,5)
                                       and dates.dt not in (select h.date from holiday h where h.active = true)
                                  group by dates.dt
                               ) as tmp
                        ) as tmp2';

        $q = $this->em->createNativeQuery($sql, $rsm);

        $q->setParameter('startDate', $startDate, 'datetime');
        $q->setParameter('endDate', $endDate, 'datetime');

        $dbResult = $q->getResult();
        $result = new report\result\ActivityReportTypeIndicator();
        $result->setAvaliableTime($dbResult[0]['avaliable_time']);
        $result->setAllocatedNormalTime($dbResult[0]['allocated_time']);
        $result->setRemainingTime($dbResult[0]['remaining_time']);

        return $result;
    }

    /**
     * @param type $filters
     * @param type $limit
     * @param type $offset
     * @return array
     */
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
        $rsm->addScalarResult('total', 'total', 'string');
        $rsm->addScalarResult('discount', 'discount', 'string');
        $rsm->addScalarResult('balance', 'balance', 'string');

        $sql = "select tb.*, (total - discount) as balance 
                  from (select u.name as username,
                               u.id as userid,
                               w.start_work,
                               w.end_work,
                               w.day_active_hour as active_hour,
                               to_char(dates.dt, 'YYYY-MM-DD') as wday,
                               case
                                    when (w.end_work - w.start_work)::time(0) > (w.day_active_hour + interval '01 hours')::time(0) then ((w.end_work - w.start_work)::time(0) - (w.day_active_hour + interval '01 hours' )::time(0))::time(0) else '00:00:00'::time(0)
                               end as total,
                               case
                                    when w.start_work is null or ((w.end_work - w.start_work)::time(0) < (w.day_active_hour + interval '01 hours')::time(0))
                               then (((coalesce(w.end_work, dates.dt::timestamp(0)) - coalesce(w.start_work, dates.dt::timestamp(0)) )::time(0) - (coalesce(w.day_active_hour, u.day_active_hour) + ( case when w.start_work is null then interval '0 hours' else interval '01 hours' end) )::time(0)) * -1 )::time(0) else '00:00:00'::time(0)
                               end as discount

                          from (select CURRENT_DATE + i as dt from generate_series(date '" . DateUtils::toDataBaseDate($startDate) . "' - CURRENT_DATE, date '" . DateUtils::toDataBaseDate($endDate) . "' - CURRENT_DATE ) i) as dates\n
                     left join users u on u.id = :user\n
                     left join user_work_day w on w.\"date\" = dates.dt and w.user_id = u.id\n
                         where extract (DOW from dates.dt) in (1,2,3,4,5)\n
                           and dates.dt not in (select h.date from holiday h where h.active = true)\n
                      order by wday, userName\n
                ) as tb \n";


        $sql .= "order by wday, userName";


        $q = $this->em->createNativeQuery($sql, $rsm);

        $q->setParameter('startDate', $startDate, 'datetime');
        $q->setParameter('endDate', $endDate, 'datetime');

        if ($user != null) {
            $q->setParameter('user', $user->getId(), 'integer');
        }

        $dbResult = $q->getResult();
        $results = array();

        $rsm->addScalarResult('username', 'userName', 'string');
        $rsm->addScalarResult('userid', 'userId', 'integer');
        $rsm->addScalarResult('start_work', 'startWork', 'datetime');
        $rsm->addScalarResult('end_work', 'endWork', 'datetime');
        $rsm->addScalarResult('active_hour', 'activeHour', 'string');
        $rsm->addScalarResult('wday', 'day', 'date');
        $rsm->addScalarResult('total', 'total', 'string');
        $rsm->addScalarResult('discount', 'discount', 'string');
        $rsm->addScalarResult('balance', 'balance', 'string');

        foreach ($dbResult as $v) {
            $r = new \report\result\WorkReportResult(
                    $v['userName'], $v['userId'], $v['startWork'], $v['endWork'], $v['day'], $v['activeHour'], $v['total'], $v['discount'], $v['balance']);

            $results[] = $r;
        }
        $result = array();
        $result['results'] = $results;
        $result['total'] = $this->calculateTotal($results);
        return $result;
    }

    private function calculateTotal($rs = array()) {
        if ($rs != '' && is_array($rs) && count($rs) > 0) {

            $total = 0;
            foreach ($rs as $v) {
                /* $v \report\result\WorkReportResult */
                $float = DateUtils::getFloat($v->getBalance());

                $total += $float;
            }

            return DateUtils::getHour($total);
        }

        return '';
    }

}
