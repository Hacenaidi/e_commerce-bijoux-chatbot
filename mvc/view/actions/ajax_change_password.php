<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../controller/ClientController.php';

if (!isset($_SESSION['id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$clientId = $_SESSION['id'];
$current = isset($_POST['current_password']) ? trim($_POST['current_password']) : '';
$new = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
$confirm = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

if ($new === '' || $current === '' || $confirm === '') {
    echo json_encode(['error' => 'Missing fields']);
    exit;
}

if ($new !== $confirm) {
    echo json_encode(['error' => 'New password and confirmation do not match']);
    exit;
}

try {
    $cc = new ClientController();
    $result = $cc->changePassword($clientId, $current, $new);
    if (isset($result['success']) && $result['success']) {
        echo json_encode(['success' => true, 'message' => 'Password updated']);
    } else {
        echo json_encode(['error' => $result['error'] ?? 'Unable to update password']);
    }
    exit;
} catch (Exception $e) {
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
    exit;
}
?>
