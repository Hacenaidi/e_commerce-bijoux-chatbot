<?php
include_once('../model/Client.php');
include_once('../controller/ClientController.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $newClient = new Client('',$nom, $prenom, $password, $email);


    $clientController = new ClientController();
    $clientController->createClient($newClient);
    header("refresh:0.5;url=checkout.php");
    echo "<script>alert('Votre compte a ete cree avec succes!')</script>";
    exit();
}




?>