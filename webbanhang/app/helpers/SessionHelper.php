<?php
class SessionHelper
{
    public static function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function checkAdmin()
    {
        self::startSession();
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: /webbanhang/');
            exit();
        }
    }
}
?>
