
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

    public function getUsuario() {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario) {
        $this->usuario = $usuario;
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

}
