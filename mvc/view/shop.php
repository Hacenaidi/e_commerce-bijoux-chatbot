<?php
session_start();
include_once("../controller/ProduitController.php");
include_once("../controller/PannierController.php");
include_once("../controller/StockController.php");
$controllerpannier = new PannierController();
$stockController = new StockController();
$id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$listpannier = $controllerpannier->listpannier($id);
$nom = $_GET['nom'];
$controller = new ProduitController();
$collectionFilter = '';
$collections = $controller->listCollections()->fetchAll(PDO::FETCH_ASSOC);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prix = $_POST['prix'];
    $nom = $_POST['nom'];
    $collectionFilter = isset($_POST['collection_id']) ? $_POST['collection_id'] : '';
    $min = substr($prix, 2, strpos($prix, '-') - 2);
    $max = substr(strchr($prix, '-'), 4);
    $listproduitdetail = $controller->listproduitparprix($nom, $min, $max, $collectionFilter);
} else {
    $listproduitdetail = $controller->listproduit($nom, $collectionFilter);
}
?>
<!DOCTYPE html>
<html lang="en">
<!-- Basic -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Site Metas -->
    <title>ThewayShop </title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Site Icons -->
    <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Site CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/custom.css">
    <!-- Chatbot Widget CSS -->
    <link rel="stylesheet" href="css/chatbot-widget.css">
    <!-- Chatbot Messages CSS (Colored & Positioned) -->
    <link rel="stylesheet" href="css/chatbot-messages.css">
    <!-- Response Format CSS -->
    <link rel="stylesheet" href="css/response-format.css">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Start Main Top -->
    <div class="main-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="text-slid-box">
                        <div id="offer-box" class="carouselTicker">
                            <ul class="offer-box">
                                <li>
                                    <i class="fab fa-opencart"></i> Off 10%! Shop Now Man
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> 50% - 80% off on Fashion
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> 20% off Entire Purchase Promo code: offT20
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> Off 50%! Shop Now
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> Off 10%! Shop Now Man
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> 50% - 80% off on Fashion
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> 20% off Entire Purchase Promo code: offT20
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> Off 50%! Shop Now
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
    </div>
    <!-- End Main Top -->

    <!-- Start Main Top -->
    <header class="main-header">
        <!-- Start Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-default bootsnav">
            <div class="container">
                <!-- Start Header Navigation -->
                <div class="navbar-header">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu"
                        aria-controls="navbars-rs-food" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="index.php"><img src="images/logo.png" class="logo" alt=""></a>
                </div>
                <!-- End Header Navigation -->

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <ul class="nav navbar-nav ml-auto" data-in="fadeInDown" data-out="fadeOutUp">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                        <li class="dropdown active megamenu-fw">
                            <a href="#" class="nav-link dropdown-toggle arrow" data-toggle="dropdown">Product</a>
                            <ul class="dropdown-menu megamenu-content" role="menu">
                                <li>
                                <div class="row">
                                        <div class="col-menu col-md-3">
                                            <h6 class="title">Top</h6>
                                            <div class="content">
                                                <ul class="menu-col">
                                                    <li><a href="shop.php?nom=Jackets">Jackets</a></li>
                                                    <li><a href="shop.php?nom=Shirts">Shirts</a></li>
                                                    <li><a href="shop.php?nom=Sweaters and Cardigans">Sweaters and Cardigans</a></li>
                                                    <li><a href="shop.php?nom=T-shirts">T-shirts</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- end col-3 -->
                                        <div class="col-menu col-md-3">
                                            <h6 class="title">Bottom</h6>
                                            <div class="content">
                                                <ul class="menu-col">
                                                    <li><a href="shop.php?nom=Swimwear">Swimwear</a></li>
                                                    <li><a href="shop.php?nom=Skirts">Skirts</a></li>
                                                    <li><a href="shop.php?nom=Jeans">Jeans</a></li>
                                                    <li><a href="shop.php?nom=Trousers">Trousers</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- end col-3 -->
                                        <div class="col-menu col-md-3">
                                            <h6 class="title">Clothing</h6>
                                            <div class="content">
                                                <ul class="menu-col">
                                                    <li><a href="shop.php?nom=Top Wear">Top Wear</a></li>
                                                    <li><a href="shop.php?nom=Party wear">Party wear</a></li>
                                                    <li><a href="shop.php?nom=Bottom Wear">Bottom Wear</a></li>
                                                    <li><a href="shop.php?nom=Indian Wear">Indian Wear</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-menu col-md-3">
                                            <h6 class="title">Accessories</h6>
                                            <div class="content">
                                                <ul class="menu-col">
                                                    <li><a href="shop.php?nom=Bags">Bags</a></li>
                                                    <li><a href="shop.php?nom=Sunglasses">Sunglasses</a></li>
                                                    <li><a href="shop.php?nom=Fragrances">Fragrances</a></li>
                                                    <li><a href="shop.php?nom=Wallets">Wallets</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <!-- end col-3 -->
                                    </div>
                                    <!-- end row -->
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="nav-link dropdown-toggle arrow" data-toggle="dropdown">SHOP</a>
                            <ul class="dropdown-menu">
                                <li><a href="cart.php">Cart</a></li>
                                <li><a href="checkout.php">Checkout</a></li>
                                <li><a href="my-account.php">My Account</a></li>
                                <!-- <li><a href="wishlist.php">Wishlist</a></li> -->
                                <!--<li><a href="shop-detail.php">Shop Detail</a></li> -->
                            </ul>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="service.php">Our Service</a></li>
                        <li class="nav-item"><a class="nav-link" href="contact-us.php">Contact Us</a></li>
                    </ul>
                </div>
                <!-- /.navbar-collapse -->

                <!-- Start Atribute Navigation -->
                <div class="attr-nav">
                    <ul>
                        <li class="search"><a href="#"><i class="fa fa-search"></i></a></li>
                        <li class="side-menu"><a href="#">
                                <i class="fa fa-shopping-bag"></i>
                                <span class="badge">
                                    <?php echo $listpannier->rowCount(); ?>
                                </span>
                            </a></li>
                    </ul>
                </div>
                <!-- End Atribute Navigation -->
            </div>
            <!-- Start Side Menu -->
            <div class="side">
                <a href="#" class="close-side"><i class="fa fa-times"></i></a>
                <li class="cart-box">
                    <ul class="cart-list">
                        <?php
                        $totlal = 0;
                        while ($l = $listpannier->fetch()) {
                            $pro = $controller->produit($l[2])->fetch();
                            ?>
                            <li>
                                <a href="#" class="photo"><img src="<?php echo $pro[7] ?>" class="cart-thumb" alt="" /></a>
                                <h6><a href="#">
                                        <?php echo $pro[5] ?>
                                    </a></h6>
                                <p>
                                    <?php echo $l[3] ?>x - <span class="price">DT
                                        <?php echo $pro[6] ?>
                                    </span>
                                </p>
                            </li>
                            <?php
                            $totlal += $l[5];
                        } ?>
                        <li class="total">
                            <a href="cart.php" class="btn btn-default hvr-hover btn-cart">VIEW CART</a>
                            <span class="float-right"><strong>Total</strong>:
                                <?php echo $totlal ?>
                            </span>
                        </li>
                    </ul>
                </li>
            </div>
            <!-- End Side Menu -->
        </nav>
        <!-- End Navigation -->
    </header>
    <!-- End Main Top -->

    <!-- Start Top Search -->
    <div class="top-search">
        <div class="container">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Search">
                <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>
            </div>
        </div>
    </div>
    <!-- End Top Search -->

    <!-- Start All Title Box -->
    <div class="all-title-box">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>Jewelry Collection</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Jewelry</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="shop-box-inner">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-sm-12 col-xs-12 sidebar-shop-left">
                    <div class="product-categori">

                        <?php
                        echo "<form action=\"" . $_SERVER['PHP_SELF'] . "\" method='POST'>"
                            ?>


                        <div class="filter-price-left">
                            <div class="title-left">
                                <h3>Budget</h3>
                            </div>
                            <div class="price-box-slider">
                                <div id="slider-range"></div>
                                <p>
                                    <input type="text" id="amount" readonly
                                        style="border:0; color:#fbb714; font-weight:bold;" name='prix'>
                                    <input type="hidden" name="nom" value='<?php echo $nom ?>'>
                                    <button class="btn hvr-hover" type="submit">Filter</button>
                                </p>
                            </div>
                        </div>
                        <div class="filter-brand-left">
                            <div class="title-left">
                                <h3>Collection</h3>
                            </div>
                            <div class="brand-box">
                                <ul>
                                    <li>
                                        <div class="radio radio-danger">
                                            <input name="collection_id" id="CollectionAll" value="" type="radio" <?php echo $collectionFilter === '' ? 'checked' : ''; ?>>
                                            <label for="CollectionAll"> Toutes </label>
                                        </div>
                                    </li>
                                    <?php foreach ($collections as $collection) { ?>
                                        <li>
                                            <div class="radio radio-danger">
                                                <input name="collection_id" id="Collection<?php echo $collection['id']; ?>" value="<?php echo $collection['id']; ?>" type="radio" <?php echo $collectionFilter == $collection['id'] ? 'checked' : ''; ?>>
                                                <label for="Collection<?php echo $collection['id']; ?>"> <?php echo htmlspecialchars($collection['nom']); ?> </label>
                                            </div>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <div class="filter-brand-left">
                            <div class="title-left">
                                <h3>Metal & Finish</h3>
                            </div>
                            <div class="brand-box">
                                <ul>
                                    <li>
                                        <div class="radio radio-danger">
                                            <input name="survey" id="Radios1" value="Gold" type="radio">
                                            <label for="Radios1"> Gold </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="radio radio-danger">
                                            <input name="survey" id="Radios2" value="Silver" type="radio">
                                            <label for="Radios2"> Silver </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="radio radio-danger">
                                            <input name="survey" id="Radios3" value="Rose Gold" type="radio">
                                            <label for="Radios3"> Rose Gold </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="radio radio-danger">
                                            <input name="survey" id="Radios4" value="Pearl" type="radio">
                                            <label for="Radios4"> Pearl </label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="radio radio-danger">
                                            <input name="survey" id="Radios5" value="Diamond" type="radio">
                                            <label for="Radios5"> Diamond </label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-9 col-sm-12 col-xs-12 shop-content-right">

                    <div class="row product-categorie-box">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade show active" id="grid-view">
                                <div class="row">
                                    <?php
                                    while ($l = $listproduitdetail->fetch()) {
                                        $stockTotal = $stockController->getTotalStockByRef($l[0]);
                                        $inStock = $stockTotal > 0;
                                        ?>
                                        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                            <div class="products-single fix jewel-card">
                                                <div class="box-img-hover jewel-media">
                                                    <div class="type-lb">
                                                        <p class="sale jewel-badge">
                                                            <?php echo $l[4] ?>
                                                        </p>
                                                    </div>
                                                    <img src="<?php echo $l[7] ?>" class="img-fluid jewel-image" alt="Image">
                                                    <div class="mask-icon jewel-mask">
                                                        <ul>
                                                            <li><a href="#" data-toggle="tooltip" data-placement="right"
                                                                    title="View details"><i class="fas fa-eye"></i></a></li>

                                                        </ul>
                                                        <a class="cart jewel-cart" href="#" type='submit'><?php echo $inStock ? 'Add to Cart' : 'Out of stock'; ?></a>
                                                    </div>
                                                </div>
                                                <div class="why-text jewel-summary">
                                                    <span class="jewel-chip">Luxury piece</span>
                                                    <h4>
                                                        <?php echo $l[5] ?>
                                                    </h4>
                                                    <p class="jewel-meta"><?php echo $l[2] ?> · <?php echo $l[3] ?></p>
                                                    <p class="jewel-meta"><?php echo $inStock ? 'In stock: ' . $stockTotal : 'Rupture de stock'; ?></p>
                                                    <h5>DT
                                                        <?php echo $l[6] ?>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                            <div class="why-text full-width jewel-details">
                                                <span class="jewel-chip">Jewelry detail</span>
                                                <h4>
                                                    <?php echo $l[5] ?>
                                                </h4>
                                                <h5>
                                                    <?php echo $l[2] ?> · <?php echo $l[3] ?>
                                                </h5>
                                                <!-- <h5> <del>$ 60.00</del> $40.79</h5> -->
                                                <p class="jewel-description">
                                                    <?php echo $l[1] ?>
                                                </p>
                                                <form action="addToCart.php" method='POST'>
                                                    <ul>
                                                        <li>
                                                            <div class="form-group size-st">
                                                                <label class="size-label">Size / Length</label>
                                                                <select id="basic" class="  form-control" name='taille'>
                                                                    <option value="Adjustable">Adjustable</option>
                                                                    <option value="16 cm">16 cm</option>
                                                                    <option value="18 cm">18 cm</option>
                                                                    <option value="20 cm">20 cm</option>
                                                                    <option value="Ring size 52">Ring size 52</option>
                                                                    <option value="Ring size 54">Ring size 54</option>
                                                                    <option value="Ring size 56">Ring size 56</option>
                                                                </select>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="form-group quantity-box">
                                                                <label class="control-label">Quantity</label>
                                                                <input name="quantity" class="form-control" value="1" min="1" max="20"
                                                                    type="number" name='qte'>
                                                            </div>
                                                        </li>
                                                        
                                                        <?php
                                                        echo "<input type='hidden' name='ref' value='" . $l[0] . "'>";
                                                        echo "<input type='hidden' name='prix' value='" . $l[6] . "'>";
                                                        echo "<input type='hidden' name='nom' value='" . $l[5] . "'>";
                                                        echo "<input type='hidden' name='image' value='" . $l[7] . "'>";
                                                        echo "<input type='hidden' name='qte' value='1'>";
                                                        echo "<input type='hidden' name='action' value='add'>";
                                                        if (isset($_SESSION['id'])) {
                                                            if ($inStock) {
                                                                echo "<button type='submit' class='btn hvr-hover'>Add to Cart</button>";
                                                            } else {
                                                                echo "<button type='button' class='btn btn-secondary' disabled>Out of stock</button>";
                                                            }

                                                        } else {
                                                            //set the header to redirect to chekout after 0.5 sec
                                                    
                                                            echo "<a href='checkout.php' class='btn hvr-hover'>Proccede to Login</a>";
                                                        }

                                                        ?>
                                                    </ul>
                                                    </form>
                                            
                                                
                                                    <!-- add to card submit -->
                                                
                                                </div>
                                            </div>

                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- End Shop Page -->
<?php
  $chat_inc = __DIR__ . '/inc/chat-embedded.php';
  if (file_exists($chat_inc)) {
      include_once($chat_inc);
  } else {
      error_log('chat include not found: ' . $chat_inc);
  }
?>
    <!-- Start Instagram Feed  -->
    <div class=" instagram-box">
                                                    <div class="main-instagram owl-carousel owl-theme">
                                                        <div class="item">
                                                            <div class="ins-inner-box">
                                                                <img src="images/instagram-img-01.jpg" alt="" />
                                                                <div class="hov-in">
                                                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <div class="ins-inner-box">
                                                                <img src="images/instagram-img-02.jpg" alt="" />
                                                                <div class="hov-in">
                                                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <div class="ins-inner-box">
                                                                <img src="images/instagram-img-03.jpg" alt="" />
                                                                <div class="hov-in">
                                                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <div class="ins-inner-box">
                                                                <img src="images/instagram-img-04.jpg" alt="" />
                                                                <div class="hov-in">
                                                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <div class="ins-inner-box">
                                                                <img src="images/instagram-img-05.jpg" alt="" />
                                                                <div class="hov-in">
                                                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <div class="ins-inner-box">
                                                                <img src="images/instagram-img-06.jpg" alt="" />
                                                                <div class="hov-in">
                                                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <div class="ins-inner-box">
                                                                <img src="images/instagram-img-07.jpg" alt="" />
                                                                <div class="hov-in">
                                                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <div class="ins-inner-box">
                                                                <img src="images/instagram-img-08.jpg" alt="" />
                                                                <div class="hov-in">
                                                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <div class="ins-inner-box">
                                                                <img src="images/instagram-img-09.jpg" alt="" />
                                                                <div class="hov-in">
                                                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="item">
                                                            <div class="ins-inner-box">
                                                                <img src="images/instagram-img-05.jpg" alt="" />
                                                                <div class="hov-in">
                                                                    <a href="#"><i class="fab fa-instagram"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                        </div>
                                        <!-- End Instagram Feed  -->


                                        <!-- Start Footer  -->
    <footer>
        <div class="footer-main">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div class="footer-widget">
                            <h4>About ThewayShop</h4>
                            <p> At thewayshop, we're passionate about providing a seamless and enjoyable shopping experience for our customers. Our platform is more than just a marketplace; it's a destination where quality meets convenience. Driven by a commitment to excellence, we curate a diverse collection of products that cater to your needs, from trendy fashion pieces to must-have gadgets and beyond.
                                </p>
                            <ul>
                                <li><a href="#"><i class="fab fa-facebook" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fab fa-twitter" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fab fa-linkedin" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fab fa-google-plus" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fa fa-rss" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fab fa-pinterest-p" aria-hidden="true"></i></a></li>
                                <li><a href="#"><i class="fab fa-whatsapp" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div class="footer-link">
                            <h4>Information</h4>
                            <ul>
                                <li><a href="#">About Us</a></li>
                                <li><a href="#">Customer Service</a></li>
                                <li><a href="#">Our Sitemap</a></li>
                                <li><a href="#">Terms &amp; Conditions</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                                <li><a href="#">Delivery Information</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div class="footer-link-contact">
                            <h4>Contact Us</h4>
                            <ul>
                                <li>
                                    <p><i class="fas fa-map-marker-alt"></i>Address: Nabeul Mrezgua  <br> QUOTED El Wafa , </p>
                                </li>
                                <li>
                                    <p><i class="fas fa-phone-square"></i>Phone: <a href="tel:+21656725104">+21656725104</a></p>
                                </li>
                                <li>
                                    <p><i class="fas fa-envelope"></i>Email: <a href="mailto:thewayshop@gmail.com">thewayshop@gmail.com</a></p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer  -->




                                        <a href="#" id="back-to-top" title="Back to top"
                                            style="display: none;">&uarr;</a>

                                        <!-- ALL JS FILES -->
                                        <script src="js/jquery-3.2.1.min.js"></script>
                                        <script src="js/popper.min.js"></script>
                                        <script src="js/bootstrap.min.js"></script>
                                        <!-- ALL PLUGINS -->
                                        <script src="js/jquery.superslides.min.js"></script>
                                        <script src="js/bootstrap-select.js"></script>
                                        <script src="js/inewsticker.js"></script>
                                        <script src="js/bootsnav.js."></script>
                                        <script src="js/images-loded.min.js"></script>
                                        <script src="js/isotope.min.js"></script>
                                        <script src="js/owl.carousel.min.js"></script>
                                        <script src="js/baguetteBox.min.js"></script>
                                        <script src="js/jquery-ui.js"></script>
                                        <script src="js/jquery.nicescroll.min.js"></script>
                                        <script src="js/form-validator.min.js"></script>
                                        <script src="js/contact-form-script.js"></script>
                                        <script src="js/custom.js"></script>
                                        <!-- Response Formatter + Chatbot -->
                                        <script src="js/response-formatter.js"></script>
                                        <script src="js/chatbot.js"></script>
                                         <!-- Chatbot Widget JS -->
                                        <script src="js/chatbot-widget.js"></script>
</body>

</html>