<?php
// View Product - Buyers can see full product details

include '../includes/session.php';
checkRole('buyer'); // Only buyers can access this page
include '../config/database.php';

// Check if product ID is provided
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Invalid product ID.";
    header("Location: browse_products.php");
    exit();
}

$product_id = $_GET['id'];

// Fetch product details
$query = "SELECT p.*, u.username AS seller_name, u.email AS seller_email FROM products p 
          JOIN users u ON p.seller_id = u.id WHERE p.id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Redirect if product not found
if (!$product) {
    $_SESSION['error'] = "Product not found.";
    header("Location: browse_products.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2><?= htmlspecialchars($product['name']); ?></h2>
    
    <div class="row">
        <div class="col-md-6">
            <img src="../uploads/<?= $product['image']; ?>" class="img-fluid" alt="<?= htmlspecialchars($product['name']); ?>">
        </div>
        <div class="col-md-6">
            <p><strong>Price:</strong> ₹<?= $product['price']; ?></p>
            <p><strong>Category:</strong> <?= htmlspecialchars($product['category']); ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($product['location']); ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($product['description']); ?></p>
            
            <hr>
            <h5>Seller Information</h5>
            <p><strong>Seller Name:</strong> <?= htmlspecialchars($product['seller_name']); ?></p>
            <p><strong>Contact:</strong> <?= htmlspecialchars($product['seller_email']); ?></p>

            <a href="place_order.php?product_id=<?= $product['id']; ?>" class="btn btn-primary">Place Order</a>
            <a href="browse_products.php" class="btn btn-secondary">Back to Products</a>
        </div>
    </div>
</div>

</body>
</html>