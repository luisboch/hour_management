<?php

require_once 'CrudBase.php';
require_once APP_DIR . 'service/CustomerService.php';

/**
 * Description of UserCrudController
 *
 * @author luis
 * @property CustomerService $service 
 */
class CustomerController extends CrudBase {

    public function initialize() {

        parent::initialize(new CustomerService());
        
        // Set default active of value
        $this->view->active = true;
        $this->setTitle('Clientes');
    }

    /**
     * 
     * @param Customer $customer
     */
    protected function populatePostData($customer) {
        $customer->setName($this->request->getPost("name"));
        $active = $this->request->getPost("active");
        $customer->setActive($active === "on");
    }

    /**
     * 
     * @param Customer $customer
     */
    protected function isValid($customer) {
        return TRUE;
    }

    protected function createNewInstance() {
        return new Customer();
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
