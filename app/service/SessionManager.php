
<?php

require_once 'utils/MessageSession.php';
require_once APP_DIR . 'entity/User.php';

/**
 * Description of SessionManager
 *
 * @author luis
 */
class SessionManager {

    private $user = null;

    /**
     *
     * @var SessionManager
     */
    private static $instance;

    /**
     * @var MessageSession
     */
    private $message;
    
    private $canStartWork = false;
    private $canEndWork = false;
    
    private $reportFilters = array();
    
    private function __construct() {
        $this->message = new MessageSession();
    }

    public function getMessage() {
        return $this->message;
    }

    /**
     *
     * @return SessionManager
     */
    public static function getInstance() {

        if (self::$instance === null) {

            session_start();

            if (!isset($_SESSION['_SS']) || $_SESSION['_SS'] == '') {
                self::$instance = new SessionManager();
                $_SESSION['_SS'] = self::$instance;
            } else {
                self::$instance = $_SESSION['_SS'];
            }
        }

        return self::$instance;
    }

    public function close() {
        $_SESSION['_SS'] = null;
        self::$instance = null;
        session_destroy();
    }

    public function getShowChat() {
        return $this->showChat;
    }

    public function setShowChat($showChat) {
        $this->showChat = $showChat;
    }

    public function isLogged() {
        return $this->user !== null;
    }

    /**
     * 
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    public function setUser($user) {
        $this->user = $user;
    }
    public function clear() {
        $this->user = null;
        $this->canEndWork = false;
        $this->canStartWorkWork = false;
    }

    public function getCanStartWork() {
        return $this->canStartWork;
    }

    public function getCanEndWork() {
        return $this->canEndWork;
    }

    public function setCanStartWork($canStartWork) {
        $this->canStartWork = $canStartWork;
    }

    public function setCanEndWork($canEndWork) {
        $this->canEndWork = $canEndWork;
    }
    
    /**
     * 
     * @return array
     */
    public function getReportFilters() {
        return $this->reportFilters;
    }

    /**
     * 
     * @param array $reportFilters
     */
    public function setReportFilters($reportFilters) {
        $this->reportFilters = $reportFilters;
    }

}
