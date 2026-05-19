<?php
$pannierCtrl = __DIR__ . '/../../controller/PannierController.php';
if (file_exists($pannierCtrl)) include_once($pannierCtrl);
$controller = new PannierController();
$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$qte = isset($_POST['qte']) ? (int) $_POST['qte'] : 1;

if ($id <= 0) {
	header("Location: ../public/cart.php");
	exit;
}

if ($qte < 1) {
	$qte = 1;
}

header("refresh:0.1;url=../public/cart.php");
$res = $controller->updatepannier($id,$qte);

?>
