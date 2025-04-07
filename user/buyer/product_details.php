<?php
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/database.php');

// Ensure buyer access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    header("Location: ../auth/login.php");
    exit();
}

$product_id = $_GET['id'] ?? '';
if (!$product_id) {
    header("Location: browse_products.php");
    exit();
}

// Fetch product details
$stmt = $conn->prepare("SELECT p.*, u.name AS seller_name, u.location AS seller_location 
                        FROM products p 
                        JOIN users u ON p.seller_id = u.id 
                        WHERE p.id = ? AND p.is_active = 1");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='container mt-5'><p class='text-danger'>Product not found.</p></div>";
    require_once('../includes/footer.php');
    exit();
}

$product = $result->fetch_assoc();
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-5">
            <?php if ($product['image']): ?>
                <img src="../uploads/<?php echo $product['image']; ?>" class="img-fluid rounded shadow-sm" alt="Product Image">
            <?php else: ?>
                <img src="../assets/no-image.png" class="img-fluid rounded shadow-sm" alt="No Image">
            <?php endif; ?>
        </div>
        <div class="col-md-7">
            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
            <p class="text-muted">By: <?php echo htmlspecialchars($product['seller_name']); ?> (<?php echo htmlspecialchars($product['seller_location']); ?>)</p>
            <p><strong>₹<?php echo number_format($product['price'], 2); ?></strong></p>
            <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
            
            <form action="place_order.php" method="POST" class="mt-4">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="1" required>
                </div>
                <button type="submit" class="btn btn-success">Place Order</button>
            </form>
        </div>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>