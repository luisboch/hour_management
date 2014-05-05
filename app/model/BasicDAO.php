<?php

require_once ROOT_DIR . 'vendor/autoload.php';
require_once SERVICE_DIR . 'Config.php';

/**
 * Description of BasicDAO
 *
 * @author luis
 * @since Jan 7, 2014
 */
class BasicDAO {

    public static $config;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    private static $entityManager;
    protected $className;

    function __construct($className) {
        $this->setupDoctrine();
        $this->className = $className;
    }

    private final function openDatabaseConnection() {
        BasicDAO::$entityManager->getConnection()->connect();
    }

    public final function setupDoctrine() {

        if (BasicDAO::$entityManager === null) {

            $paths = array(APP_DIR . "entity/");

            // the connection configuration
            $app_config = Config::getInstance();

            $config = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($paths, true);
            BasicDAO::$entityManager = Doctrine\ORM\EntityManager::create($app_config['database'], $config);

            $this->openDatabaseConnection();
        }

        $this->em = BasicDAO::$entityManager;
    }

    public function findById($id) {
        return $this->em->find($this->className, $id);
    }

    public function save($obj) {
        $this->em->persist($obj);
    }

    public function update($obj) {
        $this->em->merge($obj);
    }

    public function getEntityManager() {
        return $this->em;
    }

    protected final function find($params = array(), $order = "id") {

        $dql = "select x \nfrom " . $this->className . " x\n";

        $queryParms = array();

        if (count($params) > 0) {

            $dql.="where ";
            $i = 0;
            foreach ($params as $k => $v) {

                if ($i != 0) {
                    $dql.="and ";
                }

                $dql .= "x." . $k . " ?" . ($i + 1) . "\n";

                $queryParms[$i + 1] = $v;

                $i++;
            }
        }

        if ($order != "") {
            $dql .= "order by x." . $order . "\n ";
        }

        $query = $this->em->createQuery($dql);

        $query->setParameters($queryParms);

        return $query->getResult();
    }

    public function search($filter = array()) {

        $dql = "select x \nfrom " . $this->className . " x\n";

        if (count($params) > 0) {

            $dql.="where";
            $i = 0;
            foreach ($params as $k => $v) {

                if ($i != 0) {
                    $dql.="and";
                }

                if (is_string($v)) {
                    $v = "'$v'";
                }

                $dql .= " x." . $k . " " . $v . "\n";
                $i++;
            }
        }

        $query = $this->em->createQuery($dql);

        return $query->getResult();
    }

    protected function setParams(Doctrine\ORM\Query $q, $params) {
        if ($params !== NULL && is_array($params)) {
            foreach ($params as $k => $v) {
                $q->setParameter($k, $v);
            }
        }
    }
    
    /**
     * @param DateTime $date
     * @return DateTime
     */
    public function getPreviousWorkDate(DateTime $date = null) {

        $rsm = new \Doctrine\ORM\Query\ResultSetMappingBuilder($this->em);

        $rsm->addScalarResult('available', 'available', 'datetime');
        
        if($date == null){
            $date = new DateTime();
        }
        
        // Generate 1 month ago
        $month = clone $date;
        $month->modify('-1 month');
        $dbDate = DateUtils::toDataBaseDate($date);
        
        $sql = "
            select dates.dt as available
              from (select CURRENT_DATE + i as dt from generate_series(date '".DateUtils::toDataBaseDate($month)."' - CURRENT_DATE, date '".$dbDate."' - CURRENT_DATE ) i) as dates
             where extract (DOW from dates.dt) in (1,2,3,4,5)
               and dates.dt not in (select h.date from holiday h where h.active = true)
               and dates.dt < date '".$dbDate."'
          order by dates.dt desc limit 1";
        
        $q = $this->em->createNativeQuery($sql, $rsm);
        
        $result = $q->getSingleScalarResult();
        
        return DateTime::createFromFormat('Y-m-d', $result);
    }
    
    /**
     * @param DateTime $date
     * @return DateTime
     */
    public function getNextWorkDate(DateTime $date = null) {

        $rsm = new \Doctrine\ORM\Query\ResultSetMappingBuilder($this->em);

        $rsm->addScalarResult('available', 'available', 'datetime');
        
        if($date == null){
            $date = new DateTime();
        }
        
        // Generate 1 month ago
        $month = clone $date;
        $month->modify('+1 month');
        $dbDate = DateUtils::toDataBaseDate($date);
        
        $sql = "
            select dates.dt as available
              from (select CURRENT_DATE + i as dt from generate_series(date '".$dbDate."' - CURRENT_DATE, date '".DateUtils::toDataBaseDate($month)."' - CURRENT_DATE ) i) as dates
             where extract (DOW from dates.dt) in (1,2,3,4,5)
               and dates.dt not in (select h.date from holiday h where h.active = true)
               and dates.dt > date '".$dbDate."'
          order by dates.dt asc limit 1";
        
        $q = $this->em->createNativeQuery($sql, $rsm);
        
        $result = $q->getSingleScalarResult();
        
        return DateTime::createFromFormat('Y-m-d', $result);
    }

}
