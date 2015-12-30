<?php

require_once 'BasicDAO.php';
require_once APP_DIR . 'entity/Holiday.php';

/**
 * Description of HolidayDAO
 *
 * @author luis
 */
class HolidayDAO extends BasicDAO {

    public function __construct() {
        parent::__construct("Holiday");
    }

    public function search($filters = array(), $activeOnly = NULL, $limit = NULL, $offset = NULL) {

        $where = $this->buildWhere($filters, $activeOnly);

        $dql = "select x from " . $this->className . " x ";

        if ($where != '') {
            $dql .= "where $where ";
        }

        $dql .= "order by x.date desc";

        $q = $this->em->createQuery($dql);

        foreach ($filters as $k => $v) {
            if ($k == "active") {
                $q->setParameter($k, $v, \Doctrine\DBAL\Types\Type::BOOLEAN);
            } else if ($k == "date") {
                $q->setParameter($k, $v, \Doctrine\DBAL\Types\Type::DATE);
            } else {
                $q->setParameter($k, $v);
            }
        }

        if ($limit != NULL) {
            $q->setMaxResults($limit);
        }

        if ($offset != NULL) {
            $q->setFirstResult($offset);
        }

        return $q->getResult();
    }

    /**
     * 
     * @param array $filters
     * @param boolean $activeOnly
     * @return int
     */
    public function searchCount($filters = array(), $activeOnly = NULL) {

        $where = $this->buildWhere($filters, $activeOnly);

        $dql = "select count(x.id) as qtd from Holiday x ";

        if ($where != '') {
            $dql .= "where $where ";
        }

        $q = $this->em->createQuery($dql);

        foreach ($filters as $k => $v) {
            if ($k == "active") {
                $q->setParameter($k, $v, \Doctrine\DBAL\Types\Type::BOOLEAN);
            } else if ($k == "date") {
                $q->setParameter($k, $v, \Doctrine\DBAL\Types\Type::DATE);
            } else {
                $q->setParameter($k, $v);
            }
        }

        return $q->getSingleScalarResult();
    }

    private function buildWhere(&$filters, $activeOnly) {

        $where = "";
        $whereEmpty = true;

        if (count($filters) > 0) {

            $where .= "(";
            $whereEmpty = true;
            if (isset($filters["search"])) {
                if ($filters["search"] != '') {
                    $where .= "lower(x.description) like :search\n";
                    $whereEmpty = false;
                    $filters['search'] = '%' . mb_convert_case(str_replace(' ', '%', $filters['search']), MB_CASE_LOWER, "UTF-8") . '%';
                } else {
                    unset($filters['search']);
                }
            } else {
                $i = 0;

                if (isset($filters['description']) && $filters['description'] != '') {

                    if ($i != 0) {
                        $where .= 'and ';
                    }

                    $where .= 'lower(x.description) like :description ';

                    $filters['description'] = '%' . strtolower(str_replace(' ', '%', $filters['description'])) . '%';
                    $i++;
                } else {
                    unset($filters['description']);
                }

                if (isset($filters['date']) && $filters['date'] != '') {
                    if ($i != 0) {
                        $where .= 'and ';
                    }
                    $where .= 'date = :date ';
                    $i++;
                } else {
                    unset($filters['date']);
                }

                if ($i > 0) {
                    $whereEmpty = false;
                }
            }

            $where .= ")";

            // Clear filters
            if ($whereEmpty) {
                $where = '';
                $filters = array();
            }
        }

        if ($activeOnly !== NULL) {

            if (!$whereEmpty) {
                $where .= "and ";
            }

            $where .= "x.active = :active";
            $filters['active'] = $activeOnly;
            $whereEmpty = false;
        }
        return $where;
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @throws InvalidArgumentException
     * @return Holiday[] holidays
     */
    public function getHolidays($startDate, $endDate) {
        if ($startDate == '') {
            throw new InvalidArgumentException("Start date can't be null");
        }

        if ($endDate == '') {
            $endDate = new DateTime();
        }

        $dql = ""
                . "select h "
                . "  from Holiday h "
                . " where h.date between :start and :end"
                . "   and h.active = true";


        $q = $this->getEntityManager()->createQuery($dql);
        $q->setParameter("start", $startDate, \Doctrine\DBAL\Types\Type::DATE);
        $q->setParameter("end", $endDate, \Doctrine\DBAL\Types\Type::DATE);

        return $q->getResult();
    }

}
