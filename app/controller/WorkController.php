<?php

require_once SERVICE_DIR . 'UserService.php';
/**
 * WorkController.php
 */

/**
 * WorkController
 *
 * @author luis
 * @since Feb 18, 2014
 */
class WorkController extends AdminBase {

    private $service;

    public function initialize() {
        parent::initialize();
        $this->service = new UserService();
    }

    public function startAction() {
        try {
            $user = $this->service->findById($this->session->getUser()->getId());
            $this->service->startWork($user);
            $this->session->setCanStartWork(false);
            $this->session->setCanEndWork(true);
            $this->success("Dia iniciado com sucesso!");
            $this->response->redirect('index');
        } catch (StartedWorkException $ex) {
            $this->warn("Não é possível iniciar o dia novamente!");
            $this->response->redirect('index');
        } catch (ValidationException $ex) {
            $this->showError($ex);
            $this->response->redirect('index');
        } catch (Exception $ex) {
            $this->showError($ex);
            $this->response->redirect('index');
        }
    }

    public function endAction() {
        try {
            $user = $this->service->findById($this->session->getUser()->getId());
            $this->service->endWork($user);
            $this->success("Dia concluído com sucesso!");
            $this->session->setCanEndWork(false);
            $this->response->redirect('index');
        } catch (ValidationException $ex) {
            $this->warn("Você deve iniciar o dia para poder finalizá-lo");
            $this->response->redirect('index');
        } catch (Exception $ex) {
            $this->showError($ex);
            $this->response->redirect('index');
        }
    }

}
