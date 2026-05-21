<?php

class SessionController
{
    public function startSession()
    {
        if (function_exists('session_status')) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            return;
        }

        if (session_id() === '') {
            session_start();
        }
    }

    public function isAdminLoggedIn()
    {
        $this->startSession();
        return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
    }

    public function requireAdmin()
    {
        if (!$this->isAdminLoggedIn()) {
            header('Location: ../admin/admin.php');
            exit;
        }
    }

    public function redirectIfAdmin()
    {
        if ($this->isAdminLoggedIn()) {
            header('Location: ../admin/admin_dashboard.php');
            exit;
        }
    }

    public function logoutAdmin()
    {
        $this->startSession();
        $_SESSION = array();

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();
    }
}