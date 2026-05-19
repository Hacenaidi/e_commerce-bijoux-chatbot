<?php

session_start();

include_once('../../controller/ProduitController.php');
include_once('../../controller/StockController.php');
$pc = new ProduitController();
$sc = new StockController();
//if the request is post and is admin insert a new product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['admin']) && $_SESSION['admin'] == true) {
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $couleur = $_POST['couleur'];
    $collectionId = isset($_POST['collection_id']) ? $_POST['collection_id'] : '';
    $stockTaille = isset($_POST['stock_taille']) ? $_POST['stock_taille'] : 'Adjustable';
    $stockQuantite = isset($_POST['stock_quantite']) ? (int) $_POST['stock_quantite'] : 0;
    $image = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // ensure images directory exists and is writable
        $imagesDir = '../images';
        if (!is_dir($imagesDir)) {
            @mkdir($imagesDir, 0755, true);
        }

        // sanitize filename
        $origName = basename($_FILES['image']['name']);
        $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $origName);
        $imageName = time() . '_' . $safeName;
        $targetPath = $imagesDir . DIRECTORY_SEPARATOR . $imageName;

        if (is_uploaded_file($_FILES['image']['tmp_name']) && move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $image = '../images/' . $imageName;
        } else {
            error_log('Image upload failed: tmp=' . @$_FILES['image']['tmp_name'] . ' target=' . $targetPath);
        }
    }

    // Create Produit with correct parameter order: (ref, description, couleur, status, nom, prix, image, collection)
    $produit = new Produit("", $description, $couleur, $status, $nom, $prix, $image, $collectionId);
    $newRef = $pc->createProduit($produit, $stockTaille, $stockQuantite);

    header("Location: ../admin/admin_dashboard.php?show=products#products");
    exit;
}


?>
