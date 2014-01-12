<?php

/**
 * Description of Message
 *
 * @author luis
 * @since Jan 8, 2014
 */
class Message {
    
    const INFO = 0;
    const WARN = 1;
    const ERROR = 2;
    const SUCCESS = 3;
    
    private $message;
    private $type;
    
    function __construct($message, $type) {
        $this->message = $message;
        $this->type = $type;
    }
    
    public function getMessage() {
        return $this->message;
    }

    public function getType() {
        return $this->type;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

    public function setType($type) {
        $this->type = $type;
    }

    // Helper functions 
    public function isInfo(){
        return $this->type === self::INFO;
    }
    public function isWarn(){
        return $this->type === self::WARN;
    }
    public function isError(){
        return $this->type === self::ERROR;
    }
    public function isSuccess(){
        return $this->type === self::SUCCESS;
    }
}