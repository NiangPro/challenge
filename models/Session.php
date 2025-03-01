<?php

class Session {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            $this->initializeSession();
        }
    }

    private function initializeSession() {
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_secure', 1);
        ini_set('session.cookie_samesite', 'Strict');
        ini_set('session.gc_maxlifetime', 3600);
        session_start();
    }

    public function start($user, $token) {
        $_SESSION['user'] = $user;
        $_SESSION['token'] = $token;
        $_SESSION['last_activity'] = time();
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        
        // Régénérer l'ID de session pour prévenir la fixation de session
        session_regenerate_id(true);
    }

    public function isLoggedIn() {
        return isset($_SESSION['user']) && $this->isValid();
    }

    public function destroy() {
        $_SESSION = array();
        
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        session_destroy();
    }

    private function isValid() {
        if (!isset($_SESSION['last_activity']) || 
            !isset($_SESSION['ip']) || 
            !isset($_SESSION['user_agent'])) {
            return false;
        }

        // Vérifier l'expiration de la session (1 heure)
        if (time() - $_SESSION['last_activity'] > 3600) {
            $this->destroy();
            return false;
        }

        // Vérifier si l'IP a changé
        if ($_SESSION['ip'] !== $_SERVER['REMOTE_ADDR']) {
            $this->destroy();
            return false;
        }

        // Vérifier si le User-Agent a changé
        if ($_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
            $this->destroy();
            return false;
        }

        // Mettre à jour le timestamp de dernière activité
        $_SESSION['last_activity'] = time();

        return true;
    }

    public function refresh() {
        if ($this->isLoggedIn()) {
            // Régénérer l'ID de session périodiquement
            if (!isset($_SESSION['last_regeneration']) || 
                time() - $_SESSION['last_regeneration'] > 300) {
                session_regenerate_id(true);
                $_SESSION['last_regeneration'] = time();
            }
        }
    }

    public function getUser() {
        return $this->isLoggedIn() ? $_SESSION['user'] : null;
    }

    public function getToken() {
        return $this->isLoggedIn() ? $_SESSION['token'] : null;
    }
}
