<?php
require_once 'models/Auth.php';
require_once 'models/Session.php';
require_once 'models/Logger.php';

class LoginController {
    private $auth;
    private $session;
    private $logger;
    private $maxAttempts = 5;
    private $lockoutTime = 900; // 15 minutes

    public function __construct() {
        $this->auth = new Auth();
        $this->session = new Session();
        $this->logger = new Logger();
    }

    public function index() {
        if ($this->session->isLoggedIn()) {
            header("Location: ?page=dashboard");
            exit();
        }
        require_once("views/includes/entete.php");
        require_once("views/login.php");
    }

    public function login() {
        if (!isset($_POST["login"])) {
            return;
        }

        try {
            $this->validateCSRFToken();
            $this->checkRateLimit();

            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = $_POST['mdp'];

            if (!$this->validateInput($email, $password)) {
                throw new Exception("Données d'entrée invalides");
            }

            $user = $this->auth->authenticate($email, $password);

            if (!$user) {
                $this->handleFailedLogin($email);
                return;
            }

            if ($user->requires_2fa) {
                $this->initiate2FAProcess($user);
                return;
            }

            $this->completeLogin($user);

        } catch (Exception $e) {
            $this->logger->logError('login_error', [
                'email' => $email ?? 'unknown',
                'error' => $e->getMessage(),
                'ip' => $_SERVER['REMOTE_ADDR']
            ]);
            setmessage($e->getMessage(), "danger");
        }
    }

    private function validateCSRFToken() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            throw new Exception("Token de sécurité invalide");
        }
    }

    private function checkRateLimit() {
        $ip = $_SERVER['REMOTE_ADDR'];
        $attempts = $this->auth->getLoginAttempts($ip);

        if ($attempts >= $this->maxAttempts) {
            $lastAttempt = $this->auth->getLastLoginAttempt($ip);
            $timeElapsed = time() - $lastAttempt;

            if ($timeElapsed < $this->lockoutTime) {
                $waitTime = ceil(($this->lockoutTime - $timeElapsed) / 60);
                throw new Exception("Trop de tentatives. Réessayez dans {$waitTime} minutes.");
            }

            $this->auth->resetLoginAttempts($ip);
        }
    }

    private function validateInput($email, $password) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) && 
               strlen($password) >= 8;
    }

    private function handleFailedLogin($email) {
        $this->auth->incrementLoginAttempts($_SERVER['REMOTE_ADDR']);
        $this->logger->logWarning('failed_login', [
            'email' => $email,
            'ip' => $_SERVER['REMOTE_ADDR']
        ]);
        setmessage("Identifiants incorrects", "danger");
    }

    private function initiate2FAProcess($user) {
        $code = $this->auth->generate2FACode();
        $_SESSION['2fa_temp'] = [
            'user_id' => $user->id,
            'code' => $code,
            'expires' => time() + 600 // 10 minutes
        ];
        
        // Envoyer le code par email
        $this->auth->send2FACode($user->email, $code);
        
        header("Location: ?page=2fa-verify");
        exit();
    }

    private function completeLogin($user) {
        $token = $this->auth->generateJWTToken($user);
        
        $this->session->start($user, $token);
        $this->auth->resetLoginAttempts($_SERVER['REMOTE_ADDR']);
        
        $this->logger->logInfo('successful_login', [
            'user_id' => $user->id,
            'ip' => $_SERVER['REMOTE_ADDR']
        ]);

        header("Location: ?page=dashboard");
        exit();
    }

    public function logout() {
        $this->session->destroy();
        header("Location: ?page=login");
        exit();
    }
}

// Instanciation et exécution
$controller = new LoginController();
if (isset($_POST["login"])) {
    $controller->login();
} else {
    $controller->index();
}
