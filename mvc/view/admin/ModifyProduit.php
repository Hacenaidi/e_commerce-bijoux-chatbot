<?php
require_once('../../controller/ProduitController.php');
require_once('../../controller/StockController.php');
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
    <title>Edit Product</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/base.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin_dashboard.css">
</head>

<body class="admin-dashboard-page">
    <div class="admin-shell">
        <aside class="sidebar">
            <div class="brand-block">
                <span class="brand-kicker">Admin panel</span>
                <h2>Glowear</h2>
            </div>

            <nav class="sidebar-nav">
                <a href="./admin_dashboard.php?show=overview#overview" class="nav-item">Dashboard</a>
                <a href="./admin_dashboard.php?show=clients#clients" class="nav-item">Clients</a>
                <a href="./admin_dashboard.php?show=products#products" class="nav-item">Products</a>
                <a href="./admin_dashboard.php?show=add-product#add-product" class="nav-item">Add Product</a>
                <a href="./admin_dashboard.php?show=collections#collections" class="nav-item">Collections</a>
                <a href="./admin_dashboard.php?show=orders#orders" class="nav-item">Orders</a>
            </nav>

            <div class="sidebar-footer">
            </div>
        </aside>

        <main class="dashboard-main">
            <section class="panel panel-active">
                <div class="panel-head">
                    <div>
                        <p class="eyebrow">Product edit</p>
                        <h1>Edit product</h1>
                        <p class="panel-copy">Update product information without leaving the admin area.</p>
                    </div>
                    <a href="./admin_dashboard.php#products" class="logout-link">Back</a>
                </div>

                <div class="card-shell">
                    <form class="modern-form two-col" action="./modify_action_produit.php" method="post">
                        <div class="field">
                            <label for="nom">Name</label>
                            <input type="text" name="nom" id="nom" required value="<?php echo htmlspecialchars($pro['nom']); ?>">
                        </div>
                        <div class="field">
                            <label for="collection_id">Collection</label>
                            <?php $selectedCollection = isset($pro['id_collection']) ? $pro['id_collection'] : (isset($pro['collection_id']) ? $pro['collection_id'] : ''); ?>
                            <select name="collection_id" id="collection_id" class="modern-select">
                                <option value="">Sans collection</option>
                                <?php foreach ($collections as $collection) { ?>
                                    <option value="<?php echo $collection['id']; ?>" <?php echo ($selectedCollection !== '' && $selectedCollection == $collection['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($collection['nom']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="field">
                            <label for="prix">Price</label>
                            <input type="number" name="prix" id="prix" required value="<?php echo htmlspecialchars($pro['prix']); ?>">
                        </div>
                        <div class="field">
                            <label for="couleur">Color</label>
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
                            <label for="stock_taille">Size</label>
                            <input type="text" name="stock_taille" id="stock_taille" required value="<?php echo $firstStock ? htmlspecialchars($firstStock['taille']) : 'Adjustable'; ?>">
                        </div>
                        <div class="field">
                            <label for="stock_quantite">Stock quantity</label>
                            <input type="number" name="stock_quantite" id="stock_quantite" min="0" required value="<?php echo $firstStock ? htmlspecialchars($firstStock['quantite']) : '0'; ?>">
                        </div>
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($pro['ref']); ?>">
                        <div style="grid-column: 1 / -1; display:flex; justify-content:flex-end;">
                            <button type="submit" class="btn-red">Update</button>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </div>
    <script src="../js/admin_dashboard.js"></script>
</body>
</html>



