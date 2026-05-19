<?php
// Start output buffering to prevent accidental output (notices, HTML)
ob_start();
session_start();
require_once('../../controller/SessionController.php');
$sessionController = new SessionController();
$sessionController->requireAdmin();
require_once('../../controller/ProduitController.php');
require_once('../../controller/ClientController.php');
require_once('../../controller/OrderController.php');
require_once('../../controller/StockController.php');
require_once('../../controller/CollectionController.php');
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
    header("Location: ./admin_dashboard.php?show=clients#clients");
    exit;
}

// Collections CRUD actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['collection_action'])) {
    $action = $_POST['collection_action'];
    $collectionNom = isset($_POST['collection_nom']) ? trim($_POST['collection_nom']) : '';
    $collectionId = isset($_POST['collection_id']) ? $_POST['collection_id'] : '';

    if ($action === 'create' && $collectionNom !== '') {
        $collectionController->createCollection($collectionNom);
        header("Location: ./admin_dashboard.php?show=collections#collections");
        exit;
    }

    if ($action === 'update' && $collectionId !== '') {
        $collectionController->updateCollection($collectionId, $collectionNom);
        header("Location: ./admin_dashboard.php?show=collections#collections");
        exit;
    }

    if ($action === 'delete' && $collectionId !== '') {
        $collectionController->deleteCollection($collectionId);
        header("Location: ./admin_dashboard.php?show=collections#collections");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['approve_order'])) {
    $order->approveOrder($_GET['approve_order']);
    header("Location: ./admin_dashboard.php?show=orders#orders");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['cancel_order'])) {
    $order->cancelOrder($_GET['cancel_order']);
    header("Location: ./admin_dashboard.php?show=orders#orders");
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'order_details' && isset($_GET['order_id'])) {
    // Clean any accidental output from included files before sending JSON
    while (ob_get_level()) {
        ob_end_clean();
    }
    header('Content-Type: application/json; charset=utf-8');
    $details = $order->getOrderDetails($_GET['order_id']);
    if ($details === false || !is_array($details)) {
        echo json_encode(array('order' => null, 'items' => array(), 'error' => 'Order not found'));
    } else {
        echo json_encode($details);
    }
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
$pendingOrderCount = 0;
$approvedOrderCount = 0;
$revenueTotal = 0;
$orderStatusById = array();

function admin_truncate_text($text, $limit = 15) {
    $text = (string) $text;
    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        if (mb_strlen($text, 'UTF-8') <= $limit) {
            return $text;
        }
        return mb_substr($text, 0, $limit, 'UTF-8') . '...';
    }

    if (strlen($text) <= $limit) {
        return $text;
    }

    return substr($text, 0, $limit) . '...';
}

foreach ($orders as $orderRow) {
    $orderId = (int) $orderRow[0];
    $status = strtolower(trim((string) $order->getOrderStatus($orderId)));
    $orderStatusById[$orderId] = $status;

    if ($status === 'pending') {
        $pendingOrderCount++;
    }

    if (in_array($status, array('approved', 'accepted', 'completed', 'delivered'), true)) {
        $approvedOrderCount++;
        $revenueTotal += (float) $orderRow[2];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/base.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin_dashboard.css?v=4">
    <!-- Link any necessary libraries or frameworks here -->
</head>

<body class="admin-dashboard-page">
    <div class="grain" aria-hidden="true"></div>
    <div id="glitter-layer" aria-hidden="true"></div>
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
            <section id="overview" class="panel <?php echo $activeSection === 'overview' ? 'panel-active' : ''; ?>">
                <div class="panel-head">
                    <div>
                        <p class="eyebrow">Admin dashboard</p>
                        <h1>Overview</h1>
                        <p class="panel-copy">A clear control panel to monitor the shop, manage products and process orders.</p>
                    </div>
                    <a href="../actions/logout.php" class="logout-link">Logout</a>
                </div>

                <div class="stats-grid">
                    <article class="stat-card">
                        <span>Clients</span>
                        <strong><?php echo $clientCount; ?></strong>
                        <small>Registered users</small>
                    </article>
                    <article class="stat-card">
                        <span>Products</span>
                        <strong><?php echo $productCount; ?></strong>
                        <small>Active items in catalog</small>
                    </article>
                    <article class="stat-card">
                        <span>Orders</span>
                        <strong><?php echo $pendingOrderCount; ?></strong>
                        <small>Pending orders</small>
                    </article>
                    <article class="stat-card accent">
                        <span>Total revenue</span>
                        <strong><?php echo number_format($revenueTotal, 2, ',', ' '); ?> DT</strong>
                        <small>Approved orders only (<?php echo $approvedOrderCount; ?>)</small>
                    </article>
                </div>

                <div class="quick-grid">
                    <a class="quick-card" href="./admin_dashboard.php?show=products#products">
                        <h3>Manage products</h3>
                        <p>View, edit, or delete a product.</p>
                    </a>
                    <a class="quick-card" href="./admin_dashboard.php?show=add-product#add-product">
                        <h3>Add a product</h3>
                        <p>Create a new product quickly.</p>
                    </a>
                    <a class="quick-card" href="./admin_dashboard.php?show=orders#orders">
                        <h3>Process orders</h3>
                        <p>View and manage orders.</p>
                    </a>
                    <a class="quick-card" href="./admin_dashboard.php?show=clients#clients">
                        <h3>Clients</h3>
                        <p>Search and edit clients without leaving the page.</p>
                    </a>
                </div>
            </section>

            <section id="collections" class="panel <?php echo $activeSection === 'collections' ? 'panel-active' : ''; ?>">
                <div class="panel-head compact">
                    <div>
                            <p class="eyebrow">Collections management</p>
                            <h2>Manage collections</h2>
                        </div>
                </div>

                <!-- Form for adding new collection -->
                <div class="card-shell">
                    <form class="modern-form" method="post" style="grid-template-columns: 1fr auto;">
                        <div class="field">
                            <label for="new_collection_nom">Add a collection</label>
                            <input type="text" name="collection_nom" id="new_collection_nom" placeholder="Collection name" required>
                        </div>
                        <input type="hidden" name="collection_action" value="create">
                        <button type="submit" class="action-link modify" style="align-self: flex-end; margin-bottom: 0;">+ Add</button>
                    </form>
                </div>

                <!-- Collections list table -->
                <div class="card-shell table-shell">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Products</th>
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
                                            <button type="button" class="action-link modify btn-modify" onclick="toggleEditCollection(this)">Edit</button>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="collection_id" value="<?php echo $col['id']; ?>">
                                                <input type="hidden" name="collection_action" value="delete">
                                                <button type="submit" class="action-link danger" data-confirm="Delete this collection?" data-confirm-target-form="" data-confirm-title="Delete collection">Delete</button>
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
            function closeProductActionMenus(exceptMenu) {
                document.querySelectorAll('[data-action-menu]').forEach(function (menu) {
                    if (menu !== exceptMenu) {
                        var trigger = menu.querySelector('.action-menu-trigger');
                        var panel = menu.querySelector('.action-menu-panel');
                        if (trigger) {
                            trigger.setAttribute('aria-expanded', 'false');
                        }
                        if (panel) {
                            panel.hidden = true;
                        }
                    }
                });
            }

            function toggleProductActionMenu(button) {
                var menu = button.closest('[data-action-menu]');
                var panel = menu.querySelector('.action-menu-panel');
                var isOpen = button.getAttribute('aria-expanded') === 'true';

                closeProductActionMenus(menu);

                button.setAttribute('aria-expanded', String(!isOpen));
                panel.hidden = isOpen;

                // If opening, focus first menuitem for keyboard users
                if (!isOpen) {
                    var first = panel.querySelector('[role="menuitem"]');
                    if (first) {
                        // small timeout to ensure visibility
                        setTimeout(function () { first.focus(); }, 10);
                    }
                }
            }

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
                        alert('Name cannot be empty');
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

            document.addEventListener('click', function (event) {
                if (!event.target.closest('[data-action-menu]')) {
                    closeProductActionMenus();
                }
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeProductActionMenus();
                }
            });

            // Keyboard support for action-menu triggers
            document.addEventListener('keydown', function (event) {
                var trg = document.activeElement;
                if (!trg) return;
                var menu = trg.closest && trg.closest('[data-action-menu]');
                if (!menu) return;

                var panel = menu.querySelector('.action-menu-panel');
                var isOpen = trg.getAttribute && trg.getAttribute('aria-expanded') === 'true';

                if (event.key === 'ArrowDown') {
                    event.preventDefault();
                    if (!isOpen) {
                        toggleProductActionMenu(trg);
                    } else {
                        var first = panel.querySelector('[role="menuitem"]');
                        if (first) first.focus();
                    }
                }

                if (event.key === 'ArrowUp') {
                    event.preventDefault();
                    if (!isOpen) {
                        toggleProductActionMenu(trg);
                    } else {
                        var items = panel.querySelectorAll('[role="menuitem"]');
                        if (items.length) items[items.length - 1].focus();
                    }
                }
            });

            // Order details modal helper
            function showOrderDetailsFromMenu(menuItem) {
                var menu = menuItem.closest('[data-action-menu]');
                if (!menu) return;

                function escapeHtml(value) {
                    return String(value || '')
                        .replace(/&/g, '&amp;')
                        .replace(/</g, '&lt;')
                        .replace(/>/g, '&gt;')
                        .replace(/"/g, '&quot;')
                        .replace(/'/g, '&#39;');
                }

                var modal = document.getElementById('order-details-modal');
                if (!modal) return;
                var orderId = menu.getAttribute('data-order-id') || '';
                modal.querySelector('.od-loading').style.display = 'block';
                modal.querySelector('.order-modal-body').style.display = 'none';
                modal.removeAttribute('hidden');
                modal.classList.add('open');

                fetch('admin_dashboard.php?action=order_details&order_id=' + encodeURIComponent(orderId), { credentials: 'same-origin' })
                    .then(function (response) {
                        if (!response.ok) throw new Error('Network response was not ok');
                        return response.text();
                    })
                    .then(function (text) {
                        var payload;
                        try {
                            payload = text ? JSON.parse(text) : {};
                        } catch (e) {
                            throw new Error('Invalid JSON');
                        }

                        if (payload && payload.error) {
                            modal.querySelector('.od-loading').textContent = payload.error;
                            modal.querySelector('.od-loading').style.display = 'block';
                            modal.querySelector('.order-modal-body').style.display = 'none';
                            return;
                        }

                        var order = (payload && payload.order) ? payload.order : {};
                        var items = (payload && payload.items) ? payload.items : [];

                        modal.querySelector('.od-id').textContent = order.order_id || orderId;
                        modal.querySelector('.od-name').textContent = ((order.first_name || '') + ' ' + (order.last_name || '')).trim() || '-';
                        modal.querySelector('.od-email').textContent = order.email || '-';
                        modal.querySelector('.od-phone').textContent = order.telephone || '-';
                        modal.querySelector('.od-address').textContent = order.adress || '-';
                        modal.querySelector('.od-total').textContent = order.total || '0';

                        var itemsList = modal.querySelector('.order-items-list');
                        itemsList.innerHTML = '';

                        if (!items || !items.length) {
                                itemsList.innerHTML = '<div class="order-empty">No items found for this order. Older orders may not have items recorded yet.</div>';
                        } else {
                            items.forEach(function (item) {
                                var row = document.createElement('div');
                                row.className = 'order-item';
                                row.innerHTML = '\n                                    <div class="order-item-thumb">' +
                                    (item.product_image ? '<img src="' + escapeHtml(item.product_image) + '" alt="' + escapeHtml(item.product_name || '') + '">' : '<div class="thumb-placeholder">No image</div>') +
                                    '</div>\n                                    <div class="order-item-info">\n                                        <strong>' + escapeHtml(item.product_name || item.product_ref || '-') + '</strong>\n                                        <span>Ref: ' + escapeHtml(item.product_ref || '-') + '</span>\n                                        <span>QtÃ©: ' + escapeHtml(item.quantity || 1) + '</span>\n                                        <span>Taille: ' + escapeHtml(item.taille || '-') + '</span>\n                                    </div>\n                                    <div class="order-item-meta">\n                                        <span>DT ' + escapeHtml(item.unit_price || '0') + '</span>\n                                        <strong>DT ' + escapeHtml(item.line_total || '0') + '</strong>\n                                    </div>';
                                itemsList.appendChild(row);
                            });
                        }

                        modal.querySelector('.od-loading').style.display = 'none';
                        modal.querySelector('.order-modal-body').style.display = 'grid';
                        modal.querySelector('.modal-close').focus();
                    })
                    .catch(function (err) {
                        console.error('Order details load error:', err && err.message ? err.message : err);
                        modal.querySelector('.od-loading').textContent = 'Unable to load order details.';
                        modal.querySelector('.od-loading').style.display = 'block';
                        modal.querySelector('.order-modal-body').style.display = 'none';
                    });
            }

            function closeOrderModalAndGo() {
                var modal = document.getElementById('order-details-modal');
                if (modal) {
                    modal.classList.remove('open');
                    modal.setAttribute('hidden', '');
                }
                window.location.href = './admin_dashboard.php?show=orders#orders';
            }

            // Modal close handlers
            document.addEventListener('click', function (e) {
                var modal = document.getElementById('order-details-modal');
                if (!modal) return;
                if (e.target.matches('.modal-close') || e.target.closest('.modal-close')) {
                    modal.classList.remove('open');
                    modal.setAttribute('hidden', '');
                }
                if (e.target === modal) {
                    modal.classList.remove('open');
                    modal.setAttribute('hidden', '');
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    var modal = document.getElementById('order-details-modal');
                    if (modal && modal.classList.contains('open')) {
                        modal.classList.remove('open');
                        modal.setAttribute('hidden', '');
                    }
                }
            });
            </script>

            <section id="clients" class="panel <?php echo $activeSection === 'clients' ? 'panel-active' : ''; ?>">
                <div class="panel-head compact clients-head">
                    <div>
                        <p class="eyebrow">Client management</p>
                        <h2>Clients</h2>
                    </div>
                    <form class="client-search" action="./admin_dashboard.php" method="get">
                        <input type="hidden" name="show" value="clients">
                        <input type="text" name="client_search" value="<?php echo htmlspecialchars($clientSearch); ?>" placeholder="Search client">
                        <button type="submit">Search</button>
                    </form>
                </div>
                <div class="card-shell table-shell">
                    <table class="modern-table client-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Last Name</th>
                                <th>First Name</th>
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
                        <h2>Products</h2>
                    </div>
                </div>
                <div class="card-shell table-shell">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>Name</th>
                                <th>Collection</th>
                                <th>Color</th>
                                <th>Price</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Stock</th>
                                <th>Image</th>
                                <th>Action</th>
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
                                    <td class="description-cell" title="<?php echo htmlspecialchars($produit['description']); ?>"><?php echo htmlspecialchars(admin_truncate_text($produit['description'], 15)); ?></td>
                                    <td><?php echo $produit['status']; ?></td>
                                    <td><?php echo $stockTotal; ?></td>
                                    <td><img src="<?php echo $produit['image']; ?>" alt="Product" class="product-thumb"></td>
                                    <td>
                                        <div class="dropdown action-menu" data-action-menu data-order-id="<?php echo htmlspecialchars($l[0]); ?>" data-order-total="<?php echo htmlspecialchars($l[2]); ?>" data-order-name="<?php echo htmlspecialchars($l[3] . ' ' . $l[4]); ?>" data-order-email="<?php echo htmlspecialchars($l[5]); ?>" data-order-phone="<?php echo htmlspecialchars($l[7]); ?>" data-order-address="<?php echo htmlspecialchars($l[6]); ?>">
                                            <button type="button" class="dropdown-toggle action-menu-trigger" aria-expanded="false" aria-controls="action-panel-<?php echo htmlspecialchars($produit['ref']); ?>" onclick="toggleProductActionMenu(this)" title="Actions">
                                                <span class="action-label">Action</span>
                            
                                            </button>
                                            <div id="action-panel-<?php echo htmlspecialchars($produit['ref']); ?>" class="action-menu-panel" role="menu" hidden>
                                                <a class="action-menu-item" role="menuitem" href="ModifyProduit.php?ref=<?php echo $produit['ref']; ?>">Edit</a>
                                                <a class="action-menu-item danger confirm-delete" role="menuitem" data-confirm="Delete this product?" href="../actions/supprimerProduit.php?ref=<?php echo $produit['ref']; ?>">Delete</a>
                                            </div>
                                        </div>
                                    </td>
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
                        <h2>Add product</h2>
                    </div>
                </div>
                <div class="card-shell">
                    <form class="modern-form two-col" action="../actions/ajouterProduit.php" method="post" enctype="multipart/form-data">
                        <div class="field">
                            <label for="nom">Name</label>
                            <input type="text" name="nom" id="nom" placeholder="Product name" required>
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
                            <label for="prix">Price</label>
                            <input type="number" name="prix" id="prix" placeholder="0.00" required>
                        </div>
                        <div class="field">
                            <label for="couleur">Color</label>
                            <input type="text" name="couleur" id="couleur" placeholder="Color" required>
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
                            <label for="stock_taille">Size</label>
                            <input type="text" name="stock_taille" id="stock_taille" placeholder="Ex: Adjustable" required>
                        </div>
                        <div class="field">
                            <label for="stock_quantite">Stock quantity</label>
                            <input type="number" name="stock_quantite" id="stock_quantite" placeholder="0" min="0" required>
                        </div>
                        <input type="submit" value="Add">
                    </form>
                </div>
            </section>

            <section id="orders" class="panel <?php echo $activeSection === 'orders' ? 'panel-active' : ''; ?>">
                <div class="panel-head compact">
                    <div>
                        <p class="eyebrow">Order management</p>
                        <h2>Orders</h2>
                    </div>
                </div>
                <div class="card-shell table-shell">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Reference</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Telephone</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Action</th>
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
                                    <td><?php echo htmlspecialchars(isset($orderStatusById[(int) $l[0]]) ? $orderStatusById[(int) $l[0]] : 'pending'); ?></td>
                                    <td>
                                        <div class="dropdown action-menu" data-action-menu data-order-id="<?php echo htmlspecialchars($l[0]); ?>" data-order-total="<?php echo htmlspecialchars($l[2]); ?>" data-order-name="<?php echo htmlspecialchars($l[3] . ' ' . $l[4]); ?>" data-order-email="<?php echo htmlspecialchars($l[5]); ?>" data-order-phone="<?php echo htmlspecialchars($l[7]); ?>" data-order-address="<?php echo htmlspecialchars($l[6]); ?>">
                                            <button type="button" class="dropdown-toggle action-menu-trigger" aria-expanded="false" aria-controls="order-action-panel-<?php echo htmlspecialchars($l[0]); ?>" onclick="toggleProductActionMenu(this)" title="Actions">
                                                <span class="action-label">Action</span>
                                            </button>
                                            <div id="order-action-panel-<?php echo htmlspecialchars($l[0]); ?>" class="action-menu-panel" role="menu" hidden>
                                                <a class="action-menu-item" role="menuitem" href="#" onclick="showOrderDetailsFromMenu(this);return false;">View</a>
                                                <a class="action-menu-item" role="menuitem" href="admin_dashboard.php?show=orders&approve_order=<?php echo $l[0]; ?>#orders">Approve</a>
                                                <a class="action-menu-item danger" role="menuitem" href="admin_dashboard.php?show=orders&cancel_order=<?php echo $l[0]; ?>#orders" data-confirm="Reject this order?" data-confirm-title="Reject order">Reject</a>
                                                <a class="action-menu-item danger confirm-delete" role="menuitem" data-confirm="Delete this order?" href="../actions/supprimerorder.php?ref=<?php echo $l[0]; ?>">Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <style>
        /* Inline overrides to ensure confirm modal visuals take effect immediately */
        .confirm-modal .confirm-shell{border-radius:16px !important;box-shadow:0 20px 80px rgba(2,6,20,0.75) !important}
        .confirm-modal .btn-confirm{color:#ffffff !important;background:linear-gradient(135deg,var(--danger),#ffb2bb) !important}
        .confirm-modal .confirm-backdrop{background:transparent !important;backdrop-filter:none !important}
    </style>
    <script src="../js/confirm-modal.v9.js"></script>
    <script src="../js/admin_dashboard.js"></script>
    <script>
        // Cleanup any stray '+' text nodes left from previous builds or cached scripts
        document.addEventListener('DOMContentLoaded', function(){
            try {
                var cm = document.getElementById('confirm-modal');
                if (cm) {
                    var walker = document.createTreeWalker(cm, NodeFilter.SHOW_TEXT, null, false);
                    var nodes = [];
                    while (walker.nextNode()) nodes.push(walker.currentNode);
                    nodes.forEach(function(t){
                        if (t.nodeValue && t.nodeValue.trim() === '+') t.parentNode.removeChild(t);
                    });
                    // ensure modal is hidden initially
                    cm.style.display = 'none'; cm.classList.remove('open');
                }
            } catch (e) { /* ignore */ }
        });
        document.addEventListener('DOMContentLoaded', function () {
            var panels = Array.prototype.slice.call(document.querySelectorAll('.dashboard-main .panel'));
            if (!panels.length) {
                return;
            }

            var activePanel = document.querySelector('.dashboard-main .panel.panel-active') || document.querySelector('.dashboard-main .panel:target') || panels[0];

            panels.forEach(function (panel) {
                panel.style.display = panel === activePanel ? 'block' : 'none';
            });
        });

        // Confirmation handling is managed by the themed confirm modal in js/admin_dashboard.js
    </script>

    <div id="order-details-modal" class="order-modal" role="dialog" aria-modal="true" hidden>
        <div class="order-modal-backdrop"></div>
        <div class="order-modal-shell">
            <button type="button" class="modal-close" aria-label="Close">Ã—</button>
            <div class="order-modal-head">
                <span class="eyebrow">Order details</span>
                <h3>Order <span class="od-id"></span></h3>
            </div>
            <div class="od-loading">Loading details...</div>
            <div class="order-modal-body">
                <div class="order-summary-card">
                    <div class="order-grid">
                        <div><strong>Client</strong></div><div class="od-name"></div>
                        <div><strong>Email</strong></div><div class="od-email"></div>
                        <div><strong>Phone</strong></div><div class="od-phone"></div>
                        <div><strong>Address</strong></div><div class="od-address"></div>
                        <div><strong>Total</strong></div><div class="od-total"></div>
                    </div>
                    <div class="order-actions">
                        <button type="button" class="btn-secondary" onclick="closeOrderModalAndGo()">Back to orders</button>
                    </div>
                </div>
                <div class="order-items-card">
                    <div class="order-items-head">
                        <span>Articles commandÃ©s</span>
                    </div>
                    <div class="order-items-list"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function initGoldSnow(){
        var layer = document.getElementById('glitter-layer');
        if (!layer) return;
        function createGold(){
            var glitter = document.createElement('span');
            glitter.className = 'glitter';
            var size = 2 + Math.random() * 4;
            glitter.style.width = size + 'px';
            glitter.style.height = size + 'px';
            glitter.style.left = (Math.random() * 100) + 'vw';
            glitter.style.top = (Math.random() * 100) + 'vh';
            glitter.style.animationDuration = (1.6 + Math.random() * 2.4) + 's';
            layer.appendChild(glitter);
            setTimeout(function(){ try{ glitter.remove(); }catch(e){} }, 4200);
        }
        var gid = setInterval(createGold, 360);
        document.addEventListener('visibilitychange', function(){ if (document.hidden) clearInterval(gid); });
    })();
    </script>

</body>

</html>
