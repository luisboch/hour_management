<?php

require_once APP_DIR . 'model/UserDAO.php';
require_once 'BasicService.php';
require_once 'exceptions/ValidationException.php';
require_once 'exceptions/StartedWorkException.php';
require_once 'utils/validation/StringValidation.php';

/**
 * Description of UserService
 *
 * @author luis
 * @since Jan 7, 2014
 * 
 * @property UserDAO $dao 
 */
class UserService extends BasicService {

    public function __construct() {
        parent::__construct(new UserDAO());
    }

    /**
     * 
     * @param User $user
     * @param boolean $save
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    public function validate($user, $save = true) {
        if (is_object($user) && $user instanceof User) {
            $v = new ValidationException();
            if ($user->getId() == null && !$save) {
                throw new InvalidArgumentException("The object need an id to update");
            } else {

//            // Check cpf
//            if ($user->getCpf() == '' || !StringValidation::checkCpf($user->getCpf())) {
//                $v->addError("Por favor insira um CPF válido", 'cpf');
//            } else {
//                $userCPF = $this->dao->findByCPF($user->getCpf());
//                if ($userCPF != null && $user->getId() != $userCPF->getId()) {
//                    $v->addError("O CPF inserido já está sendo utilizado, insira um válido");
//                }
//            }
                // Check email
                if ($user->getEmail() == '' || !StringValidation::checkEmail($user->getEmail())) {
                    $v->addError("Por favor insira um email válido", 'email');
                } else {
                    $userEmail = $this->dao->findByEmail($user->getEmail());
                    if ($userEmail != null && $user->getId() != $userEmail->getId()) {
                        $v->addError("O email inserido já está sendo utilizado, insira um válido");
                    }
                }

                // Check name
                if ($user->getName() == '') {
                    $v->addError("Por favor insira seu nome", 'name');
                }

                if ($user->getDayActiveHour() == '') {
                    $v->addError("Por favor insira a quantidade de horas ativas", 'dayActiveHour');
                }

                // Check password
                if ($user->getPassword() == '') {
                    $v->addError("Por favor preencha a senha", 'password');
                }

                if (!$v->isEmtpy()) {
                    throw $v;
                }
            }
        }
    }

    /**
     * 
     * @param string $email
     * @return User
     */
    public function findByEmail($email) {
        return $this->dao->findByEmail($email);
    }

    /**
     * 
     * @param string $cpf
     * @return User
     */
    public function findByCPF($cpf) {
        return $this->dao->findByCPF($cpf);
    }

    public function startWork(User $user) {
        $workDay = $this->dao->getWorkDay($user, new DateTime());

        if ($workDay == null) {

            $workDay = new UserWorkDay();
            $workDay->setUser($user);
            $workDay->setDate(new DateTime());
        } else if ($workDay->getStartWork() != null) {
            throw new StartedWorkException();
        }

        $workDay->setStartWork(new DateTime);

        $workDay->setDayActiveHour($user->getDayActiveHour());

        if ($workDay->getId() == null) {
            $this->dao->save($workDay);
        } else {
            $this->dao->update($workDay);
        }

        $this->dao->getEntityManager()->flush();
    }

    public function endWork(User $user) {

        $workDay = $this->dao->getWorkDay($user, new DateTime());

        if ($workDay == null) {
            throw new ValidationException("Trying to end work when not started");
        }

        $workDay->setEndWork(new DateTime);

        if ($workDay->getId() == null) {
            $this->dao->save($workDay);
        } else {
            $this->dao->update($workDay);
        }
        $this->dao->getEntityManager()->flush();
    }

    public function getCurrentWorkDay(User $user) {
        return $this->dao->getWorkDay($user, new DateTime());
    }

}
