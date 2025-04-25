<?php
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/db.php');

// Restrict access to sellers only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../auth/login.php");
    exit();
}

$seller_id = $_SESSION['user_id'];

// Fetch total products added by seller
$product_sql = "SELECT COUNT(*) AS total_products FROM products WHERE seller_id = ?";
$product_stmt = $conn->prepare($product_sql);
$product_stmt->bind_param("i", $seller_id);
$product_stmt->execute();
$product_result = $product_stmt->get_result()->fetch_assoc();

// Fetch total orders for this seller
$order_sql = "SELECT COUNT(*) AS total_orders, SUM(total_amount) AS total_earnings FROM orders WHERE seller_id = ?";
$order_stmt = $conn->prepare($order_sql);
$order_stmt->bind_param("i", $seller_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result()->fetch_assoc();
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<div class="container mt-5">
    <h3 class="mb-4">Seller Dashboard</h3>

    <div class="row g-4">
        <!-- Total Products -->
        <div class="col-md-4">
            <div class="card shadow border-start border-primary border-4">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <h2><?php echo $product_result['total_products']; ?></h2>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="col-md-4">
            <div class="card shadow border-start border-success border-4">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <h2><?php echo $order_result['total_orders']; ?></h2>
                </div>
            </div>
        </div>

        <!-- Total Earnings -->
        <div class="col-md-4">
            <div class="card shadow border-start border-warning border-4">
                <div class="card-body">
                    <h5 class="card-title">Total Earnings</h5>
                    <h2>₹<?php echo number_format($order_result['total_earnings'] ?? 0, 2); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Future section: Quick Links -->
    <div class="mt-5">
        <h4>Quick Actions</h4>
        <div class="d-flex gap-3 flex-wrap">
            <a href="add_product.php" class="btn btn-outline-primary">Add New Product</a>
            <a href="manage_products.php" class="btn btn-outline-success">Manage Products</a>
            <a href="orders.php" class="btn btn-outline-dark">View Orders</a>
        </div>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>