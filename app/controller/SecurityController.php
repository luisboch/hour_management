<?php

require_once 'ControllerBase.php';
require_once APP_DIR . 'service/UserService.php';

class SecurityController extends ControllerBase {

    /**
     *
     * @var UserService
     */
    private $service;

    public function initialize() {
        parent::initialize();
        $this->view->action = "Segurança";
        $this->service = new UserService();
        $this->setTitle('Segurança');
    }

    public function loginAction($target = '') {
        try {
            $this->view->targetUrl = $target;

            if ($this->request->isPost()) {
                $email = $this->request->getPost('email');
                $passwd = $this->request->getPost('password');

                $user = $this->service->findByEmail($email);

                if ($user != null && $this->security->checkHash($passwd, $user->getPassword())) {
                    $this->session->setUser($user);
                    $user->setLastAccess(new DateTime());
                    $this->service->update($user);
                    $work = $this->service->getCurrentWorkDay($user);
                    if($work == null || $work->getStartWork() == null){
                        $this->session->setCanStartWork(true);
                    } 
                    if($work != null && $work->getStartWork() != null && $work->getEndWork() == null){
                        $this->session->setCanEndWork(true);
                    }
                    $this->response->redirect('');
                } else {
                    $this->error("Email/Senha inválido(s)");
                    $this->session->setUser(null);
                }
            }

            $this->view->tokenKey = $this->security->getTokenKey();
            $this->view->tokenValue = $this->security->getToken();
        } catch (Exception $ex) {
            $this->showError($ex);
        }
    }

    public function logoutAction() {
        $this->session->clear();
        $this->response->redirect("security/login");
    }

}
