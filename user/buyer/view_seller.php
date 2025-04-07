<?php
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/database.php');

// Only buyers can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['seller_id']) || !is_numeric($_GET['seller_id'])) {
    echo "<div class='container mt-5'><p class='text-danger'>Invalid seller ID.</p></div>";
    require_once('../includes/footer.php');
    exit();
}

$seller_id = $_GET['seller_id'];

// Fetch seller details
$sellerStmt = $conn->prepare("SELECT name, email, location FROM users WHERE id = ? AND role = 'seller'");
$sellerStmt->bind_param("i", $seller_id);
$sellerStmt->execute();
$sellerResult = $sellerStmt->get_result();

if ($sellerResult->num_rows === 0) {
    echo "<div class='container mt-5'><p class='text-danger'>Seller not found.</p></div>";
    require_once('../includes/footer.php');
    exit();
}

$seller = $sellerResult->fetch_assoc();

// Fetch seller's products
$productStmt = $conn->prepare("SELECT * FROM products WHERE seller_id = ? AND status = 'active'");
$productStmt->bind_param("i", $seller_id);
$productStmt->execute();
$productResult = $productStmt->get_result();
?>

<div class="container mt-5">
    <h3 class="mb-4">Seller: <?php echo htmlspecialchars($seller['name']); ?></h3>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($seller['email']); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($seller['location']); ?></p>

    <hr>
    <h5>Products by <?php echo htmlspecialchars($seller['name']); ?></h5>

    <?php if ($productResult->num_rows > 0): ?>
        <div class="row mt-4">
            <?php while ($product = $productResult->fetch_assoc()) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" class="card-img-top" alt="Product Image">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars(substr($product['description'], 0, 60)); ?>...</p>
                            <strong>₹<?php echo number_format($product['price'], 2); ?></strong>
                        </div>
                        <div class="card-footer text-center">
                            <a href="../orders/place_order.php?product_id=<?php echo $product['id']; ?>" class="btn btn-success btn-sm">Buy Now</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-muted">This seller has no active products.</p>
    <?php endif; ?>
</div>

<?php require_once('../includes/footer.php'); ?>