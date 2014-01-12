<?php

require_once 'Message.php';

/**
 * Description of MessageSession
 *
 * @author luis
 * @since Jan 8, 2014
 */
class MessageSession {

    /**
     *
     * @var Message[]
     */
    private $messages = array();

    public function info($message) {
        $this->messages[] = new Message($message, Message::INFO);
    }

    public function warn($message) {
        $this->messages[] = new Message($message, Message::WARN);
    }

    public function error($message) {
        $this->messages[] = new Message($message, Message::ERROR);
    }

    public function success($message) {
        $this->messages[] = new Message($message, Message::SUCCESS);
    }

    public function output() {

        $html = "";

        foreach ($this->messages as $m) {
            $html .= "<div class=\"" . $this->getClassByType($m->getType()) . "\">"
                    . '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'
                    . $m->getMessage() . "</div>";
        }

        $this->clear();

        return $html;
    }

    public function clear() {
        $this->messages = array();
    }

    private function getClassByType($int) {
        switch ($int) {
            case Message::ERROR:
                return "alert alert-danger";
            case Message::INFO:
                return "alert alert-info";
            case Message::WARN:
                return "alert alert-warning";
            case Message::SUCCESS:
                return "alert alert-success";
            default:
                return "";
        }
    }

    public function __toString() {
        return $this->output();
    }

}
