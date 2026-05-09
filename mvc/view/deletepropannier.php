<?php
include_once('../controller/PannierController.php');
$controller = new PannierController();
$id = $_GET['id'];
$controller->deletepannier($id);
header("refresh:0.1;url=cart.php");

?>