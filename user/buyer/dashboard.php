<?php
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/database.php');

// Ensure only buyer has access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    header("Location: ../auth/login.php");
    exit();
}

$buyer_id = $_SESSION['user_id'];

// Fetch total number of orders
$orderQuery = "SELECT COUNT(*) AS total_orders FROM orders WHERE buyer_id = ?";
$stmt1 = $conn->prepare($orderQuery);
$stmt1->bind_param("i", $buyer_id);
$stmt1->execute();
$result1 = $stmt1->get_result()->fetch_assoc();

// Fetch total amount spent
$amountQuery = "SELECT SUM(total_amount) AS total_spent FROM orders WHERE buyer_id = ? AND payment_status = 'success'";
$stmt2 = $conn->prepare($amountQuery);
$stmt2->bind_param("i", $buyer_id);
$stmt2->execute();
$result2 = $stmt2->get_result()->fetch_assoc();

// Fetch recent 5 orders
$recentOrders = "SELECT o.id, o.order_date, o.total_amount, o.order_status, u.name AS seller_name
                 FROM orders o
                 JOIN users u ON o.seller_id = u.id
                 WHERE o.buyer_id = ?
                 ORDER BY o.order_date DESC
                 LIMIT 5";
$stmt3 = $conn->prepare($recentOrders);
$stmt3->bind_param("i", $buyer_id);
$stmt3->execute();
$orders = $stmt3->get_result();
?>

<div class="container mt-5">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></h2>
    <hr>

    <div class="row text-center mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Total Orders</h5>
                    <h2><?php echo $result1['total_orders']; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5>Total Spent</h5>
                    <h2>₹<?php echo number_format($result2['total_spent'] ?? 0, 2); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <h4 class="mt-4">Recent Orders</h4>
    <table class="table table-bordered table-striped mt-2">
        <thead class="table-dark">
            <tr>
                <th>Order ID</th>
                <th>Seller</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($orders->num_rows > 0): ?>
                <?php while ($row = $orders->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['seller_name']); ?></td>
                        <td>₹<?php echo number_format($row['total_amount'], 2); ?></td>
                        <td><span class="badge bg-info"><?php echo ucfirst($row['order_status']); ?></span></td>
                        <td><?php echo date("d M Y", strtotime($row['order_date'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" class="text-center">No orders found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once('../includes/footer.php'); ?>