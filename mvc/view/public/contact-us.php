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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Glowear &mdash; Contact Us</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="../images/apple-touch-icon.png">

    <!-- Keep original CSS for chatbot / response format -->
    <link rel="stylesheet" href="../css/chatbot-widget.css?v=20260517.2">
    <link rel="stylesheet" href="../css/chatbot-messages.css">
    <link rel="stylesheet" href="../css/response-format.css">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/contact-us.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">

    
</head>
<body>

<!-- Grain + Glitter -->
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
    $total = 0;
    $lp2   = $controllerpannier->listpannier($id);
    while($l = $lp2->fetch()):
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
    <?php $total += $l[5]; endwhile; ?>
    <div class="sc-total">
        <span class="sc-total-label">Total</span>
        <span class="sc-total-val">DT <?php echo $total ?></span>
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
        <li><a href="contact-us.php" class="active">Contact</a></li>
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

<!-- Search overlay -->
<div class="search-ov" id="searchOv" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.96);z-index:3000;flex-direction:column;align-items:center;justify-content:center;">
    <i class="fa fa-times" id="searchClose" style="position:absolute;top:36px;right:56px;font-size:22px;color:rgba(255,255,255,.4);cursor:pointer;transition:color .3s;"></i>
    <input type="text" placeholder="Search bijoux..." style="background:none;border:none;border-bottom:1px solid rgba(255,255,255,.25);font-family:'Cormorant Garamond',serif;font-size:34px;color:#fff;width:60%;max-width:580px;padding:14px 0;text-align:center;outline:none;">
    <p style="color:rgba(255,255,255,.2);font-size:9px;letter-spacing:4px;text-transform:uppercase;margin-top:20px;">Press Enter</p>
</div>

<!--  PAGE HERO â”€ -->
<section class="page-hero">
    <div class="page-hero-bg"></div>
    <div class="page-hero-lines"></div>
    <!-- floating particles -->
    <div class="adot" style="width:3px;height:3px;left:15%;bottom:20%;animation-delay:0s;animation-duration:7s"></div>
    <div class="adot" style="width:2px;height:2px;left:55%;bottom:35%;animation-delay:1.4s;animation-duration:9s"></div>
    <div class="adot" style="width:4px;height:4px;left:80%;bottom:25%;animation-delay:2.8s;animation-duration:6s"></div>
    <div class="page-hero-content">
        <span class="page-hero-tag">We'd Love to Hear from You</span>
        <h1 class="page-hero-h1">Get in<br><em>Touch</em></h1>
    </div>
    <div class="hero-breadcrumb">
        <a href="index.php">Home</a>
        <span class="sep">âœ¦</span>
        <span>Contact Us</span>
    </div>
</section>


<!--  MAIN CONTACT AREA  -->
<section class="contact-section">

    <!-- LEFT â€” INFO COLUMN -->
    <div class="info-col rev">
        <span class="sec-label">Contact Info</span>
        <h2 class="sec-title">Let's <em>Connect</em></h2>
        <p class="info-desc">
            Welcome to Glowear! We're passionate about providing a seamless and enjoyable shopping experience. Whether it's regarding an order, a product inquiry, or simply a kind word â€” our team is just a message away.
        </p>

        <div class="contact-cards">
            <div class="c-card">
                <div class="c-card-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <p class="c-card-label">Find Us</p>
                    <p class="c-card-value">Nabeul Mrezgua</p>
                </div>
            </div>
            <div class="c-card">
                <div class="c-card-icon"><i class="fas fa-phone-alt"></i></div>
                <div>
                    <p class="c-card-label">Call Us</p>
                    <p class="c-card-value"><a href="tel:+21656725104">+216 56 725 104</a></p>
                </div>
            </div>
            <div class="c-card">
                <div class="c-card-icon"><i class="fas fa-envelope"></i></div>
                <div>
                    <p class="c-card-label">Email Us</p>
                    <p class="c-card-value"><a href="mailto:Glowear@gmail.com">Glowear@gmail.com</a></p>
                </div>
            </div>
        </div>

        <div class="info-deco-num">G</div>
    </div>

    <!-- RIGHT  FORM COLUMN -->
    <div class="form-col rev d1">
        <div class="form-header">
            <span class="sec-label">Send a Message</span>
            <h2 class="sec-title">Say <em>Hello</em></h2>
            <p>Have a question or a suggestion? Fill in the form below and we'll get back to you as soon as possible.</p>
        </div>

        <!-- !! Original form id / field names / structure preserved !! -->
        <form id="contactForm" class="contact-form" novalidate>
            <div class="field-wrap" id="wrap-name">
                <input type="text" id="name" name="name" required autocomplete="off">
                <label for="name">Your Name</label>
                <span class="field-line"></span>
            </div>
            <div class="field-wrap" id="wrap-email">
                <input type="text" id="email" name="email" required autocomplete="off">
                <label for="email">Your Email</label>
                <span class="field-line"></span>
            </div>
            <div class="field-wrap textarea-wrap" id="wrap-message">
                <textarea id="message" name="message" required></textarea>
                <label for="message">Your Message</label>
                <span class="field-line"></span>
            </div>
            <div class="form-submit-row">
                <span class="form-note">We reply within 24 hours</span>
                <button class="btn-red" id="submit" type="submit">
                    Send Message <span class="btn-arrow">&rarr;</span>
                </button>
            </div>
            <div id="msgSubmit" class="hidden"></div>
        </form>
    </div>

</section>

<!--  MARQUEE BAND  -->
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

<!--  AMBIENT STRIP -->
<div class="ambient-strip">
    <div class="adot" style="width:3px;height:3px;left:10%;bottom:20%;animation-delay:0s;animation-duration:8s"></div>
    <div class="adot" style="width:2px;height:2px;left:45%;bottom:40%;animation-delay:2s;animation-duration:6s"></div>
    <div class="adot" style="width:4px;height:4px;left:78%;bottom:30%;animation-delay:1s;animation-duration:9s"></div>
    <div class="ambient-strip-inner">
        <div class="ambient-addr">
            <i class="fas fa-map-marker-alt addr-icon"></i>
            <h3>Nabeul, Tunisia</h3>
            <p>Mrezgua Â· +216 56 725 104</p>
        </div>
    </div>
</div>

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


<!-- JS -->
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<!-- Chatbot scripts (original) -->

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
let mx = window.innerWidth/2, my = window.innerHeight/2;
let rx = mx, ry = my;
document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
(function animCursor(){
    cur.style.left  = mx + 'px'; cur.style.top  = my + 'px';
    rx += (mx - rx) * 0.14;
    ry += (my - ry) * 0.14;
    curR.style.left = rx + 'px'; curR.style.top = ry + 'px';
    requestAnimationFrame(animCursor);
})();
document.querySelectorAll('a,button,.c-card').forEach(el => {
    el.addEventListener('mouseenter', () => document.body.classList.add('big-cur'));
    el.addEventListener('mouseleave', () => document.body.classList.remove('big-cur'));
});

/*  STICKY NAV  */
window.addEventListener('scroll', () => {
    document.getElementById('nav').classList.toggle('stuck', window.scrollY > 60);
    document.getElementById('btt').style.display = window.scrollY > 400 ? 'block' : 'none';
});

/*  SCROLL REVEAL  */
const revEls = document.querySelectorAll('.rev');
const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('in'); obs.unobserve(e.target); } });
}, { threshold:0.12, rootMargin:'0px 0px -50px 0px' });
revEls.forEach(r => obs.observe(r));

/*  FLOATING LABEL (has-value state)  */
document.querySelectorAll('.field-wrap input, .field-wrap textarea').forEach(el => {
    const wrap = el.closest('.field-wrap');
    el.addEventListener('input', () => {
        wrap.classList.toggle('has-value', el.value.length > 0);
    });
});

/*  SIDE CART  */
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

/*  CONTACT FORM (original logic preserved)  */
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const name    = document.getElementById('name').value.trim();
    const email   = document.getElementById('email').value.trim();
    const message = document.getElementById('message').value.trim();
    const msgEl   = document.getElementById('msgSubmit');

    if(!name || !email || !message) {
        msgEl.className = 'error';
        msgEl.textContent = 'Please fill in all fields.';
        return;
    }

    // Original behaviour: show success (swap to your AJAX/PHP handler as needed)
    msgEl.className = 'success';
    msgEl.textContent = 'Your message has been sent successfully!';
    this.reset();
    document.querySelectorAll('.field-wrap').forEach(w => w.classList.remove('has-value'));
    setTimeout(() => { msgEl.className = 'hidden'; msgEl.textContent = ''; }, 5000);
});
</script>
</body>
</html>




