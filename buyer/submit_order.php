<?php
session_start();
require_once('../config/db.php');
require_once('../includes/spinner.html');

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$buyer_id = $_SESSION['user_id'];

// Sanitize and prepare inputs
$address = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
$city = mysqli_real_escape_string($conn, $_POST['city'] ?? '');
$pincode = mysqli_real_escape_string($conn, $_POST['pincode'] ?? '');
$contact_number = mysqli_real_escape_string($conn, $_POST['contact_number'] ?? '');
$payment_method = $_POST['payment_method'] ?? 'cod';
$full_address = "$address, $city, $pincode";
$order_date = date("Y-m-d H:i:s");
$order_status = "Pending";
$total_amount = 0;
$order_items = [];

// Single product order
if (isset($_POST['single_order']) && $_POST['single_order'] == '1') {
    $product_id = (int) $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];

    $product_query = mysqli_query($conn, "SELECT seller_id, price, discount FROM products WHERE id = $product_id");

    if ($product_query && mysqli_num_rows($product_query) > 0) {
        $product = mysqli_fetch_assoc($product_query);
        $price = $product['price'];
        $discount = $product['discount'] ?? 0;
        $seller_id = $product['seller_id'];

        $discounted_price = $price * (1 - $discount / 100);
        $total = $discounted_price * $quantity;
        $total_amount += $total;

        $order_items[] = [
            'product_id' => $product_id,
            'seller_id' => $seller_id,
            'quantity' => $quantity,
            'total_amount' => $total
        ];
    }
}
// Cart order
else {
    $cart_query = "SELECT c.product_id, c.quantity, p.price, p.discount, p.seller_id 
                   FROM cart c
                   JOIN products p ON c.product_id = p.id
                   WHERE c.buyer_id = $buyer_id";

    $cart_result = mysqli_query($conn, $cart_query);

    if ($cart_result && mysqli_num_rows($cart_result) > 0) {
        while ($item = mysqli_fetch_assoc($cart_result)) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            $discount = $item['discount'] ?? 0;
            $seller_id = $item['seller_id'];

            $discounted_price = $price * (1 - $discount / 100);
            $total = $discounted_price * $quantity;
            $total_amount += $total;

            $order_items[] = [
                'product_id' => $product_id,
                'seller_id' => $seller_id,
                'quantity' => $quantity,
                'total_amount' => $total
            ];
        }
    }
}

// Handle COD orders
if ($payment_method === 'cod') {
    foreach ($order_items as $item) {
        $insert_sql = "INSERT INTO orders (product_id, seller_id, buyer_id, quantity, total_amount, address, order_status, order_date, contact_number)
                       VALUES (
                           {$item['product_id']}, {$item['seller_id']}, $buyer_id, {$item['quantity']}, {$item['total_amount']},
                           '$full_address', '$order_status', '$order_date', '$contact_number')";
        mysqli_query($conn, $insert_sql);
    }

    // Clear cart if it's not a single product order
    if (!isset($_POST['single_order']) || $_POST['single_order'] != '1') {
        mysqli_query($conn, "DELETE FROM cart WHERE buyer_id = $buyer_id");
    }

    // Set flag to avoid redirect loops
    $_SESSION['order_just_placed'] = true;

    // Redirect to order history
    header("Location: order_history.php");
    exit;
}

// Handle Razorpay orders
elseif ($payment_method === 'razorpay') {
    $_SESSION['razorpay_order'] = [
        'buyer_id' => $buyer_id,
        'items' => $order_items,
        'total_amount' => $total_amount,
        'address' => $full_address,
        'contact_number' => $contact_number,
        'order_date' => $order_date,
        'single_order' => $_POST['single_order'] ?? '0'
    ];

    header("Location: ../payments/checkout.php");
    exit;
}

// Invalid payment method
else {
    echo "<script>alert('Invalid payment method selected.'); window.history.back();</script>";
    exit;
}
?>
