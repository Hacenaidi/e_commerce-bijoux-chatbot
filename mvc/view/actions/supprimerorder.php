<?php

session_start();

$orderCtrl = __DIR__ . '/../../controller/OrderController.php';
if (file_exists($orderCtrl)) include_once($orderCtrl);
$pc = new OrderController();

//if the request is get and is admin delete a product from the ref passed by url

if (isset($_SESSION['admin']) && $_SESSION['admin'] == true) {


    
   $ref = $_GET['ref'];



    $pc->deleteOrder($ref);
    header("Location: ../admin/admin_dashboard.php?show=orders#orders");
}





?>
