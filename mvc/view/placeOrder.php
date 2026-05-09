<?php

session_start();

include_once('../model/Adress.php');



include_once('../controller/AdressController.php');

include_once('../controller/OrderController.php');
include_once('../controller/PannierController.php');
include_once('../controller/StockController.php');

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
    $orderController->createOrder($order);

    //decrease stock using cart content
    $pannierController = new PannierController();
    $stockController = new StockController();
    $cartRows = $pannierController->listpannier($_SESSION['id']);
    while ($cartItem = $cartRows->fetch()) {
        $stockController->decreaseStock($cartItem[2], $cartItem[4], $cartItem[3]);
    }

    //empty the cart
    $pannierController->emptypannier($_SESSION['id']);

   


    //redirect to the page adress.php after 0.5 seconds
    header("refresh:0.5;url=index.php");
    //alert the user that the order has been placed
    echo '<script>alert("Order placed successfully")</script>';
    
}





?>