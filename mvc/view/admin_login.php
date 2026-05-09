<?php
include_once('../controller/AdminController.php');
session_start();
require_once('../controller/SessionController.php');
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
if ($res->rowCount() == 1) {
    $l = $res->fetch();
    $_SESSION['id_admin'] = $l[0];
    $_SESSION['nom'] = $l[1];
    $_SESSION['prenom'] = $l[2];
    $_SESSION['email'] = $l[3];
    $_SESSION['mot_de_passe'] = $l[4];
    $_SESSION['admin'] = true;

    header("Location: admin_dashboard.php");
    exit;
} else {
    $_SESSION['message'] = 'Wrong! Username/Password is invalid.';
    header("Location: admin.php");
    exit;
}





?>