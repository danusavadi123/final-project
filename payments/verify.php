<?php
session_start();
include '../config/dbcon.php'; // your database connection file

require '../vendor/autoload.php';

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

// Razorpay credentials
$api_key = 'rzp_test_ODxanSFZcvxcbR';
$api_secret = 'HlRYDe9RT6QqwBch2BTAMSUF';

if (isset($_POST['razorpay_payment_id']) && isset($_POST['razorpay_order_id']) && isset($_POST['razorpay_signature'])) {
    $api = new Api($api_key, $api_secret);

    $razorpay_order_id = $_POST['razorpay_order_id'];
    $razorpay_payment_id = $_POST['razorpay_payment_id'];
    $razorpay_signature = $_POST['razorpay_signature'];

    $generated_signature = hash_hmac('sha256', $razorpay_order_id . "|" . $razorpay_payment_id, $api_secret);

    if ($generated_signature === $razorpay_signature) {
        // ✅ Signature is valid, save order
        $user_id = $_SESSION['user_id']; // adjust according to your session
        $total_price = $_SESSION['total_price']; // get total from session or calculate
        $order_date = date("Y-m-d H:i:s");

        $query = "INSERT INTO orders (user_id, total_price, payment_id, order_date) 
                  VALUES ('$user_id', '$total_price', '$razorpay_payment_id', '$order_date')";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Payment verified and order placed successfully.'); window.location.href = '../buyer/order_history.php';</script>";
        } else {
            echo "Database Error: " . mysqli_error($conn);
        }
    } else {
        echo "<script>alert('Payment verification failed: Invalid signature.'); window.location.href = '../buyer/checkout.php';</script>";
    }
} else {
    echo "<script>alert('Missing Razorpay payment details.'); window.location.href = '../buyer/checkout.php';</script>";
}
?>
