<?php
include_once('../controller/PannierController.php');
$controller = new PannierController();
$id = $_POST['id'];
$qte = $_POST["qte"];
header("refresh:0.1;url=cart.php");
$res = $controller->updatepannier($id,$qte);

?>