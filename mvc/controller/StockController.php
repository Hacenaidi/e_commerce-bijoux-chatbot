<?php
include_once('../../model/Stock.php') ;
include_once('../../database/config.php');
class StockController extends Connexion{
function __construct() {
parent::__construct();
}



public function listStockByRef($ref) {
	$query = "SELECT * FROM stock WHERE ref = ? ORDER BY id DESC";
	$res = $this->pdo->prepare($query);
	$res->execute(array($ref));
	return $res;
}

public function getStockRow($ref, $taille) {
	$query = "SELECT * FROM stock WHERE ref = ? AND taille = ? LIMIT 1";
	$res = $this->pdo->prepare($query);
	$res->execute(array($ref, $taille));
	return $res->fetch();
}

public function getTotalStockByRef($ref) {
	$query = "SELECT COALESCE(SUM(quantite), 0) FROM stock WHERE ref = ?";
	$res = $this->pdo->prepare($query);
	$res->execute(array($ref));
	return (int) $res->fetchColumn();
}

public function upsertStock($ref, $taille, $quantite) {
	$existing = $this->getStockRow($ref, $taille);

	if ($existing) {
		$query = "UPDATE stock SET quantite = ? WHERE id = ?";
		$res = $this->pdo->prepare($query);
		$res->execute(array($quantite, $existing[0]));
		return $res;
	}

	$query = "INSERT INTO stock (ref, `taille`, `quantite`) VALUES (?, ?, ?)";
	$res = $this->pdo->prepare($query);
	$res->execute(array($ref, $taille, $quantite));
	return $res;
}

public function isAvailable($ref, $taille, $quantity) {
	$row = $this->getStockRow($ref, $taille);

	if ($row) {
		return ((int) $row[3]) >= (int) $quantity;
	}

	return $this->getTotalStockByRef($ref) >= (int) $quantity;
}

public function decreaseStock($ref, $taille, $quantity) {
	$row = $this->getStockRow($ref, $taille);

	if ($row) {
		$newQuantity = (int) $row[3] - (int) $quantity;
		if ($newQuantity < 0) {
			$newQuantity = 0;
		}

		$query = "UPDATE stock SET quantite = ? WHERE id = ?";
		$res = $this->pdo->prepare($query);
		$res->execute(array($newQuantity, $row[0]));
		return $res;
	}

	$query = "SELECT * FROM stock WHERE ref = ? ORDER BY quantite DESC LIMIT 1";
	$res = $this->pdo->prepare($query);
	$res->execute(array($ref));
	$fallbackRow = $res->fetch();

	if ($fallbackRow) {
		$newQuantity = (int) $fallbackRow[3] - (int) $quantity;
		if ($newQuantity < 0) {
			$newQuantity = 0;
		}

		$update = $this->pdo->prepare("UPDATE stock SET quantite = ? WHERE id = ?");
		$update->execute(array($newQuantity, $fallbackRow[0]));
		return $update;
	}

	return false;
}
}
?>