<?php 
session_start();
require_once('../controller/SessionController.php');
$sessionController = new SessionController();
$sessionController->logoutAdmin();
header('Location: admin.php');
exit;
?>