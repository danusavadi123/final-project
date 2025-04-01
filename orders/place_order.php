<?php
// Place Order - Buyers can order products

include '../includes/session.php';
checkRole('buyer'); // Only buyers can access this page
include '../config/database.php';

// Check if product ID is provided
if (!isset($_GET['product_id'])) {
    $_SESSION['error'] = "Invalid product selection.";
    header("Location: ../buyer/browse_products.php");
    exit();
}

$product_id = $_GET['product_id'];

// Fetch product details
$query = "SELECT p.*, u.id AS seller_id FROM products p 
          JOIN users u ON p.seller_id = u.id WHERE p.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    $_SESSION['error'] = "Product not found.";
    header("Location: ../buyer/browse_products.php");
    exit();
}

// Handle order submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $buyer_id = $_SESSION['user_id'];
    $seller_id = $product['seller_id'];
    $quantity = $_POST['quantity'];
    $address = trim($_POST['address']);
    $total_price = $product['price'] * $quantity;

    // Validate input fields
    if ($quantity <= 0 || empty($address)) {
        $_SESSION['error'] = "Invalid quantity or address.";
        header("Location: place_order.php?product_id=$product_id");
        exit();
    }

    // Insert order into database
    $query = "INSERT INTO orders (buyer_id, seller_id, product_id, quantity, total_price, address) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iiiids", $buyer_id, $seller_id, $product_id, $quantity, $total_price, $address);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Order placed successfully! Proceed to payment.";
        header("Location: ../payments/checkout.php?order_id=" . $stmt->insert_id);
        exit();
    } else {
        $_SESSION['error'] = "Error placing order. Try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Place Order</h2>

    <?php if (isset($_SESSION['error'])) { ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php } ?>

    <div class="row">
        <div class="col-md-6">
            <img src="../uploads/<?= $product['image']; ?>" class="img-fluid" alt="<?= htmlspecialchars($product['name']); ?>">
        </div>
        <div class="col-md-6">
            <h4><?= htmlspecialchars($product['name']); ?></h4>
            <p><strong>Price:</strong> ₹<?= $product['price']; ?></p>
            <p><strong>Category:</strong> <?= htmlspecialchars($product['category']); ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($product['location']); ?></p>

            <form action="place_order.php?product_id=<?= $product['id']; ?>" method="POST">
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Delivery Address</label>
                    <textarea name="address" id="address" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Confirm Order</button>
                <a href="../buyer/browse_products.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

</body>
</html>