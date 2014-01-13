<?php

require_once 'CrudBase.php';
require_once APP_DIR . 'service/UserService.php';

/**
 * Description of UserCrudController
 *
 * @author luis
 * @property UserService $service 
 */
class UsercrudController extends CrudBase {

    public function initialize() {

        parent::initialize(new UserService());

        $this->action = "Usuários";
        
        // Set default active of value
        $this->view->active = true;
    }

    /**
     * 
     * @param User $user
     */
    protected function populatePostData($user) {

        $user->setName($this->request->getPost("name"));
        $user->setEmail($this->request->getPost("email"));
        $user->setCpf($this->request->getPost("cpf"));
        
        $user->setDayActiveHour(new DateTime($this->request->getPost("day_active_hour")));

        if ($user->getId() == null || $this->request->getPost("passwd1") != '') {
            $user->setPassword($this->security->hash($this->request->getPost("passwd1")));
        }
    }

    /**
     * 
     * @param User $user
     */
    protected function isValid($user) {

        $passwd1 = $this->request->getPost("passwd1");

        $passwd2 = $this->request->getPost("passwd2");

        if ($user->getId() == null || $passwd1 != '') {
            if ($passwd1 !== $passwd2) {
                $this->error('As duas senhas devem ser iguais');
                return FALSE;
            } else if (strlen($passwd1) < 6) {
                $this->error('A senha deve possuir no mínimo 6 caracteres');
                return FALSE;
            }
        }
        return TRUE;
    }

    protected function createNewInstance() {
        $user = new User();
        $user->setCreationDate(new DateTime());
        $user->setDayActiveHour(new DateTime('08:00:00'));
        return $user;
    }

    protected function getSearchParams() {
        return array('search' => $this->request->getQuery('search'));
    }

    protected function beforeSearch() {
        $active = $this->request->getQuery('active');
        $this->showActiveResults = $active === 'on';
        $this->view->active = $this->showActiveResults;
        return true;
    }

}
