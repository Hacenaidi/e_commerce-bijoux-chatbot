<?php

session_start();

include_once('../../model/Adress.php');



include_once('../../controller/AdressController.php');

include_once('../../controller/OrderController.php');
include_once('../../controller/PannierController.php');
include_once('../../controller/StockController.php');
include_once('../../controller/ProduitController.php');

//create an object of type AdressController
$adressController = new AdressController();

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    //create an object of type Adress
    $adress = new Adress($_POST['first_name'],$_POST['last_name'],$_POST['email'],$_POST['adress'],$_POST['telephone'],$_POST['mandate'],$_POST['accrediation'],$_POST['zip'],'',$_SESSION['id']);
    //call the function createAdress from the controller
    $address_id=$adressController->createAdress($adress);
    //create an object of type OrderController
    $orderController = new OrderController();
    //create an object of type Order
    $order = new Order('',$address_id,$_POST['total']);
        //call the function createOrder from the controller
    $orderId = $orderController->createOrder($order);

    $produitController = new ProduitController();
    $orderItems = array();

    //decrease stock using cart content
    $pannierController = new PannierController();
    $stockController = new StockController();
    $cartRows = $pannierController->listpannier($_SESSION['id']);
    while ($cartItem = $cartRows->fetch()) {
        $productData = $produitController->produit($cartItem[2])->fetch(PDO::FETCH_ASSOC);
        $productName = $productData && isset($productData['nom']) ? $productData['nom'] : $cartItem[2];
        $productImage = $productData && isset($productData['image']) ? $productData['image'] : '';
        $unitPrice = $productData && isset($productData['prix']) ? $productData['prix'] : 0;

        $orderItems[] = array(
            'product_ref' => $cartItem[2],
            'product_name' => $productName,
            'product_image' => $productImage,
            'quantity' => $cartItem[3],
            'taille' => $cartItem[4],
            'unit_price' => $unitPrice,
            'line_total' => $cartItem[5]
        );

        $stockController->decreaseStock($cartItem[2], $cartItem[4], $cartItem[3]);
    }

    $orderController->saveOrderItems($orderId, $orderItems);

    //empty the cart
    $pannierController->emptypannier($_SESSION['id']);

   


    $orderTotal = isset($_POST['total']) ? $_POST['total'] : 0;
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Order Confirmed - Glowear</title>
        <meta http-equiv="refresh" content="6;url=../public/index.php">
        <link rel="stylesheet" href="../css/base.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400;1,600&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">
        <style>
            body {
                margin: 0;
                min-height: 100vh;
                display: grid;
                place-items: center;
                padding: 20px;
                cursor: auto;
            }
            .confirm-wrap {
                width: min(720px, 100%);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 22px;
                background: rgba(10, 10, 10, 0.86);
                backdrop-filter: blur(12px);
                box-shadow: 0 26px 80px rgba(0, 0, 0, 0.4);
                padding: 30px;
                text-align: center;
                color: var(--cream);
            }
            .confirm-icon {
                width: 62px;
                height: 62px;
                border-radius: 50%;
                margin: 0 auto 14px;
                display: grid;
                place-items: center;
                color: #fff;
                background: linear-gradient(135deg, #16a34a, #22c55e);
                box-shadow: 0 10px 30px rgba(34, 197, 94, 0.25);
                font-size: 26px;
            }
            .confirm-title {
                margin: 0;
                font-family: 'Cormorant Garamond', serif;
                font-size: clamp(2.2rem, 4vw, 3rem);
                font-weight: 300;
                color: #fff;
            }
            .confirm-sub {
                margin: 10px 0 0;
                color: rgba(240, 234, 224, 0.78);
            }
            .confirm-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 10px;
                margin-top: 22px;
                text-align: left;
            }
            .confirm-item {
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 14px;
                background: rgba(255, 255, 255, 0.02);
                padding: 12px 14px;
            }
            .confirm-item span {
                display: block;
                font-size: 11px;
                letter-spacing: 0.13em;
                text-transform: uppercase;
                color: rgba(240, 234, 224, 0.58);
                margin-bottom: 6px;
            }
            .confirm-item strong {
                color: #fff;
                font-weight: 500;
            }
            .confirm-actions {
                margin-top: 20px;
                display: flex;
                justify-content: center;
                gap: 10px;
                flex-wrap: wrap;
            }
            .confirm-note {
                margin-top: 12px;
                font-size: 0.9rem;
                color: rgba(240, 234, 224, 0.62);
            }
            @media (max-width: 640px) {
                .confirm-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>
    </head>
    <body>
        <div class="grain"></div>
        <div id="glitter-layer"></div>
        <section class="confirm-wrap">
            <div class="confirm-icon"><i class="fa fa-check"></i></div>
            <h1 class="confirm-title">Order Confirmed</h1>
            <p class="confirm-sub">Thank you. Your order was placed successfully.</p>

            <div class="confirm-grid">
                <div class="confirm-item">
                    <span>Order ID</span>
                    <strong>#<?php echo htmlspecialchars((string)$orderId); ?></strong>
                </div>
                <div class="confirm-item">
                    <span>Total</span>
                    <strong>DT <?php echo htmlspecialchars((string)$orderTotal); ?></strong>
                </div>
            </div>

            <div class="confirm-actions">
                <a class="btn-red" href="../account/my-account.php">My Account</a>
                <a class="btn-ghost" href="../public/shop.php?nom=Necklaces">Continue Shopping</a>
            </div>

            <p class="confirm-note">You will be redirected to the homepage in a few seconds.</p>
        </section>
    </body>
    </html>
    <?php
    exit;

    } else {
        header("Location: ../public/checkout.php");
        exit;
    }


?>
