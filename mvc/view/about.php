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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Glowear — About Us</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/chatbot-widget.css">
<link rel="stylesheet" href="css/chatbot-messages.css">
<link rel="stylesheet" href="css/response-format.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --red:     #C0392B;
            --red2:    #E74C3C;
            --red-dim: rgba(192,57,43,0.12);
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
            background:var(--dark); color:var(--cream);
            overflow-x:hidden; cursor:none;
        }

        /* ── CURSOR ── */
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
            background:radial-gradient(circle,rgba(255,255,255,.95) 0%,rgba(255,215,0,.9) 35%,rgba(255,255,255,0) 70%);
            box-shadow:0 0 8px rgba(255,215,0,.8),0 0 16px rgba(255,255,255,.35);
            opacity:0; animation:glitterFall linear forwards; mix-blend-mode:screen;
        }
        @keyframes glitterFall {
            0%  { transform:translateY(0) scale(.6) rotate(0deg); opacity:0; }
            10% { opacity:1; }
            100%{ transform:translateY(120px) scale(1.2) rotate(180deg); opacity:0; }
        }

        /* ── TICKER ── */
        .ticker { background:var(--red); padding:7px 0; overflow:hidden; white-space:nowrap; }
        .ticker-inner {
            display:inline-block; animation:tick 28s linear infinite;
            font-size:10px; font-weight:400; letter-spacing:3px;
            text-transform:uppercase; color:#fff;
        }
        .ticker-inner span { margin:0 36px; }
        @keyframes tick { from{transform:translateX(0)} to{transform:translateX(-50%)} }

        /* ── NAV ── */
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
        .nav-logo { background:rgba(255,255,255,.08); padding:2px 4px; border-radius:4px; display:inline-block; }
        .nav-logo img { height:80px; }
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
        .nav-links .active-link { color:#fff; }
        .nav-links .active-link::after { width:100%; }
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

        /* ── PAGE HERO BANNER ── */
        .page-hero {
            height:55vh; position:relative;
            display:flex; align-items:flex-end;
            padding-bottom:72px; overflow:hidden;
        }
        .page-hero-bg {
            position:absolute; inset:0;
            background:linear-gradient(160deg,#0e0505 0%,#080808 60%,#0a0a0a 100%);
        }
        .page-hero-bg::after {
            content:''; position:absolute; inset:0;
            background:radial-gradient(ellipse at 60% 50%, rgba(192,57,43,.07) 0%, transparent 60%);
        }
        /* Red animated line at bottom */
        .page-hero-line {
            position:absolute; bottom:0; left:0; right:0; height:2px;
            background:linear-gradient(to right, transparent 0%, var(--red) 30%, var(--red) 70%, transparent 100%);
            opacity:.6;
        }
        .page-hero-content {
            position:relative; z-index:2; padding:0 10vw;
        }
        .breadcrumb-bar {
            display:flex; align-items:center; gap:10px;
            font-size:10px; letter-spacing:4px; text-transform:uppercase;
            color:rgba(255,255,255,.3); margin-bottom:20px;
        }
        .breadcrumb-bar a { color:var(--red); text-decoration:none; transition:color .3s; }
        .breadcrumb-bar a:hover { color:var(--red2); }
        .breadcrumb-bar span { color:rgba(255,255,255,.2); }
        .page-hero-title {
            font-family:'Cormorant Garamond', serif;
            font-size:clamp(52px,7vw,100px); font-weight:300;
            color:#fff; line-height:.95;
            opacity:0; animation:up .9s .2s forwards;
        }
        .page-hero-title em { font-style:italic; color:var(--red2); }
        @keyframes up { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:translateY(0)} }
        @keyframes fadeIn { from{opacity:0} to{opacity:1} }

        /* ── ABOUT INTRO ── */
        .about-intro {
            padding:120px 10vw;
            display:grid; grid-template-columns:1fr 1fr; gap:80px; align-items:center;
            background:var(--dark);
        }
        .intro-text {}
        .sec-label {
            font-size:9px; letter-spacing:6px; text-transform:uppercase;
            color:var(--red); margin-bottom:16px; display:block;
        }
        .sec-title {
            font-family:'Cormorant Garamond',serif;
            font-size:clamp(34px,4.5vw,60px); font-weight:300; color:#fff;
            margin-bottom:28px;
        }
        .sec-title em { font-style:italic; color:rgba(192,57,43,.75); }
        .intro-body {
            font-size:13px; font-weight:300; line-height:1.95;
            color:rgba(255,255,255,.45); margin-bottom:40px;
        }
        .btn-red {
            background:var(--red); color:#fff;
            font-size:10px; letter-spacing:3px; text-transform:uppercase;
            padding:15px 38px; text-decoration:none; font-weight:500;
            display:inline-block; transition:background .3s,transform .3s;
        }
        .btn-red:hover { background:var(--red2); transform:translateY(-2px); color:#fff; text-decoration:none; }

        /* Image frame */
        .intro-img-wrap {
            position:relative; height:520px;
        }
        .intro-img-main {
            position:absolute; right:0; top:0; width:82%; height:88%; overflow:hidden;
        }
        .intro-img-main img { width:100%; height:100%; object-fit:cover; filter:brightness(.8); }
        .intro-img-frame {
            position:absolute; left:0; top:24px; width:58%; height:70%;
            border:1px solid rgba(192,57,43,.25); pointer-events:none;
        }
        .intro-img-badge {
            position:absolute; left:-16px; bottom:48px;
            background:var(--red); color:#fff;
            font-family:'Cormorant Garamond',serif;
            font-size:13px; font-style:italic;
            padding:12px 24px; letter-spacing:1px;
        }

        /* ── PILLARS (Trusted/Professional/Expert) ── */
        .pillars-section {
            padding:100px 10vw;
            background:var(--dark2);
        }
        .pillars-head { text-align:center; margin-bottom:72px; }
        .pillars-grid {
            display:grid; grid-template-columns:repeat(3,1fr);
            gap:1px;
            border:1px solid rgba(192,57,43,.1);
        }
        .pillar {
            padding:52px 40px; background:var(--dark3);
            text-align:center; position:relative; overflow:hidden;
            transition:background .35s;
        }
        .pillar::before {
            content:''; position:absolute; top:0; left:0; right:0; height:2px;
            background:var(--red); transform:scaleX(0);
            transform-origin:left; transition:transform .5s ease;
        }
        .pillar:hover { background:#1e1010; }
        .pillar:hover::before { transform:scaleX(1); }
        .pillar-num {
            font-family:'Cormorant Garamond',serif;
            font-size:64px; font-weight:300; line-height:1;
            color:rgba(192,57,43,.06);
            position:absolute; top:16px; right:24px;
            pointer-events:none; user-select:none;
        }
        .pillar-icon {
            font-size:28px; color:var(--red); margin-bottom:22px; display:block;
        }
        .pillar-title {
            font-family:'Cormorant Garamond',serif;
            font-size:26px; font-weight:300; color:#fff; margin-bottom:16px;
        }
        .pillar-text {
            font-size:12px; font-weight:300; color:var(--grey); line-height:1.8;
        }

        /* ── STATS BAR ── */
        .stats-bar {
            padding:72px 10vw;
            background:var(--dark);
            display:grid; grid-template-columns:repeat(4,1fr);
            border-top:1px solid rgba(192,57,43,.12);
            border-bottom:1px solid rgba(192,57,43,.12);
        }
        .stat-item {
            text-align:center; padding:0 20px;
            border-right:1px solid rgba(255,255,255,.04);
        }
        .stat-item:last-child { border-right:none; }
        .stat-n {
            font-family:'Cormorant Garamond',serif;
            font-size:56px; font-weight:300; color:var(--red);
            line-height:1;
        }
        .stat-l {
            font-size:9px; letter-spacing:3px; text-transform:uppercase;
            color:rgba(255,255,255,.3); margin-top:8px;
        }

        /* ── TEAM ── */
        .team-section {
            padding:120px 10vw;
            background:var(--dark2);
        }
        .team-head { margin-bottom:72px; }
        .team-grid {
            display:grid; grid-template-columns:repeat(4,1fr);
            gap:20px;
        }
        .team-card {
            position:relative; overflow:hidden; cursor:none;
            background:var(--dark3);
        }
        .team-card-img {
            width:100%; aspect-ratio:3/4; overflow:hidden; position:relative;
        }
        .team-card-img img {
            width:100%; height:100%; object-fit:cover; object-position:top;
            transition:transform .7s cubic-bezier(.25,.46,.45,.94), filter .5s;
            filter:brightness(.75) saturate(.8);
        }
        .team-card:hover .team-card-img img {
            transform:scale(1.07); filter:brightness(.4) saturate(.6);
        }
        /* overlay on hover */
        .team-card-overlay {
            position:absolute; inset:0;
            background:linear-gradient(to top, rgba(8,8,8,.9) 0%, transparent 50%);
            display:flex; flex-direction:column;
            justify-content:flex-end; padding:24px;
        }
        .team-social {
            display:flex; gap:10px; margin-bottom:12px;
            opacity:0; transform:translateY(10px);
            transition:opacity .4s, transform .4s;
        }
        .team-card:hover .team-social { opacity:1; transform:translateY(0); }
        .team-social a {
            width:32px; height:32px; border:1px solid rgba(255,255,255,.2);
            display:flex; align-items:center; justify-content:center;
            color:rgba(255,255,255,.6); font-size:12px; text-decoration:none;
            transition:all .3s;
        }
        .team-social a:hover { border-color:var(--red); color:var(--red); }
        .team-card-info {
            padding:20px 20px 24px;
        }
        .team-name {
            font-family:'Cormorant Garamond',serif;
            font-size:22px; font-weight:300; color:#fff; margin-bottom:4px;
        }
        .team-role {
            font-size:9px; letter-spacing:3px; text-transform:uppercase;
            color:var(--red);
        }
        /* Red accent bar on hover */
        .team-card::after {
            content:''; position:absolute; bottom:0; left:0; right:0; height:2px;
            background:var(--red); transform:scaleX(0);
            transform-origin:left; transition:transform .4s ease;
        }
        .team-card:hover::after { transform:scaleX(1); }

        /* ── MARQUEE ── */
        .marquee-band {
            background:var(--dark3); overflow:hidden;
            border-top:1px solid rgba(192,57,43,.18);
            border-bottom:1px solid rgba(192,57,43,.18);
            padding:22px 0;
        }
        .marquee-inner {
            display:flex; gap:56px; white-space:nowrap;
            animation:marquee 22s linear infinite;
        }
        .marquee-item {
            display:flex; align-items:center; gap:14px; flex-shrink:0;
            font-family:'Cormorant Garamond',serif;
            font-size:21px; font-style:italic; color:rgba(255,255,255,.18);
        }
        .rdot { width:5px; height:5px; border-radius:50%; background:var(--red); flex-shrink:0; }
        @keyframes marquee { from{transform:translateX(0)} to{transform:translateX(-50%)} }

        /* ── INSTAGRAM ── */
        .insta-sec { padding:110px 0; background:var(--dark); }
        .insta-head { text-align:center; margin-bottom:56px; }
        .insta-handle { font-size:12px; letter-spacing:2px; color:var(--red); display:inline-flex; align-items:center; gap:8px; margin-bottom:20px; }
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

        /* ── SIDE CART ── */
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

        /* ── SEARCH ── */
        .search-ov { display:none; position:fixed; inset:0; background:rgba(0,0,0,.96); z-index:3000; flex-direction:column; align-items:center; justify-content:center; }
        .search-ov.open { display:flex; }
        .search-ov input { background:none; border:none; border-bottom:1px solid rgba(255,255,255,.25); font-family:'Cormorant Garamond',serif; font-size:34px; color:#fff; width:60%; max-width:580px; padding:14px 0; text-align:center; outline:none; }
        .search-ov input::placeholder { color:rgba(255,255,255,.18); }
        .search-ov-close { position:absolute; top:36px; right:56px; font-size:22px; color:rgba(255,255,255,.4); cursor:pointer; transition:color .3s; }
        .search-ov-close:hover { color:var(--red); }

        /* ── SCROLL REVEAL ── */
        .rev { opacity:0; transform:translateY(36px); transition:opacity .85s ease,transform .85s ease; }
        .rev.in { opacity:1; transform:translateY(0); }
        .d1{transition-delay:.1s} .d2{transition-delay:.2s} .d3{transition-delay:.3s} .d4{transition-delay:.4s}

        /* ── BACK TO TOP ── */
        #btt { display:none; position:fixed; bottom:36px; right:36px; width:42px; height:42px; background:var(--red); color:#fff; text-align:center; line-height:42px; font-size:18px; z-index:999; text-decoration:none; transition:background .3s; }
        #btt:hover { background:var(--red2); color:#fff; }

        /* ── RESPONSIVE ── */
        @media(max-width:992px){
            .nav-links { display:none; }
            .nav-wrap, .nav-wrap.stuck { padding:0 24px; }
            .about-intro { grid-template-columns:1fr; }
            .intro-img-wrap { height:340px; }
            .pillars-grid { grid-template-columns:1fr; }
            .stats-bar { grid-template-columns:repeat(2,1fr); gap:40px 0; }
            .team-grid { grid-template-columns:repeat(2,1fr); }
            .ft-grid { grid-template-columns:1fr 1fr; gap:36px; }
            .insta-grid { grid-template-columns:repeat(3,1fr); }
        }
        @media(max-width:576px){
            .about-intro { padding:80px 6vw; }
            .team-grid { grid-template-columns:1fr; }
            footer { padding:60px 20px 28px; }
            .ft-grid { grid-template-columns:1fr; }
            .stats-bar { grid-template-columns:repeat(2,1fr); padding:60px 6vw; }
        }
    </style>

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
    ?>
    <div class="sc-item">
        <img src="<?php echo $pro[7] ?>" alt="">
        <div>
            <div class="sc-item-name"><?php echo $pro[5] ?></div>
            <div class="sc-item-detail"><?php echo $l[3] ?>× — <span style="color:var(--red2)">DT <?php echo $pro[6] ?></span></div>
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
        <li><a href="about.php" class="active-link">About</a></li>
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
        <?php if(isset($_SESSION['id'])): ?>
        <a href="my-account.php"><i class="fa fa-user"></i></a>
        <?php else: ?>
        <a href="client_login.php"><i class="fa fa-user"></i></a>
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
            Driven by a commitment to excellence, we curate a diverse collection of bijoux that cater to your needs — from delicate pearl necklaces to bold gold charm pieces. Our dedication to offering top-notch customer service ensures that your journey with us is nothing short of exceptional.<br><br>
            We believe in the power of craft and intention to transform the way you wear jewelry. At Glowear, we're not just a store; we're your trusted companion in the world of fine bijoux.
        </p>
        <a href="shop.php" class="btn-red rev d3">Explore Collection</a>
    </div>
    <div class="intro-img-wrap rev d2">
        <div class="intro-img-main">
            <img src="images/sun5.png" alt="About Glowear">
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
        <div class="insta-item rev"><img src="images/strass3.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d1"><img src="images/baby3.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d2"><img src="images/baby4.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d3"><img src="images/sun4.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d4"><img src="images/baby6.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
    </div>
</section>

<!-- FOOTER -->
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



<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<?php if(file_exists(__DIR__.'/js/chatbot-widget.js')): ?>
<script src="js/chatbot-widget.js"></script>
<?php endif; ?>

<script>
// ── CURSOR
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

// ── NAV STICK + BACK TO TOP
window.addEventListener('scroll',()=>{
    document.getElementById('nav').classList.toggle('stuck', window.scrollY > 60);
    document.getElementById('btt').style.display = window.scrollY > 320 ? 'block' : 'none';
});

// ── SCROLL REVEAL
const revEls = document.querySelectorAll('.rev');
const obs = new IntersectionObserver(entries=>{
    entries.forEach(e=>{ if(e.isIntersecting){ e.target.classList.add('in'); obs.unobserve(e.target); } });
},{ threshold:.1, rootMargin:'0px 0px -50px 0px' });
revEls.forEach(r=>obs.observe(r));

// ── SEARCH
document.getElementById('searchBtn').addEventListener('click',e=>{
    e.preventDefault();
    document.getElementById('searchOv').classList.add('open');
    document.querySelector('.search-ov input').focus();
});
document.getElementById('searchClose').addEventListener('click',()=>document.getElementById('searchOv').classList.remove('open'));
document.addEventListener('keydown',e=>{ if(e.key==='Escape') document.getElementById('searchOv').classList.remove('open'); });

// ── CART
function toggleCart(){
    document.getElementById('sideCart').classList.toggle('open');
    document.getElementById('cartOv').classList.toggle('open');
}
</script>
</body>
</html>