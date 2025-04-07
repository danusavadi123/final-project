<?php
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/database.php');

// Ensure only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch all orders with JOINs to display buyer and seller info
$sql = "SELECT o.id, o.amount, o.status, o.created_at,
               b.name AS buyer_name, s.name AS seller_name, p.title AS product_title
        FROM orders o
        JOIN users b ON o.buyer_id = b.id
        JOIN users s ON o.seller_id = s.id
        JOIN products p ON o.product_id = p.id
        ORDER BY o.created_at DESC";

$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h3 class="mb-4">Manage Orders</h3>

    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#Order ID</th>
                        <th>Buyer</th>
                        <th>Seller</th>
                        <th>Product</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Ordered On</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['buyer_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['seller_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['product_title']); ?></td>
                            <td>₹<?php echo number_format($order['amount'], 2); ?></td>
                            <td>
                                <span class="badge bg-<?php echo ($order['status'] === 'Delivered') ? 'success' : (($order['status'] === 'Shipped') ? 'info' : 'secondary'); ?>">
                                    <?php echo $order['status']; ?>
                                </span>
                            </td>
                            <td><?php echo date('d M Y', strtotime($order['created_at'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">No orders found.</div>
    <?php endif; ?>
</div>

<?php require_once('../includes/footer.php'); ?>