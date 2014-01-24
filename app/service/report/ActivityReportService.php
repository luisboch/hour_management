<?php

require_once SERVICE_DIR . 'BasicService.php';
require_once APP_DIR . 'model/ActivityReportDAO.php';

/**
 * Description of ActivityReportService
 *
 * @author luis
 * @since Jan 14, 2014
 * @property ActivityReportDAO $dao
 */
class ActivityReportService extends BasicService {

    function __construct() {
        parent::__construct(new ActivityReportDAO());
    }

    public function validate($entity, $newObject = true) {
        throw new Exception("Not implemented");
    }

    /**
     * @param array $filters
     * @param integer $limit
     * @param integer $offset
     * @return UserActivityResult[]
     */
    public function getUserActiveReport($filters = array(), $limit = NULL, $offset = NULL) {
        $data = $this->dao->getUserActiveReport($filters, $limit, $offset);

        foreach ($data as $v) {
            $v->setPercentFinished($this->calculeConclusionPercent($v->getUserTotalHours(), $v->getUserAllocatedHours()));
            $v->setAvaliable($this->calculeAvaliable($v->getUserTotalHours(), $v->getUserAllocatedHours()));
        }

        return $data;
    }

    /**
     * @param array $filters
     * @param integer $limit
     * @param integer $offset
     * @return ActivityReportResult
     */
    public function getActivityReport($filters = array(), $limit = NULL, $offset = NULL) {
        return $this->dao->getActivityReport($filters, $limit, $offset);
    }

    private function calculeAvaliable(DateTime $avaliable, $allocated) {
        $allocated = new DateTime($allocated);
        if ($allocated->getTimestamp() > $avaliable->getTimestamp()) {
            return '00:00';
        }

        $diff = $avaliable->diff($allocated);

        /* @var DateInterval $diff */
        return $diff->format('%H:%I');
    }

    /**
     * @param DateTime $avaliable
     * @param string $allocated
     */
    private function calculeConclusionPercent(DateTime $avaliable, $allocated) {

        $param2 = $this->getFloat($allocated);

        $param1 = $this->getFloat($avaliable);

        return floor($param2 / $param1 * 100);
    }

    public function getFloat($value) {
        if (is_object($value) && $value instanceof DateTime) {
            $value = $value->format("H:i:s");
        }

        $timestamp = strtotime($value);

        $hour = date('H', $timestamp);
        $minute = date('i', $timestamp);
        $second = date('s', $timestamp);

        return $hour + ($minute / 60) + ($second / 60 / 60);
    }

    /**
     * @param float $float
     */
    public function getHour($float) {

        $hour = (int) $float;
        $min = ($float - $hour) * 60;
        return str_pad($hour, 2, 0, STR_PAD_LEFT) . ':' . str_pad($min, 2, 0, STR_PAD_LEFT);
    }

}
