<?php

include_once('../../model/Order.php');
include_once('../../database/config.php');
class OrderController extends Connexion{
    function __construct() {
        parent::__construct();
        
    }
    //this function is used to create an order
    function createOrder(Order $order) {
        $address_id = $order->getAddress_id();
        $total = $order->getTotal();
        $status = method_exists($order, 'getStatus') ? $order->getStatus() : null;
        try {
            if ($status !== null && $status !== '') {
                $query = "INSERT INTO `order` (`address_id`, `total`, `status`) VALUES (?, ?, ?)";
                $res = $this->pdo->prepare($query);
                $res->execute(array($address_id, $total, $status));
            } else {
                // Let DB apply default for status when not provided
                $query = "INSERT INTO `order` (`address_id`, `total`) VALUES (?, ?)";
                $res = $this->pdo->prepare($query);
                $res->execute(array($address_id, $total));
            }
            return $this->pdo->lastInsertId();
        } catch (Exception $e) {
            return false;
        }
    }

    function saveOrderItems($orderId, array $items) {
        $query = "INSERT INTO order_items (`order_id`, `product_ref`, `product_name`, `product_image`, `quantity`, `taille`, `unit_price`, `line_total`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $res = $this->pdo->prepare($query);

        foreach ($items as $item) {
            $res->execute(array(
                $orderId,
                isset($item['product_ref']) ? $item['product_ref'] : '',
                isset($item['product_name']) ? $item['product_name'] : '',
                isset($item['product_image']) ? $item['product_image'] : null,
                isset($item['quantity']) ? (int) $item['quantity'] : 1,
                isset($item['taille']) ? $item['taille'] : null,
                isset($item['unit_price']) ? $item['unit_price'] : 0,
                isset($item['line_total']) ? $item['line_total'] : 0
            ));
        }

        return true;
    }

    function getOrderDetails($orderId) {
        $query = "SELECT o.id AS order_id, o.total, o.status, a.first_name, a.last_name, a.email, a.adress, a.telephone, a.zip
                      FROM `order` o
                      INNER JOIN address a ON o.address_id = a.id
                      WHERE o.id = ?";
        $res = $this->pdo->prepare($query);
        $res->execute(array($orderId));
        $order = $res->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            return false;
        }

        $itemsQuery = "SELECT * FROM order_items WHERE order_id = ? ORDER BY id ASC";
        $itemsRes = $this->pdo->prepare($itemsQuery);
        $itemsRes->execute(array($orderId));
        $items = $itemsRes->fetchAll(PDO::FETCH_ASSOC);

        return array('order' => $order, 'items' => $items);
    }
    function listorder(){
        $query = "SELECT o.*, a.* FROM `order` o INNER JOIN address a ON o.address_id = a.id ORDER BY o.id DESC";
        $res = $this->pdo->prepare($query);
        $res->execute();
        return $res;
    }

    function listOrdersByClient($clientId) {
       
        $query = "SELECT o.id, o.total, o.status, a.first_name, a.last_name, a.email, a.adress, a.telephone
                      FROM `order` o
                      INNER JOIN address a ON o.address_id = a.id
                      WHERE a.id_client = ?
                      ORDER BY o.id DESC";

        $res = $this->pdo->prepare($query);
        $res->execute(array($clientId));
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    function deleteOrder($ref) {
        $query = "DELETE FROM `order` WHERE `id` = '$ref'";
        $res = $this->pdo->prepare($query);
        $res->execute();
        return $res; 
    }

    function approveOrder($ref) {
      
        $query = "UPDATE `order` SET `status` = 'approved' WHERE `id` = ?";
        $res = $this->pdo->prepare($query);
        $res->execute(array($ref));
        return $res;
    }

    function cancelOrder($ref) {
    
        $query = "UPDATE `order` SET `status` = 'cancelled' WHERE `id` = ?";
        $res = $this->pdo->prepare($query);
        $res->execute(array($ref));
        return $res;
       
    }

    // Public method to verify order ownership
    function verifyOrderOwnership($orderId, $clientId) {
        $query = "SELECT a.id_client FROM `order` o INNER JOIN address a ON o.address_id = a.id WHERE o.id = ?";
        $res = $this->pdo->prepare($query);
        $res->execute(array($orderId));
        $owner = $res->fetchColumn();
        return $owner && (int)$owner === (int)$clientId;
    }

    // Public method to get order status
    function getOrderStatus($orderId) {
        
        $query = "SELECT status FROM `order` WHERE id = ?";
        $res = $this->pdo->prepare($query);
        $res->execute(array($orderId));
        return strtolower($res->fetchColumn() ?: 'pending');
    }

    // Public method to cancel order with status check
    function cancelOrderIfPending($orderId, $clientId) {
        // Verify ownership
        if (!$this->verifyOrderOwnership($orderId, $clientId)) {
            return ['success' => false, 'error' => 'Access denied'];
        }

        // Check status
        $status = $this->getOrderStatus($orderId);
        if ($status !== 'pending') {
            return ['success' => false, 'error' => 'Only pending orders can be cancelled'];
        }

        // Cancel order
        try {
            $query = "UPDATE `order` SET `status` = 'cancelled' WHERE `id` = ?";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array($orderId));
            return ['success' => true, 'error' => null];
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Unable to cancel: ' . $e->getMessage()];
        }
    }

    // Public getter for PDO connection
    public function getPdo() {
        return $this->pdo;
    }

}


?>