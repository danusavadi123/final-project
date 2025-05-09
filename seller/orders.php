<?php
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/db.php');

// Only sellers can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../auth/login.php");
    exit();
}

$seller_id = $_SESSION['user_id'];
$message = "";

// Fetch orders
$query = "
    SELECT o.id AS order_id, o.product_id, o.quantity, o.total_amount, o.order_status, o.order_date,
           p.name AS product_name, b.name AS buyer_name, b.email AS buyer_email
    FROM orders o
    JOIN products p ON o.product_id = p.id
    JOIN users b ON o.buyer_id = b.id
    WHERE p.seller_id = ?
    ORDER BY o.order_date DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- 3D Glass Style -->
<style>
body {
    background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
}

.container {
    max-width: 1100px;
}

h3 {
    font-weight: bold;
    margin-bottom: 30px;
    text-align: center;
}

.table-container {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    padding: 30px;
    margin-top: 40px;
    transition: transform 0.3s ease;
}

.table-container:hover {
    transform: scale(1.01);
}

.table th, .table td {
    vertical-align: middle;
    text-align: center;
}

.table th {
    background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);
    color: #fff;
    border: none;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.table td {
    background-color: rgba(255, 255, 255, 0.6);
    border: none;
    border-radius: 10px;
}

tr:hover td {
    background-color: rgba(255, 255, 255, 0.85);
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .table-container {
        padding: 20px;
    }
    .table th, .table td {
        font-size: 14px;
    }
}
</style>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<div class="container mt-5">
    <h3>📦 Your Product Orders</h3>
    <?php echo $message; ?>

    <div class="table-container">
        <table class="table table-bordered table-hover">
            <thead>
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
                        <td><?php echo number_format($row['total_amount'], 2); ?></td>
                        <td>
                            <span class="badge 
                                <?php 
                                    if ($row['order_status'] === 'pending') echo 'badge-warning';
                                    else if ($row['order_status'] === 'completed') echo 'badge-success';
                                    else echo 'badge-secondary';
                                ?>">
                                <?php echo ucfirst($row['order_status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('d M Y, h:i A', strtotime($row['order_date'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8" class="text-center">No orders found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>
