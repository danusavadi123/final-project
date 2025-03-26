<?php
// Payment Success - Updates order status

include '../includes/session.php';
checkRole('buyer'); // Only buyers can access this page
include '../config/database.php';

if (!isset($_GET['order_id']) || !isset($_GET['payment_id'])) {
    $_SESSION['error'] = "Invalid payment response.";
    header("Location: failure.php");
    exit();
}

$order_id = $_GET['order_id'];
$payment_id = $_GET['payment_id'];

// Update order status in the database
$query = "UPDATE orders SET status='Processing' WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();

$_SESSION['success'] = "Payment successful! Order is now processing.";
header("Location: ../buyer/order_history.php");
exit();
?>