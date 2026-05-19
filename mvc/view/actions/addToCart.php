<?php
session_start();
$pannierCtrl = __DIR__ . '/../../controller/PannierController.php';
if (file_exists($pannierCtrl)) include_once($pannierCtrl);
$stockCtrl = __DIR__ . '/../../controller/StockController.php';
if (file_exists($stockCtrl)) include_once($stockCtrl);

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
        header("Location: ../public/checkout.php");
        exit;
    }

$prix = floatval(preg_replace('/[^\d.]/', '', $_POST["prix"]));
$total_prod = $prix * $quantity;
    $pannier = new Pannier('', $id, $ref, $quantity, $taille, $total_prod);

    $pannierC = new PannierController();
    $pannierC->addpannier($pannier);
    // reload the page
    header("Location: ../public/checkout.php");
    exit;
}
else{
    header("Location: ../public/checkout.php");
    exit;
}


?>
