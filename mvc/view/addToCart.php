<?php
session_start();
include_once '../controller/PannierController.php';
include_once '../controller/StockController.php';

//if request post and user logged in
if(isset($_SESSION["id"])){
    $id = $_SESSION["id"];
    $quantity=(int) $_POST["quantity"];
    $ref=$_POST["ref"];
    $taille=$_POST["taille"];
    $prix=$_POST["prix"];
    $stockController = new StockController();

    if ($quantity <= 0) {
        $quantity = 1;
    }

    if (!$stockController->isAvailable($ref, $taille, $quantity)) {
        header("location:checkout.php");
        exit;
    }

$prix = floatval(preg_replace('/[^\d.]/', '', $_POST["prix"]));
$total_prod = $prix * $quantity;
    $pannier = new Pannier('', $id, $ref, $quantity, $taille, $total_prod);

    $pannierC = new PannierController();
    $pannierC->addpannier($pannier);
    // reload the page
    header("location:checkout.php");
    exit;
}
else{
    header("location:checkout.php");
    exit;
}


?>