<?php
session_start();
require_once('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$buyer_id = $_SESSION['user_id'];
$address = mysqli_real_escape_string($conn, $_POST['address']);
$city = mysqli_real_escape_string($conn, $_POST['city']);
$pincode = mysqli_real_escape_string($conn, $_POST['pincode']);
$full_address = $address . ', ' . $city . ', ' . $pincode;
$order_date = date("Y-m-d H:i:s");
$order_status = "Pending";

if (isset($_POST['single_order']) && $_POST['single_order'] == '1') {
    // 🟢 Single product order
    $product_id = (int) $_POST['product_id'];
    $quantity = (int) $_POST['quantity'];

    $product_query = mysqli_query($conn, "SELECT seller_id, price, discount FROM products WHERE id = $product_id");
    if ($product_query && mysqli_num_rows($product_query) > 0) {
        $product = mysqli_fetch_assoc($product_query);
        $seller_id = $product['seller_id'];
        $price = $product['price'];
        $discount = $product['discount'] ?? 0;

        $discounted_price = $price * (1 - $discount / 100);
        $total = $discounted_price * $quantity;

        $insert_sql = "INSERT INTO orders (product_id, seller_id, buyer_id, quantity, total_amount, address, order_status, order_date)
                       VALUES ($product_id, $seller_id, $buyer_id, $quantity, $total, '$full_address', '$order_status', '$order_date')";
        mysqli_query($conn, $insert_sql);
    }

} else {
    // 🛒 Multi-product cart order
    $cart_query = "SELECT c.product_id, c.quantity, p.price, p.discount, p.seller_id 
                   FROM cart c
                   JOIN products p ON c.product_id = p.id
                   WHERE c.buyer_id = $buyer_id";
    $cart_result = mysqli_query($conn, $cart_query);

    if ($cart_result && mysqli_num_rows($cart_result) > 0) {
        while ($item = mysqli_fetch_assoc($cart_result)) {
            $product_id = $item['product_id'];
            $seller_id = $item['seller_id'];
            $price = $item['price'];
            $quantity = $item['quantity'];
            $discount = $item['discount'] ?? 0;

            $discounted_price = $price * (1 - $discount / 100);
            $total = $discounted_price * $quantity;

            $insert_sql = "INSERT INTO orders (product_id, seller_id, buyer_id, quantity, total_amount, address, order_status, order_date)
                           VALUES ($product_id, $seller_id, $buyer_id, $quantity, $total, '$full_address', '$order_status', '$order_date')";
            mysqli_query($conn, $insert_sql);
        }

        // ✅ Clear cart after order
        mysqli_query($conn, "DELETE FROM cart WHERE buyer_id = $buyer_id");
    }
}

// ✅ Redirect to confirmation or orders page
header("Location: order_success.php");
exit;
?>
