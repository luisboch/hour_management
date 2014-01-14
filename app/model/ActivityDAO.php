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
                $whereParams[$i] = $filters['user']->getId();
            } else {
                $whereWithoutParam[] = $qb->expr()->isNull('a.user');
            }
        }
        if (isset($filters['type'])) {
            $i++;
            $qb->join('a.activityType', 't');
            $whereArray[$i] = $qb->expr()->eq('t.id', '?' . $i);
            $whereParams[$i] = $filters['type']->getId();
        }

        if (isset($filters['search'])) {
            $i++;
            $whereArray[$i] = $qb->expr()->like('lower(a.name)', '?' . $i);
            $whereParams[$i] = mb_convert_case(str_replace(' ', '%', $filters['search'] . '%'), MB_CASE_LOWER, "UTF-8");
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
            if (count($whereArray) == 0 && $k == 1) {
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

        if ($limit != NULL) {
            $qb->setMaxResults($limit);
        }

        if ($offset != NULL) {
            $qb->setFirstResult($offset);
        }
        $q = $qb->getQuery();
        $str = $q->getSQL();
        return $qb->getQuery()->getResult();
    }

    /**
     * 
     * @param array $filters
     * @param boolean $activeOnly
     * @return int
     */
    public function searchCount($filters = array(), $activeOnly = NULL) {

        $qb = $this->em->createQueryBuilder();

        $qb->select('count(a.id)')
                ->from("Activity", 'a');

        if (isset($filters['user'])) {
            $qb->leftJoin('a.user', 'u');
            if ($filters['user'] !== NULL) {
                $qb->where($qb->expr()->eq('u.id', '?1'));
                $qb->setParameter(1, $filters['user']->getId());
            } else {
                $qb->where($qb->expr()->eq('u', '?1'));
                $qb->setParameter(1, null);
            }
        }
        if (isset($filters['type']) && $filters['user'] != null) {
            $qb->join('a.activityType', 't');
            $qb->where($qb->expr()->eq('t.id', '?2'));
            $qb->setParameter(2, $filters['type']->getId());
        }

        if (isset($filters['search'])) {
            $qb->where($qb->expr()->like('lower(a.name)', '?3'));
            $qb->setParameter(3, mb_convert_case(str_replace(' ', '%', $filters['search'] . '%'), MB_CASE_LOWER, "UTF-8"));
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

}
