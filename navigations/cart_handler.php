<?php
session_start();
require_once('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$buyer_id = $_SESSION['user_id'];

// ➤ Count items in DB cart
if (isset($_GET['action']) && $_GET['action'] === 'count') {
    $sql = "SELECT COUNT(*) AS item_count FROM cart WHERE buyer_id = $buyer_id";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($res);
    echo $row['item_count'];
    exit;
}


// ➤ Add item to cart
if (isset($_GET['action']) && $_GET['action'] === 'add') {
    header('Content-Type: application/json');

    $product_id = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

    if ($product_id <= 0 || $quantity <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid product or quantity']);
        exit;
    }

    // Check if product exists
    $check = mysqli_query($conn, "SELECT id FROM products WHERE id = $product_id");
    if (!$check || mysqli_num_rows($check) === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Product not found']);
        exit;
    }

    // Check if already in cart
    $exists = mysqli_query($conn, "SELECT * FROM cart WHERE buyer_id = $buyer_id AND product_id = $product_id");
    if (mysqli_num_rows($exists) > 0) {
        // Update quantity
        $update = mysqli_query($conn, "UPDATE cart SET quantity = quantity + $quantity WHERE buyer_id = $buyer_id AND product_id = $product_id");
    } else {
        // Insert new
        $insert = mysqli_query($conn, "INSERT INTO cart (buyer_id, product_id, quantity) VALUES ($buyer_id, $product_id, $quantity)");
    }

    // Return new item count
    $res = mysqli_query($conn, "SELECT COUNT(*) AS item_count FROM cart WHERE buyer_id = $buyer_id");
    $row = mysqli_fetch_assoc($res);
    echo json_encode(['status' => 'success', 'count' => $row['item_count']]);
    exit;
}

// ➤ Remove item
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['id'])) {
    $product_id = (int) $_GET['id'];
    $delete = mysqli_query($conn, "DELETE FROM cart WHERE buyer_id = $buyer_id AND product_id = $product_id");
    echo "Item removed";
    exit;
}

// ➤ Display cart contents
$sql = "SELECT c.product_id, c.quantity, p.name, p.image, p.price 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.buyer_id = $buyer_id";

$res = mysqli_query($conn, $sql);

if (!$res || mysqli_num_rows($res) === 0) {
    echo "<p class='text-center'>Your cart is empty.</p>";
    exit;
}

echo '<div class="list-group">';
while ($item = mysqli_fetch_assoc($res)) {
    echo '<div class="list-group-item d-flex align-items-center justify-content-between">';
    echo '<div class="d-flex align-items-center">';
    echo '<img src="../uploads/' . htmlspecialchars($item['image']) . '" alt="" class="me-3" style="width: 60px; height: 60px; object-fit: cover;">';
    echo '<div>';
    echo '<strong>' . htmlspecialchars($item['name']) . '</strong><br>';
    echo 'Quantity: ' . $item['quantity'] . '<br>';
    echo 'Price: ₹' . htmlspecialchars($item['price']);
    echo '</div></div>';
    echo '<div>';
    echo '<button class="btn btn-sm btn-danger" onclick="removeFromCart(' . $item['product_id'] . ')">Remove</button>';
    echo '</div></div>';
}
echo '</div>';
?>
