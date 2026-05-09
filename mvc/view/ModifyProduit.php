<?php
require_once('../controller/ProduitController.php');
require_once('../controller/StockController.php');
$produit = new ProduitController();
$stock = new StockController();
$id = $_GET["ref"];
$pro = $produit->produit($id)->fetch();
try {
    $collections = $produit->listCollections()->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $collections = array();
}
$stockRows = $stock->listStockByRef($id)->fetchAll(PDO::FETCH_ASSOC);
$firstStock = count($stockRows) > 0 ? $stockRows[0] : null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier Produit</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin_dashboard.css">
</head>

<body>
    <div class="admin-shell single-pane">
        <main class="dashboard-main">
            <section class="panel panel-active">
                <div class="panel-head">
                    <div>
                        <p class="eyebrow">Product edit</p>
                        <h1>Modifier produit</h1>
                        <p class="panel-copy">Corrige les informations du produit sans quitter l’espace admin.</p>
                    </div>
                    <a href="admin_dashboard.php#products" class="logout-link">Retour</a>
                </div>

                <div class="card-shell">
                    <form class="modern-form two-col" action="modify_action_produit.php" method="post">
                        <div class="field">
                            <label for="nom">Nom</label>
                            <input type="text" name="nom" id="nom" required value="<?php echo htmlspecialchars($pro['nom']); ?>">
                        </div>
                        <div class="field">
                            <label for="collection_id">Collection</label>
                            <select name="collection_id" id="collection_id" class="form-control">
                                <option value="">Sans collection</option>
                                <?php foreach ($collections as $collection) { ?>
                                    <option value="<?php echo $collection['id']; ?>" <?php echo (isset($pro['collection_id']) && $pro['collection_id'] == $collection['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($collection['nom']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="field">
                            <label for="prix">Prix</label>
                            <input type="number" name="prix" id="prix" required value="<?php echo htmlspecialchars($pro['prix']); ?>">
                        </div>
                        <div class="field">
                            <label for="couleur">Couleur</label>
                            <input type="text" name="couleur" id="couleur" required value="<?php echo htmlspecialchars($pro['couleur']); ?>">
                        </div>
                        <div class="field full">
                            <label for="description">Description</label>
                            <input type="text" name="description" id="description" required value="<?php echo htmlspecialchars($pro['description']); ?>">
                        </div>
                        <div class="field full">
                            <label for="status">Status</label>
                            <input type="text" name="status" id="status" required value="<?php echo htmlspecialchars($pro['status']); ?>">
                        </div>
                        <div class="field">
                            <label for="stock_taille">Taille stock</label>
                            <input type="text" name="stock_taille" id="stock_taille" required value="<?php echo $firstStock ? htmlspecialchars($firstStock['taille']) : 'Adjustable'; ?>">
                        </div>
                        <div class="field">
                            <label for="stock_quantite">Quantite stock</label>
                            <input type="number" name="stock_quantite" id="stock_quantite" min="0" required value="<?php echo $firstStock ? htmlspecialchars($firstStock['quantite']) : '0'; ?>">
                        </div>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($pro['ref']); ?>">
                        <input type="submit" value="Modifier">
                    </form>
                </div>
            </section>
        </main>
    </div>
    <script src="js/admin_dashboard.js"></script>
</body>
</html>


