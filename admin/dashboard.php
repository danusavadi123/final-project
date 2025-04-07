<?php
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/database.php');

// Only admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Get total users (buyers and sellers)
$user_query = $conn->query("SELECT COUNT(*) AS total_users FROM users");
$total_users = $user_query->fetch_assoc()['total_users'];

// Get total products
$product_query = $conn->query("SELECT COUNT(*) AS total_products FROM products");
$total_products = $product_query->fetch_assoc()['total_products'];

// Get total orders
$order_query = $conn->query("SELECT COUNT(*) AS total_orders FROM orders");
$total_orders = $order_query->fetch_assoc()['total_orders'];

// Get recent orders
$recent_orders = $conn->query("
    SELECT o.id, o.total_amount, o.status, o.order_date, u.name 
    FROM orders o 
    JOIN users u ON o.buyer_id = u.id 
    ORDER BY o.order_date DESC 
    LIMIT 5
");
?>

<div class="container mt-5">
    <h2>Admin Dashboard</h2>
    <hr>

    <div class="row text-center">
        <div class="col-md-4 mb-3">
            <div class="card border-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text display-6"><?php echo $total_users; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-success">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <p class="card-text display-6"><?php echo $total_products; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-info">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <p class="card-text display-6"><?php echo $total_orders; ?></p>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mt-5">Recent Orders</h4>
    <table class="table table-bordered table-striped mt-3">
        <thead>
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
                    <td>#<?php echo $order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['name']); ?></td>
                    <td>₹<?php echo number_format($order['total_amount'], 2); ?></td>
                    <td>
                        <span class="badge bg-<?php 
                            switch ($order['status']) {
                                case 'pending': echo 'warning'; break;
                                case 'shipped': echo 'info'; break;
                                case 'delivered': echo 'success'; break;
                                case 'cancelled': echo 'danger'; break;
                                default: echo 'secondary';
                            }
                        ?>">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </td>
                    <td><?php echo date("d M Y, h:i A", strtotime($order['order_date'])); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once('../includes/footer.php'); ?>