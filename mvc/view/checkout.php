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
    <link rel="stylesheet" href="css/chatbot-messages.css">
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
                        <li class="dropdown megamenu-fw">
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
                        <li class="dropdown active">
                            <a href="#" class="nav-link dropdown-toggle arrow" data-toggle="dropdown">SHOP</a>
                            <ul class="dropdown-menu">
                                <li><a href="cart.php">Cart</a></li>
                                <li><a href="checkout.php">Checkout</a></li>
                                <li><a href="my-account.php">My Account</a></li>
                                <!-- <li><a href="wishlist.php">Wishlist</a></li> -->
                                <!--<li><a href="shop-detail.php">Shop Detail</a></li>-->
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
                    <h2>Checkout</h2>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Shop</a></li>
                        <li class="breadcrumb-item active">Checkout</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- End All Title Box -->

    <!-- Start Cart  -->

    <div class="cart-box-main">
        <div class="container">
            <?php if (!(isset($_SESSION['id']))) { ?>

                <div class="row new-account-login">
                    <div class="col-sm-6 col-lg-6 mb-3">
                        <div class="title-left">
                            <h3>Account Login</h3>
                        </div>
                        <h5><a data-toggle="collapse" href="#formLogin" role="button" aria-expanded="false">Click here to
                                Login</a></h5>
                        <form action="client_login.php" method="post" class="mt-3 collapse review-form-box" id="formLogin">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="InputEmail" class="mb-0">Email Address</label>
                                    <input name="email" type="email" class="form-control" id="InputEmail"
                                        placeholder="Enter Email">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="InputPassword" class="mb-0">Password</label>
                                    <input name="password" type="password" class="form-control" id="InputPassword"
                                        placeholder="Password">
                                </div>
                            </div>
                            <button type="submit" class="btn hvr-hover">Login</button>
                        </form>
                    </div>
                    <div class="col-sm-6 col-lg-6 mb-3">
                        <div class="title-left">
                            <h3>Create New Account</h3>
                        </div>
                        <h5><a data-toggle="collapse" href="#formRegister" role="button" aria-expanded="false">Click here to
                                Register</a></h5>
                        <form class="mt-3 collapse review-form-box" action="client_signup.php" method="post"
                            id="formRegister">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="InputName" class="mb-0">First Name</label>
                                    <input name="nom" type="text" class="form-control" id="InputName"
                                        placeholder="First Name">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="InputLastname" class="mb-0">Last Name</label>
                                    <input name="prenom" type="text" class="form-control" id="InputLastname"
                                        placeholder="Last Name">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="InputEmail1" class="mb-0">Email Address</label>
                                    <input name="email" type="email" class="form-control" id="InputEmail1"
                                        placeholder="Enter Email">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="InputPassword1" class="mb-0">Password</label>
                                    <input name="password" type="password" class="form-control" id="InputPassword1"
                                        placeholder="Password">
                                </div>
                            </div>
                            <button type="submit" class="btn hvr-hover">Register</button>
                        </form>
                    </div>
                </div>
            <?php } 
            
            ?>
            <!-- dont show this div only when logged in exists -->
            <?php if (isset($_SESSION['id']) && $totlal>0) { ?>
                <form action="placeOrder.php" method="post">
                    <div class="row">
                        <div class="col-sm-6 col-lg-6 mb-3">
                       
                            <div class="checkout-address">
                                <div class="title-left">
                                    <h3>Billing address</h3>
                                </div>
                                <div class="needs-validation" novalidate>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="firstName">First name *</label>
                                            <input name="first_name" type="text" class="form-control" id="firstName"
                                                placeholder="" value="" required>
                                            <div class="invalid-feedback"> Valid first name is required. </div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="lastName">Last name *</label>
                                            <input name="last_name" type="text" class="form-control" id="lastName"
                                                placeholder="" value="" required>
                                            <div class="invalid-feedback"> Valid last name is required. </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email">Email Address *</label>
                                        <input name="email" type="email" class="form-control" id="email" placeholder="">
                                        <div class="invalid-feedback"> Please enter a valid email address for shipping
                                            updates. </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address">Address *</label>
                                        <input name="adress" type="text" class="form-control" id="address" placeholder=""
                                            required>
                                        <div class="invalid-feedback"> Please enter your shipping address. </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address2">Telephone*</label>
                                        <input name="telephone" type="tel" class="form-control" id="telephone"
                                            placeholder="">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5 mb-3">
                                            <label for="country">Mandate *</label>
                                            <input name="mandate" type="text" class="form-control" id="Mandate"
                                                placeholder="" required>
                                            <div class="invalid-feedback"> Please select a valid Mandate. </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="state">Accreditation *</label>
                                            <input name="accrediation" type="text" class="form-control" id="Accreditation"
                                                placeholder="" required>
                                            <div class="invalid-feedback"> Please provide a valid Accreditation. </div>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="zip">Zip *</label>
                                            <input name="zip" type="text" class="form-control" id="zip" placeholder=""
                                                required>
                                            <div class="invalid-feedback"> Zip code required. </div>
                                        </div>
                                        <input type="hidden" value=<?php echo $totlal ?> name="total" id="">
                                    </div>

                                </div>
                            </div>
                      

                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="order-box">
                                <div class="title-left">
                                    <h3>Your order</h3>
                                </div>
                                <hr>
                                <div class="d-flex gr-total">
                                    <h5>Grand Total</h5>
                                    <!-- get the total from the PannierController -->
                                    <div class="ml-auto h5"> DT
                                        <?php echo $totlal ?>
                                    </div>

                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="col-12 d-flex shopping-box">
                            <!-- submit button -->
                            <button style="color:white;" type="submit" class="ml-auto btn hvr-hover">Place Order </button>
                        </div>


                    </div>

                </form>
            <?php }else{ ?>
               <h1>Add Products to your Cart inorder to checkout</h1> 

           
            <?php } ?>
        </div>
        <hr class="mb-4">

        <hr class="mb-4">
        <!-- End Cart -->

        <!-- Start Instagram Feed  -->
        <div class="instagram-box">
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
                                <p> At thewayshop, we're passionate about providing a seamless and enjoyable shopping
                                    experience for our customers. Our platform is more than just a marketplace; it's a
                                    destination where quality meets convenience. Driven by a commitment to excellence,
                                    we curate a diverse collection of products that cater to your needs, from trendy
                                    fashion pieces to must-have gadgets and beyond.
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
                                        <p><i class="fas fa-map-marker-alt"></i>Address: Nabeul Mrezgua <br> QUOTED El
                                            Wafa , </p>
                                    </li>
                                    <li>
                                        <p><i class="fas fa-phone-square"></i>Phone: <a
                                                href="tel:+21656725104">+21656725104</a></p>
                                    </li>
                                    <li>
                                        <p><i class="fas fa-envelope"></i>Email: <a
                                                href="mailto:thewayshop@gmail.com">thewayshop@gmail.com</a></p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- End Footer  -->

        <a href="#" id="back-to-top" title="Back to top" style="display: none;">&uarr;</a>

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
        <script src="js/form-validator.min.js"></script>
        <script src="js/contact-form-script.js"></script>
        <script src="js/custom.js"></script>
        <!-- Response Formatter -->
        <script src="js/response-formatter.js"></script>
        <!-- Chatbot scripts -->
        <script src="js/chatbot.js"></script>
        <script src="js/chatbot-widget.js"></script>
        <?php
            $chat_inc = __DIR__ . '/inc/chat-embedded.php';
            if (file_exists($chat_inc)) {
                include_once($chat_inc);
            } else {
                error_log('chat include not found: ' . $chat_inc);
            }
        ?>
</body>

</html>