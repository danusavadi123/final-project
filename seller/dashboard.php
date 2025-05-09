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
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* Background Image */
body {
    background-image: url('../assets/images/background1.jpg');
    background-size: cover;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center;
}

/* 3D Card Effect */
.card-3d {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1), 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    transform-style: preserve-3d;
    perspective: 1000px;
    border: none;
}

.card-3d:hover {
    transform: scale(1.05) rotateY(5deg) rotateX(5deg);
    box-shadow: 0 20px 30px rgba(0,0,0,0.2), 0 8px 12px rgba(0,0,0,0.15);
}

.card-title {
    font-weight: 600;
    color: #333;
}

h2 {
    font-weight: 700;
    color: #007bff;
}

h3 {
    font-weight: 700;
    color:rgb(255, 255, 255);
}

h4 {
    color:rgb(255, 255, 255);
}

/* Quick Actions */
.btn {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.2);
}

/* 3D Icons Style */
.icon-3d {
    font-size: 50px;
    margin-bottom: 15px;
    color: #007bff;
    text-shadow: 2px 2px 5px rgba(0,0,0,0.2);
    transition: transform 0.3s ease;
}

.card-3d:hover .icon-3d {
    transform: scale(1.2) rotateY(10deg);
}
</style>

<div class="container mt-5">
    <h3 class="mb-4">Seller Dashboard</h3>

    <div class="row g-4">
        <!-- Total Products -->
        <div class="col-md-4">
            <div class="card card-3d border-start border-primary border-4 p-3">
                <div class="card-body text-center">
                    <i class="fas fa-box icon-3d"></i>
                    <h5 class="card-title">Total Products</h5>
                    <h2><?php echo $product_result['total_products']; ?></h2>
                </div>
            </div>
        </div>

        <!-- Total Orders -->
        <div class="col-md-4">
            <div class="card card-3d border-start border-success border-4 p-3">
                <div class="card-body text-center">
                    <i class="fas fa-shopping-cart icon-3d" style="color: #28a745;"></i>
                    <h5 class="card-title">Total Orders</h5>
                    <h2><?php echo $order_result['total_orders']; ?></h2>
                </div>
            </div>
        </div>

        <!-- Total Earnings -->
        <div class="col-md-4">
            <div class="card card-3d border-start border-warning border-4 p-3">
                <div class="card-body text-center">
                    <i class="fas fa-coins icon-3d" style="color: #ffc107;"></i>
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
            <a href="manage_products.php" class="btn btn-outline-danger">Manage Products</a>
            <a href="orders.php" class="btn btn-outline-warning">View Orders</a>
        </div>
    </div>
</div>

<script>
// Optional: Tilt animation on mouse move (adds extra 3D feel)
document.querySelectorAll('.card-3d').forEach(card => {
    card.addEventListener('mousemove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        const rotateX = ((y - centerY) / centerY) * 5;
        const rotateY = ((x - centerX) / centerX) * 5;
        card.style.transform = `rotateX(${-rotateX}deg) rotateY(${rotateY}deg) scale(1.05)`;
    });

    card.addEventListener('mouseleave', () => {
        card.style.transform = 'rotateX(0deg) rotateY(0deg) scale(1)';
    });
});
</script>

<?php require_once('../includes/footer.php'); ?>
