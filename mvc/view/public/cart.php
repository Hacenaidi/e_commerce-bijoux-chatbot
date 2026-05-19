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
$id = isset($_SESSION['id'])?$_SESSION['id']:null;
$listpannier = $controllerpannier->listpannier($id);
$controller = new ProduitController();
$listpanniercart = $controllerpannier->listpannier($id);

function cart_product_image_path($image)
{
    if (function_exists('view_safe_image_path')) {
        return view_safe_image_path($image);
    }

    $image = trim((string) $image);
    if ($image === '') {
        return '../images/placeholder.png';
    }
    if (preg_match('#^(https?:)?//#i', $image) || strpos($image, 'data:image/') === 0) {
        return $image;
    }
    $image = str_replace('\\', '/', $image);
    $fileName = basename($image);
    return '../images/' . $fileName;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Glowear &mdash; Your Cart</title>

    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="../images/apple-touch-icon.png">

    <link rel="stylesheet" href="../css/chatbot-widget.css?v=20260517.2">
    <link rel="stylesheet" href="../css/chatbot-messages.css">
    <link rel="stylesheet" href="../css/response-format.css">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">

    
</head>
<body>

<div class="grain"></div>
<div id="glitter-layer"></div>
<div id="cur"></div>
<div id="cur-ring"></div>

<!-- â”€â”€ SIDE CART PANEL â”€â”€ -->
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
        $productName = isset($pro[4]) ? $pro[4] : '';
        $productPrice = isset($pro[5]) ? $pro[5] : '';
        $productImage = cart_product_image_path(isset($pro[6]) ? $pro[6] : '');
    ?>
    <div class="sc-item">
        <img src="<?php echo htmlspecialchars($productImage); ?>" alt="<?php echo htmlspecialchars($productName); ?>">
        <div>
            <div class="sc-item-name"><?php echo htmlspecialchars($productName); ?></div>
            <div class="sc-item-detail"><?php echo htmlspecialchars($l[5]); ?> &times; &mdash; <span style="color:var(--red2)">DT <?php echo htmlspecialchars($productPrice); ?></span></div>
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

<!-- â”€â”€ SEARCH OVERLAY â”€â”€ -->
<div id="searchOv" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.96);z-index:3000;flex-direction:column;align-items:center;justify-content:center;">
    <i class="fa fa-times" id="searchClose" style="position:absolute;top:36px;right:56px;font-size:22px;color:rgba(255,255,255,.4);cursor:pointer;"></i>
    <input type="text" placeholder="Search bijoux..." style="background:none;border:none;border-bottom:1px solid rgba(255,255,255,.25);font-family:'Cormorant Garamond',serif;font-size:34px;color:#fff;width:60%;max-width:580px;padding:14px 0;text-align:center;outline:none;">
    <p style="color:rgba(255,255,255,.2);font-size:9px;letter-spacing:4px;text-transform:uppercase;margin-top:20px;">Press Enter</p>
</div>

<!-- â”€â”€ TICKER â”€â”€ -->
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

<!-- â”€â”€ NAV â”€â”€ -->
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
            <a href="#" class="active">Shop</a>
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
        <?php if(isset($_SESSION['id'])): ?>
        <a href="../account/my-account.php"><i class="fa fa-user"></i></a>
        <?php else: ?>
        <a href="../auth/client_login.php"><i class="fa fa-user"></i></a>
        <?php endif; ?>
    </div>
</nav>

<!-- â”€â”€ PAGE HERO â”€â”€ -->
<section class="page-hero">
    <div class="page-hero-bg"></div>
    <div class="page-hero-lines"></div>
    <div class="adot" style="width:3px;height:3px;left:14%;bottom:22%;animation-delay:0s;animation-duration:7s"></div>
    <div class="adot" style="width:2px;height:2px;left:52%;bottom:40%;animation-delay:1.6s;animation-duration:9s"></div>
    <div class="adot" style="width:4px;height:4px;left:84%;bottom:25%;animation-delay:3s;animation-duration:6s"></div>
    <div class="page-hero-content">
        <span class="page-hero-tag">Your Selection</span>
        <h1 class="page-hero-h1">Shopping<br><em>Cart</em></h1>
    </div>
    <div class="hero-breadcrumb">
        <a href="index.php">Home</a>
        <span class="sep">&bull;</span>
        <a href="shop.php?nom=Necklaces">Shop</a>
        <span class="sep">&bull;</span>
        <span>Cart</span>
    </div>
</section>

<!-- â”€â”€ CART SECTION â”€â”€ -->
<div class="cart-section">
    <div class="cart-layout">

        <!-- LEFT â€” ITEMS TABLE -->
        <div class="rev">

            <?php
            /* â”€â”€ Collect rows & total (original logic) â”€â”€ */
            $total = 0;
            $rows   = [];
            while($l = $listpanniercart->fetch()){
                $pro    = $controller->produit($l[2])->fetch();
                $total += $l[5];
                $rows[] = ['l' => $l, 'pro' => $pro];
            }
            ?>

            <?php if(empty($rows)): ?>
            <!-- Empty State -->
            <div class="cart-empty">
                <i class="fas fa-gem"></i>
                <h3>Your cart is empty</h3>
                <p>Explore our collections and add your favourite pieces.</p>
                <a href="shop.php?nom=Necklaces" class="btn-red">Explore Collection &rarr;</a>
            </div>

            <?php else: ?>
            <!-- Cart Table -->
            <div class="cart-table-wrap">
                <!-- Head -->
                <div class="cart-thead">
                    <span>Image</span>
                    <span>Product</span>
                    <span>Price</span>
                    <span>Qty</span>
                    <span>Total</span>
                    <span>Del</span>
                    <span>Edit</span>
                </div>

                <!-- Rows (original PHP data: $l[0]=id, $l[2]=ref, $l[3]=qty, $l[5]=total | $pro[5]=name, $pro[6]=price, $pro[7]=img) -->
                <?php foreach($rows as $item):
                    $l   = $item['l'];
                    $pro = $item['pro'];
                    $productName = isset($pro[4]) ? $pro[4] : '';
                    $productPrice = isset($pro[5]) ? $pro[5] : '';
                    $productImage = cart_product_image_path(isset($pro[6]) ? $pro[6] : '');
                ?>
                <div class="cart-row">
                    <!-- Image -->
                    <a href="#">
                            <img class="cr-img" src="<?php echo htmlspecialchars($productImage); ?>" alt="<?php echo htmlspecialchars($productName); ?>">
                    </a>

                    <!-- Name -->
                        <a href="#" class="cr-name"><?php echo htmlspecialchars($productName) ?></a>

                    <!-- Unit price -->
                        <span class="cr-price">DT <?php echo htmlspecialchars($productPrice) ?></span>

                    <!-- Qty (original input) -->
                    <input type="number" class="cr-qty" value="<?php echo $l[3] ?>" min="0" step="1" size="4">

                    <!-- Row total -->
                    <span class="cr-total">DT <?php echo $l[5] ?></span>

                    <!-- Remove (original href) -->
                    <a class="cr-remove" href="../actions/deletepropannier.php?id=<?php echo $l[0] ?>" title="Remove">
                        <i class="fas fa-times"></i>
                    </a>

                    <!-- Modify (original href) -->
                    <div class="cr-modify">
                        <a href="../actions/updatepannier.php?id=<?php echo $l[0] ?>" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- RIGHT â€” ORDER SUMMARY (original session check preserved) -->
        <?php if(isset($_SESSION['id'])): ?>
        <div class="order-summary rev d1">
            <h2 class="os-title">Order Summary</h2>

            <div class="os-row">
                <span class="os-label">Sub Total</span>
                <span class="os-value">DT <?php echo $total ?></span>
            </div>
            <div class="os-row">
                <span class="os-label">Shipping</span>
                <span class="os-value" style="color:rgba(39,174,96,.7)">Free</span>
            </div>

            <div class="os-divider"></div>

            <div class="os-grand-row">
                <span class="os-grand-label">Grand Total</span>
                <span class="os-grand-value">DT <?php echo $total ?></span>
            </div>

            <a href="checkout.php" class="btn-red os-checkout-btn">Proceed to Checkout &rarr;</a>
            <a href="shop.php?nom=Necklaces" class="btn-ghost os-shop-btn">Continue Shopping</a>

            <p class="os-note">Secure checkout &middot; Free returns</p>
        </div>
        <?php endif; ?>

    </div>
</div>
<!-- End Cart -->

<!-- â”€â”€ MARQUEE â”€â”€ -->
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

<!-- â”€â”€ INSTAGRAM â”€â”€ -->
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
            <img src="images/<?php echo $img ?>.png" alt="">
            <div class="insta-ov"><i class="fab fa-instagram"></i></div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- â”€â”€ FOOTER â”€â”€ -->
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

<a href="#" id="btt" title="Back to top">&uarr;</a>

<!-- JS -->
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
    <script src="../js/response-formatter.js"></script>
    <script src="../js/chatbot-widget.js?v=20260517.1"></script>

<script>
/* â”€â”€ GLITTER â”€â”€ */
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

/* â”€â”€ CURSOR â”€â”€ */
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
document.querySelectorAll('a,button,input,.cart-row,.insta-item').forEach(el => {
    el.addEventListener('mouseenter', () => document.body.classList.add('big-cur'));
    el.addEventListener('mouseleave', () => document.body.classList.remove('big-cur'));
});

/* â”€â”€ STICKY NAV + BACK TO TOP â”€â”€ */
window.addEventListener('scroll', () => {
    document.getElementById('nav').classList.toggle('stuck', window.scrollY > 60);
    document.getElementById('btt').style.display = window.scrollY > 400 ? 'block' : 'none';
});

/* â”€â”€ SCROLL REVEAL â”€â”€ */
const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('in'); obs.unobserve(e.target); } });
}, { threshold:0.08, rootMargin:'0px 0px -40px 0px' });
document.querySelectorAll('.rev').forEach(r => obs.observe(r));

/* â”€â”€ CART PANEL â”€â”€ */
function toggleCart() {
    document.getElementById('sideCart').classList.toggle('open');
    document.getElementById('cartOv').classList.toggle('open');
}

/* â”€â”€ SEARCH â”€â”€ */
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
</script>
</body>
</html>




