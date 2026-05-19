<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '../../controller/OrderController.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$clientId = $_SESSION['id'];
$orderId = isset($_POST['order_id']) ? (int) $_POST['order_id'] : 0;

if (!$orderId) {
    echo json_encode(['error' => 'Missing order_id']);
    exit;
}

try {
    $oc = new OrderController();
    
    // Use public method to cancel order with all checks
    $result = $oc->cancelOrderIfPending($orderId, $clientId);
    
    if ($result['success']) {
        echo json_encode(['success' => true, 'message' => 'Order cancelled successfully']);
    } else {
        echo json_encode(['error' => $result['error']]);
    }
    exit;
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
    exit;
}


