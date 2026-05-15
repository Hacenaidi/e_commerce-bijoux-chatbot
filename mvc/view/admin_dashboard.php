<?php
session_start();
require_once('../controller/SessionController.php');
$sessionController = new SessionController();
$sessionController->requireAdmin();
require_once('../controller/ProduitController.php');
require_once('../controller/ClientController.php');
require_once('../controller/OrderController.php');
require_once('../controller/StockController.php');
require_once('../controller/CollectionController.php');
$order = new OrderController();
$produit = new ProduitController();
$client = new ClientController();
$stock = new StockController();
$collectionController = new CollectionController();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != true) {
    header("Location: admin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['client_action']) && $_POST['client_action'] === 'update') {
    $client->updateClient($_POST['client_id'], $_POST['client_nom'], $_POST['client_prenom'], $_POST['client_email']);
    header("Location: admin_dashboard.php?show=clients#clients");
    exit;
}

// Collections CRUD actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['collection_action'])) {
    $action = $_POST['collection_action'];
    $collectionNom = isset($_POST['collection_nom']) ? trim($_POST['collection_nom']) : '';
    $collectionId = isset($_POST['collection_id']) ? $_POST['collection_id'] : '';

    if ($action === 'create' && $collectionNom !== '') {
        $collectionController->createCollection($collectionNom);
        header("Location: admin_dashboard.php?show=collections#collections");
        exit;
    }

    if ($action === 'update' && $collectionId !== '') {
        $collectionController->updateCollection($collectionId, $collectionNom);
        header("Location: admin_dashboard.php?show=collections#collections");
        exit;
    }

    if ($action === 'delete' && $collectionId !== '') {
        $collectionController->deleteCollection($collectionId);
        header("Location: admin_dashboard.php?show=collections#collections");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['approve_order'])) {
    $order->approveOrder($_GET['approve_order']);
    header("Location: admin_dashboard.php?show=orders#orders");
    exit;
}

$clientSearch = isset($_GET['client_search']) ? trim($_GET['client_search']) : '';
$activeSection = isset($_GET['show']) ? $_GET['show'] : 'overview';
$res = $produit->listAllProduit();
$produits = $res->fetchAll();
try {
    $collections = $produit->listCollections()->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $collections = array();
}
$clientsStatement = $client->listAllClient($clientSearch);
$clients = $clientsStatement->fetchAll(PDO::FETCH_ASSOC);
$clientCount = $client->getClientCount();
$ordersStatement = $order->listorder();
$orders = $ordersStatement->fetchAll(PDO::FETCH_NUM);
$productCount = count($produits);
$clientTableCount = count($clients);
$orderCount = count($orders);
$revenueTotal = 0;

foreach ($orders as $orderRow) {
    $revenueTotal += (float) $orderRow[2];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/admin_dashboard.css?v=4">
    <!-- Link any necessary libraries or frameworks here -->
</head>

<body>
    <div class="admin-shell">
        <aside class="sidebar">
            <div class="brand-block">
                <span class="brand-kicker">Admin panel</span>
                <h2>Glowear</h2>
            </div>

            <nav class="sidebar-nav">
                <a href="admin_dashboard.php?show=overview#overview" class="nav-item">Dashboard</a>
                <a href="admin_dashboard.php?show=clients#clients" class="nav-item">Clients</a>
                <a href="admin_dashboard.php?show=products#products" class="nav-item">Produits</a>
                <a href="admin_dashboard.php?show=add-product#add-product" class="nav-item">Ajouter</a>
                <a href="admin_dashboard.php?show=collections#collections" class="nav-item">Collections</a>
                <a href="admin_dashboard.php?show=orders#orders" class="nav-item">Commandes</a>
            </nav>

            <div class="sidebar-footer">
            </div>
        </aside>

        <main class="dashboard-main">
            <section id="overview" class="panel <?php echo $activeSection === 'overview' ? 'panel-active' : ''; ?>">
                <div class="panel-head">
                    <div>
                        <p class="eyebrow">Admin dashboard</p>
                        <h1>Vue d'ensemble</h1>
                        <p class="panel-copy">Un espace de pilotage clair pour suivre la boutique, gérer les produits et traiter les commandes.</p>
                    </div>
                    <a href="logout.php" class="logout-link">Logout</a>
                </div>

                <div class="stats-grid">
                    <article class="stat-card">
                        <span>Clients</span>
                        <strong><?php echo $clientCount; ?></strong>
                        <small>Utilisateurs enregistrés</small>
                    </article>
                    <article class="stat-card">
                        <span>Produits</span>
                        <strong><?php echo $productCount; ?></strong>
                        <small>Articles actifs dans le catalogue</small>
                    </article>
                    <article class="stat-card">
                        <span>Commandes</span>
                        <strong><?php echo $orderCount; ?></strong>
                        <small>Demandes à suivre</small>
                    </article>
                    <article class="stat-card accent">
                        <span>Chiffre total</span>
                        <strong><?php echo number_format($revenueTotal, 2, ',', ' '); ?> DT</strong>
                        <small>Montant cumulé des commandes</small>
                    </article>
                </div>

                <div class="quick-grid">
                    <a class="quick-card" href="admin_dashboard.php?show=products#products">
                        <h3>Gérer les produits</h3>
                        <p>Consulter, modifier ou supprimer un article.</p>
                    </a>
                    <a class="quick-card" href="admin_dashboard.php?show=add-product#add-product">
                        <h3>Ajouter un produit</h3>
                        <p>Créer rapidement une nouvelle fiche produit.</p>
                    </a>
                    <a class="quick-card" href="admin_dashboard.php?show=orders#orders">
                        <h3>Traiter les commandes</h3>
                        <p>Voir les commandes et agir dessus.</p>
                    </a>
                    <a class="quick-card" href="admin_dashboard.php?show=clients#clients">
                        <h3>Clients</h3>
                        <p>Rechercher et modifier sans quitter la page.</p>
                    </a>
                </div>
            </section>

            <section id="collections" class="panel <?php echo $activeSection === 'collections' ? 'panel-active' : ''; ?>">
                <div class="panel-head compact">
                    <div>
                        <p class="eyebrow">Collections management</p>
                        <h2>Gérer les collections</h2>
                    </div>
                </div>

                <!-- Form for adding new collection -->
                <div class="card-shell">
                    <form class="modern-form" method="post" style="grid-template-columns: 1fr auto;">
                        <div class="field">
                            <label for="new_collection_nom">Ajouter une collection</label>
                            <input type="text" name="collection_nom" id="new_collection_nom" placeholder="Nom de la collection" required>
                        </div>
                        <input type="hidden" name="collection_action" value="create">
                        <button type="submit" class="action-link modify" style="align-self: flex-end; margin-bottom: 0;">+ Ajouter</button>
                    </form>
                </div>

                <!-- Collections list table -->
                <div class="card-shell table-shell">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Produits</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($collections as $col) { 
                                $prodNames = $collectionController->getProductsInCollection($col['id']);
                                $prodCount = count($prodNames);
                                $prodList = !empty($prodNames) ? implode(', ', $prodNames) : '-';
                            ?>
                                <tr class="collection-row" data-collection-id="<?php echo $col['id']; ?>">
                                    <td>
                                        <span class="collection-name-display"><?php echo htmlspecialchars($col['nom']); ?></span>
                                        <input type="hidden" class="collection-name-input" value="<?php echo htmlspecialchars($col['nom']); ?>" style="display: none;">
                                    </td>
                                    <td>
                                        <span class="prod-count"><?php echo $prodCount; ?> produit<?php echo $prodCount !== 1 ? 's' : ''; ?></span>
                                        <?php if (!empty($prodNames)) { ?>
                                            <div class="prod-list-inline"><?php echo htmlspecialchars($prodList); ?></div>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button type="button" class="action-link modify btn-modify" onclick="toggleEditCollection(this)">Modifier</button>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="collection_id" value="<?php echo $col['id']; ?>">
                                                <input type="hidden" name="collection_action" value="delete">
                                                <button type="submit" class="action-link danger" onclick="return confirm('Supprimer cette collection ?');">Supprimer</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <script>
            function toggleEditCollection(btn) {
                var row = btn.closest('.collection-row');
                var isEditing = row.classList.contains('editing');
                var collectionId = row.getAttribute('data-collection-id');
                var nameDisplay = row.querySelector('.collection-name-display');
                var nameInput = row.querySelector('.collection-name-input');
                
                if (!isEditing) {
                    // Switch to edit mode
                    nameDisplay.style.display = 'none';
                    nameInput.style.display = 'inline-block';
                    nameInput.setAttribute('type', 'text');
                    btn.textContent = 'Save';
                    btn.classList.add('btn-save');
                    row.classList.add('editing');
                } else {
                    // Save mode
                    var newName = nameInput.value.trim();
                    if (newName === '') {
                        alert('Le nom ne peut pas être vide');
                        return;
                    }
                    
                    // Create and submit form
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.style.display = 'none';
                    
                    var input1 = document.createElement('input');
                    input1.type = 'hidden';
                    input1.name = 'collection_id';
                    input1.value = collectionId;
                    form.appendChild(input1);
                    
                    var input2 = document.createElement('input');
                    input2.type = 'hidden';
                    input2.name = 'collection_nom';
                    input2.value = newName;
                    form.appendChild(input2);
                    
                    var input3 = document.createElement('input');
                    input3.type = 'hidden';
                    input3.name = 'collection_action';
                    input3.value = 'update';
                    form.appendChild(input3);
                    
                    document.body.appendChild(form);
                    form.submit();
                }
            }
            </script>

            <section id="clients" class="panel <?php echo $activeSection === 'clients' ? 'panel-active' : ''; ?>">
                <div class="panel-head compact clients-head">
                    <div>
                        <p class="eyebrow">Client management</p>
                        <h2>Clients</h2>
                    </div>
                    <form class="client-search" action="admin_dashboard.php" method="get">
                        <input type="hidden" name="show" value="clients">
                        <input type="text" name="client_search" value="<?php echo htmlspecialchars($clientSearch); ?>" placeholder="Rechercher un client">
                        <button type="submit">Search</button>
                    </form>
                </div>
                <div class="card-shell table-shell">
                    <table class="modern-table client-table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nom</th>
                                <th>Prenom</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clients as $currentClient) { ?>
                                <tr>
                                    <td><?php echo $currentClient['id']; ?></td>
                                    <td>
                                        <input form="client-form-<?php echo $currentClient['id']; ?>" type="text" name="client_nom" value="<?php echo htmlspecialchars($currentClient['nom']); ?>">
                                    </td>
                                    <td>
                                        <input form="client-form-<?php echo $currentClient['id']; ?>" type="text" name="client_prenom" value="<?php echo htmlspecialchars($currentClient['prenom']); ?>">
                                    </td>
                                    <td>
                                        <input form="client-form-<?php echo $currentClient['id']; ?>" type="email" name="client_email" value="<?php echo htmlspecialchars($currentClient['email']); ?>">
                                    </td>
                                    <td>
                                        <form id="client-form-<?php echo $currentClient['id']; ?>" method="post">
                                            <input type="hidden" name="client_id" value="<?php echo $currentClient['id']; ?>">
                                            <button type="submit" name="client_action" value="update" class="action-link edit">Save</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="products" class="panel <?php echo $activeSection === 'products' ? 'panel-active' : ''; ?>">
                <div class="panel-head compact">
                    <div>
                        <p class="eyebrow">Product management</p>
                        <h2>Catalogue</h2>
                    </div>
                </div>
                <div class="card-shell table-shell">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Nom</th>
                                <th>Collection</th>
                                <th>Couleur</th>
                                <th>Prix</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Stock</th>
                                <th>Image</th>
                                <th>Modifier</th>
                                <th>Supprimer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produits as $produit) { ?>
                                <?php $stockTotal = $stock->getTotalStockByRef($produit['ref']); ?>
                                <tr>
                                    <td><?php echo $produit['ref']; ?></td>
                                    <td><?php echo $produit['nom']; ?></td>
                                    <td><?php echo isset($produit['collection_nom']) && !empty($produit['collection_nom']) ? htmlspecialchars($produit['collection_nom']) : '-'; ?></td>
                                    <td><?php echo $produit['couleur']; ?></td>
                                    <td><?php echo $produit['prix']; ?></td>
                                    <td><?php echo $produit['description']; ?></td>
                                    <td><?php echo $produit['status']; ?></td>
                                    <td><?php echo $stockTotal; ?></td>
                                    <td><img src="<?php echo $produit['image']; ?>" alt="Produit" class="product-thumb"></td>
                                    <td><a class="action-link modify" href="ModifyProduit.php?ref=<?php echo $produit['ref']; ?>">Modifier</a></td>
                                    <td><a class="action-link danger confirm-delete" data-confirm="Supprimer ce produit ?" href="supprimerProduit.php?ref=<?php echo $produit['ref']; ?>">Supprimer</a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="add-product" class="panel <?php echo $activeSection === 'add-product' ? 'panel-active' : ''; ?>">
                <div class="panel-head compact">
                    <div>
                        <p class="eyebrow">Quick action</p>
                        <h2>Ajouter un produit</h2>
                    </div>
                </div>
                <div class="card-shell">
                    <form class="modern-form two-col" action="ajouterProduit.php" method="post" enctype="multipart/form-data">
                        <div class="field">
                            <label for="nom">Nom</label>
                            <input type="text" name="nom" id="nom" placeholder="Nom du produit" required>
                        </div>
                        <div class="field">
                            <label for="collection_id">Collection</label>
                            <select name="collection_id" id="collection_id" class="modern-select">
                                <option value="">Sans collection</option>
                                <?php foreach ($collections as $collection) { ?>
                                    <option value="<?php echo $collection['id']; ?>"><?php echo htmlspecialchars($collection['nom']); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="field">
                            <label for="prix">Prix</label>
                            <input type="number" name="prix" id="prix" placeholder="0.00" required>
                        </div>
                        <div class="field">
                            <label for="couleur">Couleur</label>
                            <input type="text" name="couleur" id="couleur" placeholder="Couleur" required>
                        </div>
                        <div class="field">
                            <label for="description">Description</label>
                            <input type="text" name="description" id="description" placeholder="Description" required>
                        </div>
                        <div class="field">
                            <label for="status">Status</label>
                            <input type="text" name="status" id="status" placeholder="Ex: available" required>
                        </div>
                        <div class="field full">
                            <label for="image">Image</label>
                            <input type="file" name="image" id="image" required>
                        </div>
                        <div class="field">
                            <label for="stock_taille">Taille stock</label>
                            <input type="text" name="stock_taille" id="stock_taille" placeholder="Ex: Adjustable" required>
                        </div>
                        <div class="field">
                            <label for="stock_quantite">Quantite stock</label>
                            <input type="number" name="stock_quantite" id="stock_quantite" placeholder="0" min="0" required>
                        </div>
                        <input type="submit" value="Ajouter">
                    </form>
                </div>
            </section>

            <section id="orders" class="panel <?php echo $activeSection === 'orders' ? 'panel-active' : ''; ?>">
                <div class="panel-head compact">
                    <div>
                        <p class="eyebrow">Order management</p>
                        <h2>Commandes</h2>
                    </div>
                </div>
                <div class="card-shell table-shell">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Prenom</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Telephone</th>
                                <th>Prix total</th>
                                <th>Approuver</th>
                                <th>Supprimer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $l) { ?>
                                <tr>
                                    <td><?php echo $l[0]; ?></td>
                                    <td><?php echo $l[3]; ?></td>
                                    <td><?php echo $l[4]; ?></td>
                                    <td><?php echo $l[5]; ?></td>
                                    <td><?php echo $l[6]; ?></td>
                                    <td><?php echo $l[7]; ?></td>
                                    <td><?php echo $l[2]; ?></td>
                                    <td><a class="action-link edit" href="admin_dashboard.php?show=orders&approve_order=<?php echo $l[0]; ?>#orders">Approuver</a></td>
                                    <td><a class="action-link danger confirm-delete" data-confirm="Supprimer cette commande ?" href="supprimerorder.php?ref=<?php echo $l[0]; ?>">Supprimer</a></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script src="js/admin_dashboard.js"></script>
    <script>
        document.querySelectorAll('.confirm-delete').forEach(function (link) {
            link.addEventListener('click', function (event) {
                var message = link.getAttribute('data-confirm') || 'Confirmer la suppression ?';
                if (!window.confirm(message)) {
                    event.preventDefault();
                }
            });
        });
    </script>

</body>

</html>