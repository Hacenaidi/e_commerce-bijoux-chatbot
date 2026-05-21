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
 $viewHelpers = __DIR__ . '/../inc/view_helpers.php';
if (file_exists($viewHelpers)) {
    include_once($viewHelpers);
}

$controllerpannier = new PannierController();
$controller = new ProduitController();
$id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$listpannier = $controllerpannier->listpannier($id);

$idpa = isset($_GET["id"]) ? $_GET["id"] : '';
if ($idpa === '') {
    header("Location: ../public/cart.php");
    exit;
}

$pannier = $controllerpannier->getpannier($idpa)->fetch(PDO::FETCH_ASSOC);
if (!$pannier) {
    header("Location: ../public/cart.php");
    exit;
}

$pro = $controller->produit($pannier['ref'] ?? $pannier[2])->fetch(PDO::FETCH_ASSOC);
if (!$pro) {
    header("Location: ../public/cart.php");
    exit;
}

$productName = $pro['nom'] ?? '';
$productPrice = $pro['prix'] ?? 0;
$productImageRaw = $pro['image'] ?? '';
$productImage = function_exists('view_safe_image_path') ? view_safe_image_path($productImageRaw) : $productImageRaw;
$quantity = isset($pannier['quant']) ? (int)$pannier['quant'] : (isset($pannier[3]) ? (int)$pannier[3] : 1);
$total = isset($pannier['total_prod']) ? $pannier['total_prod'] : (isset($pannier[5]) ? $pannier[5] : ($productPrice * $quantity));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Glowear &mdash; Update Cart</title>

    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">

    <style>
        .update-wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 32px 20px 80px;
        }
        .update-card {
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 22px;
            background: rgba(10, 10, 10, .82);
            box-shadow: 0 24px 70px rgba(0,0,0,.36);
            backdrop-filter: blur(12px);
            padding: 24px;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 26px;
        }
        .update-media {
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 18px;
            overflow: hidden;
            background: rgba(255,255,255,.02);
        }
        .update-media img {
            width: 100%;
            height: 100%;
            min-height: 260px;
            object-fit: cover;
            display: block;
        }
        .update-form h2 {
            margin: 0 0 14px;
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 300;
            color: #fff;
            line-height: 1;
        }
        .update-meta {
            color: rgba(240,234,224,.8);
            margin-bottom: 18px;
        }
        .update-grid {
            display: grid;
            gap: 14px;
            margin-top: 16px;
        }
        .update-row {
            display: grid;
            grid-template-columns: 130px 1fr;
            gap: 14px;
            align-items: center;
            color: rgba(240,234,224,.92);
        }
        .update-row .k {
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .14em;
            color: rgba(240,234,224,.55);
        }
        .qty-input {
            width: 100%;
            max-width: 260px;
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 12px;
            background: rgba(255,255,255,.04);
            color: #fff;
            padding: 12px 14px;
            outline: none;
        }
        .qty-input:focus {
            border-color: rgba(239,68,68,.7);
            box-shadow: 0 0 0 3px rgba(239,68,68,.14);
        }
        .update-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn-ghost-lite {
            border: 1px solid rgba(192,57,43,.45);
            color: #ff8a7a;
            font-size: 10px;
            letter-spacing: 3px;
            text-transform: uppercase;
            padding: 15px 26px;
            text-decoration: none;
            font-weight: 300;
            display: inline-block;
            transition: all .3s;
            background: transparent;
        }
        .btn-ghost-lite:hover {
            border-color: var(--red);
            background: var(--red-dim);
            color: #fff;
            text-decoration: none;
        }
        @media (max-width: 900px) {
            .update-card {
                grid-template-columns: 1fr;
            }
            .update-media img {
                min-height: 220px;
            }
        }
    </style>
</head>
<body>

<div class="grain"></div>
<div id="glitter-layer"></div>
<div id="cur"></div>
<div id="cur-ring"></div>

<div class="search-ov" id="searchOv">
    <i class="fa fa-times search-ov-close" id="searchClose"></i>
    <input type="text" placeholder="Search bijoux...">
    <p style="color:rgba(255,255,255,.2);font-size:9px;letter-spacing:4px;text-transform:uppercase;margin-top:20px;">Press Enter</p>
</div>

<!-- Side Cart -->
<div class="cart-overlay" id="cartOv" onclick="toggleCart()"></div>
<div class="side-cart" id="sideCart">
    <div class="sc-head">
        <span class="sc-title">Your Cart</span>
        <button class="sc-close" onclick="toggleCart()"><i class="fa fa-times"></i></button>
    </div>
    <?php
    $scTotal = 0;
    $lp2 = $controllerpannier->listpannier($id);
    while ($l = $lp2->fetch()):
        $p = $controller->produit($l[2])->fetch();
        $scName = $p[4] ?? '';
        $scPrice = $p[5] ?? '';
        $scImage = function_exists('view_safe_image_path') ? view_safe_image_path($p[6] ?? '') : ($p[6] ?? 'images/placeholder.png');
    ?>
    <div class="sc-item">
        <img src="<?php echo htmlspecialchars($scImage); ?>" alt="<?php echo htmlspecialchars($scName); ?>">
        <div>
            <div class="sc-item-name"><?php echo htmlspecialchars($scName); ?></div>
            <div class="sc-item-detail"><?php echo htmlspecialchars($l[3]); ?> x â€” <span style="color:var(--red2)">DT <?php echo htmlspecialchars($scPrice); ?></span></div>
        </div>
    </div>
    <?php $scTotal += $l[5]; endwhile; ?>
    <div class="sc-total">
        <span class="sc-total-label">Total</span>
        <span class="sc-total-val">DT <?php echo htmlspecialchars($scTotal); ?></span>
    </div>
    <a href="../public/cart.php" class="btn-red" style="display:block;text-align:center;margin-bottom:12px">View Cart</a>
    <a href="../public/checkout.php" class="btn-ghost" style="display:block;text-align:center">Checkout</a>
</div>

<!-- Ticker -->
<div class="ticker">
    <div class="ticker-inner">
        <span>&mdash;Handmade with love&mdash;</span><span>&mdash;Elegant bijoux&mdash;</span>
        <span>&mdash;Limited pieces&mdash;</span><span>&mdash;Discover your sparkle&mdash;</span>
        <span>&mdash;Glowear exclusive&mdash;</span><span>&mdash;Small details, big elegance&mdash;</span>
    </div>
</div>

<!-- Nav -->
<nav class="nav-wrap" id="nav">
    <div class="nav-logo"><a href="../public/index.php"><img src="../images/logo.png" alt="Glowear"></a></div>
    <ul class="nav-links">
        <li><a href="../public/index.php">Home</a></li>
        <li><a href="../public/about.php">About</a></li>
        <li class="drop">
            <a href="#">Collections</a>
            <ul class="drop-menu">
                <li><a href="../public/shop.php?nom=Necklaces">Necklaces</a></li>
                <li><a href="../public/shop.php?nom=Earrings">Earrings</a></li>
                <li><a href="../public/shop.php?nom=Bracelets">Bracelets</a></li>
                <li><a href="../public/shop.php?nom=Rings">Rings</a></li>
                <li><a href="../public/shop.php?nom=Sets">Sets</a></li>
            </ul>
        </li>
        <li class="drop">
            <a href="#" class="active">Shop</a>
            <ul class="drop-menu">
                <li><a href="../public/cart.php">Cart</a></li>
                <li><a href="../public/checkout.php">Checkout</a></li>
                <li><a href="../account/my-account.php">My Account</a></li>
            </ul>
        </li>
        <li><a href="../public/service.php">Services</a></li>
        <li><a href="../public/contact-us.php">Contact</a></li>
    </ul>
    <div class="nav-icons">
        <a href="#" id="searchBtn"><i class="fa fa-search"></i></a>
        <a href="#" onclick="toggleCart();return false">
            <i class="fa fa-shopping-bag"></i>
            <span class="cart-count"><?php echo $listpannier->rowCount(); ?></span>
        </a>
        <?php if (isset($_SESSION['id'])): ?>
        <a href="../account/my-account.php"><i class="fa fa-user"></i></a>
        <?php else: ?>
        <a href="../auth/client_login.php"><i class="fa fa-user"></i></a>
        <?php endif; ?>
    </div>
</nav>

<section class="page-hero" style="min-height:260px;">
    <div class="page-hero-bg"></div>
    <div class="page-hero-lines"></div>
    <div class="page-hero-content">
        <span class="page-hero-tag">Cart update</span>
        <h1 class="page-hero-h1">Update<br><em>Quantity</em></h1>
    </div>
    <div class="hero-breadcrumb">
        <a href="../public/index.php">Home</a>
        <span class="sep">&mdash;</span>
        <a href="../public/cart.php">Cart</a>
        <span class="sep">&mdash;</span>
        <span>Update</span>
    </div>
</section>

<main class="update-wrap">
    <div class="update-card">
        <div class="update-media">
            <img src="<?php echo htmlspecialchars($productImage); ?>" alt="<?php echo htmlspecialchars($productName); ?>">
        </div>

        <div class="update-form">
            <h2><?php echo htmlspecialchars($productName); ?></h2>
            <p class="update-meta">Adjust the quantity then update your cart.</p>

            <form action="update_action_pannier.php" method="post">
                <div class="update-grid">
                    <div class="update-row">
                        <span class="k">Unit Price</span>
                        <span>DT <?php echo htmlspecialchars($productPrice); ?></span>
                    </div>
                    <div class="update-row">
                        <span class="k">Current Total</span>
                        <span>DT <?php echo htmlspecialchars($total); ?></span>
                    </div>
                    <div class="update-row">
                        <label for="qte" class="k">Quantity</label>
                        <input id="qte" type="number" class="qty-input" name="qte" min="1" step="1" value="<?php echo htmlspecialchars($quantity); ?>" required>
                    </div>
                </div>

                <input type="hidden" name="id" value="<?php echo htmlspecialchars($idpa); ?>">

                <div class="update-actions">
                    <button type="submit" class="btn-red">Update</button>
                    <a href="../public/cart.php" class="btn-ghost-lite">Back To Cart</a>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script>
function createGlitter(){
    var layer = document.getElementById('glitter-layer');
    if(!layer) return;
    var g = document.createElement('span');
    g.className = 'glitter';
    g.style.left = Math.random() * 100 + 'vw';
    g.style.top = Math.random() * 100 + 'vh';
    g.style.animationDuration = (1.5 + Math.random() * 2.5) + 's';
    layer.appendChild(g);
    setTimeout(function(){ try { g.remove(); } catch (e) {} }, 4000);
}
setInterval(createGlitter, 150);

function toggleCart(){
    document.getElementById('cartOv').classList.toggle('open');
    document.getElementById('sideCart').classList.toggle('open');
}

(function(){
    var searchBtn = document.getElementById('searchBtn');
    var searchOv = document.getElementById('searchOv');
    var searchClose = document.getElementById('searchClose');
    if (searchBtn && searchOv) {
        searchBtn.addEventListener('click', function(e){
            e.preventDefault();
            searchOv.classList.add('open');
        });
    }
    if (searchClose && searchOv) {
        searchClose.addEventListener('click', function(){
            searchOv.classList.remove('open');
        });
    }
})();

const cur = document.getElementById('cur');
const curR = document.getElementById('cur-ring');
let mx = window.innerWidth / 2, my = window.innerHeight / 2, rx = mx, ry = my;
document.addEventListener('mousemove', function(e){ mx = e.clientX; my = e.clientY; });
(function animCursor(){
    rx += (mx - rx) * 0.17;
    ry += (my - ry) * 0.17;
    if (cur) {
        cur.style.left = mx + 'px';
        cur.style.top = my + 'px';
    }
    if (curR) {
        curR.style.left = rx + 'px';
        curR.style.top = ry + 'px';
    }
    requestAnimationFrame(animCursor);
})();
</script>
</body>
</html>

