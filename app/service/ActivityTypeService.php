<?php

require_once APP_DIR . 'model/ActivityTypeDAO.php';
require_once 'BasicService.php';
require_once 'exceptions/ValidationException.php';
require_once 'utils/validation/StringValidation.php';

/**
 * Description of UserService
 *
 * @author luis
 * @since Jan 7, 2014
 * 
 * @property UserDAO $dao 
 */
class ActivityTypeService extends BasicService {

    public function __construct() {
        parent::__construct(new ActivityTypeDAO());
    }

    /**
     * 
     * @param ActivityType $activity
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
            
            if (!$v->isEmtpy()) {
                throw $v;
            }
        }
    }
    
}
