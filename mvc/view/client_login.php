<?php
session_start();

include_once('../controller/ClientController.php');
$cc = new ClientController();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: checkout.php");
    exit;
}

$mail = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$res = $cc->authenticateClient($mail, $password);
if ($res->rowCount() == 1) {
    $l = $res->fetch();
    $_SESSION['id'] = $l[0];
    $_SESSION['nom'] = $l[1];
    $_SESSION['prenom'] = $l[2];
    $_SESSION['email'] = $l[4];
    $_SESSION['admin'] = false;
    header("Location: checkout.php");
    exit;
} else {
    $_SESSION['message'] = 'Wrong! Username/Password is invalid.';
    header("Location: checkout.php");
    exit;
}



?>