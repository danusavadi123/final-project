<?php
session_start();
require_once('../config/db.php');
require_once('../vendor/autoload.php'); // Razorpay PHP SDK

use Razorpay\Api\Api;

// Check if razorpay_order and phone exist in session
if (
    !isset($_SESSION['razorpay_order']) ||
    !isset($_SESSION['razorpay_order']['total_amount']) ||
    !isset($_SESSION['phone'])
) {
    // Optional: clear session data to avoid looping
    unset($_SESSION['razorpay_order']);
    unset($_SESSION['razorpay_order_id']);
    exit;
}

$razorpayData = $_SESSION['razorpay_order'];
$totalAmount = (int) $razorpayData['total_amount'] * 100; // Razorpay uses paise

// Razorpay credentials (use env or config ideally)
$apiKey = 'rzp_test_ODxanSFZcvxcbR';
$apiSecret = 'HlRYDe9RT6QqwBch2BTAMSUF';

// Create Razorpay API instance
$api = new Api($apiKey, $apiSecret);

// Try creating the Razorpay order
try {
    $razorpayOrder = $api->order->create([
        'receipt' => 'ORDER_' . rand(1000, 9999),
        'amount' => $totalAmount,
        'currency' => 'INR',
        'payment_capture' => 1
    ]);

    $_SESSION['razorpay_order_id'] = $razorpayOrder['id'];

} catch (Exception $e) {
    // Handle API error gracefully
    echo "<script>alert('Failed to initialize payment. Please try again.'); window.location.href='../checkout/fallback.php';</script>";
    exit;
}

// Safe HTML encoding
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Redirecting to Razorpay...</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <script>
        const options = {
            "key": "<?= h($apiKey) ?>",
            "amount": "<?= h($totalAmount) ?>",
            "currency": "INR",
            "name": "Bazaar E-commerce",
            "description": "Order Payment",
            "image": "https://yourdomain.com/logo.png", // Optional logo
            "order_id": "<?= h($razorpayOrder['id']) ?>",
            "handler": function (response) {
                // On successful payment
                window.location.href = "./verify.php?payment_id=" + response.razorpay_payment_id;
            },
            "prefill": {
                "name": "<?= h($_SESSION['username'] ?? 'Customer') ?>",
                "email": "<?= h($_SESSION['email'] ?? 'test@example.com') ?>",
                "contact": "<?= h($_SESSION['phone']) ?>"
            },
            "theme": {
                "color": "#3182bd"
            }
        };
        const rzp = new Razorpay(options);
        rzp.open();
    </script>
</body>
</html>
