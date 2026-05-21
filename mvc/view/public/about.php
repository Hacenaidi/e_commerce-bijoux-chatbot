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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Glowear &mdash; About Us</title>
        <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/chatbot-widget.css?v=20260517.2">
<link rel="stylesheet" href="../css/chatbot-messages.css">
<link rel="stylesheet" href="../css/response-format.css">
        <link rel="stylesheet" href="../css/base.css">
        <link rel="stylesheet" href="../css/about.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">

    

    <script>
    function createGlitter(){
        const layer=document.getElementById('glitter-layer');
        if(!layer)return;
        const g=document.createElement('span');
        g.className='glitter';
        g.style.left=Math.random()*100+'vw';
        g.style.top=Math.random()*100+'vh';
        g.style.animationDuration=(1.5+Math.random()*2.5)+'s';
        layer.appendChild(g);
        setTimeout(()=>g.remove(),4000);
    }
    setInterval(createGlitter,120);
    </script>
</head>
<body>

<div class="grain"></div>
<div id="glitter-layer"></div>
<div id="cur"></div>
<div id="cur-ring"></div>

<!-- Search -->
<div class="search-ov" id="searchOv">
    <i class="fa fa-times search-ov-close" id="searchClose"></i>
    <input type="text" placeholder="Search bijoux...">
    <p style="color:rgba(255,255,255,.2);font-size:9px;letter-spacing:4px;text-transform:uppercase;margin-top:20px;">Press Enter</p>
</div>

<!-- Cart -->
<div class="cart-overlay" id="cartOv" onclick="toggleCart()"></div>
<div class="side-cart" id="sideCart">
    <div class="sc-head">
        <span class="sc-title">Your Cart</span>
        <button class="sc-close" onclick="toggleCart()"><i class="fa fa-times"></i></button>
    </div>
    <?php
    $total=0; $lp2=$controllerpannier->listpannier($id);
    while($l=$lp2->fetch()):
        $pro=$controller->produit($l[2])->fetch();
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
    <?php $total+=$l[5]; endwhile; ?>
    <div class="sc-total">
        <span class="sc-total-label">Total</span>
        <span class="sc-total-val">DT <?php echo $total ?></span>
    </div>
    <a href="cart.php" class="btn-red" style="display:block;text-align:center;margin-bottom:12px">View Cart</a>
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
        <li><a href="about.php" class="active-link">About</a></li>
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
        <?php if(isset($_SESSION['id'])): ?>
        <a href="../account/my-account.php"><i class="fa fa-user"></i></a>
        <?php else: ?>
        <a href="../auth/client_login.php"><i class="fa fa-user"></i></a>
        <?php endif; ?>
    </div>
</nav>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="page-hero-bg"></div>
    <div class="page-hero-line"></div>
    <div class="page-hero-content">
        <div class="breadcrumb-bar" style="opacity:0;animation:up .7s .2s forwards">
            <a href="index.php">Home</a>
            <span>/</span>
            <span style="color:rgba(255,255,255,.5)">About Us</span>
        </div>
        <h1 class="page-hero-title">We Are<br><em>Glowear</em></h1>
    </div>
</section>

<!-- ABOUT INTRO -->
<section class="about-intro">
    <div class="intro-text">
        <span class="sec-label rev">Our Identity</span>
        <h2 class="sec-title rev d1">Passion for <em>Beauty</em></h2>
        <p class="intro-body rev d2">
            Welcome to Glowear!<br><br>
            At Glowear, we're passionate about providing a seamless and enjoyable shopping experience for our customers. Our platform is more than just a marketplace; it's a destination where quality meets convenience.<br><br>
            Driven by a commitment to excellence, we curate a diverse collection of bijoux that cater to your needs â€” from delicate pearl necklaces to bold gold charm pieces. Our dedication to offering top-notch customer service ensures that your journey with us is nothing short of exceptional.<br><br>
            We believe in the power of craft and intention to transform the way you wear jewelry. At Glowear, we're not just a store; we're your trusted companion in the world of fine bijoux.
        </p>
        <a href="shop.php" class="btn-red rev d3">Explore Collection</a>
    </div>
    <div class="intro-img-wrap rev d2">
        <div class="intro-img-main">
            <img src="../images/sun5.png" alt="About Glowear">
        </div>
        <div class="intro-img-frame"></div>
    </div>
</section>

<!-- STATS BAR -->
<div class="stats-bar">
    <div class="stat-item rev">
        <div class="stat-n">200+</div>
        <div class="stat-l">Unique Pieces</div>
    </div>
    <div class="stat-item rev d1">
        <div class="stat-n">3K+</div>
        <div class="stat-l">Happy Clients</div>
    </div>
    <div class="stat-item rev d2">
        <div class="stat-n">100%</div>
        <div class="stat-l">Handcrafted</div>
    </div>
    <div class="stat-item rev d3">
        <div class="stat-n">2</div>
        <div class="stat-l">Years of Glow</div>
    </div>
</div>

<!-- PILLARS -->
<section class="pillars-section">
    <div class="pillars-head rev">
        <span class="sec-label" style="text-align:center;display:block">Our Values</span>
        <h2 class="sec-title" style="text-align:center">What Makes Us <em>Different</em></h2>
    </div>
    <div class="pillars-grid">
        <div class="pillar rev">
            <div class="pillar-num">01</div>
            <span class="pillar-icon"><i class="fas fa-shield-alt"></i></span>
            <h3 class="pillar-title">We are Trusted</h3>
            <p class="pillar-text">At Glowear, trust forms the cornerstone of our relationship with our customers. Our commitment to transparency, reliability, and integrity is unwavering. We prioritize your peace of mind by ensuring that every transaction is secure and every product meets our stringent quality standards.</p>
        </div>
        <div class="pillar rev d1">
            <div class="pillar-num">02</div>
            <span class="pillar-icon"><i class="fas fa-award"></i></span>
            <h3 class="pillar-title">We are Professional</h3>
            <p class="pillar-text">Professionalism defines our approach at Glowear. From our seamless experience to our responsive customer service, we uphold the highest standards. Our dedicated team ensures that your experience with us is nothing short of exemplary at every touchpoint.</p>
        </div>
        <div class="pillar rev d2">
            <div class="pillar-num">03</div>
            <span class="pillar-icon"><i class="fas fa-gem"></i></span>
            <h3 class="pillar-title">We are Expert</h3>
            <p class="pillar-text">Expertise is the driving force behind Glowear. With experience in the industry, we have honed our skills to curate a collection that reflects the latest trends and superior quality. Our team meticulously selects pieces, ensuring each item embodies excellence.</p>
        </div>
    </div>
</section>

<!-- MARQUEE -->
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


<!-- INSTAGRAM -->
<section class="insta-sec">
    <div class="insta-head rev">
        <p class="insta-handle"><i class="fab fa-instagram"></i> @glowear</p>
        <h2 class="sec-title">As Seen <em>On</em></h2>
    </div>
    <div class="insta-grid">
        <div class="insta-item rev"><img src="../images/strass3.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d1"><img src="../images/baby3.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d2"><img src="../images/baby4.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d3"><img src="../images/sun4.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d4"><img src="../images/baby6.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
    </div>
</section>

<!-- FOOTER -->
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



<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<?php if(file_exists(__DIR__.'/../js/response-formatter.js')): ?>
<script src="../js/response-formatter.js"></script>
<?php endif; ?>
<?php if(file_exists(__DIR__.'/../js/chatbot-widget.js')): ?>
<script src="../js/chatbot-widget.js?v=20260517.1"></script>
<?php endif; ?>

<script>
const cur = document.getElementById('cur');
const curR = document.getElementById('cur-ring');
let mx=window.innerWidth/2, my=window.innerHeight/2, rx=mx, ry=my;
document.addEventListener('mousemove', e => { mx=e.clientX; my=e.clientY; });
(function animCursor(){
    cur.style.left=mx+'px'; cur.style.top=my+'px';
    rx+=(mx-rx)*.14; ry+=(my-ry)*.14;
    curR.style.left=rx+'px'; curR.style.top=ry+'px';
    requestAnimationFrame(animCursor);
})();
document.querySelectorAll('a,button,.team-card,.insta-item,.pillar').forEach(el=>{
    el.addEventListener('mouseenter',()=>document.body.classList.add('big-cur'));
    el.addEventListener('mouseleave',()=>document.body.classList.remove('big-cur'));
});

// NAV STICK + BACK TO TOP
window.addEventListener('scroll',()=>{
    document.getElementById('nav').classList.toggle('stuck', window.scrollY > 60);
    document.getElementById('btt').style.display = window.scrollY > 320 ? 'block' : 'none';
});

// SCROLL REVEAL
const revEls = document.querySelectorAll('.rev');
const obs = new IntersectionObserver(entries=>{
    entries.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('in'); obs.unobserve(e.target); } });
},{ threshold:.1, rootMargin:'0px 0px -50px 0px' });
revEls.forEach(r=>obs.observe(r));

// SEARCH
document.getElementById('searchBtn').addEventListener('click',e=>{
    e.preventDefault();
    document.getElementById('searchOv').classList.add('open');
    document.querySelector('.search-ov input').focus();
});
document.getElementById('searchClose').addEventListener('click',()=>document.getElementById('searchOv').classList.remove('open'));
document.addEventListener('keydown',e=>{ if(e.key==='Escape') document.getElementById('searchOv').classList.remove('open'); });

// CART
function toggleCart(){
    document.getElementById('sideCart').classList.toggle('open');
    document.getElementById('cartOv').classList.toggle('open');
}
</script>
</body>
</html>




