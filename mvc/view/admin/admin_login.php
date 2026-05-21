<?php
include_once(__DIR__ . '/../../controller/AdminController.php');
session_start();
require_once(__DIR__ . '/../../controller/SessionController.php');
$sessionController = new SessionController();
$sessionController->redirectIfAdmin();
$mail = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$ac = new AdminController();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin.php");
    exit;
}

$res = $ac->authenticateAdmin($mail, $password);
if ($res) {
    $_SESSION['id_admin'] = $res['id'] ?? null;
    $_SESSION['nom'] = $res['nom'] ?? '';
    $_SESSION['prenom'] = $res['prenom'] ?? '';
    $_SESSION['email'] = $res['email'] ?? '';
    $_SESSION['admin'] = true;

    header("Location: ./admin_dashboard.php");
    exit;
} else {
    $_SESSION['message'] = 'Wrong! Username/Password is invalid.';
    header("Location: ./admin.php");
    exit;
}





?>
