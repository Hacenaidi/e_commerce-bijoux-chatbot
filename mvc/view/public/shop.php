<?php
session_start();
$prodCtrl = __DIR__ . '/../../controller/ProduitController.php';
if (file_exists($prodCtrl)) {
    include_once($prodCtrl);
}
$pannierCtrl = __DIR__ . '/../../controller/PannierController.php';
if (file_exists($pannierCtrl)) {
    include_once($pannierCtrl);
}
$stockCtrl = __DIR__ . '/../../controller/StockController.php';
if (file_exists($stockCtrl)) {
    include_once($stockCtrl);
}
$viewHelpers = __DIR__ . '/../inc/view_helpers.php';
if (file_exists($viewHelpers)) {
    include_once($viewHelpers);
}
$controllerpannier = new PannierController();
$stockController = new StockController();
$id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$listpannier = $controllerpannier->listpannier($id);

$controller = new ProduitController();
$collections = $controller->listCollections()->fetchAll(PDO::FETCH_ASSOC);
$collectionFilter = '';
$priceMin = 0;
$priceMax = 500;
$nom = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
} else {
    $nom = isset($_GET['nom']) ? urldecode($_GET['nom']) : '';
}
$showAllCollections = strcasecmp(trim($nom), 'all') === 0;

// 1. Check for product detail first
$ref = isset($_GET['ref']) ? $_GET['ref'] : '';
$product = null;
if ($ref !== '') {
    $product = $controller->produit($ref)->fetch(PDO::FETCH_ASSOC);
}

// 2. If not product detail, do collection filtering as before
if ($product === false || $ref === '') {
    $collectionIdFromNom = null;
    if (!$showAllCollections) {
        foreach ($collections as $c) {
            if (strcasecmp(trim($c['nom']), trim($nom)) === 0) {
                $collectionIdFromNom = $c['id'];
                break;
            }
        }
    }

    // If we arrived via a collection name in the URL (GET), set the
    // collection radio state so the sidebar reflects the active collection.
    if ($showAllCollections) {
        $collectionFilter = 'all';
    } elseif ($_SERVER['REQUEST_METHOD'] !== 'POST' && $collectionIdFromNom !== null) {
        $collectionFilter = (string) $collectionIdFromNom;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $prix = isset($_POST['prix']) ? trim($_POST['prix']) : '';
        $collectionFilter = isset($_POST['collection_id']) ? trim($_POST['collection_id']) : '';

        if ($collectionFilter === 'all') {
            $nom = 'all';
            $showAllCollections = true;
        }

        // Keep collection scope from URL when no radio was explicitly selected.
        if ($collectionFilter === '' && $collectionIdFromNom !== null) {
            $collectionFilter = (string) $collectionIdFromNom;
        }

        // Parse "DT X - DT Y" safely, fallback to defaults.
        if (preg_match('/DT\s*(\d+)\s*-\s*DT\s*(\d+)/i', $prix, $matches)) {
            $priceMin = (int) $matches[1];
            $priceMax = (int) $matches[2];
        }

        if ($priceMin > $priceMax) {
            $tmp = $priceMin;
            $priceMin = $priceMax;
            $priceMax = $tmp;
        }

        if ($collectionFilter === 'all') {
            $listproduitdetail = $controller->listproduitparprix($priceMin, $priceMax, '');
        } else {
            $listproduitdetail = $controller->listproduitparprix($priceMin, $priceMax, $collectionFilter);
        }
    } else {
        $collectionId = $collectionIdFromNom;

        if ($showAllCollections) {
            $listproduitdetail = $controller->listAllProduits();
        } elseif ($nom !== '' && $collectionId === null) {
            $productByName = $controller->produitByName($nom);
            if ($productByName) {
                $product = $productByName;
                $ref = $product['ref'];
            } else {
                $listproduitdetail = [];
            }
        } elseif ($collectionId !== null) {
            $listproduitdetail = $controller->listproduit($collectionId);
        } else {
            $listproduitdetail = $controller->listAllProduits();
        }
    }
}

$showProductDetail = false;
if ($ref !== '' && $product) {
    $showProductDetail = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Glowear &mdash; <?php echo htmlspecialchars($nom) ?></title>

    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="../images/apple-touch-icon.png">

    <link rel="stylesheet" href="../css/chatbot-widget.css?v=20260517.2">
    <link rel="stylesheet" href="../css/chatbot-messages.css">
    <link rel="stylesheet" href="../css/response-format.css">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/shop.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css">

    
</head>
<body>

<div class="grain"></div>
<div id="glitter-layer"></div>
<div id="cur"></div>
<div id="cur-ring"></div>

<!--  SIDE CART  -->
<div class="cart-overlay" id="cartOv" onclick="toggleCart()"></div>
<div class="side-cart" id="sideCart">
    <div class="sc-head">
        <span class="sc-title">Your Cart</span>
        <button class="sc-close" onclick="toggleCart()"><i class="fa fa-times"></i></button>
    </div>
    <?php
    $total = 0;
    $lp2   = $controllerpannier->listpannier($id);
    while($l = $lp2->fetch()):
        $pro = $controller->produit($l[2])->fetch();
        $cartImage = function_exists('view_safe_image_path') ? view_safe_image_path($pro[6] ?? '') : ($pro[6] ?? '../images/placeholder.png');
        $cartName = $pro[4] ?? '';
        $cartPrice = $pro[5] ?? '';
    ?>
    <div class="sc-item">
        <img src="<?php echo htmlspecialchars($cartImage); ?>" alt="<?php echo htmlspecialchars($cartName); ?>">
        <div>
            <div class="sc-item-name"><?php echo htmlspecialchars($cartName); ?></div>
            <div class="sc-item-detail"><?php echo htmlspecialchars($l[3]); ?> x <span style="color:var(--red2)">DT <?php echo htmlspecialchars($cartPrice); ?></span></div>
        </div>
    </div>
    <?php $total += $l[5]; endwhile; ?>
    <div class="sc-total">
        <span class="sc-total-label">Total</span>
        <span class="sc-total-val">DT <?php echo $total ?></span>
    </div>
    <a href="cart.php" class="btn-red" style="display:block;text-align:center;margin-bottom:12px">View Cart</a>
    <a href="checkout.php" class="btn-ghost" style="display:block;text-align:center">Checkout</a>
</div>

<!--  SEARCH OVERLAY  -->
<div id="searchOv" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.96);z-index:3000;flex-direction:column;align-items:center;justify-content:center;">
    <i class="fa fa-times" id="searchClose" style="position:absolute;top:36px;right:56px;font-size:22px;color:rgba(255,255,255,.4);cursor:pointer;"></i>
    <input type="text" placeholder="Search bijoux..." style="background:none;border:none;border-bottom:1px solid rgba(255,255,255,.25);font-family:'Cormorant Garamond',serif;font-size:34px;color:#fff;width:60%;max-width:580px;padding:14px 0;text-align:center;outline:none;">
    <p style="color:rgba(255,255,255,.2);font-size:9px;letter-spacing:4px;text-transform:uppercase;margin-top:20px;">Press Enter</p>
</div>

<!--  TICKER  -->
<div class="ticker">
    <div class="ticker-inner">
        <span>&bull; Handmade with love</span>
        <span>&bull; Elegant bijoux</span>
        <span>&bull; Limited pieces</span>
        <span>&bull; Discover your sparkle</span>
        <span>&bull; Glowear exclusive</span>
        <span>&bull; Small details, big elegance</span>
        <span>&bull; Wear your glow</span>
        <span>&bull; Crafted to shine</span>
        <span>&bull; Handmade with love</span>
        <span>&bull; Elegant bijoux</span>
        <span>&bull; Limited pieces</span>
        <span>&bull; Discover your sparkle</span>
        <span>&bull; Glowear exclusive</span>
        <span>&bull; Small details, big elegance</span>
        <span>&bull; Wear your glow</span>
        <span>&bull; Crafted to shine</span>
    </div>
</div>

<!--  NAV  -->
<nav class="nav-wrap" id="nav">
    <div class="nav-logo"><a href="index.php"><img src="../images/logo.png" alt="Glowear"></a></div>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li class="drop">
            <a href="#" class="active">Collections</a>
            <ul class="drop-menu">
                <li><a href="shop.php?nom=Necklaces">Necklaces</a></li>
                <li><a href="shop.php?nom=Earrings">Earrings</a></li>
                <li><a href="shop.php?nom=Bracelets">Bracelets</a></li>
                <li><a href="shop.php?nom=Rings">Rings</a></li>
                <li><a href="shop.php?nom=Sets">Sets</a></li>
            </ul>
        </li>
        <li class="drop">
            <a href="#">Shop</a>
            <ul class="drop-menu">
                <li><a href="cart.php">Cart</a></li>
                <li><a href="checkout.php">Checkout</a></li>
                <li><a href="../account/my-account.php">My Account</a></li>
            </ul>
        </li>
        <li><a href="service.php">Services</a></li>
        <li><a href="contact-us.php">Contact</a></li>
    </ul>
    <div class="nav-icons">
        <a href="#" id="searchBtn"><i class="fa fa-search"></i></a>
        <a href="#" onclick="toggleCart();return false">
            <i class="fa fa-shopping-bag"></i>
            <span class="cart-count"><?php echo $listpannier->rowCount() ?></span>
        </a>
        <?php if (isset($_SESSION['id'])): ?>
        <a href="../account/my-account.php"><i class="fa fa-user"></i></a>
        <?php else: ?>
        <a href="../auth/client_login.php"><i class="fa fa-user"></i></a>
        <?php endif; ?>
    </div>
</nav>

<!--  PAGE HERO  -->
<section class="page-hero">
    <div class="page-hero-bg"></div>
    <div class="page-hero-lines"></div>
    <div class="adot" style="width:3px;height:3px;left:18%;bottom:20%;animation-delay:0s;animation-duration:7s"></div>
    <div class="adot" style="width:2px;height:2px;left:50%;bottom:38%;animation-delay:1.5s;animation-duration:9s"></div>
    <div class="adot" style="width:4px;height:4px;left:82%;bottom:24%;animation-delay:2.8s;animation-duration:6s"></div>
    <div class="page-hero-content">
        <span class="page-hero-tag">Glowear Collection</span>
        <?php if($showProductDetail): ?>
            <h1 class="page-hero-h1"><?php echo htmlspecialchars($product['nom']) ?><br><em>Detail</em></h1>
        <?php else: ?>
            <h1 class="page-hero-h1"><?php echo ($nom !== '') ? htmlspecialchars($nom) : 'All' ?><br><em>Collection</em></h1>
        <?php endif; ?>
    </div>
    <div class="hero-breadcrumb">
        <a href="index.php">Home</a>
        <span class="sep">&bull;</span>
        <a href="shop.php">Shop</a>
        <?php if($showProductDetail): ?>
        <span class="sep">&bull;</span>
        <span><?php echo htmlspecialchars($product['nom']) ?></span>
        <?php elseif($nom !== ''): ?>
        <span class="sep">&bull;</span>
        <span><?php echo htmlspecialchars($nom) ?></span>
        <?php endif; ?>
    </div>
</section>


<!-- 
     SHOP BODY
 -->
<div class="shop-wrap">

    <!--  SIDEBAR (always visible)  -->
    <aside class="sidebar">
        <form action="shop.php<?php echo ($nom !== '') ? '?nom=' . urlencode($nom) : ''; ?>" method="POST">

        <!-- Budget -->
        <div class="sidebar-block rev">
            <span class="sidebar-title">Budget</span>
            <div id="slider-range"></div>
            <input type="text" id="amount" name="prix" readonly value="<?php echo 'DT ' . (int)$priceMin . ' - DT ' . (int)$priceMax; ?>" placeholder="DT 0 - DT 500">
            <input type="hidden" name="nom" value="<?php echo htmlspecialchars($nom) ?>">
            <button class="filter-btn" type="submit">Apply Filter</button>
        </div>

        <!-- Collection -->
        <div class="sidebar-block rev d1">
            <span class="sidebar-title">Collection</span>
            <div class="radio-row">
                <input name="collection_id" id="CollectionAll" value="all" type="radio" <?php echo ($collectionFilter === '' || $collectionFilter === 'all') ? 'checked' : ''; ?>>
                <label for="CollectionAll">All Collections</label>
            </div>
            <?php foreach ($collections as $collection): ?>
            <div class="radio-row">
                <input name="collection_id" id="Collection<?php echo $collection['id'] ?>" value="<?php echo $collection['id'] ?>" type="radio" <?php echo $collectionFilter == $collection['id'] ? 'checked' : ''; ?>>
                <label for="Collection<?php echo $collection['id'] ?>"><?php echo htmlspecialchars($collection['nom']) ?></label>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Metal & Finish -->
        <div class="sidebar-block rev d2">
            <span class="sidebar-title">Metal &amp; Finish</span>
            <?php
            $metals = ['Gold','Silver','Rose Gold','Pearl','Diamond'];
            foreach($metals as $m):
            ?>
            <div class="radio-row">
                <input name="survey" id="metal-<?php echo $m ?>" value="<?php echo $m ?>" type="radio">
                <label for="metal-<?php echo $m ?>"><?php echo $m ?></label>
            </div>
            <?php endforeach; ?>
        </div>

        </form>
    </aside>


    <?php if ($showProductDetail): ?>
    <!--  SINGLE PRODUCT DETAIL VIEW  -->
    <div class="detail-wrap">
        <?php
        $stockTotal = $stockController->getTotalStockByRef($product['ref']);
        $inStock    = $stockTotal > 0;
        $detailImage = function_exists('view_safe_image_path') ? view_safe_image_path($product['image'] ?? '') : ($product['image'] ?? '../images/placeholder.png');
        ?>
        <div class="detail-card rev">

            <!-- Image -->
            <div class="detail-media">
                <span class="detail-badge <?php echo !$inStock ? 'oos' : '' ?>">
                    <?php echo $inStock ? 'New Arrival' : 'Out of Stock' ?>
                </span>
                <img src="<?php echo htmlspecialchars($detailImage) ?>" alt="<?php echo htmlspecialchars($product['nom']) ?>">
            </div>

            <!-- Body -->
            <div class="detail-body">
                <span class="detail-chip">Luxury piece &middot; <?php echo htmlspecialchars($product['categorie'] ?? '') ?></span>
                <h2 class="detail-name"><?php echo htmlspecialchars($product['nom']) ?></h2>
                <p class="detail-meta"><?php echo htmlspecialchars($product['couleur']) ?></p>
                <p class="detail-desc"><?php echo htmlspecialchars($product['description']) ?></p>
                <p class="detail-stock <?php echo $inStock ? 'in' : '' ?>">
                    <?php echo $inStock ? '&bull; In stock: ' . $stockTotal : '&bull; Out of stock' ?>
                </p>
                <p class="detail-price">DT <?php echo htmlspecialchars($product['prix']) ?></p>

                <!-- Add to Cart -->
                <div class="detail-form">
                    <form action="../actions/addToCart.php" method="POST">
                        <div class="form-row">
                            <div class="form-field">
                                <label>Size / Length</label>
                                <select name="taille">
                                    <option value="Adjustable">Adjustable</option>
                                    <option value="16 cm">16 cm</option>
                                    <option value="18 cm">18 cm</option>
                                    <option value="20 cm">20 cm</option>
                                    <option value="Ring size 52">Ring size 52</option>
                                    <option value="Ring size 54">Ring size 54</option>
                                    <option value="Ring size 56">Ring size 56</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label>Quantity</label>
                                <input type="number" name="quantity" value="1" min="1" max="20">
                            </div>
                        </div>
                        <?php
                        echo "<input type='hidden' name='ref'    value='" . htmlspecialchars($product['ref'])   . "'>";
                        echo "<input type='hidden' name='prix'   value='" . htmlspecialchars($product['prix'])  . "'>";
                        echo "<input type='hidden' name='nom'    value='" . htmlspecialchars($product['nom'])   . "'>";
                            echo "<input type='hidden' name='image'  value='" . htmlspecialchars($detailImage) . "'>";
                        echo "<input type='hidden' name='qte'    value='1'>";
                        echo "<input type='hidden' name='action' value='add'>";

                        if (isset($_SESSION['id'])) {
                            if ($inStock) {
                                echo "<button type='submit' class='btn-red' style='width:100%'>Add to Cart &rarr;</button>";
                            } else {
                                echo "<button type='button' class='btn-disabled' style='width:100%' disabled>Out of Stock</button>";
                            }
                        } else {
                            echo "<a href='../auth/client_login.php' class='btn-ghost' style='display:block;text-align:center;width:100%'>Login to Purchase</a>";
                        }
                        ?>
                    </form>
                </div>

                <a href="shop.php<?php echo $nom ? '?nom='.urlencode($nom) : '' ?>" class="detail-back">
                    <i class="fas fa-arrow-left"></i> Back to Collection
                </a>
            </div>

        </div>
    </div><!-- /detail-wrap -->


    <?php else: ?>
    <!--  PRODUCT GRID (collection / filter view)  -->
    <div class="products-area">
        <div class="products-grid">

        <?php
        if (is_array($listproduitdetail)) {
            // Empty array â€” no results
        ?>
            <div class="empty-state" style="grid-column:1/-1">
                <i class="fas fa-gem"></i>
                <h3>No pieces found</h3>
                <p>Try adjusting your filters or explore another collection.</p>
            </div>

        <?php
        } else {
            $hasProducts = false;
            while ($l = $listproduitdetail->fetch()):
                $hasProducts = true;
                $stockTotal  = $stockController->getTotalStockByRef($l[0]);
                $inStock     = $stockTotal > 0;
                $gridImage = function_exists('view_safe_image_path') ? view_safe_image_path($l[6] ?? '') : ($l[6] ?? '../images/placeholder.png');
        ?>

            <div class="product-card rev">

                <!--  Image  -->
                <div class="product-media">
                    <span class="product-badge <?php echo !$inStock ? 'oos' : '' ?>">
                        <?php echo $inStock ? htmlspecialchars($l[4]) : 'Out of Stock' ?>
                    </span>
                    <img src="<?php echo htmlspecialchars($gridImage) ?>" alt="<?php echo htmlspecialchars($l[1]) ?>">
                    <div class="product-overlay">
                        <a href="shop.php?ref=<?php echo urlencode($l[0]) ?>" class="overlay-icon" title="View detail">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>

                <!--  Info  -->
                <div class="product-info">
                    <span class="product-chip">Luxury piece</span>
                    <h4 class="product-name"><?php echo htmlspecialchars($l[1]) ?></h4>
                    <p class="product-meta"><?php echo htmlspecialchars($l[2]) ?> &middot; <?php echo htmlspecialchars($l[4]) ?></p>
                    <p class="product-meta"><?php echo htmlspecialchars($l[3]) ?></p>
                    <p class="product-stock <?php echo $inStock ? 'in' : '' ?>">
                        <?php echo $inStock ? '&bull; In stock: ' . $stockTotal : '&bull; Out of stock' ?>
                    </p>
                    <p class="product-price">DT <?php echo htmlspecialchars($l[5]) ?></p>
                </div>

                <!--  Add to Cart Form  -->
                <div class="product-form">
                    <form action="../actions/addToCart.php" method="POST">
                        <div class="form-row">
                            <div class="form-field">
                                <label>Size / Length</label>
                                <select name="taille">
                                    <option value="Adjustable">Adjustable</option>
                                    <option value="16 cm">16 cm</option>
                                    <option value="18 cm">18 cm</option>
                                    <option value="20 cm">20 cm</option>
                                    <option value="Ring size 52">Ring size 52</option>
                                    <option value="Ring size 54">Ring size 54</option>
                                    <option value="Ring size 56">Ring size 56</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label>Quantity</label>
                                <input type="number" name="quantity" value="1" min="1" max="20">
                            </div>
                        </div>
                        <?php
                        echo "<input type='hidden' name='ref'    value='" . htmlspecialchars($l[0]) . "'>";
                        echo "<input type='hidden' name='prix'   value='" . htmlspecialchars($l[5]) . "'>";
                        echo "<input type='hidden' name='nom'    value='" . htmlspecialchars($l[1]) . "'>";
                        echo "<input type='hidden' name='image'  value='" . htmlspecialchars($gridImage) . "'>";
                        echo "<input type='hidden' name='qte'    value='1'>";
                        echo "<input type='hidden' name='action' value='add'>";

                        if (isset($_SESSION['id'])) {
                            if ($inStock) {
                                echo "<button type='submit' class='btn-red' style='width:100%'>Add to Cart &rarr;</button>";
                            } else {
                                echo "<button type='button' class='btn-disabled' style='width:100%' disabled>Out of Stock</button>";
                            }
                        } else {
                            echo "<a href='../auth/client_login.php' class='btn-ghost' style='display:block;text-align:center;width:100%'>Login to Purchase</a>";
                        }
                        ?>
                    </form>
                </div>

            </div><!-- /product-card -->

        <?php
            endwhile;

            if (!$hasProducts):
        ?>
            <div class="empty-state" style="grid-column:1/-1">
                <i class="fas fa-gem"></i>
                <h3>No pieces found</h3>
                <p>Try adjusting your filters or explore another collection.</p>
            </div>
        <?php
            endif;
        }
        ?>

        </div><!-- /products-grid -->
    </div><!-- /products-area -->

    <?php endif; ?>

</div><!-- /shop-wrap -->


<!--  MARQUEE  -->
<div class="marquee-band">
    <div class="marquee-inner">
        <?php for($m=0;$m<2;$m++): ?>
        <div class="marquee-item"><span class="rdot"></span>Necklaces</div>
        <div class="marquee-item"><span class="rdot"></span>Earrings</div>
        <div class="marquee-item"><span class="rdot"></span>Bracelets</div>
        <div class="marquee-item"><span class="rdot"></span>Rings</div>
        <div class="marquee-item"><span class="rdot"></span>Charm Necklaces</div>
        <div class="marquee-item"><span class="rdot"></span>Pearl Sets</div>
        <div class="marquee-item"><span class="rdot"></span>Gold Series</div>
        <div class="marquee-item"><span class="rdot"></span>Glowear Originals</div>
        <?php endfor; ?>
    </div>
</div>

<!--  INSTAGRAM  -->
<section class="insta-sec">
    <div class="insta-head rev">
        <p class="insta-handle"><i class="fab fa-instagram"></i> @glowear</p>
        <h2 class="sec-title">As Seen <em>On</em></h2>
    </div>
    <div class="insta-grid">
        <?php
        $instaImgs = ['pink3','baby2','strass3','pink2','sun3'];
        $delays    = ['','d1','d2','d3','d4'];
        foreach($instaImgs as $i => $img): ?>
        <div class="insta-item rev <?php echo $delays[$i] ?>">
            <img src="../images/<?php echo $img ?>.png" alt="">
            <div class="insta-ov"><i class="fab fa-instagram"></i></div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!--  FOOTER  -->
<footer>
    <div class="ft-grid">
        <div>
            <div class="ft-logo"><img src="../images/logo.png" alt="Glowear"></div>
            <p class="ft-desc">Handcrafted bijoux that celebrate your unique glow. Each piece is made with intention, love, and the finest materials.</p>
            <div class="ft-social">
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-tiktok"></i></a>
                <a href="#"><i class="fab fa-pinterest-p"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
        <div>
            <h4 class="ft-col-title">Collections</h4>
            <ul class="ft-links">
                <li><a href="shop.php?nom=Necklaces">Necklaces</a></li>
                <li><a href="shop.php?nom=Earrings">Earrings</a></li>
                <li><a href="shop.php?nom=Bracelets">Bracelets</a></li>
                <li><a href="shop.php?nom=Rings">Rings</a></li>
                <li><a href="shop.php?nom=Sets">Sets</a></li>
            </ul>
        </div>
        <div>
            <h4 class="ft-col-title">Information</h4>
            <ul class="ft-links">
                <li><a href="about.php">About Us</a></li>
                <li><a href="#">Care Guide</a></li>
                <li><a href="#">Returns Policy</a></li>
                <li><a href="#">Shipping Info</a></li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
        </div>
        <div>
            <h4 class="ft-col-title">Contact</h4>
            <div class="ft-contact">
                <span><i class="fas fa-map-marker-alt"></i>Nabeul Mrezgua, El Wafa, Tunisia</span>
                <span><i class="fas fa-phone"></i><a href="tel:+21656725104" style="color:var(--grey);text-decoration:none">+216 56 725 104</a></span>
                <span><i class="fas fa-envelope"></i><a href="mailto:glowear@gmail.com" style="color:var(--grey);text-decoration:none">glowear@gmail.com</a></span>
            </div>
        </div>
    </div>
    <div class="ft-bottom">
        <p class="ft-copy">&copy; 2026 Glowear &mdash; All rights reserved</p>
        <div class="ft-bottom-links"><a href="#">Terms</a><a href="#">Privacy</a><a href="#">Cookies</a></div>
    </div>
</footer>

<a href="#" id="btt"><i class="fas fa-chevron-up"></i></a>

<!--  JS  -->
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
    <script src="../js/response-formatter.js"></script>
    <script src="../js/chatbot-widget.js?v=20260517.1"></script>

<script>
/*  GLITTER  */
function createGlitter() {
    const layer = document.getElementById('glitter-layer');
    if (!layer) return;
    const g = document.createElement('span');
    g.className = 'glitter';
    g.style.left = Math.random() * 100 + 'vw';
    g.style.top  = Math.random() * 100 + 'vh';
    g.style.animationDuration = (1.5 + Math.random() * 2.5) + 's';
    layer.appendChild(g);
    setTimeout(() => g.remove(), 4000);
}
setInterval(createGlitter, 120);

/*  CURSOR  */
const cur  = document.getElementById('cur');
const curR = document.getElementById('cur-ring');
let mx = window.innerWidth/2, my = window.innerHeight/2, rx = mx, ry = my;
document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
(function animCursor(){
    cur.style.left  = mx + 'px'; cur.style.top  = my + 'px';
    rx += (mx - rx) * 0.14; ry += (my - ry) * 0.14;
    curR.style.left = rx + 'px'; curR.style.top = ry + 'px';
    requestAnimationFrame(animCursor);
})();
document.querySelectorAll('a,button,select,input,.product-card,.detail-card,.sidebar-block').forEach(el => {
    el.addEventListener('mouseenter', () => document.body.classList.add('big-cur'));
    el.addEventListener('mouseleave', () => document.body.classList.remove('big-cur'));
});

/*  STICKY NAV + BACK TO TOP  */
window.addEventListener('scroll', () => {
    document.getElementById('nav').classList.toggle('stuck', window.scrollY > 60);
    document.getElementById('btt').style.display = window.scrollY > 400 ? 'block' : 'none';
});

/*  SCROLL REVEAL  */
const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('in'); obs.unobserve(e.target); } });
}, { threshold:0.1, rootMargin:'0px 0px -40px 0px' });
document.querySelectorAll('.rev').forEach(r => obs.observe(r));

/*  CART  */
function toggleCart() {
    document.getElementById('sideCart').classList.toggle('open');
    document.getElementById('cartOv').classList.toggle('open');
}

/*  SEARCH  */
document.getElementById('searchBtn').addEventListener('click', e => {
    e.preventDefault();
    const ov = document.getElementById('searchOv');
    ov.style.display = 'flex';
    ov.querySelector('input').focus();
});
document.getElementById('searchClose').addEventListener('click', () => {
    document.getElementById('searchOv').style.display = 'none';
});
document.addEventListener('keydown', e => {
    if(e.key === 'Escape') document.getElementById('searchOv').style.display = 'none';
});

/*  FILTER SUBMIT SYNC  */
(function() {
    var form = document.querySelector('.sidebar form');
    if (!form) return;

    form.addEventListener('submit', function () {
        var selected = form.querySelector('input[name="collection_id"]:checked');
        var nomInput = form.querySelector('input[name="nom"]');

        if (selected && selected.value === 'all') {
            if (nomInput) {
                nomInput.value = 'all';
            }
            form.action = 'shop.php?nom=all';
        } else {
            if (nomInput) {
                nomInput.value = '';
            }
            form.action = 'shop.php';
        }
    });
}());

/*  PRICE RANGE SLIDER  */
$(function() {
    var initialMin = <?php echo (int)$priceMin; ?>;
    var initialMax = <?php echo (int)$priceMax; ?>;
    if (initialMin < 0) initialMin = 0;
    if (initialMax > 500) initialMax = 500;
    if (initialMin > initialMax) {
        var temp = initialMin;
        initialMin = initialMax;
        initialMax = temp;
    }

    $("#slider-range").slider({
        range: true,
        min: 0,
        max: 500,
        values: [initialMin, initialMax],
        slide: function(event, ui) {
            $("#amount").val("DT " + ui.values[0] + " - DT " + ui.values[1]);
        }
    });
    $("#amount").val("DT " + $("#slider-range").slider("values", 0) +
        " - DT " + $("#slider-range").slider("values", 1));
});
</script>
</body>
</html>




