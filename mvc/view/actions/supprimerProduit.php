<?php

session_start();

$prodCtrl = __DIR__ . '/../../controller/ProduitController.php';
if (file_exists($prodCtrl)) include_once($prodCtrl);
$pc = new ProduitController();

//if the request is get and is admin delete a product from the ref passed by url

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_SESSION['admin']) && $_SESSION['admin'] == true) {


    
    $ref = $_GET['ref'];



    $pc->deleteProduit($ref);
    header("Location: ../admin/admin_dashboard.php?show=products#products");
}





?>
