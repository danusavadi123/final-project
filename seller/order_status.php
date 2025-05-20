<?php
session_start();
require_once '../config/db.php';
header('Content-Type: application/json');

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['role']) && $_SESSION['role'] === 'seller') {
    $orderId = $_POST['id'] ?? null;

    if ($orderId) {
        $stmt = $conn->prepare("UPDATE orders SET order_status = 'Processed' WHERE id = ? AND seller_id = ?");
        $stmt->bind_param("ii", $orderId, $_SESSION['user_id']);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Order marked as processed.";
        } else {
            $response['success'] = false;
            $response['message'] = "Failed to update order status.";
        }

        $stmt->close();
    } else {
        $response['success'] = false;
        $response['message'] = "Order ID missing.";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Unauthorized or invalid request.";
}

echo json_encode($response);
?>
