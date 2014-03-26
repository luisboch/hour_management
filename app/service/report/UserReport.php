<?php

require_once APP_DIR . 'model/UserReportDAO.php';

/**
 * Description of UserReport
 *
 * @author luis
 * @property UserReportDAO $dao
 */
class UserReport extends BasicService {

    function __construct() {
        parent::__construct(new UserReportDAO());
    }

    public function validate($entity, $newObject = true) {
        throw new Exception("Not implemented");
    }

    /**
     * @param array $filters
     * Allowed keys: startDate The start of action
     *                endDate The end of action
     *                user The logged user or user id
     * @param integer $limit
     * @param integer $offset
     * @return UserActivityDetailResult[]
     */
    public function getUserActivityReport($filters = array(), $limit = NULL, $offset = NULL) {
        return $this->dao->getUserActivityReport($filters, $limit, $offset);
    }

}
