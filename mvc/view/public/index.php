<?php
session_start();
include_once("../../controller/ProduitController.php");
include_once("../../controller/PannierController.php");
$viewHelpers = __DIR__ . '/../inc/view_helpers.php';
if (file_exists($viewHelpers)) {
    include_once($viewHelpers);
}
$controllerpannier = new PannierController();
$id = isset($_SESSION['id'])?$_SESSION['id']:null;
$listpannier = $controllerpannier->listpannier($id);
$controller = new ProduitController();
$produits = $controller->getAllnom();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Glowear &mdash; Fine Bijoux</title>
    <link rel="shortcut icon" href="../images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/chatbot-widget.css?v=20260517.2">
<link rel="stylesheet" href="../css/chatbot-messages.css">
<link rel="stylesheet" href="../css/response-format.css">
        <link rel="stylesheet" href="../css/base.css">
        <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">

    
    <script>
function createGlitter() {
    const layer = document.getElementById('glitter-layer');
    if (!layer) return;

    const glitter = document.createElement('span');
    glitter.className = 'glitter';

    glitter.style.left = Math.random() * 100 + 'vw';
    glitter.style.top = Math.random() * 100 + 'vh';
    glitter.style.animationDuration = (1.5 + Math.random() * 2.5) + 's';
    glitter.style.animationDelay = '0s';

    layer.appendChild(glitter);

    setTimeout(() => {
        glitter.remove();
    }, 4000);
}

setInterval(createGlitter, 120);
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
        <?php if(isset($_SESSION['id'])): ?>
        <a href="../account/my-account.php"><i class="fa fa-user"></i></a>
        <?php else: ?>
        <a href="../auth/client_login.php"><i class="fa fa-user"></i></a>
        <?php endif; ?>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-bg-layer"></div>
    <div class="pt" style="width:3px;height:3px;left:22%;bottom:18%;animation-delay:0s;animation-duration:7s"></div>
    <div class="pt" style="width:2px;height:2px;left:32%;bottom:28%;animation-delay:1.2s;animation-duration:9s"></div>
    <div class="pt" style="width:4px;height:4px;left:18%;bottom:38%;animation-delay:2.5s;animation-duration:6s"></div>
    <div class="pt" style="width:2px;height:2px;left:42%;bottom:22%;animation-delay:.5s;animation-duration:8s"></div>
    <div class="hero-content">
        <span class="hero-tag">New Collection 2026</span>
        <h1 class="hero-h1">Wear<br>Your<br><em>Glow</em></h1>
        <p class="hero-sub">Handcrafted bijoux designed to celebrate the light in you. Each piece tells a story of elegance and intention.</p>
        <div class="hero-btns">
            <a href="shop.php" class="btn-red">Explore Collection</a>
            <a href="about.php" class="btn-ghost">Our Story</a>
        </div>
    </div>
    <div class="hero-img">
        <img src="../images/pink.png" alt="Glowear Bijoux">
    </div>
    <div class="hero-scroll-hint">
        <div class="scroll-bar"></div>
        <span>Scroll to discover</span>
    </div>
    <span class="hero-side-label">Glowear &mdash; Bijoux 2026</span>
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

<!-- FILM 1 &mdash; Pearl -->
<section class="film-section film-bars" id="film1">
    <div class="film-bg"><img src="../images/strass.png" alt="Pearl Collection" id="fImg1"></div>
    <div class="film-vignette"></div>
    <div class="film-content" id="fCon1">
        <span class="film-tag">Chapter 01 &mdash; Pearl</span>
        <h2 class="film-title">The Pearl<br><em>Collection</em></h2>
        <p class="film-desc">Delicate pearls meeting crystal clarity. Designed for the woman who shines wherever she goes.</p>
        <a href="shop.php?nom=Necklaces" class="btn-red">Shop Necklaces</a>
    </div>
    <div class="film-number">01</div>
</section>

<!-- FILM 2 &mdash; Gold Charms -->
<section class="film-section film-bars" id="film2">
    <div class="film-bg"><img src="../images/sun1.png" alt="Gold Collection" id="fImg2"></div>
    <div class="film-vignette"></div>
    <div class="film-content right" id="fCon2">
        <span class="film-tag">Chapter 02 &mdash; Gold</span>
        <h2 class="film-title">Sun &amp;<br><em>Charms</em></h2>
        <p class="film-desc">Playful gold charms inspired by the sea, the sun, and all things beautiful in nature.</p>
        <a href="shop.php?nom=Bracelets" class="btn-red">Shop Charms</a>
    </div>
    <div class="film-number" style="left:8vw;right:auto">02</div>
</section>

<!-- FILM 3 &mdash; Earrings -->
<section class="film-section film-bars" id="film3">
    <div class="film-bg"><img src="../images/strass4.png" alt="Earrings" id="fImg3"></div>
    <div class="film-vignette"></div>
    <div class="film-content" id="fCon3">
        <span class="film-tag">Chapter 03 &mdash; Earrings</span>
        <h2 class="film-title">Crystal<br><em>Drops</em></h2>
        <p class="film-desc">Crystal stud meets lustrous pearl drop. Minimal, elegant, unforgettable. Every pair, a statement.</p>
        <a href="shop.php?nom=Earrings" class="btn-red">Shop Earrings</a>
    </div>
    <div class="film-number">03</div>
</section>

<!-- CATEGORIES -->
<section class="cat-section">
    <div class="sec-head rev">
        <span class="sec-label">Browse by Collection</span>
        <h2 class="sec-title">Find Your <em>Style</em></h2>
    </div>
    <div class="cat-grid rev">
        <?php
        $p2=$controller->getAllnom(); $ci=0; $bdg=['New','Hot','Limited','','Exclusive'];
        while($l=$p2->fetch()): if($ci>=5) break;
        ?>
        <div class="cat-card">
            <img src="<?php echo "$l[1]" ?>" alt="<?php echo $l[0] ?>">
            <?php if($bdg[$ci%count($bdg)]): ?>
            <span class="cat-badge"><?php echo $bdg[$ci%count($bdg)] ?></span>
            <?php endif; ?>
            <div class="cat-overlay">
                <span class="cat-name"><?php echo $l[0] ?></span>
                <a class="cat-cta" href="shop.php?nom=<?php echo urlencode($l[0]) ?>">Shop Now</a>
            </div>
        </div>
        <?php $ci++; endwhile; ?>
    </div>
</section>

<!-- SHOWCASE -->
<section class="showcase">
    <div class="showcase-head">
        <div class="rev">
            <span class="sec-label">Curated Pieces</span>
            <h2 class="sec-title">New <em>Arrivals</em></h2>
        </div>
        <span class="drag-hint rev">Drag to explore</span>
    </div>
    <div class="h-track-wrap" id="hTrack">
        <div class="h-track">
            <?php
            $sf=$controller->getAllnom();
            $sb=['New','Hot','Limited','Exclusive',''];
            $sc=['Necklace','Necklace','Bracelet','Earrings','Necklace'];
            $si=0;
            while($f=$sf->fetch()):
            ?>
            <div class="s-card">
                <div class="s-card-img">
                    <img src="<?php echo "$f[1]" ?>" alt="<?php echo $f[0] ?>">
                    <?php if($sb[$si%count($sb)]): ?>
                    <span class="s-card-badge"><?php echo $sb[$si%count($sb)] ?></span>
                    <?php endif; ?>
                </div>
                <div class="s-card-info">
                    <p class="s-card-cat"><?php echo $sc[$si%count($sc)] ?></p>
                    <h3 class="s-card-name"><?php echo $f[0] ?></h3>
                </div>
            </div>
            <?php $si++; endwhile; ?>
        </div>
    </div>
</section>

<!-- STORY -->
<section class="story">
    <div class="story-imgs rev">
        <div class="s-img-main"><img src="../images/baby.png" alt=""></div>
        <div class="s-img-accent"><img src="../images/baby5.png" alt=""></div>
        <div class="s-frame"></div>
        <div class="s-num">01</div>
    </div>
    <div>
        <span class="sec-label rev">Our Story</span>
        <h2 class="sec-title rev d1">Crafted with<br><em>Intention</em></h2>
        <p class="story-body rev d2">At Glowear, every piece of jewelry is born from a passion for beauty and a dedication to craft. We believe the right bijou doesn't just accessorize â€” it expresses who you are. From delicate pearl chokers to bold gold charm necklaces, each creation is designed to make you feel radiant.</p>
        <div class="stats rev d3">
            <div><div class="stat-n">200+</div><div class="stat-l">Unique Pieces</div></div>
            <div><div class="stat-n">3K+</div><div class="stat-l">Happy Clients</div></div>
            <div><div class="stat-n">100%</div><div class="stat-l">Handcrafted</div></div>
        </div>
        <a href="about.php" class="btn-red rev d4">Discover Our Story</a>
    </div>
</section>

<!-- FEATURES -->
<section class="features">
    <div class="rev" style="text-align:center">
        <span class="sec-label">Why Glowear</span>
        <h2 class="sec-title">The <em>Difference</em></h2>
    </div>
    <div class="feat-grid">
        <div class="feat rev"><i class="fas fa-gem feat-icon"></i><h3 class="feat-title">Handcrafted Quality</h3><p class="feat-text">Every piece made by hand â€” no two exactly alike.</p></div>
        <div class="feat rev d1"><i class="fas fa-shield-alt feat-icon"></i><h3 class="feat-title">Premium Materials</h3><p class="feat-text">High-quality metals, genuine pearls, selected stones.</p></div>
        <div class="feat rev d2"><i class="fas fa-box-open feat-icon"></i><h3 class="feat-title">Gift-Ready Packaging</h3><p class="feat-text">Arrives in our signature Glowear box, ready to gift.</p></div>
        <div class="feat rev d1"><i class="fas fa-redo feat-icon"></i><h3 class="feat-title">Easy Returns</h3><p class="feat-text">Hassle-free returns within 14 days. Your happiness first.</p></div>
        <div class="feat rev d2"><i class="fas fa-star feat-icon"></i><h3 class="feat-title">Limited Editions</h3><p class="feat-text">Exclusive pieces â€” once gone, they won't come back.</p></div>
        <div class="feat rev d3"><i class="fas fa-heart feat-icon"></i><h3 class="feat-title">Made with Love</h3><p class="feat-text">From our hands to your skin â€” every bijou carries warmth.</p></div>
    </div>
</section>

<!-- INSTAGRAM -->
<section class="insta-sec">
    <div class="insta-head rev">
        <p class="insta-handle"><i class="fab fa-instagram"></i> @glowear</p>
        <h2 class="sec-title">As Seen <em>On</em></h2>
    </div>
    <div class="insta-grid">
        <div class="insta-item rev"><img src="../images/baby2.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d1"><img src="../images/baby3.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d2"><img src="../images/baby4.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d3"><img src="../images/baby5.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
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
// â”€â”€ SMOOTH CURSOR
const cur = document.getElementById('cur');
const curR = document.getElementById('cur-ring');
let mx=window.innerWidth/2, my=window.innerHeight/2;
let rx=mx, ry=my;
document.addEventListener('mousemove', e => { mx=e.clientX; my=e.clientY; });
(function animCursor(){
    cur.style.left = mx+'px'; cur.style.top = my+'px';
    rx += (mx-rx) * 0.14;
    ry += (my-ry) * 0.14;
    curR.style.left = rx+'px'; curR.style.top = ry+'px';
    requestAnimationFrame(animCursor);
})();
document.querySelectorAll('a,button,.cat-card,.s-card,.insta-item,.feat').forEach(el=>{
    el.addEventListener('mouseenter', () => document.body.classList.add('big-cur'));
    el.addEventListener('mouseleave', () => document.body.classList.remove('big-cur'));
});

// â”€â”€ NAV
window.addEventListener('scroll', () => {
    document.getElementById('nav').classList.toggle('stuck', window.scrollY > 60);
});

// â”€â”€ CINEMATIC SCROLL ZOOM + PARALLAX (the film effect)
const films = [
    { sec: document.getElementById('film1'), img: document.getElementById('fImg1'), con: document.getElementById('fCon1') },
    { sec: document.getElementById('film2'), img: document.getElementById('fImg2'), con: document.getElementById('fCon2') },
    { sec: document.getElementById('film3'), img: document.getElementById('fImg3'), con: document.getElementById('fCon3') },
];

function onScroll() {
    const wh = window.innerHeight;

    films.forEach(f => {
        const rect = f.sec.getBoundingClientRect();

        // 0 = section top at bottom of screen, 1 = section bottom at top of screen
        const progress = (wh - rect.top) / (wh + rect.height);
        const p = Math.max(0, Math.min(1, progress));

        // ZOOM: image starts zoomed in (1.15), slowly zooms out to 1.0 as you scroll through
        const scale = 1.15 - p * 0.15;
        // PARALLAX: image shifts vertically
        const yShift = (0.5 - p) * 55;

        if (f.img) {
            f.img.style.transform = `scale(${scale}) translateY(${yShift}px)`;
        }

        // Reveal content text when ~30% visible
        if (p > 0.28 && f.con) {
            f.con.classList.add('show');
            f.sec.classList.add('show');
        }
    });
}

window.addEventListener('scroll', onScroll, { passive: true });
onScroll(); // run once on load

// â”€â”€ SCROLL REVEAL (general elements)
const revEls = document.querySelectorAll('.rev');
const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('in'); obs.unobserve(e.target); } });
}, { threshold: 0.12, rootMargin: '0px 0px -50px 0px' });
revEls.forEach(r => obs.observe(r));

// â”€â”€ HORIZONTAL DRAG SCROLL
const track = document.getElementById('hTrack');
let isDragging=false, startX, sl;
track.addEventListener('mousedown', e => { isDragging=true; startX=e.pageX-track.offsetLeft; sl=track.scrollLeft; });
track.addEventListener('mouseleave', () => isDragging=false);
track.addEventListener('mouseup', () => isDragging=false);
track.addEventListener('mousemove', e => {
    if(!isDragging) return; e.preventDefault();
    track.scrollLeft = sl - (e.pageX - track.offsetLeft - startX) * 1.6;
});

// â”€â”€ SEARCH
document.getElementById('searchBtn').addEventListener('click', e => {
    e.preventDefault();
    document.getElementById('searchOv').classList.add('open');
    document.querySelector('.search-ov input').focus();
});
document.getElementById('searchClose').addEventListener('click', () => {
    document.getElementById('searchOv').classList.remove('open');
});
document.addEventListener('keydown', e => {
    if(e.key === 'Escape') document.getElementById('searchOv').classList.remove('open');
});

// â”€â”€ CART
function toggleCart() {
    document.getElementById('sideCart').classList.toggle('open');
    document.getElementById('cartOv').classList.toggle('open');
}
</script>
</body>
</html>





