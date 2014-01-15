<?php

require_once 'ControllerBase.php';

/**
 * Description of ErrorController
 *
 * @author luis
 */
class ErrorController extends ControllerBase {

    public function initialize() {
        parent::initialize();
        $this->view->setViewsDir('../app/view/');
        $this->setTitle('Erro');
    }

    public function show404Action() {
        $this->response->setStatusCode(404, "Not Found");
    }

    public function exceptionAction() {
        $this->view->action = "Error";
        $this->view->exception = $this->dispatcher->getParam('exception');
        $this->view->trace = $this->view->exception->getTraceAsString();
        $this->view->message = $this->view->exception->getMessage();
    }

}
