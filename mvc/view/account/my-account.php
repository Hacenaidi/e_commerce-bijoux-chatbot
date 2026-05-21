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
$orderCtrl = __DIR__ . '/../../controller/OrderController.php';
if (file_exists($orderCtrl)) {
    include_once($orderCtrl);
}
 $viewHelpers = __DIR__ . '/../inc/view_helpers.php';
if (file_exists($viewHelpers)) {
    include_once($viewHelpers);
}
$controllerpannier = new PannierController();
$id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$listpannier = $controllerpannier->listpannier($id);
$controller = new ProduitController();
$ordersData = array();
if ($id) {
    $orderController = new OrderController();
    $ordersData = $orderController->listOrdersByClient($id);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Glowear &mdash; My Account</title>
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="../images/apple-touch-icon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- Chatbot CSS -->
    <link rel="stylesheet" href="../css/chatbot-widget.css?v=20260517.2">
    <link rel="stylesheet" href="../css/chatbot-messages.css">
    <link rel="stylesheet" href="../css/response-format.css">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/my-account.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">

    

    <script>
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
    setInterval(createGlitter, 150);
    </script>
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
    $sc_total = 0;
    $lp_side  = $controllerpannier->listpannier($id);
    while ($l = $lp_side->fetch()):
        $pro = $controller->produit($l[2])->fetch();
        $cartImage = function_exists('view_safe_image_path') ? view_safe_image_path($pro[6] ?? '') : ($pro[6] ?? 'images/placeholder.png');
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
    <?php $sc_total += $l[5]; endwhile; ?>
    <div class="sc-total">
        <span class="sc-total-label">Total</span>
        <span class="sc-total-val">DT <?php echo $sc_total ?></span>
    </div>
    <a href="../public/cart.php" class="btn-red" style="display:block;text-align:center;margin-bottom:12px">View Cart</a>
    <a href="../public/checkout.php" class="btn-ghost" style="display:block;text-align:center">Checkout</a>
</div>

<!-- Ticker -->
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
            <a href="#">Shop</a>
            <ul class="drop-menu">
                <li><a href="../public/cart.php">Cart</a></li>
                <li><a href="../public/checkout.php">Checkout</a></li>
                <li><a href="my-account.php">My Account</a></li>
            </ul>
        </li>
        <li><a href="../public/service.php">Services</a></li>
        <li><a href="../public/contact-us.php">Contact</a></li>
    </ul>
    <div class="nav-icons">
        <a href="#" id="searchBtn"><i class="fa fa-search"></i></a>
        <a href="#" onclick="toggleCart();return false">
            <i class="fa fa-shopping-bag"></i>
            <span class="cart-count"><?php echo $listpannier->rowCount() ?></span>
        </a>
        <?php if (isset($_SESSION['id'])): ?>
        <a href="my-account.php" style="color:var(--red)"><i class="fa fa-user"></i></a>
        <?php else: ?>
        <a href="../auth/client_login.php"><i class="fa fa-user"></i></a>
        <?php endif; ?>
    </div>
</nav>

<!-- Page Hero -->
<div class="page-hero">
    <span class="page-hero-tag">Your Space</span>
    <h1>My <em>Account</em></h1>
    <div class="breadcrumb-row">
        <a href="../public/index.php">Home</a>
        <span>/</span>
        <a href="../public/shop.php">Shop</a>
        <span>/</span>
        <span class="active">My Account</span>
    </div>
</div>

<div class="account-wrap">

    <?php if (isset($_SESSION['id'])): ?>

    <!-- Welcome strip -->
    <div class="welcome-strip" id="welcomeStrip">
        <div>
            <div class="welcome-text">Welcome back, <em>Glowear</em></div>
            <div class="welcome-sub">Manage your orders, security &amp; preferences</div>
        </div>
        <div class="welcome-avatar"><i class="fas fa-user"></i></div>
    </div>

    <!-- Account Cards Grid -->
    <div class="account-grid">

        <!-- Orders -->
        <a href="orders.php" class="account-card" id="card1">
            <span class="card-bg-num">01</span>
            <div class="card-icon-wrap">
                <i class="fas fa-gem"></i>
            </div>
            <span class="card-label">Track &amp; View</span>
            <h3 class="card-title">Your Orders</h3>
            <p class="card-desc">Browse your order history, track deliveries, and review past purchases at a glance.</p>
            <span class="card-arrow">View Orders</span>
        </a>

        <!-- Security -->
        <a href="login_security.php" class="account-card d1" id="card2">
            <span class="card-bg-num">02</span>
            <div class="card-icon-wrap">
                <i class="fas fa-lock"></i>
            </div>
            <span class="card-label">Privacy &amp; Access</span>
            <h3 class="card-title">Login &amp; Security</h3>
            <p class="card-desc">Edit your name, email address and password. Keep your account safe and up to date.</p>
            <span class="card-arrow">Manage Security</span>
        </a>


        <!-- Logout -->
        <a href="../actions/logout.php" class="account-card logout-card d2" id="card3">
            <span class="card-bg-num">03</span>
            <div class="card-icon-wrap">
                <i class="fas fa-sign-out-alt"></i>
            </div>
            <span class="card-label">Session</span>
            <h3 class="card-title">Log Out</h3>
            <p class="card-desc">End your current session and sign out of your Glowear account securely.</p>
            <span class="card-arrow">Sign Out</span>
        </a>

    </div>

    <!-- Orders moved to orders.php -->

    <?php else: ?>

    <!-- Guest State -->
    <div class="guest-banner" id="guestBanner">
        <span class="guest-icon"><i class="fas fa-user-circle"></i></span>
        <div class="guest-bg-num">?</div>
        <h2 class="guest-title">Welcome to <em>Glowear</em></h2>
        <p class="guest-text">Sign in to access your orders, manage your account,<br>and enjoy a personalised shopping experience.</p>
        <a href="../auth/client_login.php" class="btn-red">Sign In</a>
        &nbsp;&nbsp;
        <a href="../auth/client_signup.php" class="btn-ghost">Create Account</a>
    </div>

    <?php endif; ?>

</div>
<!-- end account-wrap -->

<!-- Instagram Feed -->
<section class="insta-sec">
    <div class="insta-head rev">
        <p class="insta-handle"><i class="fab fa-instagram"></i> @glowear</p>
        <h2 class="sec-title">As Seen <em>On</em></h2>
    </div>
    <div class="insta-grid">
        <?php
        $instaImgs = ['sun4','baby4','strass','pink2','baby6'];
        $delays    = ['','d1','d2','d3','d4'];
        foreach($instaImgs as $i => $img): ?>
        <div class="insta-item rev <?php echo $delays[$i] ?>">
            <img src="../images/<?php echo $img ?>.png" alt="">
            <div class="insta-ov"><i class="fab fa-instagram"></i></div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Footer -->
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
                <li><a href="../public/shop.php?nom=Necklaces">Necklaces</a></li>
                <li><a href="../public/shop.php?nom=Earrings">Earrings</a></li>
                <li><a href="../public/shop.php?nom=Bracelets">Bracelets</a></li>
                <li><a href="../public/shop.php?nom=Rings">Rings</a></li>
                <li><a href="../public/shop.php?nom=Sets">Sets</a></li>
            </ul>
        </div>
        <div>
            <h4 class="ft-col-title">Information</h4>
            <ul class="ft-links">
                <li><a href="../public/about.php">About Us</a></li>
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

<a href="#" id="btt" title="Back to top"><i class="fas fa-chevron-up"></i></a>

<!-- JS -->
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/response-formatter.js"></script>
<script src="js/chatbot.js?v=20260517.1"></script>

<script>
const cur  = document.getElementById('cur');
const curR = document.getElementById('cur-ring');
let mx = window.innerWidth / 2, my = window.innerHeight / 2;
let rx = mx, ry = my;
document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
(function animCursor() {
    cur.style.left = mx + 'px'; cur.style.top = my + 'px';
    rx += (mx - rx) * 0.14; ry += (my - ry) * 0.14;
    curR.style.left = rx + 'px'; curR.style.top = ry + 'px';
    requestAnimationFrame(animCursor);
})();
document.querySelectorAll('a, button, .account-card, .insta-item').forEach(el => {
    el.addEventListener('mouseenter', () => document.body.classList.add('big-cur'));
    el.addEventListener('mouseleave', () => document.body.classList.remove('big-cur'));
});

// â”€â”€ STICKY NAV
window.addEventListener('scroll', () => {
    document.getElementById('nav').classList.toggle('stuck', window.scrollY > 60);
    document.getElementById('btt').style.display = window.scrollY > 400 ? 'block' : 'none';
});

const allRev = document.querySelectorAll(
    '.rev, .account-card, .welcome-strip, .guest-banner'
);
const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('in'); obs.unobserve(e.target); }
    });
}, { threshold: 0.10, rootMargin: '0px 0px -40px 0px' });
allRev.forEach(r => obs.observe(r));


document.getElementById('searchBtn').addEventListener('click', e => {
    e.preventDefault();
    document.getElementById('searchOv').classList.add('open');
    document.querySelector('.search-ov input').focus();
});
document.getElementById('searchClose').addEventListener('click', () => {
    document.getElementById('searchOv').classList.remove('open');
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.getElementById('searchOv').classList.remove('open');
});


function toggleCart() {
    document.getElementById('sideCart').classList.toggle('open');
    document.getElementById('cartOv').classList.toggle('open');
}

document.getElementById('btt').addEventListener('click', e => {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
</script>
</body>
</html>




