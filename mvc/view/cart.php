<?php
session_start();
include_once("../controller/ProduitController.php");
include_once("../controller/PannierController.php");
$controllerpannier = new PannierController();
$id = isset($_SESSION['id'])?$_SESSION['id']:null;
$listpannier = $controllerpannier->listpannier($id);
$controller = new ProduitController();
$listpanniercart = $controllerpannier->listpannier($id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Glowear — Your Cart</title>

    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">

    <link rel="stylesheet" href="css/chatbot-widget.css">
    <link rel="stylesheet" href="css/chatbot-messages.css">
    <link rel="stylesheet" href="css/response-format.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════
           DESIGN TOKENS — identical to index.php
        ═══════════════════════════════════════════ */
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
            font-family:'Montserrat',sans-serif;
            background:var(--dark); color:var(--cream);
            overflow-x:hidden; cursor:none;
        }

        /* ── CURSOR ── */
        #cur { position:fixed; pointer-events:none; z-index:9999; width:10px; height:10px; border-radius:50%; background:var(--red); transform:translate(-50%,-50%); transition:width .25s,height .25s; }
        #cur-ring { position:fixed; pointer-events:none; z-index:9998; width:34px; height:34px; border-radius:50%; border:1.5px solid var(--red); transform:translate(-50%,-50%); transition:width .3s,height .3s; opacity:.55; }
        .big-cur #cur { width:20px; height:20px; }
        .big-cur #cur-ring { width:58px; height:58px; border-color:var(--red2); }

        /* ── GRAIN ── */
        .grain { position:fixed; inset:0; pointer-events:none; z-index:9990; opacity:.03; background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E"); background-size:180px; }

        /* ── GLITTER ── */
        #glitter-layer { position:fixed; inset:0; pointer-events:none; z-index:9991; overflow:hidden; }
        .glitter { position:absolute; width:4px; height:4px; border-radius:50%; background:radial-gradient(circle, rgba(255,255,255,.95) 0%, rgba(255,215,0,.9) 35%, rgba(255,255,255,0) 70%); box-shadow:0 0 8px rgba(255,215,0,.8),0 0 16px rgba(255,255,255,.35); opacity:0; animation:glitterFall linear forwards; mix-blend-mode:screen; }
        @keyframes glitterFall { 0%{transform:translateY(0) scale(.6) rotate(0deg);opacity:0} 10%{opacity:1} 100%{transform:translateY(120px) scale(1.2) rotate(180deg);opacity:0} }

        /* ── TICKER ── */
        .ticker { background:var(--red); padding:7px 0; overflow:hidden; white-space:nowrap; }
        .ticker-inner { display:inline-block; animation:tick 28s linear infinite; font-size:10px; font-weight:400; letter-spacing:3px; text-transform:uppercase; color:#fff; }
        .ticker-inner span { margin:0 36px; }
        @keyframes tick { from{transform:translateX(0)} to{transform:translateX(-50%)} }

        /* ── NAV ── */
        .nav-wrap { position:fixed; top:36px; left:0; right:0; z-index:1000; display:flex; align-items:center; justify-content:space-between; padding:0 56px; transition:top .4s,background .4s,backdrop-filter .4s,padding .4s; }
        .nav-wrap.stuck { top:0; padding:14px 56px; background:rgba(8,8,8,.94); backdrop-filter:blur(18px); border-bottom:1px solid rgba(192,57,43,.25); }
        .nav-wrap .nav-logo { background:rgba(255,255,255,.08); padding:2px 4px; border-radius:4px; display:inline-block; }
        .nav-wrap .nav-logo img { height:80px; }
        .nav-links { display:flex; gap:36px; list-style:none; }
        .nav-links a { font-size:11px; letter-spacing:3px; text-transform:uppercase; color:rgba(255,255,255,.65); text-decoration:none; position:relative; transition:color .3s; }
        .nav-links a::after { content:''; position:absolute; bottom:-3px; left:0; width:0; height:1px; background:var(--red); transition:width .3s; }
        .nav-links a:hover, .nav-links a.active { color:#fff; }
        .nav-links a:hover::after, .nav-links a.active::after { width:100%; }
        .nav-links .drop { position:relative; }
        .nav-links .drop-menu { display:none; position:absolute; top:28px; left:-16px; background:rgba(12,12,12,.97); border:1px solid rgba(192,57,43,.2); list-style:none; padding:16px 0; min-width:180px; }
        .nav-links .drop:hover .drop-menu { display:block; }
        .nav-links .drop-menu li a { display:block; padding:8px 20px; font-size:10px; letter-spacing:2px; }
        .nav-icons { display:flex; gap:20px; align-items:center; }
        .nav-icons a { color:rgba(255,255,255,.6); font-size:15px; text-decoration:none; transition:color .3s; }
        .nav-icons a:hover { color:var(--red); }
        .cart-count { background:var(--red); color:#fff; font-size:9px; font-weight:600; width:15px; height:15px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; position:relative; top:-8px; left:-5px; }

        /* ── SIDE CART PANEL ── */
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

        /* ── BUTTONS ── */
        .btn-red { background:var(--red); color:#fff; font-size:10px; letter-spacing:3px; text-transform:uppercase; padding:15px 40px; text-decoration:none; font-weight:500; display:inline-block; transition:background .3s,transform .3s; border:none; cursor:pointer; }
        .btn-red:hover { background:var(--red2); transform:translateY(-2px); color:#fff; text-decoration:none; }
        .btn-ghost { border:1px solid rgba(192,57,43,.45); color:var(--red2); font-size:10px; letter-spacing:3px; text-transform:uppercase; padding:15px 40px; text-decoration:none; font-weight:300; display:inline-block; transition:all .3s; background:none; cursor:pointer; }
        .btn-ghost:hover { border-color:var(--red); background:var(--red-dim); color:var(--red2); text-decoration:none; }

        /* ── PAGE HERO ── */
        .page-hero { height:36vh; min-height:280px; position:relative; display:flex; align-items:flex-end; overflow:hidden; padding-bottom:52px; }
        .page-hero-bg { position:absolute; inset:0; background:radial-gradient(ellipse at 60% 40%, rgba(192,57,43,.09) 0%, transparent 55%), linear-gradient(160deg,#0e0505 0%,#080808 60%,#0a0a0a 100%); }
        .page-hero-lines { position:absolute; inset:0; overflow:hidden; pointer-events:none; }
        .page-hero-lines::before,.page-hero-lines::after { content:''; position:absolute; background:linear-gradient(90deg,transparent,rgba(192,57,43,.18),transparent); height:1px; left:0; right:0; animation:lineSlide 6s ease-in-out infinite alternate; }
        .page-hero-lines::before { top:38%; }
        .page-hero-lines::after  { top:68%; animation-delay:1.8s; }
        @keyframes lineSlide { from{opacity:.2;transform:scaleX(.4)} to{opacity:1;transform:scaleX(1)} }
        .adot { position:absolute; border-radius:50%; background:var(--red); opacity:0; animation:floatPt 7s infinite; }
        @keyframes floatPt { 0%{opacity:0;transform:translateY(0) scale(0)} 20%{opacity:.4} 80%{opacity:.1} 100%{opacity:0;transform:translateY(-80px) scale(1.2)} }
        .page-hero-content { position:relative; z-index:2; padding-left:10vw; }
        .page-hero-tag { display:inline-flex; align-items:center; gap:10px; font-size:10px; letter-spacing:5px; text-transform:uppercase; color:var(--red); margin-bottom:14px; opacity:0; animation:up .9s .3s forwards; }
        .page-hero-tag::before { content:''; width:30px; height:1px; background:var(--red); }
        .page-hero-h1 { font-family:'Cormorant Garamond',serif; font-size:clamp(38px,6.5vw,88px); font-weight:300; line-height:.92; color:#fff; opacity:0; animation:up .9s .5s forwards; }
        .page-hero-h1 em { font-style:italic; color:var(--red2); }
        .hero-breadcrumb { position:absolute; bottom:22px; right:56px; display:flex; align-items:center; gap:10px; font-size:9px; letter-spacing:4px; text-transform:uppercase; color:rgba(255,255,255,.22); opacity:0; animation:fadeIn .9s .8s forwards; }
        .hero-breadcrumb a { color:rgba(255,255,255,.22); text-decoration:none; transition:color .3s; }
        .hero-breadcrumb a:hover { color:var(--red); }
        .hero-breadcrumb .sep { color:var(--red); }
        @keyframes up     { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:translateY(0)} }
        @keyframes fadeIn { from{opacity:0} to{opacity:1} }

        /* ── CART SECTION ── */
        .cart-section { padding:80px 56px 110px; background:var(--dark); }
        .cart-layout { display:grid; grid-template-columns:1fr 340px; gap:32px; align-items:start; }

        /* ── CART TABLE ── */
        .cart-table-wrap { background:var(--dark2); }

        /* table head */
        .cart-thead { display:grid; grid-template-columns:80px 1fr 120px 120px 120px 60px 80px; gap:0; padding:14px 24px; border-bottom:1px solid rgba(192,57,43,.15); }
        .cart-thead span { font-size:8px; letter-spacing:4px; text-transform:uppercase; color:rgba(255,255,255,.3); }

        /* each row */
        .cart-row { display:grid; grid-template-columns:80px 1fr 120px 120px 120px 60px 80px; gap:0; align-items:center; padding:20px 24px; border-bottom:1px solid rgba(255,255,255,.03); transition:background .25s; }
        .cart-row:hover { background:var(--dark3); }

        /* image cell */
        .cart-row .cr-img { width:64px; height:64px; object-fit:cover; display:block; }

        /* name */
        .cr-name { font-family:'Cormorant Garamond',serif; font-size:18px; font-weight:300; color:#fff; text-decoration:none; display:block; transition:color .2s; }
        .cr-name:hover { color:var(--red2); }

        /* price / total */
        .cr-price, .cr-total { font-family:'Cormorant Garamond',serif; font-size:17px; font-weight:300; color:var(--cream); }
        .cr-total { color:var(--red2); }

        /* qty input */
        .cr-qty { width:68px; background:var(--dark3); border:1px solid rgba(255,255,255,.06); color:#fff; font-family:'Montserrat',sans-serif; font-size:12px; font-weight:300; padding:8px 10px; outline:none; text-align:center; -webkit-appearance:none; transition:border-color .2s; }
        .cr-qty:focus { border-color:rgba(192,57,43,.4); }

        /* remove */
        .cr-remove { color:rgba(255,255,255,.2); font-size:14px; text-decoration:none; transition:color .25s,transform .25s; display:flex; align-items:center; justify-content:center; }
        .cr-remove:hover { color:var(--red); transform:scale(1.2); }

        /* modify */
        .cr-modify { display:inline-block; }
        .cr-modify a { display:flex; align-items:center; justify-content:center; width:32px; height:32px; border:1px solid rgba(192,57,43,.3); color:var(--red2); font-size:11px; text-decoration:none; transition:all .25s; }
        .cr-modify a:hover { background:var(--red); border-color:var(--red); color:#fff; }

        /* empty cart state */
        .cart-empty { text-align:center; padding:80px 20px; }
        .cart-empty i { font-size:44px; color:rgba(192,57,43,.25); display:block; margin-bottom:20px; }
        .cart-empty h3 { font-family:'Cormorant Garamond',serif; font-size:32px; font-weight:300; color:rgba(255,255,255,.4); margin-bottom:12px; }
        .cart-empty p { font-size:12px; font-weight:300; color:var(--grey); margin-bottom:28px; }

        /* ── ORDER SUMMARY ── */
        .order-summary { background:var(--dark2); border-left:2px solid rgba(192,57,43,.2); padding:36px 32px; position:sticky; top:110px; }
        .os-title { font-family:'Cormorant Garamond',serif; font-size:26px; font-weight:300; color:#fff; margin-bottom:32px; }
        .os-row { display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid rgba(255,255,255,.04); }
        .os-label { font-size:10px; letter-spacing:3px; text-transform:uppercase; color:var(--grey); }
        .os-value { font-family:'Cormorant Garamond',serif; font-size:18px; font-weight:300; color:var(--cream); }
        .os-divider { height:1px; background:rgba(192,57,43,.18); margin:18px 0; }
        .os-grand-row { display:flex; justify-content:space-between; align-items:center; margin-bottom:28px; }
        .os-grand-label { font-size:10px; letter-spacing:4px; text-transform:uppercase; color:#fff; }
        .os-grand-value { font-family:'Cormorant Garamond',serif; font-size:30px; font-weight:300; color:var(--red2); }
        .os-checkout-btn { display:block; text-align:center; width:100%; margin-bottom:10px; }
        .os-shop-btn { display:block; text-align:center; width:100%; }
        .os-note { font-size:9px; letter-spacing:2px; text-transform:uppercase; color:rgba(255,255,255,.18); text-align:center; margin-top:18px; }

        /* ── MARQUEE ── */
        .marquee-band { background:var(--dark2); overflow:hidden; border-top:1px solid rgba(192,57,43,.18); border-bottom:1px solid rgba(192,57,43,.18); padding:22px 0; }
        .marquee-inner { display:flex; gap:56px; white-space:nowrap; animation:marquee 22s linear infinite; }
        .marquee-item { display:flex; align-items:center; gap:14px; flex-shrink:0; font-family:'Cormorant Garamond',serif; font-size:21px; font-style:italic; color:rgba(255,255,255,.18); }
        .rdot { width:5px; height:5px; border-radius:50%; background:var(--red); flex-shrink:0; }
        @keyframes marquee { from{transform:translateX(0)} to{transform:translateX(-50%)} }

        /* ── INSTAGRAM ── */
        .insta-sec { padding:80px 0; background:var(--dark3); }
        .insta-head { text-align:center; margin-bottom:48px; }
        .insta-handle { font-size:12px; letter-spacing:2px; color:var(--red); display:inline-flex; align-items:center; gap:8px; margin-bottom:14px; }
        .sec-title { font-family:'Cormorant Garamond',serif; font-size:clamp(28px,4vw,54px); font-weight:300; color:#fff; }
        .sec-title em { font-style:italic; color:rgba(192,57,43,.7); }
        .insta-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:3px; padding:0 3px; }
        .insta-item { aspect-ratio:1; overflow:hidden; position:relative; cursor:none; }
        .insta-item img { width:100%; height:100%; object-fit:cover; transition:transform .5s,filter .5s; filter:brightness(.75) saturate(.8); }
        .insta-item:hover img { transform:scale(1.1); filter:brightness(.4) saturate(1.1); }
        .insta-ov { position:absolute; inset:0; background:rgba(192,57,43,.25); display:flex; align-items:center; justify-content:center; opacity:0; transition:opacity .3s; }
        .insta-item:hover .insta-ov { opacity:1; }
        .insta-ov i { font-size:22px; color:#fff; }

        /* ── FOOTER ── */
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

        /* ── SCROLL REVEAL ── */
        .rev { opacity:0; transform:translateY(36px); transition:opacity .85s ease,transform .85s ease; }
        .rev.in { opacity:1; transform:translateY(0); }
        .d1{transition-delay:.1s} .d2{transition-delay:.2s} .d3{transition-delay:.3s}

        /* ── BACK TO TOP ── */
        #btt { display:none; position:fixed; bottom:36px; right:36px; width:42px; height:42px; background:var(--red); color:#fff; text-align:center; line-height:42px; font-size:18px; z-index:999; text-decoration:none; transition:background .3s; }
        #btt:hover { background:var(--red2); color:#fff; }

        /* ── RESPONSIVE ── */
        @media(max-width:1100px){
            .cart-layout { grid-template-columns:1fr; }
            .order-summary { position:static; }
        }
        @media(max-width:992px){
            .nav-links { display:none; }
            .nav-wrap,.nav-wrap.stuck { padding:0 24px; }
            .cart-section { padding:60px 20px 80px; }
            .cart-thead { display:none; }
            .cart-row { grid-template-columns:64px 1fr; grid-template-rows:auto; gap:10px; padding:18px 16px; position:relative; }
            .cart-row .cr-img { width:64px; height:64px; grid-row:1/4; }
            .cr-price,.cr-qty,.cr-total { grid-column:2; font-size:13px; }
            .cr-remove { position:absolute; top:16px; right:16px; }
            .cr-modify { position:absolute; bottom:16px; right:16px; }
            .ft-grid { grid-template-columns:1fr 1fr; gap:36px; }
            .insta-grid { grid-template-columns:repeat(3,1fr); }
            .hero-breadcrumb { right:24px; }
        }
        @media(max-width:576px){
            footer { padding:60px 20px 28px; }
            .ft-grid { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>

<div class="grain"></div>
<div id="glitter-layer"></div>
<div id="cur"></div>
<div id="cur-ring"></div>

<!-- ── SIDE CART PANEL ── -->
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
    ?>
    <div class="sc-item">
        <img src="<?php echo $pro[6] ?>" alt="">
        <div>
            <div class="sc-item-name"><?php echo $pro[4] ?></div>
            <div class="sc-item-detail"><?php echo $l[5] ?>× — <span style="color:var(--red2)">DT <?php echo $pro[6] ?></span></div>
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

<!-- ── SEARCH OVERLAY ── -->
<div id="searchOv" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.96);z-index:3000;flex-direction:column;align-items:center;justify-content:center;">
    <i class="fa fa-times" id="searchClose" style="position:absolute;top:36px;right:56px;font-size:22px;color:rgba(255,255,255,.4);cursor:pointer;"></i>
    <input type="text" placeholder="Search bijoux..." style="background:none;border:none;border-bottom:1px solid rgba(255,255,255,.25);font-family:'Cormorant Garamond',serif;font-size:34px;color:#fff;width:60%;max-width:580px;padding:14px 0;text-align:center;outline:none;">
    <p style="color:rgba(255,255,255,.2);font-size:9px;letter-spacing:4px;text-transform:uppercase;margin-top:20px;">Press Enter</p>
</div>

<!-- ── TICKER ── -->
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

<!-- ── NAV ── -->
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
            <a href="#" class="active">Shop ▾</a>
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
        <?php if(isset($_SESSION['id'])): ?>
        <a href="my-account.php"><i class="fa fa-user"></i></a>
        <?php else: ?>
        <a href="client_login.php"><i class="fa fa-user"></i></a>
        <?php endif; ?>
    </div>
</nav>

<!-- ── PAGE HERO ── -->
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
        <span class="sep">✦</span>
        <a href="shop.php?nom=Necklaces">Shop</a>
        <span class="sep">✦</span>
        <span>Cart</span>
    </div>
</section>

<!-- ── CART SECTION ── -->
<div class="cart-section">
    <div class="cart-layout">

        <!-- LEFT — ITEMS TABLE -->
        <div class="rev">

            <?php
            /* ── Collect rows & total (original logic) ── */
            $totlal = 0;
            $rows   = [];
            while($l = $listpanniercart->fetch()){
                $pro    = $controller->produit($l[2])->fetch();
                $totlal += $l[5];
                $rows[] = ['l' => $l, 'pro' => $pro];
            }
            ?>

            <?php if(empty($rows)): ?>
            <!-- Empty State -->
            <div class="cart-empty">
                <i class="fas fa-gem"></i>
                <h3>Your cart is empty</h3>
                <p>Explore our collections and add your favourite pieces.</p>
                <a href="shop.php?nom=Necklaces" class="btn-red">Explore Collection →</a>
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
                ?>
                <div class="cart-row">
                    <!-- Image -->
                    <a href="#">
                        <img class="cr-img" src="<?php echo $pro[7] ?>" alt="<?php echo htmlspecialchars($pro[5]) ?>">
                    </a>

                    <!-- Name -->
                    <a href="#" class="cr-name"><?php echo htmlspecialchars($pro[5]) ?></a>

                    <!-- Unit price -->
                    <span class="cr-price">DT <?php echo $pro[6] ?></span>

                    <!-- Qty (original input) -->
                    <input type="number" class="cr-qty" value="<?php echo $l[3] ?>" min="0" step="1" size="4">

                    <!-- Row total -->
                    <span class="cr-total">DT <?php echo $l[5] ?></span>

                    <!-- Remove (original href) -->
                    <a class="cr-remove" href="deletepropannier.php?id=<?php echo $l[0] ?>" title="Remove">
                        <i class="fas fa-times"></i>
                    </a>

                    <!-- Modify (original href) -->
                    <div class="cr-modify">
                        <a href="updatepannier.php?id=<?php echo $l[0] ?>" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- RIGHT — ORDER SUMMARY (original session check preserved) -->
        <?php if(isset($_SESSION['id'])): ?>
        <div class="order-summary rev d1">
            <h2 class="os-title">Order Summary</h2>

            <div class="os-row">
                <span class="os-label">Sub Total</span>
                <span class="os-value">DT <?php echo $totlal ?></span>
            </div>
            <div class="os-row">
                <span class="os-label">Shipping</span>
                <span class="os-value" style="color:rgba(39,174,96,.7)">Free</span>
            </div>

            <div class="os-divider"></div>

            <div class="os-grand-row">
                <span class="os-grand-label">Grand Total</span>
                <span class="os-grand-value">DT <?php echo $totlal ?></span>
            </div>

            <a href="checkout.php" class="btn-red os-checkout-btn">Proceed to Checkout →</a>
            <a href="shop.php?nom=Necklaces" class="btn-ghost os-shop-btn">Continue Shopping</a>

            <p class="os-note">✦ Secure checkout · Free returns</p>
        </div>
        <?php endif; ?>

    </div>
</div>
<!-- End Cart -->

<!-- ── MARQUEE ── -->
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

<!-- ── INSTAGRAM ── -->
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

<!-- ── FOOTER ── -->
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

<a href="#" id="btt" title="Back to top">↑</a>

<!-- JS -->
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/chatbot-widget.js"></script>

<script>
/* ── GLITTER ── */
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

/* ── CURSOR ── */
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

/* ── STICKY NAV + BACK TO TOP ── */
window.addEventListener('scroll', () => {
    document.getElementById('nav').classList.toggle('stuck', window.scrollY > 60);
    document.getElementById('btt').style.display = window.scrollY > 400 ? 'block' : 'none';
});

/* ── SCROLL REVEAL ── */
const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('in'); obs.unobserve(e.target); } });
}, { threshold:0.08, rootMargin:'0px 0px -40px 0px' });
document.querySelectorAll('.rev').forEach(r => obs.observe(r));

/* ── CART PANEL ── */
function toggleCart() {
    document.getElementById('sideCart').classList.toggle('open');
    document.getElementById('cartOv').classList.toggle('open');
}

/* ── SEARCH ── */
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