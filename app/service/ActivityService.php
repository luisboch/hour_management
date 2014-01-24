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

            if ($activity->getInteractions() !== null) {

                foreach ($activity->getInteractions() as $value) {

                    if ($value->getId() == null) {
                        $value->setCreationDate(new DateTime());
                    }

                    if ($value->getActivity() === null) {
                        $value->setActivity($activity);
                    }

                    if ($value->getAllocatedTime() === null) {
                        $v->addError("Insira o tempo alocado na ação", "interaction.allocatedTime");
                    }

                    if ($value->getUser() === null) {
                        $v->addError("Please enter a selected user", "interaction.user");
                    }

                    if ($value->getDescription() == '') {
                        $v->addError("Insira a descrição da ação", "interaction.description");
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
                // When saving a sactivity we need to save a work day to User, to 
                // keep the history of $dayActiveHour property by day
                $userDAO = new UserDAO();
                $workDay = $userDAO->getWorkDay($i->getUser(), new DateTime());
                if($workDay != null){
                    $workDay->setDayActiveHour($i->getUser()->getDayActiveHour());
                    $this->dao->update($workDay);
                } else {
                    $workDay =new UserWorkDay();
                    $workDay->setUser($i->getUser());
                    $workDay->setDate(new DateTime());
                    $workDay->setDayActiveHour($i->getUser()->getDayActiveHour());
                    $this->dao->save($workDay);
                }
                return;
            }
        }
    }
}