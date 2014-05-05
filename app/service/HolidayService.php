<?php

require_once APP_DIR . 'model/HolidayDAO.php';
require_once SERVICE_DIR . 'BasicService.php';

/**
 * Description of HolidayService
 * @author luis
 */
class HolidayService extends BasicService {

    public function __construct() {
        parent::__construct(new HolidayDAO());
    }

    /**
     * 
     * @param Holiday $entity
     * @param boolean $newObject
     */
    public function validate($entity, $newObject = true) {

        $v = new ValidationException();

        if ($entity->getDescription() == '') {
            $v->addError("Descrição inválida", "description");
        }

        if ($entity->getDate() == '') {
            $v->addError("Data inválida", "date");
        }

        if (!$v->isEmtpy()) {
            throw $v;
        }
    }

}
