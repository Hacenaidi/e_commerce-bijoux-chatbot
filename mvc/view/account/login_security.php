<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: ../auth/client_login.php');
    exit;
}

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Glowear &mdash; Login &amp; Security</title>
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="../images/apple-touch-icon.png">

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/chatbot-widget.css?v=20260517.2">
    <link rel="stylesheet" href="../css/chatbot-messages.css">
    <link rel="stylesheet" href="../css/response-format.css">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/my-account.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">
    <style>
        .login-security-page .account-wrap {
            max-width: 1080px;
            padding: 88px 56px 120px;
        }
        .login-security-page .security-section {
            background: linear-gradient(180deg, rgba(15, 16, 22, 0.92), rgba(10, 10, 14, 0.92));
            border: 1px solid rgba(192,57,43,.18);
            border-radius: 14px;
            padding: 34px;
            box-shadow: 0 24px 70px rgba(0,0,0,.45);
        }
        .login-security-page .orders-head {
            margin-bottom: 26px;
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        .login-security-page .orders-head h2 {
            font-size: 44px;
            line-height: 1;
            margin: 0;
        }
        .login-security-page .orders-head p {
            letter-spacing: 1px;
            text-transform: none;
            font-size: 13px;
            color: rgba(240,234,224,.72);
        }
        .login-security-page .account-grid {
            grid-template-columns: 1.15fr .85fr;
            gap: 18px;
        }
        .login-security-page .account-card.security-panel {
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,.07);
            background: linear-gradient(165deg, rgba(18,20,28,.95), rgba(10,10,14,.95));
            padding: 30px 28px;
            min-height: 360px;
            cursor: default;
        }
        .login-security-page .account-card.security-panel h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 38px;
            font-weight: 300;
            color: #fff;
            margin: 0 0 18px;
            line-height: 1;
        }
        .login-security-page #change-password-form .form-row {
            margin-bottom: 12px;
        }
        .login-security-page #change-password-form label {
            display: block;
            margin-bottom: 7px;
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255,255,255,.7);
        }
        .login-security-page #change-password-form input {
            width: 100%;
            height: 42px;
            border: 1px solid rgba(255,255,255,.12);
            border-bottom: 1px solid rgba(192,57,43,.45);
            background: rgba(255,255,255,.02);
            color: #fff;
            padding: 0 12px;
            outline: none;
            transition: border-color .2s, background .2s;
        }
        .login-security-page #change-password-form input:focus {
            border-color: rgba(192,57,43,.48);
            background: rgba(192,57,43,.08);
        }
        .login-security-page #change-password-form .btn-red {
            width: 100%;
            margin-top: 4px;
        }
        .login-security-page .security-tips {
            margin: 0;
            padding-left: 18px;
            color: rgba(255,255,255,.85);
            line-height: 1.8;
            font-size: 14px;
        }
        .login-security-page .security-tips li {
            margin-bottom: 8px;
        }
        @media (max-width: 992px) {
            .login-security-page .account-wrap {
                padding: 56px 24px 90px;
            }
            .login-security-page .account-grid {
                grid-template-columns: 1fr;
            }
            .login-security-page .orders-head h2 {
                font-size: 36px;
            }
        }
    </style>
</head>
<body class="login-security-page">

<div class="grain"></div>
<div id="glitter-layer"></div>
<div id="cur"></div>
<div id="cur-ring"></div>

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
    <?php $sc_total += $l[5]; endwhile; ?>
    <div class="sc-total">
        <span class="sc-total-label">Total</span>
        <span class="sc-total-val">DT <?php echo $sc_total ?></span>
    </div>
    <a href="../public/cart.php" class="btn-red" style="display:block;text-align:center;margin-bottom:12px">View Cart</a>
    <a href="../public/checkout.php" class="btn-ghost" style="display:block;text-align:center">Checkout</a>
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
    <h1>Login <em>&amp;</em> Security</h1>
    <div class="breadcrumb-row">
        <a href="../public/index.php">Home</a>
        <span>/</span>
        <a href="../public/shop.php">Shop</a>
        <span>/</span>
        <span class="active">Login &amp; Security</span>
    </div>
</div>

<div class="account-wrap">
    <?php if (isset($_SESSION['id'])): ?>

    <section class="rev d2 security-section">
        <div class="orders-head">
            <h2>Login &amp; Security</h2>
            <p>Change your password and manage account security settings.</p>
        </div>

        <div class="account-grid">
            <section class="account-card security-panel">
                <h3>Change Password</h3>
                <form id="change-password-form">
                    <div class="form-row">
                        <label for="current_password">Current password</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-row">
                        <label for="new_password">New password</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-row">
                        <label for="confirm_password">Confirm new password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <div class="form-row">
                        <button type="submit" class="btn-red">Update Password</button>
                    </div>
                    <div id="cp-msg" style="margin-top:10px;color:#f88"></div>
                </form>
            </section>

            <section class="account-card security-panel">
                <h3>Security Tips</h3>
                <ul class="security-tips">
                    <li>Use a strong, unique password.</li>
                    <li>Don't reuse passwords across sites.</li>
                    <li>Consider enabling 2FA if available.</li>
                </ul>
            </section>
        </div>
    </section>

    <?php else: ?>

    <div class="orders-empty">
        <h3>Please log in to manage your account</h3>
        <a href="../auth/client_login.php" class="btn-red">Log In</a>
    </div>

    <?php endif; ?>

</div>

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
<script src="../js/chatbot.js?v=20260517.1"></script>

<script>
// cursor & UI interactions (same as orders.php)
const cur  = document.getElementById('cur');
const curR = document.getElementById('cur-ring');
let mx = window.innerWidth / 2, my = window.innerHeight / 2;
let rx = mx, ry = my;
document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
(function animCursor() {
    if (!cur || !curR) return;
    cur.style.left = mx + 'px'; cur.style.top = my + 'px';
    rx += (mx - rx) * 0.14; ry += (my - ry) * 0.14;
    curR.style.left = rx + 'px'; curR.style.top = ry + 'px';
    requestAnimationFrame(animCursor);
})();
document.querySelectorAll('a, button, .account-card, .insta-item').forEach(el => {
    el.addEventListener('mouseenter', () => document.body.classList.add('big-cur'));
    el.addEventListener('mouseleave', () => document.body.classList.remove('big-cur'));
});

// STICKY NAV
window.addEventListener('scroll', () => {
    const nav = document.getElementById('nav'); if(nav) nav.classList.toggle('stuck', window.scrollY > 60);
    const btt = document.getElementById('btt'); if(btt) btt.style.display = window.scrollY > 400 ? 'block' : 'none';
});

// SCROLL REVEAL
const revealNodes = document.querySelectorAll('.rev, .account-card, .welcome-strip, .guest-banner');
if ('IntersectionObserver' in window) {
    const revealObs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('in');
                revealObs.unobserve(e.target);
            }
        });
    }, { threshold: 0.10, rootMargin: '0px 0px -40px 0px' });
    revealNodes.forEach(node => revealObs.observe(node));
} else {
    revealNodes.forEach(node => node.classList.add('in'));
}

// SEARCH
const searchBtn = document.getElementById('searchBtn');
if(searchBtn){ searchBtn.addEventListener('click', e => { e.preventDefault(); const ov = document.getElementById('searchOv'); if(ov) ov.style.display='block'; }); }
const searchClose = document.getElementById('searchClose');
if(searchClose){ searchClose.addEventListener('click', e => { const ov = document.getElementById('searchOv'); if(ov) ov.style.display='none'; }); }

// CART
function toggleCart() {
    const cart = document.getElementById('sideCart'); const ov = document.getElementById('cartOv');
    if(cart) cart.classList.toggle('open'); if(ov) ov.classList.toggle('open');
}

// Change password AJAX
document.addEventListener('DOMContentLoaded', function(){
    const form = document.getElementById('change-password-form');
    if(!form) return;
    form.addEventListener('submit', function(e){
        e.preventDefault();
        const current = document.getElementById('current_password').value;
        const nw = document.getElementById('new_password').value;
        const confirm = document.getElementById('confirm_password').value;
        const msg = document.getElementById('cp-msg');
        msg.textContent = '';
        if(nw !== confirm){ msg.style.color='#f88'; msg.textContent = 'Passwords do not match'; return; }

        const body = new URLSearchParams();
        body.append('current_password', current);
        body.append('new_password', nw);
        body.append('confirm_password', confirm);

        fetch('../actions/ajax_change_password.php', {method:'POST', body: body})
        .then(r => r.json())
        .then(data => {
            if(data.error){ msg.style.color = '#f88'; msg.textContent = data.error; return; }
            msg.style.color = '#8f8'; msg.textContent = data.message || 'Password updated';
        })
        .catch(err => { msg.style.color = '#f88'; msg.textContent = 'Server error'; });
    });
});
</script>

</body>
</html>

