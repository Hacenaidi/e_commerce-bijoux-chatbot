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
    <title>Glowear &mdash; Our Services</title>
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/chatbot-widget.css?v=20260517.2">
    <link rel="stylesheet" href="../css/service.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">

    

    <script>
    function createGlitter(){
        const l=document.getElementById('glitter-layer');
        if(!l)return;
        const g=document.createElement('span');
        g.className='glitter';
        g.style.left=Math.random()*100+'vw';
        g.style.top=Math.random()*100+'vh';
        g.style.animationDuration=(1.5+Math.random()*2.5)+'s';
        l.appendChild(g);
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
        <li><a href="service.php" class="active-link">Services</a></li>
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

<!-- â•â• PAGE HERO â•â• -->
<section class="page-hero">
    <div class="page-hero-bg"></div>
    <!-- Decorative floating shapes -->
    <div class="hero-shape" style="width:300px;height:300px;top:-60px;right:15%;animation-delay:0s"></div>
    <div class="hero-shape" style="width:180px;height:180px;top:40%;right:28%;animation-delay:2s"></div>
    <div class="hero-shape" style="width:90px;height:90px;top:20%;right:8%;animation-delay:1s"></div>
    <div class="page-hero-line"></div>
    <div class="page-hero-content">
        <div class="breadcrumb-bar">
            <a href="index.php">Home</a>
            <span>/</span>
            <span style="color:rgba(255,255,255,.5)">Our Services</span>
        </div>
        <h1 class="page-hero-title">Our <em>Services</em></h1>
    </div>
</section>

<!-- â•â• CORE PILLARS â€” Mission / Vision / Philosophy â•â• -->
<section class="pillars-section">
    <div class="pillars-head rev">
        <span class="sec-label" style="display:block;text-align:center">What We Stand For</span>
        <h2 class="sec-title" style="text-align:center">Our <em>Foundation</em></h2>
    </div>
    <div class="mvp-grid">
        <div class="mvp-card rev">
            <div class="mvp-big-num">01</div>
            <span class="mvp-icon"><i class="fas fa-bullseye"></i></span>
            <h3 class="mvp-title">Our Mission</h3>
            <p class="mvp-text">At Glowear, our mission is to redefine bijoux retailing by offering more than just jewelry. We strive to curate a shopping experience that transcends mere transactions â€” blending craft and elegance to empower individuals to express their unique style effortlessly and authentically.</p>
        </div>
        <div class="mvp-card rev d1">
            <div class="mvp-big-num">02</div>
            <span class="mvp-icon"><i class="fas fa-eye"></i></span>
            <h3 class="mvp-title">Our Vision</h3>
            <p class="mvp-text">Our vision is to be the ultimate destination for bijoux enthusiasts. We envision a space that seamlessly integrates the latest trends, personalized recommendations, and an inclusive community â€” a fashion hub that inspires confidence and celebrates individual beauty.</p>
        </div>
        <div class="mvp-card rev d2">
            <div class="mvp-big-num">03</div>
            <span class="mvp-icon"><i class="fas fa-leaf"></i></span>
            <h3 class="mvp-title">Our Philosophy</h3>
            <p class="mvp-text">At Glowear, our philosophy is rooted in a passion for jewelry, innovation, and authenticity. We believe in offering not just bijoux but a reflection of individuality and self-expression â€” with commitment to ethical sourcing, quality craftsmanship, and genuine customer satisfaction.</p>
        </div>
    </div>
</section>

<!-- â•â• HORIZONTAL SERVICE CARDS â•â• -->
<section class="service-strip">
    <div class="strip-head">
        <div class="rev" style="display:flex;justify-content:space-between;align-items:flex-end">
            <div>
                <span class="sec-label">What We Offer</span>
                <h2 class="sec-title">Our <em>Services</em></h2>
            </div>
            <span class="drag-hint rev">Drag to explore</span>
        </div>
    </div>
    <div class="strip-track-wrap" id="svcTrack">
        <div class="strip-track">

            <div class="svc-card">
                <div class="svc-card-num">01</div>
                <span class="svc-icon"><i class="fas fa-shield-alt"></i></span>
                <h3 class="svc-title">We are Trusted</h3>
                <p class="svc-text">Glowear stands as a trusted haven for bijoux enthusiasts, ensuring authenticity, reliability, and style. Our commitment to sourcing quality pieces, providing accurate descriptions, and delivering on promises has earned us the trust of our discerning clientele.</p>
            </div>

            <div class="svc-card">
                <div class="svc-card-num">02</div>
                <span class="svc-icon"><i class="fas fa-award"></i></span>
                <h3 class="svc-title">We are Professional</h3>
                <p class="svc-text">At Glowear, professionalism defines every aspect of our service. Our team consists of jewelry enthusiasts dedicated to offering a seamless shopping experience â€” from presenting stunning collections to providing prompt and personalized customer support.</p>
            </div>

            <div class="svc-card">
                <div class="svc-card-num">03</div>
                <span class="svc-icon"><i class="fas fa-book-open"></i></span>
                <h3 class="svc-title">Our Stories</h3>
                <p class="svc-text">Glowear's journey through the bijoux landscape is woven with tales of elegance, customer satisfaction, and trendsetting collections. From humble beginnings to becoming a go-to jewelry destination, our stories echo our commitment to empowering individuals through beautiful adornment.</p>
            </div>

            <div class="svc-card">
                <div class="svc-card-num">04</div>
                <span class="svc-icon"><i class="fas fa-box-open"></i></span>
                <h3 class="svc-title">Gift Packaging</h3>
                <p class="svc-text">Every Glowear order arrives in our signature gift box â€” wrapped with care, ready to surprise. Whether it's a gift for someone you love or a treat for yourself, we make the unboxing experience as beautiful as the jewelry inside.</p>
            </div>

            <div class="svc-card">
                <div class="svc-card-num">05</div>
                <span class="svc-icon"><i class="fas fa-shipping-fast"></i></span>
                <h3 class="svc-title">Fast Delivery</h3>
                <p class="svc-text">We know you can't wait to wear your new bijoux. That's why we offer swift, secure delivery across Tunisia. Your order is handled with care from the moment it leaves our hands to the moment it reaches yours.</p>
            </div>

            <div class="svc-card">
                <div class="svc-card-num">06</div>
                <span class="svc-icon"><i class="fas fa-redo"></i></span>
                <h3 class="svc-title">Easy Returns</h3>
                <p class="svc-text">Not in love with your order? No problem. We offer hassle-free returns within 14 days. Your satisfaction is our absolute priority, and we're always here to make things right â€” quickly and without the drama.</p>
            </div>

        </div>
    </div>
</section>

<!-- â•â• PROCESS â•â• -->
<section class="process-section">
    <div class="process-head rev">
        <span class="sec-label">How It Works</span>
        <h2 class="sec-title">Your Journey <em>With Us</em></h2>
    </div>
    <div class="process-steps">
        <div class="process-step rev">
            <div class="step-circle">01</div>
            <h3 class="step-title">Browse</h3>
            <p class="step-text">Explore our curated collections of handcrafted bijoux â€” necklaces, earrings, bracelets and more.</p>
        </div>
        <div class="process-step rev d1">
            <div class="step-circle">02</div>
            <h3 class="step-title">Choose</h3>
            <p class="step-text">Select the pieces that speak to your style and add them to your cart with one click.</p>
        </div>
        <div class="process-step rev d2">
            <div class="step-circle">03</div>
            <h3 class="step-title">Order</h3>
            <p class="step-text">Checkout securely. We'll prepare your order and pack it in our signature gift box.</p>
        </div>
        <div class="process-step rev d3">
            <div class="step-circle">04</div>
            <h3 class="step-title">Glow</h3>
            <p class="step-text">Receive your bijoux, wear them with pride, and let your inner glow shine through every piece.</p>
        </div>
    </div>
</section>

<!-- â•â• WHY GLOWEAR â•â• -->
<section class="why-section">
    <div class="why-img">
        <img src="../images/strass2.png" alt="Why Glowear">
    </div>
    <div class="why-content">
        <span class="sec-label rev">Why Choose Us</span>
        <h2 class="sec-title rev d1">More Than <em>Jewelry</em></h2>
        <ul class="why-list">
            <li class="why-item rev d1">
                <i class="fas fa-gem why-item-icon"></i>
                <div>
                    <div class="why-item-title">100% Handcrafted</div>
                    <p class="why-item-text">Every single piece is made by hand with attention to detail. No mass production â€” just pure craft and passion.</p>
                </div>
            </li>
            <li class="why-item rev d2">
                <i class="fas fa-star why-item-icon"></i>
                <div>
                    <div class="why-item-title">Limited Collections</div>
                    <p class="why-item-text">Our pieces are exclusive and limited. Once they're gone, they're gone â€” own something truly rare.</p>
                </div>
            </li>
            <li class="why-item rev d3">
                <i class="fas fa-heart why-item-icon"></i>
                <div>
                    <div class="why-item-title">Made with Love</div>
                    <p class="why-item-text">From our hands to your skin â€” every jewelry carries the warmth and passion of the person who created it.</p>
                </div>
            </li>
            <li class="why-item rev d4">
                <i class="fas fa-headset why-item-icon"></i>
                <div>
                    <div class="why-item-title">Dedicated Support</div>
                    <p class="why-item-text">Our team is always available to help you find the perfect piece, track your order, or answer any question.</p>
                </div>
            </li>
        </ul>
    </div>
</section>

<!-- â•â• PROMISE BANNER â•â• -->
<section class="promise-banner">
    <div class="promise-bg-text">Glowear</div>
    <p class="promise-sub rev">Our Commitment</p>
    <h2 class="promise-quote rev d1">
        "Every jewelry we create is a <em>promise</em> <br>
        a promise of quality, of beauty,<br>
        of the glow within you."
    </h2>
    <a href="shop.php" class="btn-red rev d2">Shop the Collection</a>
</section>

<!-- â•â• STATS â•â• -->
<div class="stats-bar">
    <div class="stat-item rev"><div class="stat-n">200+</div><div class="stat-l">Unique Pieces</div></div>
    <div class="stat-item rev d1"><div class="stat-n">3K+</div><div class="stat-l">Happy Clients</div></div>
    <div class="stat-item rev d2"><div class="stat-n">14</div><div class="stat-l">Day Returns</div></div>
    <div class="stat-item rev d3"><div class="stat-n">100%</div><div class="stat-l">Handcrafted</div></div>
</div>

<!-- â•â• MARQUEE â•â• -->
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

<!-- â•â• CTA â•â• -->
<section class="cta-section">
    <div>
        <span class="sec-label rev">Ready to Shine?</span>
        <h2 class="cta-title rev d1">Start Your <em>Glowear</em><br>Journey Today</h2>
        <p class="cta-sub rev d2">Explore our handcrafted collections and find the bijou that speaks to you. Each piece is waiting to become part of your story.</p>
    </div>
    <div class="cta-btns rev d2">
        <a href="shop.php" class="btn-red">Shop Now</a>
        <a href="contact-us.php" class="btn-ghost">Contact Us</a>
    </div>
</section>

<!-- â•â• INSTAGRAM â•â• -->
<section class="insta-sec">
    <div class="insta-head rev">
        <p class="insta-handle"><i class="fab fa-instagram"></i> @glowear</p>
        <h2 class="sec-title">As Seen <em>On</em></h2>
    </div>
    <div class="insta-grid">
        <div class="insta-item rev"><img src="../images/baby2.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d1"><img src="../images/sun2.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d2"><img src="../images/pink2.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d3"><img src="../images/strass.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d4"><img src="../images/baby3.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
    </div>
</section>

<!-- â•â• FOOTER â•â• -->
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
<script src="../js/response-formatter.js"></script>
<?php if(file_exists(__DIR__.'/../js/chatbot-widget.js')): ?>
<script src="../js/chatbot-widget.js?v=20260517.1"></script>
<?php endif; ?>
<script>
// â”€â”€ CURSOR
const cur=document.getElementById('cur'), curR=document.getElementById('cur-ring');
let mx=window.innerWidth/2, my=window.innerHeight/2, rx=mx, ry=my;
document.addEventListener('mousemove',e=>{mx=e.clientX;my=e.clientY;});
(function animCursor(){
    cur.style.left=mx+'px'; cur.style.top=my+'px';
    rx+=(mx-rx)*.14; ry+=(my-ry)*.14;
    curR.style.left=rx+'px'; curR.style.top=ry+'px';
    requestAnimationFrame(animCursor);
})();
document.querySelectorAll('a,button,.mvp-card,.svc-card,.insta-item,.why-item,.process-step').forEach(el=>{
    el.addEventListener('mouseenter',()=>document.body.classList.add('big-cur'));
    el.addEventListener('mouseleave',()=>document.body.classList.remove('big-cur'));
});

// â”€â”€ NAV + BACK TO TOP
window.addEventListener('scroll',()=>{
    document.getElementById('nav').classList.toggle('stuck', window.scrollY>60);
    document.getElementById('btt').style.display = window.scrollY>320 ? 'block':'none';
});

// â”€â”€ SCROLL REVEAL
const revEls=document.querySelectorAll('.rev');
const obs=new IntersectionObserver(entries=>{
    entries.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');obs.unobserve(e.target);}});
},{threshold:.1,rootMargin:'0px 0px -50px 0px'});
revEls.forEach(r=>obs.observe(r));

// â”€â”€ HORIZONTAL DRAG SCROLL
const svcTrack=document.getElementById('svcTrack');
let isDragging=false, startX, sl;
svcTrack.addEventListener('mousedown',e=>{isDragging=true;startX=e.pageX-svcTrack.offsetLeft;sl=svcTrack.scrollLeft;});
svcTrack.addEventListener('mouseleave',()=>isDragging=false);
svcTrack.addEventListener('mouseup',()=>isDragging=false);
svcTrack.addEventListener('mousemove',e=>{
    if(!isDragging)return; e.preventDefault();
    svcTrack.scrollLeft=sl-(e.pageX-svcTrack.offsetLeft-startX)*1.6;
});

// â”€â”€ SEARCH
document.getElementById('searchBtn').addEventListener('click',e=>{
    e.preventDefault();
    document.getElementById('searchOv').classList.add('open');
    document.querySelector('.search-ov input').focus();
});
document.getElementById('searchClose').addEventListener('click',()=>document.getElementById('searchOv').classList.remove('open'));
document.addEventListener('keydown',e=>{if(e.key==='Escape')document.getElementById('searchOv').classList.remove('open');});

// â”€â”€ CART
function toggleCart(){
    document.getElementById('sideCart').classList.toggle('open');
    document.getElementById('cartOv').classList.toggle('open');
}
</script>
</body>
</html>




