<?php

require_once APP_DIR . 'model/CustomerDAO.php';
require_once 'BasicService.php';
require_once 'exceptions/ValidationException.php';
require_once 'utils/validation/StringValidation.php';

/**
 * @author luis
 * @since Jan 7, 2014
 * @property ActivityTypeDAO $dao 
 */
class CustomerService extends BasicService {

    public function __construct() {
        parent::__construct(new CustomerDAO());
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
                $v->addError("Por favor insira o nome do cliente", 'name');
            }
            
            if (!$v->isEmtpy()) {
                throw $v;
            }
        }
    }
    
}
