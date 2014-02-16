<?php

/**
 * Description of Config
 *
 * @author luis
 * @since Jan 13, 2014
 */
class Config implements ArrayAccess {
    /**
     *
     * @var Config
     */
    private static $instance = null;
    
    private $arr;

    private function __construct() {
        $this->arr = parse_ini_file(ROOT_DIR . 'etc/config.ini', true);
    }
    
    /**
     * @return Config
     */
    public static function getInstance() {
        
        if(self::$instance === null){
            self::$instance = new Config();
        }
        
        return self::$instance;
    }

    public function offsetExists($offset) {
        return isset($this->arr[$offset]);
    }

    public function offsetGet($offset) {
        return $this->arr[$offset];
    }

    public function offsetSet($offset, $value) {
        throw new Exception("Canot set value, please change config file!");
    }

    public function offsetUnset($offset) {
        throw new Exception("Canot set value, please change config file!");
    }

    public function getConfig() {
        return $this->arr;
    }

}
