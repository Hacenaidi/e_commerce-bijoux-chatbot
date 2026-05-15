<?php
session_start();
include_once("../controller/ProduitController.php");
include_once("../controller/PannierController.php");
$controllerpannier = new PannierController();
$id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$listpannier = $controllerpannier->listpannier($id);
$controller = new ProduitController();
$totlal = 0;
// Pre-calculate total for the side cart badge
$lp_badge = $controllerpannier->listpannier($id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Glowear — Checkout</title>
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">

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

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--dark);
            color: var(--cream);
            overflow-x: hidden;
            cursor: none;
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
            background: radial-gradient(circle, rgba(255,255,255,.95) 0%, rgba(255,215,0,.9) 35%, rgba(255,255,255,0) 70%);
            box-shadow: 0 0 8px rgba(255,215,0,.8), 0 0 16px rgba(255,255,255,.35);
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
            transition: top .4s, background .4s, backdrop-filter .4s, padding .4s;
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

        /* ── SEARCH */
        .search-ov { display:none; position:fixed; inset:0; background:rgba(0,0,0,.96); z-index:3000; flex-direction:column; align-items:center; justify-content:center; }
        .search-ov.open { display:flex; }
        .search-ov input { background:none; border:none; border-bottom:1px solid rgba(255,255,255,.25); font-family:'Cormorant Garamond',serif; font-size:34px; color:#fff; width:60%; max-width:580px; padding:14px 0; text-align:center; outline:none; }
        .search-ov input::placeholder { color:rgba(255,255,255,.18); }
        .search-ov-close { position:absolute; top:36px; right:56px; font-size:22px; color:rgba(255,255,255,.4); cursor:pointer; transition:color .3s; }
        .search-ov-close:hover { color:var(--red); }

        /* ── PAGE HERO BAND */
        .page-hero {
            padding-top: 160px;
            padding-bottom: 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .page-hero::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse at 50% 0%, rgba(192,57,43,.09) 0%, transparent 60%);
        }
        .page-hero-tag {
            display: inline-flex; align-items: center; gap: 10px;
            font-size: 10px; letter-spacing: 5px; text-transform: uppercase;
            color: var(--red); margin-bottom: 18px;
        }
        .page-hero-tag::before,
        .page-hero-tag::after { content: ''; width: 28px; height: 1px; background: var(--red); }
        .page-hero h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(48px, 6vw, 88px); font-weight: 300;
            color: #fff; line-height: .95;
        }
        .page-hero h1 em { font-style: italic; color: var(--red2); }
        .breadcrumb-row {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            margin-top: 20px; font-size: 10px; letter-spacing: 3px; text-transform: uppercase;
        }
        .breadcrumb-row a { color: var(--grey); text-decoration: none; transition: color .3s; }
        .breadcrumb-row a:hover { color: var(--red); }
        .breadcrumb-row span { color: rgba(255,255,255,.2); }
        .breadcrumb-row .active { color: var(--red); }

        /* ── MAIN CHECKOUT AREA */
        .checkout-wrap {
            padding: 60px 56px 120px;
            max-width: 1300px;
            margin: 0 auto;
        }

        /* ── AUTH PANEL */
        .auth-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2px;
            margin-bottom: 60px;
            border: 1px solid rgba(192,57,43,.15);
        }
        .auth-panel {
            background: var(--dark2);
            padding: 48px 44px;
            position: relative;
            overflow: hidden;
            opacity: 0;
            transform: translateY(28px);
            transition: opacity .85s ease, transform .85s ease;
        }
        .auth-panel.in { opacity: 1; transform: translateY(0); }
        .auth-panel::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(to right, transparent, var(--red), transparent);
        }
        .auth-panel-num {
            position: absolute; right: 20px; top: 10px;
            font-family: 'Cormorant Garamond', serif; font-size: 80px; font-weight: 300;
            color: rgba(255,255,255,.03); line-height: 1; pointer-events: none;
        }
        .auth-label {
            font-size: 9px; letter-spacing: 5px; text-transform: uppercase;
            color: var(--red); margin-bottom: 14px; display: block;
        }
        .auth-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 30px; font-weight: 300; color: #fff;
            margin-bottom: 22px;
        }
        .auth-toggle {
            display: inline-flex; align-items: center; gap: 10px;
            font-size: 10px; letter-spacing: 3px; text-transform: uppercase;
            color: var(--red); text-decoration: none; cursor: pointer;
            border: none; background: none; transition: gap .3s;
        }
        .auth-toggle::after { content: '→'; transition: transform .3s; }
        .auth-toggle:hover::after { transform: translateX(4px); }
        .auth-form {
            margin-top: 28px; display: none;
        }
        .auth-form.open { display: block; }

        /* ── FORM FIELDS */
        .gl-field { margin-bottom: 20px; }
        .gl-field label {
            display: block; font-size: 9px; letter-spacing: 4px; text-transform: uppercase;
            color: var(--grey); margin-bottom: 8px;
        }
        .gl-field input,
        .gl-field select {
            width: 100%; background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.08);
            border-bottom: 1px solid rgba(192,57,43,.35);
            color: #fff; padding: 12px 14px;
            font-family: 'Montserrat', sans-serif; font-size: 13px; font-weight: 300;
            outline: none; transition: border-color .3s, background .3s;
        }
        .gl-field input:focus,
        .gl-field select:focus {
            border-color: transparent;
            border-bottom-color: var(--red);
            background: rgba(192,57,43,.06);
        }
        .gl-field input::placeholder { color: rgba(255,255,255,.2); }
        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .field-row-3 { display: grid; grid-template-columns: 1.4fr 1fr .8fr; gap: 16px; }

        /* ── BTN STYLES */
        .btn-red {
            background: var(--red); color: #fff;
            font-size: 10px; letter-spacing: 3px; text-transform: uppercase;
            padding: 14px 34px; border: none; font-weight: 500;
            display: inline-block; cursor: pointer;
            transition: background .3s, transform .3s; font-family: 'Montserrat', sans-serif;
            text-decoration: none;
        }
        .btn-red:hover { background: var(--red2); transform: translateY(-2px); color: #fff; text-decoration: none; }
        .btn-ghost {
            border: 1px solid rgba(192,57,43,.45); color: var(--red2); background: none;
            font-size: 10px; letter-spacing: 3px; text-transform: uppercase;
            padding: 14px 34px; font-weight: 300; cursor: pointer;
            display: inline-block; transition: all .3s; font-family: 'Montserrat', sans-serif;
            text-decoration: none;
        }
        .btn-ghost:hover { border-color: var(--red); background: var(--red-dim); color: var(--red2); text-decoration: none; }

        /* ── CHECKOUT GRID */
        .checkout-grid {
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 2px;
            align-items: start;
        }

        /* ── BILLING PANEL */
        .billing-panel {
            background: var(--dark2);
            border: 1px solid rgba(192,57,43,.12);
            padding: 52px 48px;
            position: relative;
            overflow: hidden;
            opacity: 0; transform: translateY(36px);
            transition: opacity .9s ease, transform .9s ease;
        }
        .billing-panel.in { opacity: 1; transform: translateY(0); }
        .billing-panel::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(to right, var(--red), transparent 70%);
        }
        .panel-tag {
            font-size: 9px; letter-spacing: 5px; text-transform: uppercase;
            color: var(--red); margin-bottom: 12px; display: block;
        }
        .panel-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 34px; font-weight: 300; color: #fff;
            margin-bottom: 38px; line-height: 1;
        }
        .panel-title em { font-style: italic; color: rgba(255,255,255,.35); }
        .panel-bg-num {
            position: absolute; right: 20px; bottom: -10px;
            font-family: 'Cormorant Garamond', serif; font-size: 180px; font-weight: 300;
            color: rgba(255,255,255,.018); line-height: 1; pointer-events: none;
        }

        /* ── ORDER SUMMARY PANEL */
        .order-panel {
            background: var(--dark3);
            border: 1px solid rgba(192,57,43,.12);
            border-left: none;
            padding: 52px 40px;
            position: sticky; top: 120px;
            opacity: 0; transform: translateY(36px);
            transition: opacity .9s .15s ease, transform .9s .15s ease;
        }
        .order-panel.in { opacity: 1; transform: translateY(0); }
        .order-panel::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 2px;
            background: linear-gradient(to right, transparent, var(--red), transparent);
        }

        /* Order items */
        .order-item {
            display: flex; align-items: center; gap: 14px;
            padding: 16px 0;
            border-bottom: 1px solid rgba(255,255,255,.05);
        }
        .order-item img { width: 58px; height: 58px; object-fit: cover; border: 1px solid rgba(192,57,43,.2); }
        .order-item-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 17px; font-weight: 300; color: #fff; flex: 1;
        }
        .order-item-qty {
            font-size: 10px; letter-spacing: 2px; color: var(--grey); margin-top: 3px;
        }
        .order-item-price {
            font-size: 14px; font-weight: 300; color: var(--red2); white-space: nowrap;
        }

        .order-divider {
            border: none; border-top: 1px solid rgba(192,57,43,.15);
            margin: 22px 0;
        }
        .order-row {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 10px;
        }
        .order-row-label {
            font-size: 9px; letter-spacing: 3px; text-transform: uppercase; color: var(--grey);
        }
        .order-row-val {
            font-size: 13px; font-weight: 300; color: rgba(255,255,255,.6);
        }
        .order-total-row {
            display: flex; justify-content: space-between; align-items: flex-end;
            padding-top: 16px; border-top: 1px solid rgba(192,57,43,.25);
            margin-top: 8px; margin-bottom: 28px;
        }
        .order-total-label {
            font-size: 9px; letter-spacing: 4px; text-transform: uppercase; color: var(--grey);
        }
        .order-total-val {
            font-family: 'Cormorant Garamond', serif;
            font-size: 36px; font-weight: 300; color: var(--red2);
        }

        /* Place order button */
        .place-btn {
            width: 100%;
            background: var(--red); color: #fff;
            font-size: 10px; letter-spacing: 4px; text-transform: uppercase;
            padding: 18px; border: none; font-weight: 500;
            cursor: none; font-family: 'Montserrat', sans-serif;
            transition: background .3s, transform .3s;
            display: flex; align-items: center; justify-content: center; gap: 12px;
            position: relative; overflow: hidden;
        }
        .place-btn::before {
            content: '';
            position: absolute; left: -100%; top: 0; bottom: 0; width: 100%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,.08), transparent);
            transition: left .5s;
        }
        .place-btn:hover { background: var(--red2); transform: translateY(-1px); }
        .place-btn:hover::before { left: 100%; }

        /* ── EMPTY STATE */
        .empty-state {
            text-align: center; padding: 80px 40px;
            background: var(--dark2); border: 1px solid rgba(192,57,43,.12);
        }
        .empty-icon {
            font-size: 48px; color: rgba(192,57,43,.3);
            margin-bottom: 24px; display: block;
        }
        .empty-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 36px; font-weight: 300; color: #fff;
            margin-bottom: 12px;
        }
        .empty-text {
            font-size: 13px; font-weight: 300; color: var(--grey);
            margin-bottom: 32px; line-height: 1.7;
        }

        /* ── SECURE BADGES */
        .secure-row {
            display: flex; align-items: center; justify-content: center; gap: 20px;
            margin-top: 20px; flex-wrap: wrap;
        }
        .secure-item {
            display: flex; align-items: center; gap: 7px;
            font-size: 9px; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,.2);
        }
        .secure-item i { color: rgba(192,57,43,.5); }

        /* ── SCROLL REVEAL */
        .rev { opacity:0; transform:translateY(36px); transition:opacity .85s ease,transform .85s ease; }
        .rev.in { opacity:1; transform:translateY(0); }
        .d1{transition-delay:.12s} .d2{transition-delay:.22s} .d3{transition-delay:.32s}

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
        #btt { display:none; position:fixed; bottom:36px; right:36px; width:42px; height:42px; background:var(--red); color:#fff; text-align:center; line-height:42px; font-size:18px; z-index:999; text-decoration:none; transition:background .3s; }
        #btt:hover { background:var(--red2); color:#fff; }

        /* ── RESPONSIVE */
        @media(max-width: 1100px) {
            .checkout-grid { grid-template-columns: 1fr; }
            .order-panel { border-left: 1px solid rgba(192,57,43,.12); border-top: none; position: static; }
        }
        @media(max-width: 768px) {
            .nav-links { display: none; }
            .nav-wrap, .nav-wrap.stuck { padding: 0 24px; }
            .checkout-wrap { padding: 40px 24px 80px; }
            .auth-row { grid-template-columns: 1fr; }
            .billing-panel, .order-panel { padding: 36px 28px; }
            .field-row, .field-row-3 { grid-template-columns: 1fr; }
            .ft-grid { grid-template-columns: 1fr 1fr; gap: 36px; }
        }
        @media(max-width: 576px) {
            footer { padding: 60px 20px 28px; }
            .ft-grid { grid-template-columns: 1fr; }
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
        <a href="my-account.php"><i class="fa fa-user"></i></a>
        <?php else: ?>
        <a href="client_login.php"><i class="fa fa-user"></i></a>
        <?php endif; ?>
    </div>
</nav>

<!-- Page Hero Band -->
<div class="page-hero">
    <span class="page-hero-tag">Secure Checkout</span>
    <h1>Complete Your <em>Order</em></h1>
    <div class="breadcrumb-row">
        <a href="index.php">Home</a>
        <span>/</span>
        <a href="shop.php">Shop</a>
        <span>/</span>
        <span class="active">Checkout</span>
    </div>
</div>

<!-- ══ MAIN CONTENT ══ -->
<div class="checkout-wrap">

    <?php
    /* ── Re-fetch list for order display (the first fetch was for the badge) */
    $listpannier2 = $controllerpannier->listpannier($id);
    $totlal = 0;
    $cart_items = [];
    while ($l = $listpannier2->fetch()) {
        $pro = $controller->produit($l[2])->fetch();
        $cart_items[] = ['l' => $l, 'pro' => $pro];
        $totlal += $pro[5] * $l[3];
    }
    
    ?>

    <?php if (!isset($_SESSION['id'])): ?>
    <!-- ── AUTH PANELS (not logged in) -->
    <div class="auth-row">
        <div class="auth-panel" id="authPanel1">
            <span class="auth-panel-num">01</span>
            <span class="auth-label">Returning Customer</span>
            <h3 class="auth-title">Account Login</h3>
            <button class="auth-toggle" onclick="toggleAuthForm('formLogin', this)">Sign in to your account</button>
            <div class="auth-form" id="formLogin">
                <form action="client_login.php" method="post">
                    <div class="field-row">
                        <div class="gl-field">
                            <label for="InputEmail">Email Address</label>
                            <input name="email" type="email" id="InputEmail" placeholder="your@email.com">
                        </div>
                        <div class="gl-field">
                            <label for="InputPassword">Password</label>
                            <input name="password" type="password" id="InputPassword" placeholder="••••••••">
                        </div>
                    </div>
                    <button type="submit" class="btn-red">Login</button>
                </form>
            </div>
        </div>

        <div class="auth-panel d1" id="authPanel2">
            <span class="auth-panel-num">02</span>
            <span class="auth-label">New Customer</span>
            <h3 class="auth-title">Create Account</h3>
            <button class="auth-toggle" onclick="toggleAuthForm('formRegister', this)">Register for free</button>
            <div class="auth-form" id="formRegister">
                <form action="client_signup.php" method="post">
                    <div class="field-row">
                        <div class="gl-field">
                            <label for="InputName">First Name</label>
                            <input name="nom" type="text" id="InputName" placeholder="First name">
                        </div>
                        <div class="gl-field">
                            <label for="InputLastname">Last Name</label>
                            <input name="prenom" type="text" id="InputLastname" placeholder="Last name">
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="gl-field">
                            <label for="InputEmail1">Email Address</label>
                            <input name="email" type="email" id="InputEmail1" placeholder="your@email.com">
                        </div>
                        <div class="gl-field">
                            <label for="InputPassword1">Password</label>
                            <input name="password" type="password" id="InputPassword1" placeholder="••••••••">
                        </div>
                    </div>
                    <button type="submit" class="btn-red">Create Account</button>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['id']) && $totlal > 0): ?>
    <!-- ── CHECKOUT FORM (logged in + has items) -->
    <form action="placeOrder.php" method="post">
        <input type="hidden" name="total" value="<?php echo $totlal ?>">

        <div class="checkout-grid">

            <!-- LEFT: Billing -->
            <div class="billing-panel" id="billingPanel">
                <span class="panel-tag">Step 01 — Details</span>
                <h2 class="panel-title">Billing <em>Address</em></h2>

                <div class="field-row">
                    <div class="gl-field">
                        <label for="firstName">First Name *</label>
                        <input name="first_name" type="text" id="firstName" placeholder="First name" required>
                    </div>
                    <div class="gl-field">
                        <label for="lastName">Last Name *</label>
                        <input name="last_name" type="text" id="lastName" placeholder="Last name" required>
                    </div>
                </div>

                <div class="gl-field">
                    <label for="email">Email Address *</label>
                    <input name="email" type="email" id="email" placeholder="your@email.com">
                </div>

                <div class="gl-field">
                    <label for="address">Delivery Address *</label>
                    <input name="adress" type="text" id="address" placeholder="Street address, building..." required>
                </div>

                <div class="gl-field">
                    <label for="telephone">Telephone *</label>
                    <input name="telephone" type="tel" id="telephone" placeholder="+216 XX XXX XXX">
                </div>

                <div class="field-row-3">
                    <div class="gl-field">
                        <label for="Mandate">Mandate *</label>
                        <input name="mandate" type="text" id="Mandate" placeholder="Mandate" required>
                    </div>
                    <div class="gl-field">
                        <label for="Accreditation">Accreditation *</label>
                        <input name="accrediation" type="text" id="Accreditation" placeholder="Accreditation" required>
                    </div>
                    <div class="gl-field">
                        <label for="zip">Zip Code *</label>
                        <input name="zip" type="text" id="zip" placeholder="0000" required>
                    </div>
                </div>

                <div class="panel-bg-num">01</div>
            </div>

            <!-- RIGHT: Order Summary -->
            <div class="order-panel" id="orderPanel">
                <span class="panel-tag">Step 02 — Review</span>
                <h2 class="panel-title">Your <em>Order</em></h2>

                <!-- Cart Items -->
                <?php foreach ($cart_items as $ci): ?>
                <div class="order-item">
                    <img src="<?php echo $ci['pro'][6] ?>" alt="<?php echo $ci['pro'][5] ?>">
                    <div style="flex:1">
                        <div class="order-item-name"><?php echo $ci['pro'][4] ?></div>
                        <div class="order-item-qty"><?php echo $ci['l'][3] ?>× piece<?php echo $ci['l'][3] > 1 ? 's' : '' ?></div>
                    </div>
                    <div class="order-item-price">DT <?php echo $ci['pro'][5] ?></div>
                </div>
                <?php endforeach; ?>

                <hr class="order-divider">

                <div class="order-row">
                    <span class="order-row-label">Subtotal</span>
                    <span class="order-row-val">DT <?php echo $totlal ?></span>
                </div>
                <div class="order-row">
                    <span class="order-row-label">Shipping</span>
                    <span class="order-row-val" style="color:rgba(192,57,43,.7)">Free</span>
                </div>

                <div class="order-total-row">
                    <span class="order-total-label">Grand Total</span>
                    <span class="order-total-val">DT <?php echo $totlal ?></span>
                </div>

                <button type="submit" class="place-btn">
                    <i class="fas fa-gem" style="font-size:13px"></i>
                    Place Order
                </button>

                <div class="secure-row">
                    <span class="secure-item"><i class="fas fa-lock"></i> Secure</span>
                    <span class="secure-item"><i class="fas fa-shield-alt"></i> Protected</span>
                    <span class="secure-item"><i class="fas fa-redo"></i> Easy Returns</span>
                </div>
            </div>

        </div>
    </form>

    <?php else: ?>
    <!-- ── EMPTY / NOT LOGGED IN WITH ITEMS -->
    <div class="empty-state rev">
        <span class="empty-icon"><i class="fas fa-gem"></i></span>
        <h2 class="empty-title">Your cart is empty</h2>
        <p class="empty-text">Add some beautiful pieces to your cart<br>before proceeding to checkout.</p>
        <a href="shop.php" class="btn-red">Discover Collection</a>
        &nbsp;&nbsp;
        <a href="cart.php" class="btn-ghost">View Cart</a>
    </div>
    <?php endif; ?>

</div>
<!-- end checkout-wrap -->

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


<!-- JS -->
<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<?php if (file_exists(__DIR__ . '/js/chatbot-widget.js')): ?>
<script src="js/chatbot-widget.js"></script>
<?php endif; ?>


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
document.querySelectorAll('a, button, input, .order-item, .auth-panel').forEach(el => {
    el.addEventListener('mouseenter', () => document.body.classList.add('big-cur'));
    el.addEventListener('mouseleave', () => document.body.classList.remove('big-cur'));
});

// ── STICKY NAV
window.addEventListener('scroll', () => {
    document.getElementById('nav').classList.toggle('stuck', window.scrollY > 60);
    const btt = document.getElementById('btt');
    btt.style.display = window.scrollY > 400 ? 'block' : 'none';
});

// ── SCROLL REVEAL
const revEls = document.querySelectorAll('.rev, .auth-panel, .billing-panel, .order-panel');
const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('in'); obs.unobserve(e.target); }
    });
}, { threshold: 0.10, rootMargin: '0px 0px -40px 0px' });
revEls.forEach(r => obs.observe(r));

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

// ── AUTH FORM TOGGLE
function toggleAuthForm(id, btn) {
    const form = document.getElementById(id);
    const isOpen = form.classList.contains('open');
    // close all
    document.querySelectorAll('.auth-form').forEach(f => f.classList.remove('open'));
    document.querySelectorAll('.auth-toggle').forEach(b => b.style.opacity = '1');
    if (!isOpen) {
        form.classList.add('open');
        btn.style.opacity = '.5';
    }
}

// ── BACK TO TOP
document.getElementById('btt').addEventListener('click', e => {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: 'smooth' });
});
</script>
</body>
</html>