<?php
session_start();
include_once("../controller/ProduitController.php");
include_once("../controller/PannierController.php");
include_once("../controller/StockController.php");
$controllerpannier = new PannierController();
$stockController = new StockController();
$id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$listpannier = $controllerpannier->listpannier($id);

$controller = new ProduitController();

// 1. Check for product detail first
$ref = isset($_GET['ref']) ? $_GET['ref'] : '';
$product = null;
if ($ref !== '') {
    $product = $controller->produit($ref)->fetch(PDO::FETCH_ASSOC);
}

// 2. If not product detail, do collection filtering as before
if ($product === false || $ref === '') {
    $nom = isset($_GET['nom']) ? urldecode($_GET['nom']) : '';
    $collectionFilter = '';
    $collections = $controller->listCollections()->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $prix = $_POST['prix'];
        $collectionFilter = isset($_POST['collection_id']) ? $_POST['collection_id'] : '';
        $min = substr($prix, 2, strpos($prix, '-') - 2);
        $max = substr(strchr($prix, '-'), 4);
        $listproduitdetail = $controller->listproduitparprix($min, $max, $collectionFilter);
    } else {
        $collectionId = null;
        foreach ($collections as $c) {
            if (strcasecmp(trim($c['nom']), trim($nom)) === 0) {
                $collectionId = $c['id'];
                break;
            }
        }

        if ($nom !== '' && $collectionId === null) {
            $productByName = $controller->produitByName($nom);
            if ($productByName) {
                $product = $productByName;
                $ref = $product['ref'];
            } else {
                $listproduitdetail = [];
            }
        } elseif ($collectionId !== null) {
            $listproduitdetail = $controller->listproduit($collectionId);
        } else {
            $listproduitdetail = $controller->listAllProduits();
        }
    }
}

$showProductDetail = false;
if ($ref !== '' && $product) {
    $showProductDetail = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Glowear — <?php echo htmlspecialchars($nom) ?></title>

    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">

    <link rel="stylesheet" href="css/chatbot-widget.css">
    <link rel="stylesheet" href="css/chatbot-messages.css">
    <link rel="stylesheet" href="css/response-format.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css">

    <style>
        /* ═══════════════════════════════════════════
           DESIGN TOKENS
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

        /* ── BUTTONS ── */
        .btn-red { background:var(--red); color:#fff; font-size:10px; letter-spacing:3px; text-transform:uppercase; padding:13px 30px; text-decoration:none; font-weight:500; display:inline-block; transition:background .3s,transform .3s; border:none; cursor:pointer; }
        .btn-red:hover { background:var(--red2); transform:translateY(-2px); color:#fff; }
        .btn-ghost { border:1px solid rgba(192,57,43,.45); color:var(--red2); font-size:10px; letter-spacing:3px; text-transform:uppercase; padding:13px 30px; text-decoration:none; font-weight:300; display:inline-block; transition:all .3s; background:none; cursor:pointer; }
        .btn-ghost:hover { border-color:var(--red); background:var(--red-dim); color:var(--red2); }
        .btn-disabled { background:rgba(255,255,255,.06); color:var(--grey); font-size:10px; letter-spacing:3px; text-transform:uppercase; padding:13px 30px; font-weight:300; border:none; cursor:not-allowed; display:inline-block; }

        /* ── PAGE HERO ── */
        .page-hero { height:38vh; min-height:300px; position:relative; display:flex; align-items:flex-end; overflow:hidden; padding-bottom:56px; }
        .page-hero-bg { position:absolute; inset:0; background:radial-gradient(ellipse at 65% 40%, rgba(192,57,43,.09) 0%, transparent 55%), linear-gradient(160deg,#0e0505 0%,#080808 60%,#0a0a0a 100%); }
        .page-hero-lines { position:absolute; inset:0; overflow:hidden; pointer-events:none; }
        .page-hero-lines::before,.page-hero-lines::after { content:''; position:absolute; background:linear-gradient(90deg,transparent,rgba(192,57,43,.18),transparent); height:1px; left:0; right:0; animation:lineSlide 6s ease-in-out infinite alternate; }
        .page-hero-lines::before { top:35%; }
        .page-hero-lines::after  { top:68%; animation-delay:1.5s; }
        @keyframes lineSlide { from{opacity:.2;transform:scaleX(.4)} to{opacity:1;transform:scaleX(1)} }
        .adot { position:absolute; border-radius:50%; background:var(--red); opacity:0; animation:floatPt 7s infinite; }
        @keyframes floatPt { 0%{opacity:0;transform:translateY(0) scale(0)} 20%{opacity:.4} 80%{opacity:.1} 100%{opacity:0;transform:translateY(-80px) scale(1.2)} }
        .page-hero-content { position:relative; z-index:2; padding-left:10vw; }
        .page-hero-tag { display:inline-flex; align-items:center; gap:10px; font-size:10px; letter-spacing:5px; text-transform:uppercase; color:var(--red); margin-bottom:14px; opacity:0; animation:up .9s .3s forwards; }
        .page-hero-tag::before { content:''; width:30px; height:1px; background:var(--red); }
        .page-hero-h1 { font-family:'Cormorant Garamond',serif; font-size:clamp(38px,6.5vw,88px); font-weight:300; line-height:.92; color:#fff; opacity:0; animation:up .9s .5s forwards; }
        .page-hero-h1 em { font-style:italic; color:var(--red2); }
        .hero-breadcrumb { position:absolute; bottom:24px; right:56px; display:flex; align-items:center; gap:10px; font-size:9px; letter-spacing:4px; text-transform:uppercase; color:rgba(255,255,255,.22); opacity:0; animation:fadeIn .9s .8s forwards; }
        .hero-breadcrumb a { color:rgba(255,255,255,.22); text-decoration:none; transition:color .3s; }
        .hero-breadcrumb a:hover { color:var(--red); }
        .hero-breadcrumb .sep { color:var(--red); }
        @keyframes up     { from{opacity:0;transform:translateY(28px)} to{opacity:1;transform:translateY(0)} }
        @keyframes fadeIn { from{opacity:0} to{opacity:1} }

        /* ── SHOP LAYOUT ── */
        .shop-wrap { display:grid; grid-template-columns:260px 1fr; gap:0; padding:80px 56px 100px; background:var(--dark); align-items:start; }

        /* ── SIDEBAR ── */
        .sidebar { position:sticky; top:100px; }
        .sidebar-block { background:var(--dark2); border-left:2px solid rgba(192,57,43,.15); padding:28px 24px; margin-bottom:2px; transition:border-color .3s; }
        .sidebar-block:hover { border-left-color:var(--red); }
        .sidebar-title { font-size:9px; letter-spacing:5px; text-transform:uppercase; color:var(--red); margin-bottom:20px; display:block; }

        /* price slider */
        .ui-slider-horizontal { background:rgba(255,255,255,.07); border:none; height:3px; border-radius:0; margin:14px 0 18px; }
        .ui-slider .ui-slider-range { background:var(--red); border:none; border-radius:0; }
        .ui-slider .ui-slider-handle { background:var(--red); border:none; border-radius:50%; width:14px; height:14px; top:-6px; cursor:pointer; outline:none; transition:background .2s; }
        .ui-slider .ui-slider-handle:hover { background:var(--red2); }
        #amount { background:none; border:none; border-bottom:1px solid rgba(192,57,43,.3); color:var(--red2); font-family:'Cormorant Garamond',serif; font-size:16px; font-weight:300; width:100%; padding:6px 0; margin-bottom:16px; outline:none; }

        /* radio buttons */
        .radio-row { display:flex; align-items:center; gap:10px; margin-bottom:10px; cursor:pointer; }
        .radio-row input[type="radio"] { appearance:none; -webkit-appearance:none; width:14px; height:14px; border:1px solid rgba(255,255,255,.2); border-radius:50%; flex-shrink:0; cursor:pointer; transition:border-color .2s,background .2s; }
        .radio-row input[type="radio"]:checked { border-color:var(--red); background:var(--red); box-shadow:0 0 0 3px rgba(192,57,43,.2); }
        .radio-row label { font-size:11px; font-weight:300; color:rgba(255,255,255,.5); letter-spacing:1px; cursor:pointer; transition:color .2s; }
        .radio-row:hover label { color:rgba(255,255,255,.85); }

        .filter-btn { width:100%; background:var(--red); border:none; color:#fff; font-size:9px; letter-spacing:3px; text-transform:uppercase; padding:12px; cursor:pointer; font-family:'Montserrat',sans-serif; font-weight:500; transition:background .3s; margin-top:6px; }
        .filter-btn:hover { background:var(--red2); }

        /* ── PRODUCT GRID ── */
        .products-area { padding-left:40px; }
        .products-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:20px; }

        /* product card */
        .product-card { background:var(--dark2); border-bottom:1px solid rgba(192,57,43,.0); transition:border-color .3s; display:flex; flex-direction:column; }
        .product-card:hover { border-bottom-color:var(--red); }

        /* image area */
        .product-media { position:relative; overflow:hidden; aspect-ratio:3/4; }
        .product-media img { width:100%; height:100%; object-fit:cover; filter:brightness(.82) saturate(.85); transition:transform .8s cubic-bezier(.25,.46,.45,.94),filter .5s; display:block; }
        .product-card:hover .product-media img { transform:scale(1.08); filter:brightness(.5) saturate(1.1); }

        /* badge */
        .product-badge { position:absolute; top:14px; left:14px; background:var(--red); color:#fff; font-size:8px; letter-spacing:2px; text-transform:uppercase; padding:5px 12px; font-weight:500; z-index:1; }
        .product-badge.oos { background:rgba(255,255,255,.1); color:var(--grey); }

        /* quick actions overlay */
        .product-overlay { position:absolute; inset:0; background:rgba(8,8,8,.55); display:flex; flex-direction:column; align-items:center; justify-content:center; gap:12px; opacity:0; transition:opacity .35s; }
        .product-card:hover .product-overlay { opacity:1; }
        .overlay-icon { width:40px; height:40px; border:1px solid rgba(255,255,255,.3); background:none; color:#fff; font-size:13px; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all .2s; text-decoration:none; }
        .overlay-icon:hover { background:var(--red); border-color:var(--red); color:#fff; }

        /* info */
        .product-info { padding:18px 18px 0; }
        .product-chip { font-size:8px; letter-spacing:3px; text-transform:uppercase; color:var(--red); margin-bottom:6px; display:block; }
        .product-name { font-family:'Cormorant Garamond',serif; font-size:20px; font-weight:300; color:#fff; margin-bottom:4px; line-height:1.2; }
        .product-meta { font-size:10px; font-weight:300; color:var(--grey); letter-spacing:.5px; margin-bottom:6px; }
        .product-stock { font-size:9px; letter-spacing:2px; text-transform:uppercase; color:rgba(255,255,255,.25); margin-bottom:4px; }
        .product-stock.in { color:rgba(39,174,96,.6); }
        .product-price { font-family:'Cormorant Garamond',serif; font-size:22px; font-weight:300; color:var(--red2); margin-bottom:14px; }

        /* ── FORM inside card ── */
        .product-form { padding:0 18px 18px; margin-top:auto; }
        .form-row { display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:10px; }
        .form-field label { font-size:8px; letter-spacing:3px; text-transform:uppercase; color:rgba(255,255,255,.3); display:block; margin-bottom:5px; }
        .form-field select,
        .form-field input[type="number"] {
            width:100%; background:var(--dark3); border:1px solid rgba(255,255,255,.06); color:#fff;
            font-family:'Montserrat',sans-serif; font-size:11px; font-weight:300;
            padding:9px 10px; outline:none; appearance:none; -webkit-appearance:none;
            transition:border-color .2s;
        }
        .form-field select:focus,
        .form-field input[type="number"]:focus { border-color:rgba(192,57,43,.4); }
        .form-field select option { background:var(--dark3); }

        /* ── PRODUCT DETAIL ── */
        .detail-wrap { padding-left:40px; }
        .detail-card { background:var(--dark2); display:grid; grid-template-columns:1fr 1fr; gap:0; }
        .detail-media { position:relative; overflow:hidden; aspect-ratio:3/4; }
        .detail-media img { width:100%; height:100%; object-fit:cover; filter:brightness(.88); display:block; }
        .detail-badge { position:absolute; top:14px; left:14px; background:var(--red); color:#fff; font-size:8px; letter-spacing:2px; text-transform:uppercase; padding:5px 12px; font-weight:500; z-index:1; }
        .detail-badge.oos { background:rgba(255,255,255,.1); color:var(--grey); }
        .detail-body { padding:48px 40px; display:flex; flex-direction:column; justify-content:center; }
        .detail-chip { font-size:8px; letter-spacing:4px; text-transform:uppercase; color:var(--red); margin-bottom:14px; display:block; }
        .detail-name { font-family:'Cormorant Garamond',serif; font-size:clamp(28px,3vw,44px); font-weight:300; color:#fff; line-height:1.1; margin-bottom:12px; }
        .detail-meta { font-size:11px; font-weight:300; color:var(--grey); letter-spacing:.5px; margin-bottom:10px; }
        .detail-desc { font-size:12px; font-weight:300; color:rgba(255,255,255,.5); line-height:1.8; margin-bottom:16px; border-left:2px solid rgba(192,57,43,.3); padding-left:16px; }
        .detail-stock { font-size:9px; letter-spacing:2px; text-transform:uppercase; color:rgba(255,255,255,.25); margin-bottom:6px; }
        .detail-stock.in { color:rgba(39,174,96,.6); }
        .detail-price { font-family:'Cormorant Garamond',serif; font-size:36px; font-weight:300; color:var(--red2); margin-bottom:28px; }
        .detail-form .form-row { display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:14px; }
        .detail-back { display:inline-flex; align-items:center; gap:8px; font-size:9px; letter-spacing:3px; text-transform:uppercase; color:rgba(255,255,255,.3); text-decoration:none; margin-top:20px; transition:color .3s; }
        .detail-back:hover { color:var(--red); }
        .detail-back i { font-size:10px; }

        /* ── MARQUEE ── */
        .marquee-band { background:var(--dark2); overflow:hidden; border-top:1px solid rgba(192,57,43,.18); border-bottom:1px solid rgba(192,57,43,.18); padding:22px 0; }
        .marquee-inner { display:flex; gap:56px; white-space:nowrap; animation:marquee 22s linear infinite; }
        .marquee-item { display:flex; align-items:center; gap:14px; flex-shrink:0; font-family:'Cormorant Garamond',serif; font-size:21px; font-style:italic; color:rgba(255,255,255,.18); }
        .rdot { width:5px; height:5px; border-radius:50%; background:var(--red); flex-shrink:0; }
        @keyframes marquee { from{transform:translateX(0)} to{transform:translateX(-50%)} }

        /* ── INSTAGRAM ── */
        .insta-sec { padding:80px 0; background:var(--dark3); }
        .insta-head { text-align:center; margin-bottom:48px; }
        .insta-handle { font-size:12px; letter-spacing:2px; color:var(--red); display:inline-flex; align-items:center; gap:8px; margin-bottom:16px; }
        .sec-title { font-family:'Cormorant Garamond',serif; font-size:clamp(30px,4vw,56px); font-weight:300; color:#fff; }
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
        .d1{transition-delay:.1s}.d2{transition-delay:.2s}.d3{transition-delay:.3s}

        /* ── BACK TO TOP ── */
        #btt { display:none; position:fixed; bottom:36px; right:36px; width:42px; height:42px; background:var(--red); color:#fff; text-align:center; line-height:42px; font-size:18px; z-index:999; text-decoration:none; transition:background .3s; }
        #btt:hover { background:var(--red2); color:#fff; }

        /* ── EMPTY STATE ── */
        .empty-state { text-align:center; padding:80px 20px; }
        .empty-state i { font-size:44px; color:rgba(192,57,43,.3); display:block; margin-bottom:20px; }
        .empty-state h3 { font-family:'Cormorant Garamond',serif; font-size:30px; font-weight:300; color:rgba(255,255,255,.4); margin-bottom:10px; }
        .empty-state p { font-size:12px; font-weight:300; color:var(--grey); }

        /* ── RESPONSIVE ── */
        @media(max-width:1100px){ .products-grid{ grid-template-columns:1fr; } .detail-card{ grid-template-columns:1fr; } }
        @media(max-width:992px){
            .nav-links{display:none}
            .nav-wrap,.nav-wrap.stuck{padding:0 24px}
            .shop-wrap{grid-template-columns:1fr;padding:60px 24px 80px}
            .products-area,.detail-wrap{padding-left:0;margin-top:32px}
            .products-grid{grid-template-columns:repeat(2,1fr)}
            .ft-grid{grid-template-columns:1fr 1fr;gap:36px}
            .insta-grid{grid-template-columns:repeat(3,1fr)}
        }
        @media(max-width:576px){
            .products-grid{grid-template-columns:1fr}
            footer{padding:60px 20px 28px}
            .ft-grid{grid-template-columns:1fr}
        }
    </style>
</head>
<body>

<div class="grain"></div>
<div id="glitter-layer"></div>
<div id="cur"></div>
<div id="cur-ring"></div>

<!-- ── SIDE CART ── -->
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
            <a href="#" class="active">Collections ▾</a>
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

<!-- ── PAGE HERO ── -->
<section class="page-hero">
    <div class="page-hero-bg"></div>
    <div class="page-hero-lines"></div>
    <div class="adot" style="width:3px;height:3px;left:18%;bottom:20%;animation-delay:0s;animation-duration:7s"></div>
    <div class="adot" style="width:2px;height:2px;left:50%;bottom:38%;animation-delay:1.5s;animation-duration:9s"></div>
    <div class="adot" style="width:4px;height:4px;left:82%;bottom:24%;animation-delay:2.8s;animation-duration:6s"></div>
    <div class="page-hero-content">
        <span class="page-hero-tag">Glowear Collection</span>
        <?php if($showProductDetail): ?>
            <h1 class="page-hero-h1"><?php echo htmlspecialchars($product['nom']) ?><br><em>Detail</em></h1>
        <?php else: ?>
            <h1 class="page-hero-h1"><?php echo ($nom !== '') ? htmlspecialchars($nom) : 'All' ?><br><em>Collection</em></h1>
        <?php endif; ?>
    </div>
    <div class="hero-breadcrumb">
        <a href="index.php">Home</a>
        <span class="sep">✦</span>
        <a href="shop.php">Shop</a>
        <?php if($showProductDetail): ?>
        <span class="sep">✦</span>
        <span><?php echo htmlspecialchars($product['nom']) ?></span>
        <?php elseif($nom !== ''): ?>
        <span class="sep">✦</span>
        <span><?php echo htmlspecialchars($nom) ?></span>
        <?php endif; ?>
    </div>
</section>


<!-- ══════════════════════════════════════════
     SHOP BODY
═══════════════════════════════════════════ -->
<div class="shop-wrap">

    <!-- ── SIDEBAR (always visible) ── -->
    <aside class="sidebar">
        <?php echo "<form action=\"" . $_SERVER['PHP_SELF'] . "\" method='POST'>"; ?>

        <!-- Budget -->
        <div class="sidebar-block rev">
            <span class="sidebar-title">Budget</span>
            <div id="slider-range"></div>
            <input type="text" id="amount" name="prix" readonly placeholder="DT 0 — 500">
            <input type="hidden" name="nom" value="<?php echo htmlspecialchars($nom) ?>">
            <button class="filter-btn" type="submit">Apply Filter</button>
        </div>

        <!-- Collection -->
        <div class="sidebar-block rev d1">
            <span class="sidebar-title">Collection</span>
            <div class="radio-row">
                <input name="collection_id" id="CollectionAll" value="" type="radio" <?php echo $collectionFilter === '' ? 'checked' : ''; ?>>
                <label for="CollectionAll">All Collections</label>
            </div>
            <?php foreach ($collections as $collection): ?>
            <div class="radio-row">
                <input name="collection_id" id="Collection<?php echo $collection['id'] ?>" value="<?php echo $collection['id'] ?>" type="radio" <?php echo $collectionFilter == $collection['id'] ? 'checked' : ''; ?>>
                <label for="Collection<?php echo $collection['id'] ?>"><?php echo htmlspecialchars($collection['nom']) ?></label>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Metal & Finish -->
        <div class="sidebar-block rev d2">
            <span class="sidebar-title">Metal &amp; Finish</span>
            <?php
            $metals = ['Gold','Silver','Rose Gold','Pearl','Diamond'];
            foreach($metals as $m):
            ?>
            <div class="radio-row">
                <input name="survey" id="metal-<?php echo $m ?>" value="<?php echo $m ?>" type="radio">
                <label for="metal-<?php echo $m ?>"><?php echo $m ?></label>
            </div>
            <?php endforeach; ?>
        </div>

        </form>
    </aside>


    <?php if ($showProductDetail): ?>
    <!-- ══════════════════════════════════════
         SINGLE PRODUCT DETAIL VIEW
    ═══════════════════════════════════════ -->
    <div class="detail-wrap">
        <?php
        $stockTotal = $stockController->getTotalStockByRef($product['ref']);
        $inStock    = $stockTotal > 0;
        ?>
        <div class="detail-card rev">

            <!-- Image -->
            <div class="detail-media">
                <span class="detail-badge <?php echo !$inStock ? 'oos' : '' ?>">
                    <?php echo $inStock ? 'New Arrival' : 'Out of Stock' ?>
                </span>
                <img src="<?php echo htmlspecialchars($product['image']) ?>" alt="<?php echo htmlspecialchars($product['nom']) ?>">
            </div>

            <!-- Body -->
            <div class="detail-body">
                <span class="detail-chip">Luxury piece · <?php echo htmlspecialchars($product['categorie'] ?? '') ?></span>
                <h2 class="detail-name"><?php echo htmlspecialchars($product['nom']) ?></h2>
                <p class="detail-meta"><?php echo htmlspecialchars($product['couleur']) ?></p>
                <p class="detail-desc"><?php echo htmlspecialchars($product['description']) ?></p>
                <p class="detail-stock <?php echo $inStock ? 'in' : '' ?>">
                    <?php echo $inStock ? '✦ In stock: ' . $stockTotal : '✦ Out of stock' ?>
                </p>
                <p class="detail-price">DT <?php echo htmlspecialchars($product['prix']) ?></p>

                <!-- Add to Cart -->
                <div class="detail-form">
                    <form action="addToCart.php" method="POST">
                        <div class="form-row">
                            <div class="form-field">
                                <label>Size / Length</label>
                                <select name="taille">
                                    <option value="Adjustable">Adjustable</option>
                                    <option value="16 cm">16 cm</option>
                                    <option value="18 cm">18 cm</option>
                                    <option value="20 cm">20 cm</option>
                                    <option value="Ring size 52">Ring size 52</option>
                                    <option value="Ring size 54">Ring size 54</option>
                                    <option value="Ring size 56">Ring size 56</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label>Quantity</label>
                                <input type="number" name="quantity" value="1" min="1" max="20">
                            </div>
                        </div>
                        <?php
                        echo "<input type='hidden' name='ref'    value='" . htmlspecialchars($product['ref'])   . "'>";
                        echo "<input type='hidden' name='prix'   value='" . htmlspecialchars($product['prix'])  . "'>";
                        echo "<input type='hidden' name='nom'    value='" . htmlspecialchars($product['nom'])   . "'>";
                        echo "<input type='hidden' name='image'  value='" . htmlspecialchars($product['image']) . "'>";
                        echo "<input type='hidden' name='qte'    value='1'>";
                        echo "<input type='hidden' name='action' value='add'>";

                        if (isset($_SESSION['id'])) {
                            if ($inStock) {
                                echo "<button type='submit' class='btn-red' style='width:100%'>Add to Cart →</button>";
                            } else {
                                echo "<button type='button' class='btn-disabled' style='width:100%' disabled>Out of Stock</button>";
                            }
                        } else {
                            echo "<a href='client_login.php' class='btn-ghost' style='display:block;text-align:center;width:100%'>Login to Purchase</a>";
                        }
                        ?>
                    </form>
                </div>

                <a href="shop.php<?php echo $nom ? '?nom='.urlencode($nom) : '' ?>" class="detail-back">
                    <i class="fas fa-arrow-left"></i> Back to Collection
                </a>
            </div>

        </div>
    </div><!-- /detail-wrap -->


    <?php else: ?>
    <!-- ══════════════════════════════════════
         PRODUCT GRID (collection / filter view)
    ═══════════════════════════════════════ -->
    <div class="products-area">
        <div class="products-grid">

        <?php
        if (is_array($listproduitdetail)) {
            // Empty array — no results
        ?>
            <div class="empty-state" style="grid-column:1/-1">
                <i class="fas fa-gem"></i>
                <h3>No pieces found</h3>
                <p>Try adjusting your filters or explore another collection.</p>
            </div>

        <?php
        } else {
            $hasProducts = false;
            while ($l = $listproduitdetail->fetch()):
                $hasProducts = true;
                $stockTotal  = $stockController->getTotalStockByRef($l[0]);
                $inStock     = $stockTotal > 0;
        ?>

            <div class="product-card rev">

                <!-- ── Image ── -->
                <div class="product-media">
                    <span class="product-badge <?php echo !$inStock ? 'oos' : '' ?>">
                        <?php echo $inStock ? htmlspecialchars($l[4]) : 'Out of Stock' ?>
                    </span>
                    <img src="<?php echo htmlspecialchars($l[6]) ?>" alt="<?php echo htmlspecialchars($l[1]) ?>">
                    <div class="product-overlay">
                        <a href="shop.php?ref=<?php echo urlencode($l[0]) ?>" class="overlay-icon" title="View detail">
                            <i class="fas fa-eye"></i>
                        </a>
                    </div>
                </div>

                <!-- ── Info ── -->
                <div class="product-info">
                    <span class="product-chip">Luxury piece</span>
                    <h4 class="product-name"><?php echo htmlspecialchars($l[1]) ?></h4>
                    <p class="product-meta"><?php echo htmlspecialchars($l[2]) ?> · <?php echo htmlspecialchars($l[4]) ?></p>
                    <p class="product-meta"><?php echo htmlspecialchars($l[3]) ?></p>
                    <p class="product-stock <?php echo $inStock ? 'in' : '' ?>">
                        <?php echo $inStock ? '✦ In stock: ' . $stockTotal : '✦ Out of stock' ?>
                    </p>
                    <p class="product-price">DT <?php echo htmlspecialchars($l[5]) ?></p>
                </div>

                <!-- ── Add to Cart Form ── -->
                <div class="product-form">
                    <form action="addToCart.php" method="POST">
                        <div class="form-row">
                            <div class="form-field">
                                <label>Size / Length</label>
                                <select name="taille">
                                    <option value="Adjustable">Adjustable</option>
                                    <option value="16 cm">16 cm</option>
                                    <option value="18 cm">18 cm</option>
                                    <option value="20 cm">20 cm</option>
                                    <option value="Ring size 52">Ring size 52</option>
                                    <option value="Ring size 54">Ring size 54</option>
                                    <option value="Ring size 56">Ring size 56</option>
                                </select>
                            </div>
                            <div class="form-field">
                                <label>Quantity</label>
                                <input type="number" name="quantity" value="1" min="1" max="20">
                            </div>
                        </div>
                        <?php
                        echo "<input type='hidden' name='ref'    value='" . htmlspecialchars($l[0]) . "'>";
                        echo "<input type='hidden' name='prix'   value='" . htmlspecialchars($l[5]) . "'>";
                        echo "<input type='hidden' name='nom'    value='" . htmlspecialchars($l[1]) . "'>";
                        echo "<input type='hidden' name='image'  value='" . htmlspecialchars($l[6]) . "'>";
                        echo "<input type='hidden' name='qte'    value='1'>";
                        echo "<input type='hidden' name='action' value='add'>";

                        if (isset($_SESSION['id'])) {
                            if ($inStock) {
                                echo "<button type='submit' class='btn-red' style='width:100%'>Add to Cart →</button>";
                            } else {
                                echo "<button type='button' class='btn-disabled' style='width:100%' disabled>Out of Stock</button>";
                            }
                        } else {
                            echo "<a href='client_login.php' class='btn-ghost' style='display:block;text-align:center;width:100%'>Login to Purchase</a>";
                        }
                        ?>
                    </form>
                </div>

            </div><!-- /product-card -->

        <?php
            endwhile;

            if (!$hasProducts):
        ?>
            <div class="empty-state" style="grid-column:1/-1">
                <i class="fas fa-gem"></i>
                <h3>No pieces found</h3>
                <p>Try adjusting your filters or explore another collection.</p>
            </div>
        <?php
            endif;
        }
        ?>

        </div><!-- /products-grid -->
    </div><!-- /products-area -->

    <?php endif; ?>

</div><!-- /shop-wrap -->


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
        $instaImgs = ['pink3','baby2','strass3','pink2','sun3'];
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

<a href="#" id="btt"><i class="fas fa-chevron-up"></i></a>

<!-- ── JS ── -->
<script src="js/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
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
document.querySelectorAll('a,button,select,input,.product-card,.detail-card,.sidebar-block').forEach(el => {
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
}, { threshold:0.1, rootMargin:'0px 0px -40px 0px' });
document.querySelectorAll('.rev').forEach(r => obs.observe(r));

/* ── CART ── */
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

/* ── PRICE RANGE SLIDER ── */
$(function() {
    $("#slider-range").slider({
        range: true,
        min: 0,
        max: 500,
        values: [0, 500],
        slide: function(event, ui) {
            $("#amount").val("DT " + ui.values[0] + " - DT " + ui.values[1]);
        }
    });
    $("#amount").val("DT " + $("#slider-range").slider("values", 0) +
        " - DT " + $("#slider-range").slider("values", 1));
});
</script>
</body>
</html>