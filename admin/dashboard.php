<?php
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('./admin_navbar.php');
require_once('../config/db.php');

// Only admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Get user counts
$buyers = $conn->query("SELECT COUNT(*) AS count FROM users WHERE role = 'buyer'")->fetch_assoc()['count'];
$sellers = $conn->query("SELECT COUNT(*) AS count FROM users WHERE role = 'seller'")->fetch_assoc()['count'];

// Get product count
$total_products = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];

// Get order count
$total_orders = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];

// Get recent orders
$recent_orders = $conn->query("
    SELECT o.id, o.total_amount, o.order_status, o.order_date, u.name 
    FROM orders o 
    JOIN users u ON o.buyer_id = u.id 
    ORDER BY o.order_date DESC 
    LIMIT 5
");
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<div class="container py-5">
    <h2 class="mb-4 text-center text-primary">Admin Dashboard</h2>

    <div class="row g-4 text-white mb-5">
        <div class="col-md-3">
            <div class="card bg-primary h-100 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-user fa-3x me-3"></i>
                    <div>
                        <h6>Buyers</h6>
                        <h4><?= $buyers ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success h-100 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-store fa-3x me-3"></i>
                    <div>
                        <h6>Sellers</h6>
                        <h4><?= $sellers ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark h-100 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-box-open fa-3x me-3"></i>
                    <div>
                        <h6>Products</h6>
                        <h4><?= $total_products ?></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-dark h-100 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="fas fa-shopping-cart fa-3x me-3"></i>
                    <div>
                        <h6>Total Orders</h6>
                        <h4><?= $total_orders ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mb-3">Recent Orders</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Buyer Name</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = $recent_orders->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= $order['id'] ?></td>
                        <td><?= htmlspecialchars($order['name']) ?></td>
                        <td>₹<?= number_format($order['total_amount'], 2) ?></td>
                        <td>
                            <span class="badge bg-<?php 
                                switch (strtolower($order['order_status'])) {
                                    case 'Pending': echo 'warning'; break;
                                    case 'Processed': echo 'primary'; break;
                                    case 'Shipped': echo 'info'; break;
                                    case 'Delivered': echo 'success'; break;
                                    case 'Cancelled': echo 'danger'; break;
                                    default: echo 'secondary';
                                }
                            ?>">
                                <?= ucfirst($order['order_status']) ?>
                            </span>
                        </td>
                        <td><?= date("d M Y, h:i A", strtotime($order['order_date'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>
