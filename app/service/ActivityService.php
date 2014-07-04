<?php

require_once APP_DIR . 'model/ActivityDAO.php';
require_once APP_DIR . 'model/UserDAO.php';
require_once 'BasicService.php';
require_once 'exceptions/ValidationException.php';
require_once 'utils/validation/StringValidation.php';

/**
 *
 * @author luis
 * @since Jan 7, 2014
 * 
 * @property ActivityDAO $dao 
 */
class ActivityService extends BasicService {

    public function __construct() {
        parent::__construct(new ActivityDAO());
    }

    /**
     * @param Activity $activity
     * @param boolean $newObject
     * @throws InvalidArgumentException
     * @throws ValidationException
     */
    public function validate($activity, $newObject = true) {

        $v = new ValidationException();

        if ($activity->getId() == null && !$newObject) {
            throw new InvalidArgumentException("The object need an id to update");
        } else {

            // Check name
            if ($activity->getName() == '') {
                $v->addError("Por favor insira o nome da atividade", 'name');
            }

            if ($activity->getDescription() == '') {
                $v->addError("Insira a descrição da atividade", 'description');
            }

            if ($activity->getActivityType() === null) {
                $v->addError("Selecione o tipo da atividade", 'activityType');
            }

            if ($activity->getPriority() == null) {
                $v->addError("Insira a prioridade", 'priority');
            }
            
            if($activity->getCustomer() == null){
                $v->addError("Selecione o cliente", "customer");
            }

            if ($activity->getInteractions() !== null) {

                foreach ($activity->getInteractions() as $value) {

                    if ($value->getId() == null) {
                        $value->setCreationDate(new DateTime());
                    }

                    if ($value->getActivity() === null) {
                        $value->setActivity($activity);
                    }

                    if ($value->getStartDate() === null) {
                        $v->addError("Insira a data de inicio", "interaction.startDate");
                    }

                    if ($value->getUser() === null) {
                        $v->addError("Please enter a selected user", "interaction.user");
                    }
                    
                    if($activity->isFinished() && $value->getEndDate() == null){
                        $v->addError("A atividade não pode ser finalizada com hora em aberto!");
                    }
                }
            }

            if (!$v->isEmtpy()) {
                throw $v;
            }
        }
    }

    /**
     * 
     * @param Activity $entity
     */
    protected function saveRelations(Activity $entity) {
        foreach ($entity->getInteractions() as $i) {
            if ($i->getId() == null) {
                // When saving a activity we need to save a work day to User, to 
                // keep the history of $dayActiveHour property by day
                $userDAO = new UserDAO();
                $workDay = $userDAO->getWorkDay($i->getUser(), new DateTime());
                if ($workDay != null) {
                    $workDay->setDayActiveHour($i->getUser()->getDayActiveHour());
                    $this->dao->update($workDay);
                } else {
                    $workDay = new UserWorkDay();
                    $workDay->setUser($i->getUser());
                    $workDay->setDate(new DateTime());
                    $workDay->setDayActiveHour($i->getUser()->getDayActiveHour());
                    $this->dao->save($workDay);
                }
                return;
            }
        }
    }
    
    public function startInteraction(Activity $activity, User $user) {
        
        if($activity->isFinished()){
            throw new ValidationException("Não é possível iniciar uma ação em atividade fechada!");
        }
        
        foreach($activity->getInteractions() as $i){
            if($i->getUser()->getId() == $user->getId()){
                if($i->getEndDate() == null){
                    throw new ValidationException("Você já possui uma ação aberta!");
                }
            }
        }
        
        $ai = new ActivityInteraction();
        $ai->setActivity($activity);
        $ai->setStartDate(new DateTime());
        $ai->setCreationDate(new DateTime());
        $ai->setUser($user);
        
        $activity->getInteractions()->add($ai);
        
        $this->update($activity);
        
    }
}