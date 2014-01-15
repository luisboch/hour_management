<?php

require_once 'BasicDAO.php';
require_once APP_DIR . 'entity/Activity.php';

/**
 * Description of UserDAO
 *
 * @author luis
 * @since Jan 7, 2014
 */
class ActivityDAO extends BasicDAO {

    public function __construct() {
        parent::__construct("Activity");
    }

    public function search($filters = array(), $activeOnly = NULL, $limit = NULL, $offset = NULL) {

        // Add status filter
        if (!array_key_exists('status', $filters)) {
            $filters['status'] = 0; // Only opened activities
        }

        $qb = $this->em->createQueryBuilder();
        $i = 0;
        $qb->select('a')
                ->from("Activity", 'a');


        $whereArray = array();
        $whereParams = array();
        $whereWithoutParam = array();
        if (array_key_exists('user', $filters)) {

            if ($filters['user'] !== NULL) {

                $qb->leftJoin('a.user', 'u');
                $i++;
                $whereArray[$i] = $qb->expr()->eq('u.id', '?' . $i);
                
                if (is_object($filters['user'])) {
                    $whereParams[$i] = $filters['user']->getId();
                } else {
                    $whereParams[$i] = $filters['user'];
                }
            } else {
                $whereWithoutParam[] = $qb->expr()->isNull('a.user');
            }
        }

        if (isset($filters['type'])) {
            $i++;
            $qb->join('a.activityType', 't');
            $whereArray[$i] = $qb->expr()->eq('t.id', '?' . $i);
            if (is_object($filters['type'])) {
                $whereParams[$i] = $filters['type']->getId();
            } else {
                $whereParams[$i] = $filters['type'];
            }
        }

        if (isset($filters['search'])) {
            $i++;
            $whereArray[$i] = $qb->expr()->like('lower(a.name)', '?' . $i);
            $whereParams[$i] = mb_convert_case(str_replace(' ', '%', $filters['search'] . '%'), MB_CASE_LOWER, "UTF-8");
        }

        if (isset($filters['status'])) {
            $i++;
            $whereArray[$i] = $qb->expr()->eq('a.status', '?' . $i);
            $whereParams[$i] = $filters['status'];
        }

        if ($activeOnly !== null) {
            $i++;
            $whereArray[$i] = $qb->expr()->eq('a.active', '?' . $i);
            $whereParams[$i] = $activeOnly;
        }

        // set Where
        foreach ($whereArray as $k => $v) {
            if ($k == 1) {
                $qb->where($v);
            } else {
                $qb->andWhere($v);
            }
        }

        foreach ($whereWithoutParam as $k => $v) {
            if (count($whereArray) == 0 && $k == 0) {
                $qb->where($v);
            } else {
                $qb->andWhere($v);
            }
        }
        // Set Params
        foreach ($whereParams as $k => $v) {
            $qb->setParameter($k, $v);
        }

        $qb->orderBy('a.priority', 'desc');
        $qb->addOrderBy('a.lastUpdate', 'desc');

        if ($limit != NULL) {
            $qb->setMaxResults($limit);
        }

        if ($offset != NULL) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * 
     * @param array $filters
     * @param boolean $activeOnly
     * @return int
     */
    public function searchCount($filters = array(), $activeOnly = NULL) {

        // Add status filter
        if (!array_key_exists('status', $filters)) {
            $filters['status'] = 0; // only opened activities
        }

        $qb = $this->em->createQueryBuilder();
        $i = 0;
        $qb->select('count(a.id)')
                ->from("Activity", 'a');


        $whereArray = array();
        $whereParams = array();
        $whereWithoutParam = array();
        if (array_key_exists('user', $filters)) {

            if ($filters['user'] !== NULL) {

                $qb->leftJoin('a.user', 'u');
                $i++;
                $whereArray[$i] = $qb->expr()->eq('u.id', '?' . $i);
                
                if (is_object($filters['user'])) {
                    $whereParams[$i] = $filters['user']->getId();
                } else {
                    $whereParams[$i] = $filters['user'];
                }
            } else {
                $whereWithoutParam[] = $qb->expr()->isNull('a.user');
            }
        }

        if (isset($filters['type'])) {
            $i++;
            $qb->join('a.activityType', 't');
            $whereArray[$i] = $qb->expr()->eq('t.id', '?' . $i);
            if (is_object($filters['type'])) {
                $whereParams[$i] = $filters['type']->getId();
            } else {
                $whereParams[$i] = $filters['type'];
            }
        }

        if (isset($filters['search'])) {
            $i++;
            $whereArray[$i] = $qb->expr()->like('lower(a.name)', '?' . $i);
            $whereParams[$i] = mb_convert_case(str_replace(' ', '%', $filters['search'] . '%'), MB_CASE_LOWER, "UTF-8");
        }

        if (isset($filters['status'])) {
            $i++;
            $whereArray[$i] = $qb->expr()->eq('a.status', '?' . $i);
            $whereParams[$i] = $filters['status'];
        }

        if ($activeOnly !== null) {
            $i++;
            $whereArray[$i] = $qb->expr()->eq('a.active', '?' . $i);
            $whereParams[$i] = $activeOnly;
        }

        // set Where
        foreach ($whereArray as $k => $v) {
            if ($k == 1) {
                $qb->where($v);
            } else {
                $qb->andWhere($v);
            }
        }

        foreach ($whereWithoutParam as $k => $v) {
            if (count($whereArray) == 0 && $k == 0) {
                $qb->where($v);
            } else {
                $qb->andWhere($v);
            }
        }
        // Set Params
        foreach ($whereParams as $k => $v) {
            $qb->setParameter($k, $v);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

}
