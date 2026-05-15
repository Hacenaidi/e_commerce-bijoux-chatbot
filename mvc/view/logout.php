<?php
session_start();
require_once('../controller/SessionController.php');

// Check both admin and user session variables
$isAdmin = !empty($_SESSION['admin']);
$isUser = !empty($_SESSION['user']);

$sessionController = new SessionController();
$sessionController->logoutAdmin();

if ($isAdmin && !$isUser) {
    header('Location: admin.php');
} else {
    header('Location: client_login.php');
}
exit;
?>