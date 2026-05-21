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
$id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$listpannier = $controllerpannier->listpannier($id);
$controller = new ProduitController();
$total = 0;
// Pre-calculate total for the side cart badge
$lp_badge = $controllerpannier->listpannier($id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Glowear &mdash; Checkout</title>
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- Chatbot CSS -->
    <link rel="stylesheet" href="../css/chatbot-widget.css?v=20260517.2">
    <link rel="stylesheet" href="../css/chatbot-messages.css">
    <link rel="stylesheet" href="../css/response-format.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">

    
    <link rel="stylesheet" href="../css/base.css">

    
    <link rel="stylesheet" href="../css/checkout.css">
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

<!-- Search Overlay -->
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
            <a href="deletepropannier.php?id=<?php echo urlencode($l[0]); ?>&redirect=<?php echo urlencode('checkout.php'); ?>" style="display:inline-block;margin-top:6px;color:rgba(255,255,255,.55);font-size:10px;letter-spacing:2px;text-transform:uppercase;text-decoration:none;">Supprimer</a>
        </div>
    </div>
    <?php $sc_total += $l[5]; endwhile; ?>
    <div class="sc-total">
        <span class="sc-total-label">Total</span>
        <span class="sc-total-val">DT <?php echo $sc_total ?></span>
        
    </div>
    <a href="cart.php" class="btn-red" style="display:block;text-align:center;margin-bottom:12px">View Cart</a>
    <a href="checkout.php" class="btn-ghost" style="display:block;text-align:center">Checkout</a>
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
    <div class="nav-logo"><a href="index.php"><img src="../images/logo.png" alt="Glowear"></a></div>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li class="drop">
            <a href="#">Collections</a>
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

<!-- Page Hero Band -->
<div class="page-hero">
    <span class="page-hero-tag">Secure Checkout</span>
    <h1>Complete Your <em>Order</em></h1>
    <div class="breadcrumb-row">
        <a href="index.php">Home</a>
        <span>/</span>
        <a href="shop.php">Shop</a>
        <span>/</span>
        <span class="active">Checkout</span>
    </div>
</div>

<!--  MAIN CONTENT  -->
<div class="checkout-wrap">

    <?php
    /*  Re-fetch list for order display (the first fetch was for the badge) */
    $listpannier2 = $controllerpannier->listpannier($id);
    $total = 0;
    $cart_items = [];
    while ($l = $listpannier2->fetch()) {
        $pro = $controller->produit($l[2])->fetch();
        $cart_items[] = ['l' => $l, 'pro' => $pro];
        $total += $pro[5] * $l[3];
    }
    
    ?>

    <?php if (!isset($_SESSION['id'])): ?>
    <!--  AUTH PANELS (not logged in) -->
    <div class="auth-row">
        <div class="auth-panel" id="authPanel1">
            <span class="auth-panel-num">01</span>
            <span class="auth-label">Returning Customer</span>
            <h3 class="auth-title">Account Login</h3>
            <button class="auth-toggle" onclick="toggleAuthForm('formLogin', this)">Sign in to your account</button>
            <div class="auth-form" id="formLogin">
                <form action="../auth/client_login.php" method="post">
                    <div class="field-row">
                        <div class="gl-field">
                            <label for="InputEmail">Email Address</label>
                            <input name="email" type="email" id="InputEmail" placeholder="your@email.com">
                        </div>
                        <div class="gl-field">
                            <label for="InputPassword">Password</label>
                            <input name="password" type="password" id="InputPassword" placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢">
                        </div>
                    </div>
                    <button type="submit" class="btn-red">Login</button>
                </form>
            </div>
        </div>

        <div class="auth-panel d1" id="authPanel2">
            <span class="auth-panel-num">02</span>
            <span class="auth-label">New Customer</span>
            <h3 class="auth-title">Create Account</h3>
            <button class="auth-toggle" onclick="toggleAuthForm('formRegister', this)">Register for free</button>
            <div class="auth-form" id="formRegister">
                <form action="client_signup.php" method="post">
                    <div class="field-row">
                        <div class="gl-field">
                            <label for="InputName">First Name</label>
                            <input name="nom" type="text" id="InputName" placeholder="First name">
                        </div>
                        <div class="gl-field">
                            <label for="InputLastname">Last Name</label>
                            <input name="prenom" type="text" id="InputLastname" placeholder="Last name">
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="gl-field">
                            <label for="InputEmail1">Email Address</label>
                            <input name="email" type="email" id="InputEmail1" placeholder="your@email.com">
                        </div>
                        <div class="gl-field">
                            <label for="InputPassword1">Password</label>
                            <input name="password" type="password" id="InputPassword1" placeholder="••••••••">
                        </div>
                    </div>
                    <button type="submit" class="btn-red">Create Account</button>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['id']) && $total > 0): ?>
    <!--  CHECKOUT FORM (logged in + has items) -->
    <form action="../actions/placeOrder.php" method="post">
        <input type="hidden" name="total" value="<?php echo $total ?>">

        <div class="checkout-grid">

            <!-- LEFT: Billing -->
            <div class="billing-panel" id="billingPanel">
                <span class="panel-tag">Step 01 &mdash; Details</span>
                <h2 class="panel-title">Billing <em>Address</em></h2>

                <div class="field-row">
                    <div class="gl-field">
                        <label for="firstName">First Name *</label>
                        <input name="first_name" type="text" id="firstName" placeholder="First name" required>
                    </div>
                    <div class="gl-field">
                        <label for="lastName">Last Name *</label>
                        <input name="last_name" type="text" id="lastName" placeholder="Last name" required>
                    </div>
                </div>

                <div class="gl-field">
                    <label for="email">Email Address *</label>
                    <input name="email" type="email" id="email" placeholder="your@email.com">
                </div>

                <div class="gl-field">
                    <label for="address">Delivery Address *</label>
                    <input name="adress" type="text" id="address" placeholder="Street address, building..." required>
                </div>

                <div class="gl-field">
                    <label for="telephone">Telephone *</label>
                    <input name="telephone" type="tel" id="telephone" placeholder="+216 XX XXX XXX">
                </div>

                <div class="field-row-3">
                    <div class="gl-field">
                        <label for="Mandate">Mandate *</label>
                        <input name="mandate" type="text" id="Mandate" placeholder="Mandate" required>
                    </div>
                    <div class="gl-field">
                        <label for="Accreditation">Accreditation *</label>
                        <input name="accrediation" type="text" id="Accreditation" placeholder="Accreditation" required>
                    </div>
                    <div class="gl-field">
                        <label for="zip">Zip Code *</label>
                        <input name="zip" type="text" id="zip" placeholder="0000" required>
                    </div>
                </div>

                <div class="panel-bg-num">01</div>
            </div>

            <!-- RIGHT: Order Summary -->
            <div class="order-panel" id="orderPanel">
                <span class="panel-tag">Step 02 &mdash; Review</span>
                <h2 class="panel-title">Your <em>Order</em></h2>

                <!-- Cart Items -->
                <?php foreach ($cart_items as $ci): ?>
                <div class="order-item">
                    <img src="<?php echo $ci['pro'][6] ?>" alt="<?php echo $ci['pro'][5] ?>">
                    <div style="flex:1">
                        <div class="order-item-name"><?php echo $ci['pro'][4] ?></div>
                        <div class="order-item-qty"><?php echo htmlspecialchars($ci['l'][3]); ?> x piece<?php echo $ci['l'][3] > 1 ? 's' : '' ?></div>
                    </div>
                    <div class="order-item-price">DT <?php echo $ci['pro'][5] ?></div>
                </div>
                <?php endforeach; ?>

                <hr class="order-divider">

                <div class="order-row">
                    <span class="order-row-label">Subtotal</span>
                    <span class="order-row-val">DT <?php echo $total ?></span>
                </div>
                <div class="order-row">
                    <span class="order-row-label">Shipping</span>
                    <span class="order-row-val" style="color:rgba(192,57,43,.7)">Free</span>
                </div>

                <div class="order-total-row">
                    <span class="order-total-label">Grand Total</span>
                    <span class="order-total-val">DT <?php echo $total ?></span>
                </div>

                <button type="submit" class="place-btn">
                    <i class="fas fa-gem" style="font-size:13px"></i>
                    Place Order
                </button>

                <div class="secure-row">
                    <span class="secure-item"><i class="fas fa-lock"></i> Secure</span>
                    <span class="secure-item"><i class="fas fa-shield-alt"></i> Protected</span>
                    <span class="secure-item"><i class="fas fa-redo"></i> Easy Returns</span>
                </div>
            </div>

        </div>
    </form>

    <?php else: ?>
    <!--  EMPTY / NOT LOGGED IN WITH ITEMS -->
    <div class="empty-state rev">
        <span class="empty-icon"><i class="fas fa-gem"></i></span>
        <h2 class="empty-title">Your cart is empty</h2>
        <p class="empty-text">Add some beautiful pieces to your cart<br>before proceeding to checkout.</p>
        <a href="shop.php" class="btn-red">Discover Collection</a>
        &nbsp;&nbsp;
        <a href="cart.php" class="btn-ghost">View Cart</a>
    </div>
    <?php endif; ?>

</div>
<!-- end checkout-wrap -->

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


<!-- JS -->
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<?php if (file_exists(__DIR__ . '/../js/response-formatter.js')): ?>
<script src="../js/response-formatter.js"></script>
<?php endif; ?>
<?php if (file_exists(__DIR__ . '/../js/chatbot-widget.js')): ?>
<script src="../js/chatbot-widget.js?v=20260517.1"></script>
<?php endif; ?>


<script>
//  SMOOTH CURSOR
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
document.querySelectorAll('a, button, input, .order-item, .auth-panel').forEach(el => {
    el.addEventListener('mouseenter', () => document.body.classList.add('big-cur'));
    el.addEventListener('mouseleave', () => document.body.classList.remove('big-cur'));
});

//  STICKY NAV
window.addEventListener('scroll', () => {
    document.getElementById('nav').classList.toggle('stuck', window.scrollY > 60);
    const btt = document.getElementById('btt');
    btt.style.display = window.scrollY > 400 ? 'block' : 'none';
});

//  SCROLL REVEAL
const revEls = document.querySelectorAll('.rev, .auth-panel, .billing-panel, .order-panel');
const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('in'); obs.unobserve(e.target); }
    });
}, { threshold: 0.10, rootMargin: '0px 0px -40px 0px' });
revEls.forEach(r => obs.observe(r));

//  SEARCH
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

//  CART
function toggleCart() {
    document.getElementById('sideCart').classList.toggle('open');
    document.getElementById('cartOv').classList.toggle('open');
}

//  AUTH FORM TOGGLE
function toggleAuthForm(id, btn) {
    const form = document.getElementById(id);
    const isOpen = form.classList.contains('open');
    // close all
    document.querySelectorAll('.auth-form').forEach(f => f.classList.remove('open'));
    document.querySelectorAll('.auth-toggle').forEach(b => b.style.opacity = '1');
    if (!isOpen) {
        form.classList.add('open');
        btn.style.opacity = '.5';
    }
}

//  BACK TO TOP
document.getElementById('btt').addEventListener('click', e => {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
</script>
</body>
</html>




