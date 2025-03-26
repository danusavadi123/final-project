<?php
// Checkout - Payment Processing

include '../includes/session.php';
checkRole('buyer'); // Only buyers can access this page
include '../config/database.php';
require '../vendor/autoload.php'; // Include Razorpay SDK

use Razorpay\Api\Api;

// Razorpay API Credentials
$api_key = "rzp_test_g6EblohbAvjqdg"; 
$api_secret = "C1yySJ5jG6dxoShLB7brzuOL"; 

$api = new Api($api_key, $api_secret);

// Check if order ID is provided
if (!isset($_GET['order_id'])) {
    $_SESSION['error'] = "Invalid order.";
    header("Location: ../buyer/order_history.php");
    exit();
}

$order_id = $_GET['order_id'];

// Fetch order details
$query = "SELECT o.*, p.name AS product_name FROM orders o 
          JOIN products p ON o.product_id = p.id WHERE o.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

// Redirect if order not found
if (!$order) {
    $_SESSION['error'] = "Order not found.";
    header("Location: ../buyer/order_history.php");
    exit();
}

// Create Razorpay Order
$razorpay_order = $api->order->create([
    'receipt' => "ORDER_" . $order_id,
    'amount' => $order['total_price'] * 100, // Convert to paise
    'currency' => 'INR',
    'payment_capture' => 1
]);

$_SESSION['razorpay_order_id'] = $razorpay_order->id;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>

<div class="container mt-5">
    <h2>Checkout</h2>

    <p><strong>Product:</strong> <?= htmlspecialchars($order['product_name']); ?></p>
    <p><strong>Amount:</strong> ₹<?= $order['total_price']; ?></p>

    <button id="payBtn" class="btn btn-success">Pay Now</button>
    <a href="../buyer/order_history.php" class="btn btn-secondary">Cancel</a>
</div>

<script>
    var options = {
        "key": "<?= $api_key; ?>", 
        "amount": "<?= $order['total_price'] * 100; ?>",
        "currency": "INR",
        "name": "Local Marketplace",
        "description": "Order Payment",
        "order_id": "<?= $razorpay_order->id; ?>",
        "handler": function (response) {
            window.location.href = "success.php?order_id=<?= $order_id; ?>&payment_id=" + response.razorpay_payment_id;
        },
        "prefill": {
            "name": "<?= $_SESSION['username']; ?>",
            "email": "<?= $_SESSION['email']; ?>"
        },
        "theme": {
            "color": "#3399cc"
        }
    };
    
    var rzp1 = new Razorpay(options);
    document.getElementById('payBtn').onclick = function (e) {
        rzp1.open();
        e.preventDefault();
    };
</script>

</body>
</html>