<?php

// Start some constants
define("ROOT_DIR", realpath(__DIR__ . '/../') . '/');
define("APP_DIR", realpath(__DIR__ . '/../app') . '/');
define("CONTROLLER_DIR", APP_DIR . 'controller/');
define("SERVICE_DIR", APP_DIR . 'service/');
define("LIB_DIR", APP_DIR . 'lib/');

// Used for pagination
define("DEFAULT_LIMITS_PER_PAGE", 20);
