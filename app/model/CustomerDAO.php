<?php

require_once 'BasicDAO.php';
require_once APP_DIR . 'entity/Customer.php';

/**
 *
 * @author luis
 * @since Jan 7, 2014
 */
class CustomerDAO extends BasicDAO {

    public function __construct() {
        parent::__construct("Customer");
    }

    public function search($filters = array(), $activeOnly = NULL, $limit = NULL, $offset = NULL) {

        $where = $this->buildWhere($filters, $activeOnly);

        $dql = "select x from " . $this->className . " x ";

        if ($where != '') {
            $dql .= "where $where ";
        }

        $dql .= "order by x.name";

        $q = $this->em->createQuery($dql);

        $this->setParams($q, $filters);

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

        $dql = "select count(x.id) as qtd from " . $this->className . " x ";

        if ($where != '') {
            $dql .= "where $where ";
        }

        $q = $this->em->createQuery($dql);

        $this->setParams($q, $filters);

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
                    $where .= "lower(x.name) like :search\n";
                    $whereEmpty = false;
                    $filters['search'] = '%' . mb_convert_case(str_replace(' ', '%', $filters['search']), MB_CASE_LOWER, "UTF-8") . '%';
                } else {
                    unset($filters['search']);
                }
            } else {
                $i = 0;

                if (isset($filters['name']) && $filters['name'] != '') {

                    if ($i != 0) {
                        $where .= 'and ';
                    }

                    $where .= 'lower(x.name) like :name ';

                    $filters['name'] = '%' . strtolower(str_replace(' ', '%', $filters['name'])) . '%';
                    $i++;
                } else {
                    unset($filters['name']);
                }

                if (isset($filters['id']) && $filters['id'] != '') {
                    if ($i != 0) {
                        $where .= 'and ';
                    }
                    $where .= 'id = :id ';
                    $i++;
                } else {
                    unset($filters['id']);
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

}
