<?php
session_start();
$sessionCtrl = __DIR__ . '/../../controller/SessionController.php';
if (file_exists($sessionCtrl)) require_once $sessionCtrl;

// Check both admin and user session variables
$isAdmin = !empty($_SESSION['admin']);
$isUser = !empty($_SESSION['user']);

$sessionController = new SessionController();
$sessionController->logoutAdmin();

if ($isAdmin && !$isUser) {
    header('Location: ../admin/admin.php');
} else {
    header('Location: ../auth/client_login.php');
}
exit;
?>
