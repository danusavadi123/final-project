<?php
session_start();
require_once('../config/db.php');
require_once('../vendor/autoload.php'); // Razorpay SDK

use Razorpay\Api\Api;

if (!isset($_SESSION['checkout_data'])) {
    die("Session expired. Please try placing the order again.");
}

$data = $_SESSION['checkout_data'];
$orders = $data['orders'];
$buyer_id = $data['buyer_id'];
$address = $data['address'];
$contact = $data['contact_number'];
$order_status = $data['order_status'];
$order_date = $data['order_date'];
$expected_delivery = $data['expected_delivery'];
$payment_method = $data['payment_method'];
$single_order = $data['single_order'];

// Razorpay credentials
$api_key = 'rzp_test_ODxanSFZcvxcbR';
$api_secret = 'HlRYDe9RT6QqwBch2BTAMSUF';

$api = new Api($api_key, $api_secret);

// If Razorpay response is received
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['razorpay_payment_id'])) {
    $payment_id = $_POST['razorpay_payment_id'];

    try {
        $payment = $api->payment->fetch($payment_id);

        // Capture the payment if it's authorized
        if ($payment->status === 'authorized') {
            $payment->capture(['amount' => $payment['amount']]);
            $payment = $api->payment->fetch($payment_id); // Refresh status after capture
        }

        if ($payment->status === 'captured') {
            // Insert orders
            foreach ($orders as $order) {
                $stmt = mysqli_prepare($conn, "INSERT INTO orders (product_id, seller_id, buyer_id, quantity, total_amount, address, order_status, order_date, expected_delivery, payment_method, payment_id, contact_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, 'iiiidsssssss',
                    $order['product_id'],
                    $order['seller_id'],
                    $buyer_id,
                    $order['quantity'],
                    $order['total_amount'],
                    $address,
                    $order_status,
                    $order_date,
                    $expected_delivery,
                    $payment_method,
                    $payment_id,
                    $contact
                );
                mysqli_stmt_execute($stmt);
            }

            if (!$single_order) {
                mysqli_query($conn, "DELETE FROM cart WHERE buyer_id = $buyer_id");
            }

            unset($_SESSION['checkout_data']);
            echo "<script>
                alert('🎉 Order placed successfully!');
                window.location.href = '../buyer/order_history.php';
            </script>";
            exit;
        } else {
            echo "<script>alert('Payment not captured. Please try again.'); window.location.href = '../buyer/dashboard.php';</script>";
            exit;
        }
    } catch (Exception $e) {
        echo "<script>alert('Error verifying payment: " . $e->getMessage() . "'); window.location.href = '../buyer/dashboard.php';</script>";
        exit;
    }
}

// Otherwise, display Razorpay checkout
$total_amount = 0;
foreach ($orders as $order) {
    $total_amount += $order['total_amount'];
}
$total_amount_in_paisa = $total_amount * 100;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout - Razorpay</title>
  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body onload="startRazorpay()">

<script>
function startRazorpay() {
    var options = {
        "key": "<?= $api_key ?>",
        "amount": "<?= $total_amount_in_paisa ?>",
        "currency": "INR",
        "name": "Your Shop Name",
        "description": "Order Payment",
        "handler": function (response){
            // Submit form with payment ID
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '';

            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'razorpay_payment_id';
            input.value = response.razorpay_payment_id;
            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        },
        "prefill": {
            "name": "<?= $_SESSION['username'] ?? '' ?>",
            "email": "<?= $_SESSION['email'] ?? '' ?>",
            "contact": "<?= $contact ?>"
        },
        "theme": {
            "color": "#3182bd"
        }
    };
    var rzp = new Razorpay(options);
    rzp.open();
}
</script>

</body>
</html>
