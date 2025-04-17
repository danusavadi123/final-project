<?php
session_start();
require_once '../config/db.php';
header('Content-Type: application/json');

$response = [];

if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller') {
    $seller_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("
        SELECT o.id, o.buyer_id, o.quantity, o.order_status, p.name AS product_name
        FROM orders o
        JOIN products p ON o.product_id = p.id
        WHERE o.seller_id = ? AND o.order_status = 'Pending'
    ");
    $stmt->bind_param("i", $seller_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }

        $response['success'] = true;
        $response['count'] = count($orders);
        $response['orders'] = $orders;
    } else {
        $response['success'] = false;
        $response['message'] = 'Database query failed: ' . $stmt->error;
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Unauthorized';
}

echo json_encode($response);
?>
