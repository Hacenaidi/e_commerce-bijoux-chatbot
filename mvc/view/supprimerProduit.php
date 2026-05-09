<?php

session_start();

include_once('../controller/ProduitController.php');
$pc = new ProduitController();

//if the request is get and is admin delete a product from the ref passed by url

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_SESSION['admin']) && $_SESSION['admin'] == true) {


    
    $ref = $_GET['ref'];



    $pc->deleteProduit($ref);
    header("Location: admin_dashboard.php?show=products#products");
}





?>