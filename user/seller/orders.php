<?php
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/database.php');

// Only sellers can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../auth/login.php");
    exit();
}

$seller_id = $_SESSION['user_id'];
$message = "";

// Fetch orders for seller’s products
$query = "
    SELECT o.id AS order_id, o.product_id, o.quantity, o.total_price, o.status, o.created_at,
           p.title AS product_name, b.name AS buyer_name, b.email AS buyer_email
    FROM orders o
    JOIN products p ON o.product_id = p.id
    JOIN users b ON o.buyer_id = b.id
    WHERE p.seller_id = ?
    ORDER BY o.created_at DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-5">
    <h3>Orders for Your Products</h3>
    <?php echo $message; ?>

    <table class="table table-bordered table-striped mt-4">
        <thead class="table-dark">
            <tr>
                <th>Order ID</th>
                <th>Product</th>
                <th>Buyer</th>
                <th>Email</th>
                <th>Qty</th>
                <th>Total (₹)</th>
                <th>Status</th>
                <th>Ordered On</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $row['order_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['buyer_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['buyer_email']); ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo number_format($row['total_price'], 2); ?></td>
                    <td><?php echo ucfirst($row['status']); ?></td>
                    <td><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="8" class="text-center">No orders found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once('../includes/footer.php'); ?>