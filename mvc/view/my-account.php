<?php
session_start();
include_once("../controller/ProduitController.php");
include_once("../controller/PannierController.php");
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
    <title>Glowear — My Account</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Chatbot CSS -->
    <link rel="stylesheet" href="css/chatbot-widget.css">
    <link rel="stylesheet" href="css/chatbot-messages.css">
    <link rel="stylesheet" href="css/response-format.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --red:     #C0392B;
            --red2:    #E74C3C;
            --red-dim: rgba(192,57,43,0.15);
            --gold:    #C9A84C;
            --dark:    #080808;
            --dark2:   #101010;
            --dark3:   #181818;
            --cream:   #F0EAE0;
            --grey:    #777;
            --white:   #FFFFFF;
        }

        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior:smooth; }

        body {
            font-family:'Montserrat', sans-serif;
            background:var(--dark);
            color:var(--cream);
            overflow-x:hidden;
            cursor:none;
        }

        /* ── CURSOR */
        #cur { position:fixed; pointer-events:none; z-index:9999;
            width:10px; height:10px; border-radius:50%;
            background:var(--red); transform:translate(-50%,-50%);
            transition:width .25s,height .25s; }
        #cur-ring { position:fixed; pointer-events:none; z-index:9998;
            width:34px; height:34px; border-radius:50%;
            border:1.5px solid var(--red); transform:translate(-50%,-50%);
            transition:width .3s,height .3s; opacity:.55; }
        .big-cur #cur  { width:20px; height:20px; }
        .big-cur #cur-ring { width:58px; height:58px; border-color:var(--red2); }

        /* ── GRAIN */
        .grain {
            position:fixed; inset:0; pointer-events:none; z-index:9990; opacity:.03;
            background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size:180px;
        }

        /* ── GLITTER */
        #glitter-layer { position:fixed; inset:0; pointer-events:none; z-index:9991; overflow:hidden; }
        .glitter {
            position:absolute; width:4px; height:4px; border-radius:50%;
            background:radial-gradient(circle, rgba(255,255,255,.95) 0%, rgba(255,215,0,.9) 35%, rgba(255,255,255,0) 70%);
            box-shadow:0 0 8px rgba(255,215,0,.8), 0 0 16px rgba(255,255,255,.35);
            opacity:0; animation:glitterFall linear forwards; mix-blend-mode:screen;
        }
        @keyframes glitterFall {
            0%   { transform:translateY(0) scale(.6) rotate(0deg); opacity:0; }
            10%  { opacity:1; }
            100% { transform:translateY(120px) scale(1.2) rotate(180deg); opacity:0; }
        }

        /* ── TICKER */
        .ticker { background:var(--red); padding:7px 0; overflow:hidden; white-space:nowrap; }
        .ticker-inner {
            display:inline-block; animation:tick 28s linear infinite;
            font-size:10px; font-weight:400; letter-spacing:3px;
            text-transform:uppercase; color:#fff;
        }
        .ticker-inner span { margin:0 36px; }
        @keyframes tick { from{transform:translateX(0)} to{transform:translateX(-50%)} }

        /* ── NAV */
        .nav-wrap {
            position:fixed; top:36px; left:0; right:0; z-index:1000;
            display:flex; align-items:center; justify-content:space-between;
            padding:0 56px;
            transition:top .4s, background .4s, backdrop-filter .4s, padding .4s;
        }
        .nav-wrap.stuck {
            top:0; padding:14px 56px;
            background:rgba(8,8,8,.94);
            backdrop-filter:blur(18px);
            border-bottom:1px solid rgba(192,57,43,.25);
        }
        .nav-wrap .nav-logo {
            background:rgba(255,255,255,.08);
            padding:2px 4px; border-radius:4px; display:inline-block;
        }
        .nav-wrap .nav-logo img { height:80px; }
        .nav-links { display:flex; gap:36px; list-style:none; }
        .nav-links a {
            font-size:11px; letter-spacing:3px; text-transform:uppercase;
            color:rgba(255,255,255,.65); text-decoration:none;
            position:relative; transition:color .3s;
        }
        .nav-links a::after {
            content:''; position:absolute; bottom:-3px; left:0;
            width:0; height:1px; background:var(--red); transition:width .3s;
        }
        .nav-links a:hover { color:#fff; }
        .nav-links a:hover::after { width:100%; }
        .nav-links .drop { position:relative; }
        .nav-links .drop-menu {
            display:none; position:absolute; top:28px; left:-16px;
            background:rgba(12,12,12,.97);
            border:1px solid rgba(192,57,43,.2);
            list-style:none; padding:16px 0; min-width:180px;
        }
        .nav-links .drop:hover .drop-menu { display:block; }
        .nav-links .drop-menu li a { display:block; padding:8px 20px; font-size:10px; letter-spacing:2px; }
        .nav-icons { display:flex; gap:20px; align-items:center; }
        .nav-icons a { color:rgba(255,255,255,.6); font-size:15px; text-decoration:none; transition:color .3s; }
        .nav-icons a:hover { color:var(--red); }
        .cart-count {
            background:var(--red); color:#fff; font-size:9px; font-weight:600;
            width:15px; height:15px; border-radius:50%;
            display:inline-flex; align-items:center; justify-content:center;
            position:relative; top:-8px; left:-5px;
        }

        /* ── SIDE CART */
        .cart-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.65); z-index:1999; backdrop-filter:blur(5px); }
        .cart-overlay.open { display:block; }
        .side-cart { position:fixed; right:-400px; top:0; bottom:0; width:370px; background:var(--dark2); border-left:1px solid rgba(192,57,43,.2); z-index:2000; padding:36px 28px; transition:right .4s cubic-bezier(.25,.46,.45,.94); overflow-y:auto; }
        .side-cart.open { right:0; }
        .sc-head { display:flex; justify-content:space-between; align-items:center; margin-bottom:36px; }
        .sc-title { font-family:'Cormorant Garamond',serif; font-size:26px; font-weight:300; color:#fff; }
        .sc-close { background:none; border:none; color:var(--grey); font-size:18px; cursor:pointer; }
        .sc-item { display:flex; gap:14px; padding:14px 0; border-bottom:1px solid rgba(255,255,255,.05); }
        .sc-item img { width:64px; height:64px; object-fit:cover; }
        .sc-item-name { font-family:'Cormorant Garamond',serif; font-size:16px; font-weight:300; color:#fff; }
        .sc-item-detail { font-size:11px; color:var(--grey); margin-top:4px; }
        .sc-total { margin-top:28px; padding-top:20px; border-top:1px solid rgba(192,57,43,.2); display:flex; justify-content:space-between; align-items:center; margin-bottom:22px; }
        .sc-total-label { font-size:10px; letter-spacing:3px; text-transform:uppercase; color:var(--grey); }
        .sc-total-val { font-family:'Cormorant Garamond',serif; font-size:26px; color:var(--red2); }

        /* ── SEARCH OVERLAY */
        .search-ov { display:none; position:fixed; inset:0; background:rgba(0,0,0,.96); z-index:3000; flex-direction:column; align-items:center; justify-content:center; }
        .search-ov.open { display:flex; }
        .search-ov input { background:none; border:none; border-bottom:1px solid rgba(255,255,255,.25); font-family:'Cormorant Garamond',serif; font-size:34px; color:#fff; width:60%; max-width:580px; padding:14px 0; text-align:center; outline:none; }
        .search-ov input::placeholder { color:rgba(255,255,255,.18); }
        .search-ov-close { position:absolute; top:36px; right:56px; font-size:22px; color:rgba(255,255,255,.4); cursor:pointer; transition:color .3s; }
        .search-ov-close:hover { color:var(--red); }

        /* ── PAGE HERO BAND */
        .page-hero {
            padding-top:160px; padding-bottom:60px;
            text-align:center; position:relative; overflow:hidden;
        }
        .page-hero::before {
            content:''; position:absolute; inset:0;
            background:radial-gradient(ellipse at 50% 0%, rgba(192,57,43,.09) 0%, transparent 60%);
        }
        .page-hero-tag {
            display:inline-flex; align-items:center; gap:10px;
            font-size:10px; letter-spacing:5px; text-transform:uppercase;
            color:var(--red); margin-bottom:18px;
        }
        .page-hero-tag::before,
        .page-hero-tag::after { content:''; width:28px; height:1px; background:var(--red); }
        .page-hero h1 {
            font-family:'Cormorant Garamond', serif;
            font-size:clamp(48px,6vw,88px); font-weight:300;
            color:#fff; line-height:.95;
        }
        .page-hero h1 em { font-style:italic; color:var(--red2); }
        .breadcrumb-row {
            display:flex; align-items:center; justify-content:center; gap:10px;
            margin-top:20px; font-size:10px; letter-spacing:3px; text-transform:uppercase;
        }
        .breadcrumb-row a { color:var(--grey); text-decoration:none; transition:color .3s; }
        .breadcrumb-row a:hover { color:var(--red); }
        .breadcrumb-row span { color:rgba(255,255,255,.2); }
        .breadcrumb-row .active { color:var(--red); }

        /* ── ACCOUNT SECTION */
        .account-wrap {
            padding:70px 56px 130px;
            max-width:1200px; margin:0 auto;
        }

        /* Welcome strip */
        .welcome-strip {
            display:flex; align-items:center; justify-content:space-between;
            padding:28px 40px;
            background:var(--dark2);
            border:1px solid rgba(192,57,43,.15);
            border-left:3px solid var(--red);
            margin-bottom:56px;
            opacity:0; transform:translateY(20px);
            transition:opacity .7s ease, transform .7s ease;
        }
        .welcome-strip.in { opacity:1; transform:translateY(0); }
        .welcome-text { font-family:'Cormorant Garamond',serif; font-size:22px; font-weight:300; color:#fff; }
        .welcome-text em { font-style:italic; color:var(--red2); }
        .welcome-sub { font-size:10px; letter-spacing:3px; text-transform:uppercase; color:var(--grey); margin-top:4px; }
        .welcome-avatar {
            width:52px; height:52px; border-radius:50%;
            background:var(--red-dim); border:1px solid rgba(192,57,43,.4);
            display:flex; align-items:center; justify-content:center;
            color:var(--red); font-size:20px;
        }

        /* ── ACCOUNT CARDS GRID */
        .account-grid {
            display:grid;
            grid-template-columns:repeat(3, 1fr);
            gap:2px;
        }

        .account-card {
            position:relative; overflow:hidden;
            background:var(--dark2);
            padding:52px 44px;
            text-decoration:none;
            display:block;
            border:1px solid rgba(192,57,43,.08);
            transition:background .35s, border-color .35s;
            opacity:0; transform:translateY(36px);
            transition:opacity .85s ease, transform .85s ease, background .35s, border-color .35s;
            cursor:none;
        }
        .account-card.in { opacity:1; transform:translateY(0); }
        .account-card:hover { background:var(--dark3); border-color:rgba(192,57,43,.3); text-decoration:none; }

        /* top accent line */
        .account-card::before {
            content:''; position:absolute; top:0; left:0; right:0; height:2px;
            background:linear-gradient(to right, var(--red), transparent 70%);
            transform:scaleX(0); transform-origin:left;
            transition:transform .4s ease;
        }
        .account-card:hover::before { transform:scaleX(1); }

        /* ghost bg number */
        .card-bg-num {
            position:absolute; right:16px; bottom:-14px;
            font-family:'Cormorant Garamond',serif; font-size:110px; font-weight:300; line-height:1;
            color:rgba(255,255,255,.025); pointer-events:none; user-select:none;
            transition:color .35s;
        }
        .account-card:hover .card-bg-num { color:rgba(192,57,43,.06); }

        .card-icon-wrap {
            width:64px; height:64px;
            border:1px solid rgba(192,57,43,.3);
            display:flex; align-items:center; justify-content:center;
            margin-bottom:28px;
            transition:background .35s, border-color .35s;
            position:relative;
        }
        .account-card:hover .card-icon-wrap {
            background:var(--red-dim);
            border-color:rgba(192,57,43,.6);
        }
        .card-icon-wrap i {
            font-size:22px; color:var(--red);
            transition:transform .35s;
        }
        .account-card:hover .card-icon-wrap i { transform:scale(1.15); }

        .card-label {
            font-size:9px; letter-spacing:5px; text-transform:uppercase;
            color:var(--red); margin-bottom:10px; display:block;
        }
        .card-title {
            font-family:'Cormorant Garamond',serif;
            font-size:26px; font-weight:300; color:#fff;
            margin-bottom:10px; line-height:1.1;
        }
        .card-desc {
            font-size:12px; font-weight:300; color:var(--grey); line-height:1.7;
        }
        .card-arrow {
            display:inline-flex; align-items:center; gap:8px;
            margin-top:24px; font-size:9px; letter-spacing:3px; text-transform:uppercase;
            color:var(--red); opacity:0; transform:translateX(-8px);
            transition:opacity .3s, transform .3s;
        }
        .card-arrow::after { content:'→'; }
        .account-card:hover .card-arrow { opacity:1; transform:translateX(0); }

        /* logout card special styling */
        .account-card.logout-card { border-color:rgba(192,57,43,.12); }
        .account-card.logout-card:hover { background:#0f0505; border-color:rgba(192,57,43,.4); }
        .account-card.logout-card .card-icon-wrap { border-color:rgba(192,57,43,.25); }
        .account-card.logout-card:hover .card-icon-wrap { background:rgba(192,57,43,.12); }

        /* ── GUEST STATE (not logged in) */
        .guest-banner {
            text-align:center; padding:80px 40px;
            background:var(--dark2);
            border:1px solid rgba(192,57,43,.12);
            position:relative; overflow:hidden;
            opacity:0; transform:translateY(28px);
            transition:opacity .9s ease, transform .9s ease;
        }
        .guest-banner.in { opacity:1; transform:translateY(0); }
        .guest-banner::before {
            content:''; position:absolute; top:0; left:0; right:0; height:2px;
            background:linear-gradient(to right, transparent, var(--red), transparent);
        }
        .guest-icon { font-size:52px; color:rgba(192,57,43,.3); margin-bottom:24px; display:block; }
        .guest-title {
            font-family:'Cormorant Garamond',serif; font-size:40px; font-weight:300; color:#fff;
            margin-bottom:12px;
        }
        .guest-title em { font-style:italic; color:var(--red2); }
        .guest-text { font-size:13px; font-weight:300; color:var(--grey); margin-bottom:36px; line-height:1.8; }
        .guest-bg-num {
            position:absolute; right:20px; bottom:-20px;
            font-family:'Cormorant Garamond',serif; font-size:180px; font-weight:300;
            color:rgba(255,255,255,.018); pointer-events:none;
        }

        /* ── BUTTONS */
        .btn-red {
            background:var(--red); color:#fff;
            font-size:10px; letter-spacing:3px; text-transform:uppercase;
            padding:14px 34px; border:none; font-weight:500;
            display:inline-block; cursor:pointer;
            transition:background .3s, transform .3s;
            font-family:'Montserrat', sans-serif; text-decoration:none;
        }
        .btn-red:hover { background:var(--red2); transform:translateY(-2px); color:#fff; text-decoration:none; }
        .btn-ghost {
            border:1px solid rgba(192,57,43,.45); color:var(--red2); background:none;
            font-size:10px; letter-spacing:3px; text-transform:uppercase;
            padding:14px 34px; font-weight:300; cursor:pointer;
            display:inline-block; transition:all .3s;
            font-family:'Montserrat', sans-serif; text-decoration:none;
        }
        .btn-ghost:hover { border-color:var(--red); background:var(--red-dim); color:var(--red2); text-decoration:none; }

        /* ── INSTAGRAM */
        .insta-sec { padding:100px 0; background:var(--dark3); }
        .insta-head { text-align:center; margin-bottom:56px; }
        .insta-handle { font-size:12px; letter-spacing:2px; color:var(--red); display:inline-flex; align-items:center; gap:8px; margin-bottom:20px; }
        .sec-label { font-size:9px; letter-spacing:6px; text-transform:uppercase; color:var(--red); margin-bottom:14px; display:block; }
        .sec-title { font-family:'Cormorant Garamond',serif; font-size:clamp(36px,5vw,68px); font-weight:300; color:#fff; }
        .sec-title em { font-style:italic; color:rgba(192,57,43,.7); }
        .insta-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:3px; padding:0 3px; }
        .insta-item { aspect-ratio:1; overflow:hidden; position:relative; cursor:none; }
        .insta-item img { width:100%; height:100%; object-fit:cover; transition:transform .5s,filter .5s; filter:brightness(.75) saturate(.8); }
        .insta-item:hover img { transform:scale(1.1); filter:brightness(.4) saturate(1.1); }
        .insta-ov { position:absolute; inset:0; background:rgba(192,57,43,.25); display:flex; align-items:center; justify-content:center; opacity:0; transition:opacity .3s; }
        .insta-item:hover .insta-ov { opacity:1; }
        .insta-ov i { font-size:22px; color:#fff; }

        /* ── FOOTER */
        footer { background:#040404; border-top:1px solid rgba(192,57,43,.18); padding:80px 56px 36px; }
        .ft-grid { display:grid; grid-template-columns:1.5fr 1fr 1fr 1fr; gap:56px; margin-bottom:56px; }
        .ft-logo img { height:44px; margin-bottom:18px; }
        .ft-desc { font-size:12px; font-weight:300; color:var(--grey); line-height:1.8; margin-bottom:22px; }
        .ft-social { display:flex; gap:12px; }
        .ft-social a { width:34px; height:34px; border:1px solid rgba(255,255,255,.08); display:flex; align-items:center; justify-content:center; color:var(--grey); font-size:12px; text-decoration:none; transition:all .3s; }
        .ft-social a:hover { border-color:var(--red); color:var(--red); }
        .ft-col-title { font-size:9px; letter-spacing:4px; text-transform:uppercase; color:#fff; margin-bottom:22px; font-weight:500; }
        .ft-links { list-style:none; }
        .ft-links li { margin-bottom:10px; }
        .ft-links a { font-size:12px; font-weight:300; color:var(--grey); text-decoration:none; transition:color .3s; }
        .ft-links a:hover { color:var(--red); }
        .ft-contact { font-size:12px; font-weight:300; color:var(--grey); display:flex; flex-direction:column; gap:14px; }
        .ft-contact span { display:flex; gap:10px; align-items:flex-start; }
        .ft-contact i { color:var(--red); margin-top:2px; }
        .ft-bottom { border-top:1px solid rgba(255,255,255,.04); padding-top:28px; display:flex; justify-content:space-between; align-items:center; }
        .ft-copy { font-size:10px; letter-spacing:2px; color:rgba(255,255,255,.18); }
        .ft-bottom-links { display:flex; gap:20px; }
        .ft-bottom-links a { font-size:10px; letter-spacing:1px; color:rgba(255,255,255,.18); text-decoration:none; transition:color .3s; }
        .ft-bottom-links a:hover { color:var(--red); }

        /* ── BACK TO TOP */
        #btt { display:none; position:fixed; bottom:36px; right:36px; width:42px; height:42px; background:var(--red); color:#fff; text-align:center; line-height:42px; font-size:14px; z-index:999; text-decoration:none; transition:background .3s; }
        #btt:hover { background:var(--red2); color:#fff; }

        /* ── SCROLL REVEAL */
        .rev { opacity:0; transform:translateY(36px); transition:opacity .85s ease, transform .85s ease; }
        .rev.in { opacity:1; transform:translateY(0); }
        .d1 { transition-delay:.12s; } .d2 { transition-delay:.22s; } .d3 { transition-delay:.32s; }

        /* ── RESPONSIVE */
        @media(max-width:992px) {
            .nav-links { display:none; }
            .nav-wrap, .nav-wrap.stuck { padding:0 24px; }
            .account-grid { grid-template-columns:1fr 1fr; }
            .account-wrap { padding:40px 28px 80px; }
            .ft-grid { grid-template-columns:1fr 1fr; gap:36px; }
            .insta-grid { grid-template-columns:repeat(3,1fr); }
        }
        @media(max-width:576px) {
            .account-grid { grid-template-columns:1fr; }
            footer { padding:60px 20px 28px; }
            .ft-grid { grid-template-columns:1fr; }
            .welcome-strip { flex-direction:column; gap:16px; text-align:center; }
        }
    </style>

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
    ?>
    <div class="sc-item">
        <img src="<?php echo $pro[7] ?>" alt="">
        <div>
            <div class="sc-item-name"><?php echo $pro[5] ?></div>
            <div class="sc-item-detail"><?php echo $l[3] ?>× — <span style="color:var(--red2)">DT <?php echo $pro[6] ?></span></div>
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
        <span>✦ Handmade with love</span><span>✦ Elegant bijoux 💖</span>
        <span>✦ Limited pieces 💎</span><span>✦ Discover your sparkle 🌸</span>
        <span>✦ Glowear exclusive ✨</span><span>✦ Small details, big elegance 💫</span>
        <span>✦ Wear your glow 🌙</span><span>✦ Crafted to shine 💝</span>
        <span>✦ Handmade with love</span><span>✦ Elegant bijoux 💖</span>
        <span>✦ Limited pieces 💎</span><span>✦ Discover your sparkle 🌸</span>
        <span>✦ Glowear exclusive ✨</span><span>✦ Small details, big elegance 💫</span>
        <span>✦ Wear your glow 🌙</span><span>✦ Crafted to shine 💝</span>
    </div>
</div>

<!-- Nav -->
<nav class="nav-wrap" id="nav">
    <div class="nav-logo"><a href="index.php"><img src="images/logo.png" alt="Glowear"></a></div>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li class="drop">
            <a href="#">Collections ▾</a>
            <ul class="drop-menu">
                <li><a href="shop.php?nom=Necklaces">Necklaces</a></li>
                <li><a href="shop.php?nom=Earrings">Earrings</a></li>
                <li><a href="shop.php?nom=Bracelets">Bracelets</a></li>
                <li><a href="shop.php?nom=Rings">Rings</a></li>
                <li><a href="shop.php?nom=Sets">Sets</a></li>
            </ul>
        </li>
        <li class="drop">
            <a href="#">Shop ▾</a>
            <ul class="drop-menu">
                <li><a href="cart.php">Cart</a></li>
                <li><a href="checkout.php">Checkout</a></li>
                <li><a href="my-account.php">My Account</a></li>
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
        <a href="my-account.php" style="color:var(--red)"><i class="fa fa-user"></i></a>
        <?php else: ?>
        <a href="client_login.php"><i class="fa fa-user"></i></a>
        <?php endif; ?>
    </div>
</nav>

<!-- Page Hero -->
<div class="page-hero">
    <span class="page-hero-tag">Your Space</span>
    <h1>My <em>Account</em></h1>
    <div class="breadcrumb-row">
        <a href="index.php">Home</a>
        <span>/</span>
        <a href="shop.php">Shop</a>
        <span>/</span>
        <span class="active">My Account</span>
    </div>
</div>

<!-- ══ MAIN CONTENT ══ -->
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
        <a href="#" class="account-card" id="card1">
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
        <a href="#" class="account-card d1" id="card2">
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
        <a href="logout.php" class="account-card logout-card d2" id="card3">
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

    <?php else: ?>

    <!-- Guest State -->
    <div class="guest-banner" id="guestBanner">
        <span class="guest-icon"><i class="fas fa-user-circle"></i></span>
        <div class="guest-bg-num">?</div>
        <h2 class="guest-title">Welcome to <em>Glowear</em></h2>
        <p class="guest-text">Sign in to access your orders, manage your account,<br>and enjoy a personalised shopping experience.</p>
        <a href="client_login.php" class="btn-red">Sign In</a>
        &nbsp;&nbsp;
        <a href="client_signup.php" class="btn-ghost">Create Account</a>
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
        <div class="insta-item rev"><img src="images/instagram-img-01.jpg" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d1"><img src="images/instagram-img-02.jpg" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d2"><img src="images/instagram-img-03.jpg" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d3"><img src="images/instagram-img-04.jpg" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev" style="transition-delay:.4s"><img src="images/instagram-img-05.jpg" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="ft-grid">
        <div>
            <div class="ft-logo"><img src="images/logo.png" alt="Glowear"></div>
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
        <p class="ft-copy">© 2026 Glowear — All rights reserved</p>
        <div class="ft-bottom-links"><a href="#">Terms</a><a href="#">Privacy</a><a href="#">Cookies</a></div>
    </div>
</footer>

<a href="#" id="btt" title="Back to top"><i class="fas fa-chevron-up"></i></a>

<!-- JS -->
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/response-formatter.js"></script>
<script src="js/chatbot.js"></script>

<script>
// ── SMOOTH CURSOR
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

// ── STICKY NAV
window.addEventListener('scroll', () => {
    document.getElementById('nav').classList.toggle('stuck', window.scrollY > 60);
    document.getElementById('btt').style.display = window.scrollY > 400 ? 'block' : 'none';
});

// ── SCROLL REVEAL
const allRev = document.querySelectorAll(
    '.rev, .account-card, .welcome-strip, .guest-banner'
);
const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('in'); obs.unobserve(e.target); }
    });
}, { threshold: 0.10, rootMargin: '0px 0px -40px 0px' });
allRev.forEach(r => obs.observe(r));

// ── SEARCH
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

// ── CART
function toggleCart() {
    document.getElementById('sideCart').classList.toggle('open');
    document.getElementById('cartOv').classList.toggle('open');
}

// ── BACK TO TOP
document.getElementById('btt').addEventListener('click', e => {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
</script>
</body>
</html>