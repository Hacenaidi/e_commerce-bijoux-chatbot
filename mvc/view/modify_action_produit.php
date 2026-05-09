<?php

session_start();

include_once('../controller/ProduitController.php');
include_once('../controller/StockController.php');
$pc = new ProduitController();
$sc = new StockController();
//if the request is post and is admin insert a new product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['admin']) && $_SESSION['admin'] == true) {
    $nom = $_POST['nom'];
    $id = $_POST['id'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $couleur = $_POST['couleur'];
    $collectionId = isset($_POST['collection_id']) ? $_POST['collection_id'] : '';
    $stockTaille = isset($_POST['stock_taille']) ? $_POST['stock_taille'] : 'Adjustable';
    $stockQuantite = isset($_POST['stock_quantite']) ? (int) $_POST['stock_quantite'] : 0;

    // Create Produit with correct parameter order: (ref, description, couleur, status, nom, prix, image, collection)
    $produit = new Produit($id, $description, $couleur, $status, $nom, $prix, "", $collectionId);
    $pc->updateproduit($produit, $stockTaille, $stockQuantite);
    header("Location: admin_dashboard.php?show=products#products");
}


?>