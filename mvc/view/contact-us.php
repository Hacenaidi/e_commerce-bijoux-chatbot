<?php
session_start();
include_once("../controller/ProduitController.php");
include_once("../controller/PannierController.php");
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
    <title>Glowear — Contact Us</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">

    <!-- Keep original CSS for chatbot / response format -->
    <link rel="stylesheet" href="css/chatbot-widget.css">
    <link rel="stylesheet" href="css/chatbot-messages.css">
    <link rel="stylesheet" href="css/response-format.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════
           DESIGN TOKENS  (identical to index.php)
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
            font-family:'Montserrat', sans-serif;
            background:var(--dark);
            color:var(--cream);
            overflow-x:hidden;
            cursor:none;
        }

        /* ── CUSTOM CURSOR ── */
        #cur {
            position:fixed; pointer-events:none; z-index:9999;
            width:10px; height:10px; border-radius:50%;
            background:var(--red); transform:translate(-50%,-50%);
            transition:width .25s,height .25s,background .25s;
        }
        #cur-ring {
            position:fixed; pointer-events:none; z-index:9998;
            width:34px; height:34px; border-radius:50%;
            border:1.5px solid var(--red); transform:translate(-50%,-50%);
            transition:width .3s,height .3s; opacity:.55;
        }
        .big-cur #cur  { width:20px; height:20px; }
        .big-cur #cur-ring { width:58px; height:58px; border-color:var(--red2); }

        /* ── GRAIN ── */
        .grain {
            position:fixed; inset:0; pointer-events:none; z-index:9990; opacity:.03;
            background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-size:180px;
        }

        /* ── GLITTER ── */
        #glitter-layer { position:fixed; inset:0; pointer-events:none; z-index:9991; overflow:hidden; }
        .glitter {
            position:absolute; width:4px; height:4px; border-radius:50%;
            background:radial-gradient(circle, rgba(255,255,255,.95) 0%, rgba(255,215,0,.9) 35%, rgba(255,255,255,0) 70%);
            box-shadow:0 0 8px rgba(255,215,0,.8), 0 0 16px rgba(255,255,255,.35);
            opacity:0; animation:glitterFall linear forwards; mix-blend-mode:screen;
        }
        @keyframes glitterFall {
            0%   { transform:translateY(0) scale(.6) rotate(0deg);   opacity:0; }
            10%  { opacity:1; }
            100% { transform:translateY(120px) scale(1.2) rotate(180deg); opacity:0; }
        }

        /* ── TICKER ── */
        .ticker { background:var(--red); padding:7px 0; overflow:hidden; white-space:nowrap; }
        .ticker-inner {
            display:inline-block;
            animation:tick 28s linear infinite;
            font-size:10px; font-weight:400; letter-spacing:3px; text-transform:uppercase; color:#fff;
        }
        .ticker-inner span { margin:0 36px; }
        @keyframes tick { from{transform:translateX(0)} to{transform:translateX(-50%)} }

        /* ── NAV ── */
        .nav-wrap {
            position:fixed; top:36px; left:0; right:0; z-index:1000;
            display:flex; align-items:center; justify-content:space-between;
            padding:0 56px;
            transition:top .4s,background .4s,backdrop-filter .4s,padding .4s;
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
        .nav-links a.active { color:#fff; }
        .nav-links a.active::after { width:100%; }
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
            background:var(--red); color:#fff;
            font-size:9px; font-weight:600;
            width:15px; height:15px; border-radius:50%;
            display:inline-flex; align-items:center; justify-content:center;
            position:relative; top:-8px; left:-5px;
        }

        /* ── SIDE CART ── */
        .cart-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,.65); z-index:1999; backdrop-filter:blur(5px); }
        .cart-overlay.open { display:block; }
        .side-cart {
            position:fixed; right:-400px; top:0; bottom:0; width:370px;
            background:var(--dark2); border-left:1px solid rgba(192,57,43,.2);
            z-index:2000; padding:36px 28px;
            transition:right .4s cubic-bezier(.25,.46,.45,.94); overflow-y:auto;
        }
        .side-cart.open { right:0; }
        .sc-head { display:flex; justify-content:space-between; align-items:center; margin-bottom:36px; }
        .sc-title { font-family:'Cormorant Garamond',serif; font-size:26px; font-weight:300; color:#fff; }
        .sc-close { background:none; border:none; color:var(--grey); font-size:18px; cursor:pointer; }
        .sc-item { display:flex; gap:14px; padding:14px 0; border-bottom:1px solid rgba(255,255,255,.05); }
        .sc-item img { width:64px; height:64px; object-fit:cover; }
        .sc-item-name { font-family:'Cormorant Garamond',serif; font-size:16px; font-weight:300; color:#fff; }
        .sc-item-detail { font-size:11px; color:var(--grey); margin-top:4px; }
        .sc-total {
            margin-top:28px; padding-top:20px; border-top:1px solid rgba(192,57,43,.2);
            display:flex; justify-content:space-between; align-items:center; margin-bottom:22px;
        }
        .sc-total-label { font-size:10px; letter-spacing:3px; text-transform:uppercase; color:var(--grey); }
        .sc-total-val { font-family:'Cormorant Garamond',serif; font-size:26px; color:var(--red2); }

        /* ── BUTTONS ── */
        .btn-red {
            background:var(--red); color:#fff;
            font-size:10px; letter-spacing:3px; text-transform:uppercase;
            padding:15px 38px; text-decoration:none; font-weight:500;
            display:inline-block; transition:background .3s,transform .3s; border:none; cursor:pointer;
        }
        .btn-red:hover { background:var(--red2); transform:translateY(-2px); color:#fff; text-decoration:none; }
        .btn-ghost {
            border:1px solid rgba(192,57,43,.45); color:var(--red2);
            font-size:10px; letter-spacing:3px; text-transform:uppercase;
            padding:15px 38px; text-decoration:none; font-weight:300;
            display:inline-block; transition:all .3s; background:none; cursor:pointer;
        }
        .btn-ghost:hover { border-color:var(--red); background:var(--red-dim); color:var(--red2); text-decoration:none; }

        /* ── HERO / PAGE HEADER ── */
        .page-hero {
            height:42vh; min-height:340px;
            position:relative; display:flex; align-items:flex-end;
            overflow:hidden; padding-bottom:64px;
        }
        .page-hero-bg {
            position:absolute; inset:0;
            background:radial-gradient(ellipse at 60% 40%, rgba(192,57,43,.09) 0%, transparent 60%),
                        linear-gradient(160deg,#0e0505 0%,#080808 60%,#0a0a0a 100%);
        }
        /* animated red lines decoration */
        .page-hero-lines {
            position:absolute; inset:0; overflow:hidden; pointer-events:none;
        }
        .page-hero-lines::before, .page-hero-lines::after {
            content:''; position:absolute;
            background:linear-gradient(90deg, transparent, rgba(192,57,43,.18), transparent);
            height:1px; left:0; right:0;
            animation:lineSlide 6s ease-in-out infinite alternate;
        }
        .page-hero-lines::before { top:35%; animation-delay:0s; }
        .page-hero-lines::after  { top:65%; animation-delay:1.5s; }
        @keyframes lineSlide { from{opacity:.2;transform:scaleX(.4)} to{opacity:1;transform:scaleX(1)} }
        .page-hero-content { position:relative; z-index:2; padding-left:10vw; }
        .page-hero-tag {
            display:inline-flex; align-items:center; gap:10px;
            font-size:10px; letter-spacing:5px; text-transform:uppercase;
            color:var(--red); margin-bottom:16px;
            opacity:0; animation:up .9s .3s forwards;
        }
        .page-hero-tag::before { content:''; width:30px; height:1px; background:var(--red); }
        .page-hero-h1 {
            font-family:'Cormorant Garamond',serif;
            font-size:clamp(42px,7vw,96px); font-weight:300;
            line-height:.92; color:#fff;
            opacity:0; animation:up .9s .5s forwards;
        }
        .page-hero-h1 em { font-style:italic; color:var(--red2); }
        /* breadcrumb */
        .hero-breadcrumb {
            position:absolute; bottom:28px; right:56px;
            display:flex; align-items:center; gap:10px;
            font-size:9px; letter-spacing:4px; text-transform:uppercase;
            color:rgba(255,255,255,.22);
            opacity:0; animation:fadeIn .9s .8s forwards;
        }
        .hero-breadcrumb a { color:rgba(255,255,255,.22); text-decoration:none; transition:color .3s; }
        .hero-breadcrumb a:hover { color:var(--red); }
        .hero-breadcrumb .sep { color:var(--red); }
        @keyframes up     { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:translateY(0)} }
        @keyframes fadeIn { from{opacity:0} to{opacity:1} }

        /* ── MAIN CONTACT SECTION ── */
        .contact-section {
            padding:100px 56px 120px;
            background:var(--dark);
            display:grid;
            grid-template-columns:1fr 1.4fr;
            gap:80px;
            align-items:start;
        }

        /* ── INFO COLUMN ── */
        .info-col { position:relative; }
        .sec-label {
            font-size:9px; letter-spacing:6px; text-transform:uppercase;
            color:var(--red); margin-bottom:14px; display:block;
        }
        .sec-title {
            font-family:'Cormorant Garamond',serif;
            font-size:clamp(36px,4.5vw,62px); font-weight:300; color:#fff; margin-bottom:30px;
        }
        .sec-title em { font-style:italic; color:rgba(192,57,43,.7); }
        .info-desc {
            font-size:13px; font-weight:300; line-height:1.9;
            color:rgba(255,255,255,.42); margin-bottom:48px;
        }

        /* Contact cards */
        .contact-cards { display:flex; flex-direction:column; gap:2px; }
        .c-card {
            display:flex; align-items:flex-start; gap:20px;
            padding:22px 24px; background:var(--dark2);
            border-left:2px solid transparent;
            transition:background .3s, border-color .3s;
            cursor:none;
        }
        .c-card:hover { background:var(--dark3); border-left-color:var(--red); }
        .c-card-icon {
            width:40px; height:40px; flex-shrink:0;
            background:var(--red-dim); display:flex; align-items:center; justify-content:center;
            font-size:14px; color:var(--red);
            transition:background .3s;
        }
        .c-card:hover .c-card-icon { background:rgba(192,57,43,.3); }
        .c-card-label {
            font-size:8px; letter-spacing:4px; text-transform:uppercase;
            color:var(--grey); margin-bottom:5px;
        }
        .c-card-value {
            font-family:'Cormorant Garamond',serif; font-size:18px; font-weight:300; color:#fff;
        }
        .c-card-value a { color:inherit; text-decoration:none; }
        .c-card-value a:hover { color:var(--red2); }

        /* Decorative number */
        .info-deco-num {
            position:absolute; right:-20px; bottom:-40px;
            font-family:'Cormorant Garamond',serif; font-size:160px; font-weight:300; line-height:1;
            color:rgba(192,57,43,.04); pointer-events:none; user-select:none;
        }

        /* ── FORM COLUMN ── */
        .form-col { }
        .form-header { margin-bottom:40px; }
        .form-header p {
            font-size:13px; font-weight:300; line-height:1.9;
            color:rgba(255,255,255,.38); margin-top:12px;
        }

        .contact-form { display:flex; flex-direction:column; gap:0; }

        .field-wrap { position:relative; margin-bottom:2px; }
        .field-wrap label {
            position:absolute; left:20px; top:50%; transform:translateY(-50%);
            font-size:9px; letter-spacing:4px; text-transform:uppercase;
            color:rgba(255,255,255,.25); pointer-events:none;
            transition:top .25s, font-size .25s, color .25s, transform .25s;
            z-index:1;
        }
        .field-wrap.has-value label,
        .field-wrap input:focus ~ label,
        .field-wrap textarea:focus ~ label {
            top:14px; transform:none; font-size:8px; color:var(--red); letter-spacing:3px;
        }
        /* textarea label adjustment */
        .field-wrap.textarea-wrap label {
            top:20px; transform:none;
        }
        .field-wrap.textarea-wrap.has-value label,
        .field-wrap.textarea-wrap textarea:focus ~ label {
            top:14px;
        }

        .field-wrap input,
        .field-wrap textarea {
            width:100%; background:var(--dark2);
            border:none; border-bottom:1px solid rgba(255,255,255,.06);
            color:#fff; padding:28px 20px 14px;
            font-family:'Montserrat',sans-serif; font-size:13px; font-weight:300;
            outline:none; transition:background .3s, border-color .3s;
            -webkit-appearance:none; resize:none;
        }
        .field-wrap input:focus,
        .field-wrap textarea:focus {
            background:var(--dark3);
            border-bottom-color:var(--red);
        }
        .field-wrap textarea { height:140px; padding-top:34px; }

        /* focus line accent */
        .field-line {
            position:absolute; bottom:0; left:0; width:0; height:2px;
            background:var(--red); transition:width .4s ease;
        }
        .field-wrap input:focus ~ .field-line,
        .field-wrap textarea:focus ~ .field-line { width:100%; }

        .form-submit-row {
            display:flex; align-items:center; justify-content:space-between;
            margin-top:4px; padding:28px 20px 24px; background:var(--dark2);
        }
        .form-note {
            font-size:9px; letter-spacing:2px; text-transform:uppercase;
            color:rgba(255,255,255,.2);
        }
        #submit {
            padding:14px 40px; font-size:10px; letter-spacing:3px;
            display:inline-flex; align-items:center; gap:10px;
        }
        #submit .btn-arrow { transition:transform .3s; }
        #submit:hover .btn-arrow { transform:translateX(4px); }

        /* success / error msg */
        #msgSubmit {
            font-size:11px; letter-spacing:2px; text-transform:uppercase;
            padding:12px 20px; text-align:left;
        }
        #msgSubmit.success { background:rgba(39,174,96,.12); color:#27ae60; border-left:2px solid #27ae60; }
        #msgSubmit.error   { background:rgba(192,57,43,.12); color:var(--red2); border-left:2px solid var(--red); }
        #msgSubmit.hidden  { display:none; }

        /* ── DIVIDER BAND ── */
        .marquee-band {
            background:var(--dark2); overflow:hidden;
            border-top:1px solid rgba(192,57,43,.18);
            border-bottom:1px solid rgba(192,57,43,.18);
            padding:22px 0;
        }
        .marquee-inner { display:flex; gap:56px; white-space:nowrap; animation:marquee 22s linear infinite; }
        .marquee-item {
            display:flex; align-items:center; gap:14px; flex-shrink:0;
            font-family:'Cormorant Garamond',serif;
            font-size:21px; font-style:italic; color:rgba(255,255,255,.18);
        }
        .rdot { width:5px; height:5px; border-radius:50%; background:var(--red); flex-shrink:0; }
        @keyframes marquee { from{transform:translateX(0)} to{transform:translateX(-50%)} }

        /* ── MAP / AMBIENT STRIP ── */
        .ambient-strip {
            height:320px; position:relative; overflow:hidden;
            background:var(--dark3);
            border-top:1px solid rgba(192,57,43,.08);
        }
        .ambient-strip-inner {
            position:absolute; inset:0;
            background:
                radial-gradient(ellipse at 30% 50%, rgba(192,57,43,.07) 0%, transparent 55%),
                radial-gradient(ellipse at 75% 60%, rgba(201,168,76,.04) 0%, transparent 45%);
            display:flex; align-items:center; justify-content:center;
        }
        .ambient-addr {
            text-align:center;
            opacity:0; animation:fadeIn 1s .3s forwards;
        }
        .ambient-addr .addr-icon {
            font-size:28px; color:var(--red); margin-bottom:18px; display:block;
            animation:pulse 3s ease-in-out infinite;
        }
        @keyframes pulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.12)} }
        .ambient-addr h3 {
            font-family:'Cormorant Garamond',serif; font-size:32px; font-weight:300;
            color:#fff; margin-bottom:10px;
        }
        .ambient-addr p {
            font-size:11px; letter-spacing:3px; text-transform:uppercase;
            color:rgba(255,255,255,.3);
        }
        /* floating dots */
        .adot { position:absolute; border-radius:50%; background:var(--red); opacity:0; animation:floatPt 7s infinite; }
        @keyframes floatPt {
            0%{opacity:0;transform:translateY(0) scale(0)}
            20%{opacity:.4} 80%{opacity:.1}
            100%{opacity:0;transform:translateY(-80px) scale(1.2)}
        }

        /* ── FOOTER ── */
        footer { background:#040404; border-top:1px solid rgba(192,57,43,.18); padding:80px 56px 36px; }
        .ft-grid { display:grid; grid-template-columns:1.5fr 1fr 1fr 1fr; gap:56px; margin-bottom:56px; }
        .ft-logo img { height:44px; margin-bottom:18px; }
        .ft-desc { font-size:12px; font-weight:300; color:var(--grey); line-height:1.8; margin-bottom:22px; }
        .ft-social { display:flex; gap:12px; }
        .ft-social a {
            width:34px; height:34px; border:1px solid rgba(255,255,255,.08);
            display:flex; align-items:center; justify-content:center;
            color:var(--grey); font-size:12px; text-decoration:none; transition:all .3s;
        }
        .ft-social a:hover { border-color:var(--red); color:var(--red); }
        .ft-col-title { font-size:9px; letter-spacing:4px; text-transform:uppercase; color:#fff; margin-bottom:22px; font-weight:500; }
        .ft-links { list-style:none; }
        .ft-links li { margin-bottom:10px; }
        .ft-links a { font-size:12px; font-weight:300; color:var(--grey); text-decoration:none; transition:color .3s; }
        .ft-links a:hover { color:var(--red); }
        .ft-contact { font-size:12px; font-weight:300; color:var(--grey); display:flex; flex-direction:column; gap:14px; }
        .ft-contact span { display:flex; gap:10px; align-items:flex-start; }
        .ft-contact i { color:var(--red); margin-top:2px; }
        .ft-bottom {
            border-top:1px solid rgba(255,255,255,.04); padding-top:28px;
            display:flex; justify-content:space-between; align-items:center;
        }
        .ft-copy { font-size:10px; letter-spacing:2px; color:rgba(255,255,255,.18); }
        .ft-bottom-links { display:flex; gap:20px; }
        .ft-bottom-links a { font-size:10px; letter-spacing:1px; color:rgba(255,255,255,.18); text-decoration:none; transition:color .3s; }
        .ft-bottom-links a:hover { color:var(--red); }

        /* ── SCROLL REVEAL ── */
        .rev { opacity:0; transform:translateY(36px); transition:opacity .85s ease,transform .85s ease; }
        .rev.in { opacity:1; transform:translateY(0); }
        .d1{transition-delay:.1s} .d2{transition-delay:.2s} .d3{transition-delay:.3s}

        /* ── BACK TO TOP ── */
        #btt {
            display:none; position:fixed; bottom:36px; right:36px;
            width:42px; height:42px; background:var(--red); color:#fff;
            text-align:center; line-height:42px; font-size:18px;
            z-index:999; text-decoration:none; transition:background .3s;
        }
        #btt:hover { background:var(--red2); color:#fff; }

        /* ── RESPONSIVE ── */
        @media(max-width:992px){
            .nav-links { display:none; }
            .nav-wrap,.nav-wrap.stuck { padding:0 24px; }
            .contact-section { grid-template-columns:1fr; gap:56px; padding:80px 28px; }
            .ft-grid { grid-template-columns:1fr 1fr; gap:36px; }
            .hero-breadcrumb { right:24px; }
            .page-hero-content { padding-left:6vw; }
        }
        @media(max-width:576px){
            footer { padding:60px 20px 28px; }
            .ft-grid { grid-template-columns:1fr; }
            .form-submit-row { flex-direction:column; gap:16px; align-items:flex-start; }
        }
    </style>
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
    ?>
    <div class="sc-item">
        <img src="<?php echo $pro[7] ?>" alt="">
        <div>
            <div class="sc-item-name"><?php echo $pro[5] ?></div>
            <div class="sc-item-detail"><?php echo $l[3] ?>× — <span style="color:var(--red2)">DT <?php echo $pro[6] ?></span></div>
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
        <li><a href="contact-us.php" class="active">Contact</a></li>
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

<!-- Search overlay -->
<div class="search-ov" id="searchOv" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.96);z-index:3000;flex-direction:column;align-items:center;justify-content:center;">
    <i class="fa fa-times" id="searchClose" style="position:absolute;top:36px;right:56px;font-size:22px;color:rgba(255,255,255,.4);cursor:pointer;transition:color .3s;"></i>
    <input type="text" placeholder="Search bijoux..." style="background:none;border:none;border-bottom:1px solid rgba(255,255,255,.25);font-family:'Cormorant Garamond',serif;font-size:34px;color:#fff;width:60%;max-width:580px;padding:14px 0;text-align:center;outline:none;">
    <p style="color:rgba(255,255,255,.2);font-size:9px;letter-spacing:4px;text-transform:uppercase;margin-top:20px;">Press Enter</p>
</div>

<!-- ── PAGE HERO ─────────────────────────────── -->
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
        <span class="sep">✦</span>
        <span>Contact Us</span>
    </div>
</section>


<!-- ── MAIN CONTACT AREA ────────────────────── -->
<section class="contact-section">

    <!-- LEFT — INFO COLUMN -->
    <div class="info-col rev">
        <span class="sec-label">Contact Info</span>
        <h2 class="sec-title">Let's <em>Connect</em></h2>
        <p class="info-desc">
            Welcome to Glowear! We're passionate about providing a seamless and enjoyable shopping experience. Whether it's regarding an order, a product inquiry, or simply a kind word — our team is just a message away.
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

    <!-- RIGHT — FORM COLUMN -->
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
                    Send Message <span class="btn-arrow">→</span>
                </button>
            </div>
            <div id="msgSubmit" class="hidden"></div>
        </form>
    </div>

</section>

<!-- ── MARQUEE BAND ──────────────────────────── -->
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

<!-- ── AMBIENT STRIP ─────────────────────────── -->
<div class="ambient-strip">
    <div class="adot" style="width:3px;height:3px;left:10%;bottom:20%;animation-delay:0s;animation-duration:8s"></div>
    <div class="adot" style="width:2px;height:2px;left:45%;bottom:40%;animation-delay:2s;animation-duration:6s"></div>
    <div class="adot" style="width:4px;height:4px;left:78%;bottom:30%;animation-delay:1s;animation-duration:9s"></div>
    <div class="ambient-strip-inner">
        <div class="ambient-addr">
            <i class="fas fa-map-marker-alt addr-icon"></i>
            <h3>Nabeul, Tunisia</h3>
            <p>Mrezgua · +216 56 725 104</p>
        </div>
    </div>
</div>

<!-- ── FOOTER ────────────────────────────────── -->
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


<!-- JS -->
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!-- Chatbot scripts (original) -->

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

/* ── STICKY NAV ── */
window.addEventListener('scroll', () => {
    document.getElementById('nav').classList.toggle('stuck', window.scrollY > 60);
    document.getElementById('btt').style.display = window.scrollY > 400 ? 'block' : 'none';
});

/* ── SCROLL REVEAL ── */
const revEls = document.querySelectorAll('.rev');
const obs = new IntersectionObserver(entries => {
    entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('in'); obs.unobserve(e.target); } });
}, { threshold:0.12, rootMargin:'0px 0px -50px 0px' });
revEls.forEach(r => obs.observe(r));

/* ── FLOATING LABEL (has-value state) ── */
document.querySelectorAll('.field-wrap input, .field-wrap textarea').forEach(el => {
    const wrap = el.closest('.field-wrap');
    el.addEventListener('input', () => {
        wrap.classList.toggle('has-value', el.value.length > 0);
    });
});

/* ── SIDE CART ── */
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

/* ── CONTACT FORM (original logic preserved) ── */
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