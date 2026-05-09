<?php

session_start();

include_once('../controller/OrderController.php');
$pc = new OrderController();

//if the request is get and is admin delete a product from the ref passed by url

if (isset($_SESSION['admin']) && $_SESSION['admin'] == true) {


    
   $ref = $_GET['ref'];



    $pc->deleteOrder($ref);
    header("Location: admin_dashboard.php?show=orders#orders");
}





?>