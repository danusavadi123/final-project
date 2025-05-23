<?php
session_start();
require_once('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$buyer_id = $_SESSION['user_id'];
$single_order = isset($_POST['single_order']) && $_POST['single_order'] == '1';
$address = $_POST['address'] ?? '';
$city = $_POST['city'] ?? '';
$pincode = $_POST['pincode'] ?? '';
$contact = $_POST['contact_number'] ?? '';
$payment_method = $_POST['payment_method'] ?? '';

$full_address = "$address, $city - $pincode";
$order_status = "Pending";
$order_date = date("Y-m-d H:i:s");
$expected_delivery = date("Y-m-d", strtotime("+5 days"));

$orders = [];

if ($single_order) {
    $product_id = (int) $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];

    $product_res = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
    if ($product_res && mysqli_num_rows($product_res) > 0) {
        $product = mysqli_fetch_assoc($product_res);
        $discount = $product['discount'] ?? 0;
        $price = $product['price'] * (1 - $discount / 100);
        $total_amount = $price * $quantity;
        $seller_id = $product['seller_id'];

        $orders[] = [
            'product_id' => $product_id,
            'quantity' => $quantity,
            'total_amount' => $total_amount,
            'seller_id' => $seller_id
        ];
    } else {
        die("Invalid product.");
    }
} else {
    $cart_sql = "SELECT c.product_id, c.quantity, p.price, p.discount, p.seller_id
                 FROM cart c
                 JOIN products p ON c.product_id = p.id
                 WHERE c.buyer_id = $buyer_id";
    $cart_res = mysqli_query($conn, $cart_sql);

    if ($cart_res && mysqli_num_rows($cart_res) > 0) {
        while ($row = mysqli_fetch_assoc($cart_res)) {
            $discount = $row['discount'] ?? 0;
            $price = $row['price'] * (1 - $discount / 100);
            $total_amount = $price * $row['quantity'];
            $orders[] = [
                'product_id' => $row['product_id'],
                'quantity' => $row['quantity'],
                'total_amount' => $total_amount,
                'seller_id' => $row['seller_id']
            ];
        }
    } else {
        die("Cart is empty.");
    }
}

// Handle payment
if ($payment_method === 'cod') {
    foreach ($orders as $order) {
        $stmt = mysqli_prepare($conn, "INSERT INTO orders (product_id, seller_id, buyer_id, quantity, total_amount, address, order_status, order_date, expected_delivery, payment_method, contact_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'iiiidssssss',
            $order['product_id'],
            $order['seller_id'],
            $buyer_id,
            $order['quantity'],
            $order['total_amount'],
            $full_address,
            $order_status,
            $order_date,
            $expected_delivery,
            $payment_method,
            $contact
        );
        mysqli_stmt_execute($stmt);
    }

    if (!$single_order) {
        mysqli_query($conn, "DELETE FROM cart WHERE buyer_id = $buyer_id");
    }

    // Show popup and redirect
    echo "<script>
        alert('🎉 Order placed successfully!');
        window.location.href = 'order_history.php';
    </script>";
    exit;

} elseif ($payment_method === 'razorpay') {
    $_SESSION['checkout_data'] = [
        'orders' => $orders,
        'address' => $full_address,
        'contact_number' => $contact,
        'buyer_id' => $buyer_id,
        'order_status' => $order_status,
        'order_date' => $order_date,
        'expected_delivery' => $expected_delivery,
        'payment_method' => 'Razorpay',
        'single_order' => $single_order
    ];

    header("Location: ../payments/checkout.php");
    exit;
} else {
    die("Invalid payment method.");
}
?>
