<?php

require_once ROOT_DIR . 'vendor/autoload.php';
require_once SERVICE_DIR. 'Config.php';

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
    protected $className;

    function __construct($className) {
        $this->setupDoctrine();
        $this->openDatabaseConnection();
        $this->className = $className;
    }

    private final function openDatabaseConnection() {
        $this->em->getConnection()->connect();
    }

    public final function setupDoctrine() {

        $paths = array(APP_DIR . "entity/");

        // the connection configuration
        $app_config = Config::getInstance();

        $config = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($paths, true);
        $this->em = Doctrine\ORM\EntityManager::create($app_config['database'], $config);
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
        
        if($order != ""){
            $dql .= "order by x.".$order."\n ";
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

}
