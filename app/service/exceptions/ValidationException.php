<?php

/*
 * ValidacaoExecao.php
 */

/**
 * Description of ExeceaoValidacao
 *
 * @author Luis
 * @since Feb 24, 2013
 */
class ValidationException extends Exception {

    /**
     *
     * @var ValidationError[]
     */
    private $errors = array();

    public function __construct($message = "Errors found while executing an action", $code = 0, $previous = NULL) {
        parent::__construct($message, $code, $previous);
    }

    /**
     *
     * @return ValidationError[]
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     *
     * @return boolean
     */
    public function isEmtpy() {
        return count($this->errors) == 0;
    }

    public function addError($message, $field = NULL) {
        $err = new ValidationError($message, $field);
        $this->errors[] = $err;
    }

}

class ValidationError {

    /**
     *
     * @var string
     */
    private $message;

    /**
     *
     * @var string
     */
    private $field;

    function __construct($mensagem, $field = NULL) {
        $this->message = $mensagem;
        $this->field = $field;
    }

    /**
     *
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     *
     * @param string $mensagem
     */
    public function setMensagem($mensagem) {
        $this->message = $mensagem;
    }

    /**
     *
     * @return string
     */
    public function getField() {
        return $this->field;
    }

    /**
     *
     * @param string $campo
     */
    public function setField($campo) {
        $this->field = $campo;
    }

}
