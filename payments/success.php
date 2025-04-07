<?php
require_once('../includes/session.php');
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    header("Location: ../auth/login.php");
    exit();
}

require('../vendor/autoload.php'); // Razorpay PHP SDK

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

// Replace with your Razorpay API credentials
$keyId = 'rzp_test_YourApiKeyHere';
$keySecret = 'YourApiSecretHere';

if (isset($_GET['order_id'], $_GET['payment_id'], $_GET['signature'])) {
    $order_id = intval($_GET['order_id']);
    $payment_id = $_GET['payment_id'];
    $signature = $_GET['signature'];

    // Fetch razorpay_order_id from DB for signature verification
    $stmt = $conn->prepare("SELECT razorpay_order_id FROM orders WHERE id = ? AND buyer_id = ?");
    $stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($razorpay_order_id);

    if ($stmt->num_rows === 1) {
        $stmt->fetch();

        try {
            $api = new Api($keyId, $keySecret);

            $attributes = [
                'razorpay_order_id' => $razorpay_order_id,
                'razorpay_payment_id' => $payment_id,
                'razorpay_signature' => $signature
            ];

            $api->utility->verifyPaymentSignature($attributes);

            // Signature verified, update order status
            $update = $conn->prepare("UPDATE orders SET payment_status = 'Paid', payment_id = ? WHERE id = ?");
            $update->bind_param("si", $payment_id, $order_id);
            $update->execute();

            echo "<h2>Payment Successful!</h2>";
            echo "<p>Your order has been confirmed.</p>";
            echo "<a href='../buyer/order_history.php'>View Orders</a>";

        } catch (SignatureVerificationError $e) {
            echo "<h2>Payment Verification Failed</h2>";
            echo "<p>" . $e->getMessage() . "</p>";
        }

    } else {
        echo "<h2>Invalid Order</h2>";
    }
} else {
    echo "<h2>Invalid Payment Request</h2>";
}
?>