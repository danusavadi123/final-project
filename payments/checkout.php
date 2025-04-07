<?php
require_once('../includes/session.php');
require_once('../config/database.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    die("Order ID not provided.");
}

$order_id = intval($_GET['order_id']);
$buyer_id = $_SESSION['user_id'];

// Fetch order and buyer info
$stmt = $conn->prepare("SELECT o.total_amount, o.razorpay_order_id, u.name, u.email 
                        FROM orders o 
                        JOIN users u ON o.buyer_id = u.id 
                        WHERE o.id = ? AND o.buyer_id = ?");
$stmt->bind_param("ii", $order_id, $buyer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Invalid order.");
}

$order = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Razorpay</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <h2>Redirecting to payment...</h2>
    <script>
        const options = {
            "key": "rzp_test_YourApiKeyHere", // Replace with your Razorpay key
            "amount": "<?php echo $order['total_amount'] * 100; ?>",
            "currency": "INR",
            "name": "Local Marketplace",
            "description": "Purchase from local seller",
            "order_id": "<?php echo $order['razorpay_order_id']; ?>",
            "handler": function (response) {
                // Redirect to success handler
                window.location.href = "success.php?order_id=<?php echo $order_id; ?>&payment_id=" + response.razorpay_payment_id + "&signature=" + response.razorpay_signature;
            },
            "prefill": {
                "name": "<?php echo htmlspecialchars($order['name']); ?>",
                "email": "<?php echo htmlspecialchars($order['email']); ?>"
            },
            "theme": {
                "color": "#3399cc"
            }
        };

        const rzp = new Razorpay(options);
        rzp.open();
    </script>
</body>
</html>