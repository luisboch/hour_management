<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
require_once 'etc/config.php';
require_once 'vendor/autoload.php';
require_once SERVICE_DIR.'Config.php';

$paths = array(APP_DIR .  "entity/");
$confi = Config::getInstance();

// the connection configuration
$app_config = Config::getInstance();
$config = Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($paths, true);
$entityManager = Doctrine\ORM\EntityManager::create($confi['database'], $config);
$entityManager->getConnection()->connect();

return ConsoleRunner::createHelperSet($entityManager);