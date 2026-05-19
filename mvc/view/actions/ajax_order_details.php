<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../controller/OrderController.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$clientId = $_SESSION['id'];
$orderId = isset($_GET['order_id']) ? (int) $_GET['order_id'] : 0;

if (!$orderId) {
    echo json_encode(['error' => 'Missing order_id']);
    exit;
}

try {
    $oc = new OrderController();
    
    // Verify ownership using public method
    if (!$oc->verifyOrderOwnership($orderId, $clientId)) {
        echo json_encode(['error' => 'Access denied']);
        exit;
    }
    
    // Get order details
    $data = $oc->getOrderDetails($orderId);
    if (!$data) {
        echo json_encode(['error' => 'Order not found']);
        exit;
    }
    
    // Normalize items images using view helper if available
    $viewHelpers = __DIR__ . '/../inc/view_helpers.php';
    if (file_exists($viewHelpers)) {
        include_once($viewHelpers);
    }
    
    foreach ($data['items'] as &$item) {
        if (function_exists('view_safe_image_path')) {
            $item['product_image'] = view_safe_image_path($item['product_image']);
        }
    }
    
    echo json_encode(['success' => true, 'order' => $data['order'], 'items' => $data['items']]);
    exit;
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
    exit;
}


