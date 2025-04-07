<?php
require_once('../includes/session.php');
require_once('../config/database.php');
require_once('../vendor/autoload.php'); // Razorpay autoload (install via composer)

// Ensure buyer is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    header("Location: ../auth/login.php");
    exit();
}

// Razorpay API Keys
$razorpay_api_key = "rzp_test_YourApiKeyHere";
$razorpay_api_secret = "YourApiSecretHere";

use Razorpay\Api\Api;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $price = floatval($_POST['price']);
    $buyer_id = $_SESSION['user_id'];
    $total_amount = $quantity * $price;

    // Fetch product to get seller ID
    $stmt = $conn->prepare("SELECT seller_id FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        die("Product not found.");
    }
    $product = $result->fetch_assoc();
    $seller_id = $product['seller_id'];

    // Insert order in DB
    $order_query = $conn->prepare("INSERT INTO orders (buyer_id, seller_id, product_id, quantity, total_amount, payment_status, order_status, created_at) 
                                   VALUES (?, ?, ?, ?, ?, 'pending', 'processing', NOW())");
    $order_query->bind_param("iiidi", $buyer_id, $seller_id, $product_id, $quantity, $total_amount);
    $order_query->execute();
    $order_id = $conn->insert_id;

    // Create Razorpay Order
    $api = new Api($razorpay_api_key, $razorpay_api_secret);
    $razorpayOrder = $api->order->create([
        'receipt' => 'order_rcptid_' . $order_id,
        'amount' => $total_amount * 100, // in paise
        'currency' => 'INR',
        'notes' => [
            'buyer_id' => $buyer_id,
            'order_id' => $order_id
        ]
    ]);

    // Store Razorpay order ID for verification
    $update = $conn->prepare("UPDATE orders SET razorpay_order_id = ? WHERE id = ?");
    $update->bind_param("si", $razorpayOrder['id'], $order_id);
    $update->execute();

    // Redirect to checkout page
    header("Location: ../payments/checkout.php?order_id=" . $order_id);
    exit();
} else {
    header("Location: ../buyer/browse_products.php");
    exit();
}