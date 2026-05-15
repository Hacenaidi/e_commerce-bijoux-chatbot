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
    <title>Glowear — Our Services</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/bootstrap.min.css">
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
        .nav-links a:hover, .nav-links .active-link { color:#fff; }
        .nav-links a:hover::after, .nav-links .active-link::after { width:100%; }
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

        /* ── BUTTONS ── */
        .btn-red {
            background:var(--red); color:#fff;
            font-size:10px; letter-spacing:3px; text-transform:uppercase;
            padding:15px 38px; text-decoration:none; font-weight:500;
            display:inline-block; transition:background .3s,transform .3s;
        }
        .btn-red:hover { background:var(--red2); transform:translateY(-2px); color:#fff; text-decoration:none; }
        .btn-ghost {
            border:1px solid rgba(192,57,43,.45); color:var(--red2);
            font-size:10px; letter-spacing:3px; text-transform:uppercase;
            padding:15px 38px; text-decoration:none; font-weight:300;
            display:inline-block; transition:all .3s;
        }
        .btn-ghost:hover { border-color:var(--red); background:var(--red-dim); color:var(--red2); text-decoration:none; }

        /* ── PAGE HERO BANNER ── */
        .page-hero {
            height:60vh; position:relative;
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
        /* animated floating jewel shapes */
        .hero-shape {
            position:absolute; border-radius:50%;
            border:1px solid rgba(192,57,43,.15);
            pointer-events:none; animation:shapePulse 6s ease-in-out infinite;
        }
        @keyframes shapePulse {
            0%,100%{ transform:scale(1); opacity:.3; }
            50%{ transform:scale(1.08); opacity:.6; }
        }
        .page-hero-line {
            position:absolute; bottom:0; left:0; right:0; height:2px;
            background:linear-gradient(to right, transparent 0%, var(--red) 30%, var(--red) 70%, transparent 100%);
            opacity:.6;
        }
        .page-hero-content { position:relative; z-index:2; padding:0 10vw; }
        .breadcrumb-bar {
            display:flex; align-items:center; gap:10px;
            font-size:10px; letter-spacing:4px; text-transform:uppercase;
            color:rgba(255,255,255,.3); margin-bottom:20px;
            opacity:0; animation:up .7s .2s forwards;
        }
        .breadcrumb-bar a { color:var(--red); text-decoration:none; transition:color .3s; }
        .breadcrumb-bar a:hover { color:var(--red2); }
        .breadcrumb-bar span { color:rgba(255,255,255,.2); }
        .page-hero-title {
            font-family:'Cormorant Garamond',serif;
            font-size:clamp(52px,7vw,100px); font-weight:300;
            color:#fff; line-height:.95;
            opacity:0; animation:up .9s .3s forwards;
        }
        .page-hero-title em { font-style:italic; color:var(--red2); }
        @keyframes up { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:translateY(0)} }

        /* ── SHARED LABELS ── */
        .sec-label {
            font-size:9px; letter-spacing:6px; text-transform:uppercase;
            color:var(--red); margin-bottom:14px; display:block;
        }
        .sec-title {
            font-family:'Cormorant Garamond',serif;
            font-size:clamp(34px,4.5vw,62px); font-weight:300; color:#fff;
        }
        .sec-title em { font-style:italic; color:rgba(192,57,43,.75); }

        /* ── CORE PILLARS (Mission/Vision/Philosophy) ── */
        .pillars-section { padding:120px 10vw; background:var(--dark); }
        .pillars-head { margin-bottom:72px; text-align:center; }
        .mvp-grid {
            display:grid; grid-template-columns:repeat(3,1fr);
            gap:20px; margin-bottom:0;
        }
        .mvp-card {
            position:relative; padding:52px 36px 44px; overflow:hidden;
            background:var(--dark2); cursor:none;
            border:1px solid rgba(192,57,43,.07);
            transition:background .35s, border-color .35s;
        }
        .mvp-card::before {
            content:''; position:absolute; top:0; left:0; right:0; height:3px;
            background:var(--red); transform:scaleX(0); transform-origin:left;
            transition:transform .5s ease;
        }
        .mvp-card:hover { background:#150a0a; border-color:rgba(192,57,43,.18); }
        .mvp-card:hover::before { transform:scaleX(1); }
        .mvp-big-num {
            font-family:'Cormorant Garamond',serif;
            font-size:80px; font-weight:300; line-height:1;
            color:rgba(192,57,43,.05);
            position:absolute; top:12px; right:20px;
            pointer-events:none; user-select:none;
        }
        .mvp-icon { font-size:30px; color:var(--red); margin-bottom:24px; display:block; }
        .mvp-title {
            font-family:'Cormorant Garamond',serif;
            font-size:28px; font-weight:300; color:#fff; margin-bottom:16px;
        }
        .mvp-text { font-size:12px; font-weight:300; color:var(--grey); line-height:1.85; }

        /* ── CINEMATIC SERVICE STRIP ── */
        /* Full-width horizontal film strip */
        .service-strip {
            padding:100px 0; background:var(--dark3); overflow:hidden;
        }
        .strip-head { padding:0 10vw; margin-bottom:72px; }
        .strip-track-wrap {
            overflow-x:auto; padding:0 10vw 32px;
            scrollbar-width:thin; scrollbar-color:var(--red) transparent;
            scroll-snap-type:x mandatory; cursor:grab;
        }
        .strip-track-wrap:active { cursor:grabbing; }
        .strip-track-wrap::-webkit-scrollbar { height:2px; }
        .strip-track-wrap::-webkit-scrollbar-thumb { background:var(--red); }
        .strip-track { display:flex; gap:24px; width:max-content; }
        .svc-card {
            width:340px; flex-shrink:0; scroll-snap-align:start;
            background:var(--dark2); border:1px solid rgba(192,57,43,.08);
            padding:44px 36px; position:relative; overflow:hidden;
            transition:background .35s, border-color .35s; cursor:none;
        }
        .svc-card::after {
            content:''; position:absolute; bottom:0; left:0; right:0; height:2px;
            background:var(--red); transform:scaleX(0); transform-origin:left;
            transition:transform .5s ease;
        }
        .svc-card:hover { background:#140909; border-color:rgba(192,57,43,.2); }
        .svc-card:hover::after { transform:scaleX(1); }
        .svc-card-num {
            font-family:'Cormorant Garamond',serif;
            font-size:72px; font-weight:300; line-height:1;
            color:rgba(192,57,43,.05);
            position:absolute; top:16px; right:20px;
            pointer-events:none; user-select:none;
        }
        .svc-icon { font-size:28px; color:var(--red); margin-bottom:24px; display:block; }
        .svc-title {
            font-family:'Cormorant Garamond',serif;
            font-size:24px; font-weight:300; color:#fff; margin-bottom:14px;
        }
        .svc-text { font-size:12px; font-weight:300; color:var(--grey); line-height:1.85; }
        .drag-hint {
            font-size:10px; letter-spacing:3px; text-transform:uppercase;
            color:var(--grey); display:flex; align-items:center; gap:10px;
        }
        .drag-hint::after { content:'→'; color:var(--red); }

        /* ── PROCESS SECTION ── */
        .process-section { padding:120px 10vw; background:var(--dark2); }
        .process-head { margin-bottom:80px; }
        .process-steps {
            display:grid; grid-template-columns:repeat(4,1fr);
            gap:0; position:relative;
        }
        /* connecting line */
        .process-steps::before {
            content:''; position:absolute;
            top:36px; left:10%; right:10%; height:1px;
            background:linear-gradient(to right, transparent, rgba(192,57,43,.35) 20%, rgba(192,57,43,.35) 80%, transparent);
            z-index:0;
        }
        .process-step {
            text-align:center; padding:0 24px; position:relative; z-index:1;
        }
        .step-circle {
            width:72px; height:72px; border-radius:50%;
            border:1px solid rgba(192,57,43,.35);
            background:var(--dark2);
            display:flex; align-items:center; justify-content:center;
            margin:0 auto 24px;
            font-family:'Cormorant Garamond',serif;
            font-size:22px; color:var(--red);
            transition:all .4s;
        }
        .process-step:hover .step-circle {
            background:var(--red); color:#fff;
            border-color:var(--red);
            box-shadow:0 0 24px rgba(192,57,43,.4);
        }
        .step-title {
            font-family:'Cormorant Garamond',serif;
            font-size:20px; font-weight:300; color:#fff; margin-bottom:10px;
        }
        .step-text { font-size:11px; font-weight:300; color:var(--grey); line-height:1.7; }

        /* ── WHY GLOWEAR (full-width dark panel) ── */
        .why-section {
            padding:0; background:var(--dark);
            display:grid; grid-template-columns:1fr 1fr;
            min-height:60vh;
        }
        .why-img {
            position:relative; overflow:hidden; min-height:480px;
        }
        .why-img img {
            width:100%; height:100%; object-fit:cover;
            filter:brightness(.45) saturate(.7);
            transition:filter .6s, transform .6s;
        }
        .why-section:hover .why-img img { filter:brightness(.55) saturate(.85); transform:scale(1.04); }
        .why-img::after {
            content:''; position:absolute; inset:0;
            background:linear-gradient(to right, transparent 50%, var(--dark));
        }
        .why-content {
            padding:80px 8vw 80px 6vw;
            display:flex; flex-direction:column; justify-content:center;
        }
        .why-list { list-style:none; margin-top:36px; display:flex; flex-direction:column; gap:20px; }
        .why-item {
            display:flex; align-items:flex-start; gap:18px;
            padding:20px 24px;
            background:rgba(255,255,255,.02);
            border:1px solid rgba(192,57,43,.07);
            transition:border-color .3s, background .3s;
        }
        .why-item:hover { border-color:rgba(192,57,43,.22); background:rgba(192,57,43,.04); }
        .why-item-icon { font-size:18px; color:var(--red); margin-top:2px; flex-shrink:0; }
        .why-item-title {
            font-family:'Cormorant Garamond',serif;
            font-size:19px; font-weight:300; color:#fff; margin-bottom:4px;
        }
        .why-item-text { font-size:11px; font-weight:300; color:var(--grey); line-height:1.7; }

        /* ── PROMISE BANNER (cinematic full-width quote) ── */
        .promise-banner {
            padding:120px 10vw; background:var(--dark3);
            text-align:center; position:relative; overflow:hidden;
        }
        .promise-banner::before {
            content:''; position:absolute; top:0; left:0; right:0; height:2px;
            background:linear-gradient(to right, transparent, var(--red) 40%, var(--red) 60%, transparent);
            opacity:.5;
        }
        .promise-banner::after {
            content:''; position:absolute; bottom:0; left:0; right:0; height:2px;
            background:linear-gradient(to right, transparent, var(--red) 40%, var(--red) 60%, transparent);
            opacity:.5;
        }
        .promise-quote {
            font-family:'Cormorant Garamond',serif;
            font-size:clamp(28px,4vw,56px); font-weight:300;
            color:#fff; line-height:1.25; margin-bottom:32px;
            max-width:900px; margin-left:auto; margin-right:auto;
        }
        .promise-quote em { font-style:italic; color:var(--red2); }
        .promise-sub {
            font-size:11px; letter-spacing:4px; text-transform:uppercase;
            color:rgba(255,255,255,.3); margin-bottom:48px;
        }
        /* big faded background text */
        .promise-bg-text {
            position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);
            font-family:'Cormorant Garamond',serif;
            font-size:200px; font-weight:300; line-height:1;
            color:rgba(192,57,43,.03); pointer-events:none; user-select:none;
            white-space:nowrap;
        }

        /* ── STATS ── */
        .stats-bar {
            padding:72px 10vw; background:var(--dark);
            display:grid; grid-template-columns:repeat(4,1fr);
            border-top:1px solid rgba(192,57,43,.12);
            border-bottom:1px solid rgba(192,57,43,.12);
        }
        .stat-item { text-align:center; padding:0 20px; border-right:1px solid rgba(255,255,255,.04); }
        .stat-item:last-child { border-right:none; }
        .stat-n { font-family:'Cormorant Garamond',serif; font-size:56px; font-weight:300; color:var(--red); line-height:1; }
        .stat-l { font-size:9px; letter-spacing:3px; text-transform:uppercase; color:rgba(255,255,255,.3); margin-top:8px; }

        /* ── MARQUEE ── */
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

        /* ── CTA SECTION ── */
        .cta-section {
            padding:120px 10vw; background:var(--dark);
            display:grid; grid-template-columns:1fr auto;
            gap:60px; align-items:center;
        }
        .cta-title {
            font-family:'Cormorant Garamond',serif;
            font-size:clamp(36px,5vw,68px); font-weight:300; color:#fff;
        }
        .cta-title em { font-style:italic; color:var(--red2); }
        .cta-sub { font-size:13px; font-weight:300; color:var(--grey); margin-top:12px; line-height:1.7; max-width:480px; }
        .cta-btns { display:flex; gap:16px; flex-direction:column; align-items:flex-end; }

        /* ── INSTAGRAM ── */
        .insta-sec { padding:110px 0; background:var(--dark3); }
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
            .nav-wrap,.nav-wrap.stuck { padding:0 24px; }
            .mvp-grid { grid-template-columns:1fr; }
            .process-steps { grid-template-columns:repeat(2,1fr); gap:40px; }
            .process-steps::before { display:none; }
            .why-section { grid-template-columns:1fr; }
            .why-img { min-height:280px; }
            .stats-bar { grid-template-columns:repeat(2,1fr); gap:40px 0; }
            .cta-section { grid-template-columns:1fr; }
            .cta-btns { align-items:flex-start; flex-direction:row; }
            .ft-grid { grid-template-columns:1fr 1fr; gap:36px; }
            .insta-grid { grid-template-columns:repeat(3,1fr); }
            .pillars-section,.process-section { padding:80px 6vw; }
        }
        @media(max-width:576px){
            .page-hero { height:50vh; }
            .process-steps { grid-template-columns:1fr; }
            .stats-bar { grid-template-columns:repeat(2,1fr); padding:60px 6vw; }
            footer { padding:60px 20px 28px; }
            .ft-grid { grid-template-columns:1fr; }
            .cta-section { padding:80px 6vw; }
        }
    </style>

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
        <a href="my-account.php"><i class="fa fa-user"></i></a>
        <?php else: ?>
        <a href="client_login.php"><i class="fa fa-user"></i></a>
        <?php endif; ?>
    </div>
</nav>

<!-- ══ PAGE HERO ══ -->
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

<!-- ══ CORE PILLARS — Mission / Vision / Philosophy ══ -->
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
            <p class="mvp-text">At Glowear, our mission is to redefine bijoux retailing by offering more than just jewelry. We strive to curate a shopping experience that transcends mere transactions — blending craft and elegance to empower individuals to express their unique style effortlessly and authentically.</p>
        </div>
        <div class="mvp-card rev d1">
            <div class="mvp-big-num">02</div>
            <span class="mvp-icon"><i class="fas fa-eye"></i></span>
            <h3 class="mvp-title">Our Vision</h3>
            <p class="mvp-text">Our vision is to be the ultimate destination for bijoux enthusiasts. We envision a space that seamlessly integrates the latest trends, personalized recommendations, and an inclusive community — a fashion hub that inspires confidence and celebrates individual beauty.</p>
        </div>
        <div class="mvp-card rev d2">
            <div class="mvp-big-num">03</div>
            <span class="mvp-icon"><i class="fas fa-leaf"></i></span>
            <h3 class="mvp-title">Our Philosophy</h3>
            <p class="mvp-text">At Glowear, our philosophy is rooted in a passion for jewelry, innovation, and authenticity. We believe in offering not just bijoux but a reflection of individuality and self-expression — with commitment to ethical sourcing, quality craftsmanship, and genuine customer satisfaction.</p>
        </div>
    </div>
</section>

<!-- ══ HORIZONTAL SERVICE CARDS ══ -->
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
                <p class="svc-text">At Glowear, professionalism defines every aspect of our service. Our team consists of jewelry enthusiasts dedicated to offering a seamless shopping experience — from presenting stunning collections to providing prompt and personalized customer support.</p>
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
                <p class="svc-text">Every Glowear order arrives in our signature gift box — wrapped with care, ready to surprise. Whether it's a gift for someone you love or a treat for yourself, we make the unboxing experience as beautiful as the jewelry inside.</p>
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
                <p class="svc-text">Not in love with your order? No problem. We offer hassle-free returns within 14 days. Your satisfaction is our absolute priority, and we're always here to make things right — quickly and without the drama.</p>
            </div>

        </div>
    </div>
</section>

<!-- ══ PROCESS ══ -->
<section class="process-section">
    <div class="process-head rev">
        <span class="sec-label">How It Works</span>
        <h2 class="sec-title">Your Journey <em>With Us</em></h2>
    </div>
    <div class="process-steps">
        <div class="process-step rev">
            <div class="step-circle">01</div>
            <h3 class="step-title">Browse</h3>
            <p class="step-text">Explore our curated collections of handcrafted bijoux — necklaces, earrings, bracelets and more.</p>
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

<!-- ══ WHY GLOWEAR ══ -->
<section class="why-section">
    <div class="why-img">
        <img src="images/strass2.png" alt="Why Glowear">
    </div>
    <div class="why-content">
        <span class="sec-label rev">Why Choose Us</span>
        <h2 class="sec-title rev d1">More Than <em>Jewelry</em></h2>
        <ul class="why-list">
            <li class="why-item rev d1">
                <i class="fas fa-gem why-item-icon"></i>
                <div>
                    <div class="why-item-title">100% Handcrafted</div>
                    <p class="why-item-text">Every single piece is made by hand with attention to detail. No mass production — just pure craft and passion.</p>
                </div>
            </li>
            <li class="why-item rev d2">
                <i class="fas fa-star why-item-icon"></i>
                <div>
                    <div class="why-item-title">Limited Collections</div>
                    <p class="why-item-text">Our pieces are exclusive and limited. Once they're gone, they're gone — own something truly rare.</p>
                </div>
            </li>
            <li class="why-item rev d3">
                <i class="fas fa-heart why-item-icon"></i>
                <div>
                    <div class="why-item-title">Made with Love</div>
                    <p class="why-item-text">From our hands to your skin — every jewelry carries the warmth and passion of the person who created it.</p>
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

<!-- ══ PROMISE BANNER ══ -->
<section class="promise-banner">
    <div class="promise-bg-text">Glowear</div>
    <p class="promise-sub rev">Our Commitment</p>
    <h2 class="promise-quote rev d1">
        "Every jewelry we create is a <em>promise</em> —<br>
        a promise of quality, of beauty,<br>
        of the glow within you."
    </h2>
    <a href="shop.php" class="btn-red rev d2">Shop the Collection</a>
</section>

<!-- ══ STATS ══ -->
<div class="stats-bar">
    <div class="stat-item rev"><div class="stat-n">200+</div><div class="stat-l">Unique Pieces</div></div>
    <div class="stat-item rev d1"><div class="stat-n">3K+</div><div class="stat-l">Happy Clients</div></div>
    <div class="stat-item rev d2"><div class="stat-n">14</div><div class="stat-l">Day Returns</div></div>
    <div class="stat-item rev d3"><div class="stat-n">100%</div><div class="stat-l">Handcrafted</div></div>
</div>

<!-- ══ MARQUEE ══ -->
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

<!-- ══ CTA ══ -->
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

<!-- ══ INSTAGRAM ══ -->
<section class="insta-sec">
    <div class="insta-head rev">
        <p class="insta-handle"><i class="fab fa-instagram"></i> @glowear</p>
        <h2 class="sec-title">As Seen <em>On</em></h2>
    </div>
    <div class="insta-grid">
        <div class="insta-item rev"><img src="images/baby2.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d1"><img src="images/sun2.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d2"><img src="images/pink2.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d3"><img src="images/strass.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
        <div class="insta-item rev d4"><img src="images/baby3.png" alt=""><div class="insta-ov"><i class="fab fa-instagram"></i></div></div>
    </div>
</section>

<!-- ══ FOOTER ══ -->
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

// ── NAV + BACK TO TOP
window.addEventListener('scroll',()=>{
    document.getElementById('nav').classList.toggle('stuck', window.scrollY>60);
    document.getElementById('btt').style.display = window.scrollY>320 ? 'block':'none';
});

// ── SCROLL REVEAL
const revEls=document.querySelectorAll('.rev');
const obs=new IntersectionObserver(entries=>{
    entries.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');obs.unobserve(e.target);}});
},{threshold:.1,rootMargin:'0px 0px -50px 0px'});
revEls.forEach(r=>obs.observe(r));

// ── HORIZONTAL DRAG SCROLL
const svcTrack=document.getElementById('svcTrack');
let isDragging=false, startX, sl;
svcTrack.addEventListener('mousedown',e=>{isDragging=true;startX=e.pageX-svcTrack.offsetLeft;sl=svcTrack.scrollLeft;});
svcTrack.addEventListener('mouseleave',()=>isDragging=false);
svcTrack.addEventListener('mouseup',()=>isDragging=false);
svcTrack.addEventListener('mousemove',e=>{
    if(!isDragging)return; e.preventDefault();
    svcTrack.scrollLeft=sl-(e.pageX-svcTrack.offsetLeft-startX)*1.6;
});

// ── SEARCH
document.getElementById('searchBtn').addEventListener('click',e=>{
    e.preventDefault();
    document.getElementById('searchOv').classList.add('open');
    document.querySelector('.search-ov input').focus();
});
document.getElementById('searchClose').addEventListener('click',()=>document.getElementById('searchOv').classList.remove('open'));
document.addEventListener('keydown',e=>{if(e.key==='Escape')document.getElementById('searchOv').classList.remove('open');});

// ── CART
function toggleCart(){
    document.getElementById('sideCart').classList.toggle('open');
    document.getElementById('cartOv').classList.toggle('open');
}
</script>
</body>
</html>