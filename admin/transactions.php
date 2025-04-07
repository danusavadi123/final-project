<?php
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/database.php');

// Ensure only admin has access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch all completed transactions with joined user and product data
$sql = "SELECT o.id, o.order_date, o.total_amount, o.payment_status, o.order_status, 
               b.name AS buyer_name, s.name AS seller_name 
        FROM orders o
        JOIN users b ON o.buyer_id = b.id
        JOIN users s ON o.seller_id = s.id
        ORDER BY o.order_date DESC";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2>All Transactions</h2>
    <hr>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Order ID</th>
                <th>Buyer</th>
                <th>Seller</th>
                <th>Total (₹)</th>
                <th>Payment Status</th>
                <th>Order Status</th>
                <th>Order Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['buyer_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['seller_name']); ?></td>
                    <td><?php echo number_format($row['total_amount'], 2); ?></td>
                    <td>
                        <span class="badge bg-<?php echo $row['payment_status'] === 'success' ? 'success' : 'danger'; ?>">
                            <?php echo ucfirst($row['payment_status']); ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-secondary">
                            <?php echo ucfirst($row['order_status']); ?>
                        </span>
                    </td>
                    <td><?php echo date("d M Y, h:i A", strtotime($row['order_date'])); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once('../includes/footer.php'); ?>