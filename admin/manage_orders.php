<?php
// Manage Orders - Admin can view and monitor all orders

include '../includes/session.php';
checkRole('admin'); // Only admins can access this page
include '../config/database.php';

// Get all orders from the database
$query = "SELECT o.*, 
                 p.name AS product_name, p.image, 
                 b.username AS buyer_name, b.email AS buyer_email, 
                 s.username AS seller_name, s.email AS seller_email 
          FROM orders o
          JOIN products p ON o.product_id = p.id
          JOIN users b ON o.buyer_id = b.id
          JOIN users s ON o.seller_id = s.id
          ORDER BY o.created_at DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>All Orders</h2>

    <?php if (isset($_SESSION['success'])) { ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php } ?>

    <?php if (isset($_SESSION['error'])) { ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php } ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Buyer</th>
                <th>Seller</th>
                <th>Quantity</th>
                <th>Total Price (₹)</th>
                <th>Order Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td>
                        <img src="../uploads/<?= $row['image']; ?>" width="50" height="50" alt="<?= htmlspecialchars($row['product_name']); ?>">
                        <?= htmlspecialchars($row['product_name']); ?>
                    </td>
                    <td>
                        <?= htmlspecialchars($row['buyer_name']); ?><br>
                        <small><?= htmlspecialchars($row['buyer_email']); ?></small>
                    </td>
                    <td>
                        <?= htmlspecialchars($row['seller_name']); ?><br>
                        <small><?= htmlspecialchars($row['seller_email']); ?></small>
                    </td>
                    <td><?= $row['quantity']; ?></td>
                    <td>₹<?= $row['total_price']; ?></td>
                    <td><?= date("d M Y", strtotime($row['created_at'])); ?></td>
                    <td>
                        <span class="badge bg-<?= getBadgeClass($row['status']); ?>">
                            <?= $row['status']; ?>
                        </span>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>

</body>
</html>

<?php
// Function to return Bootstrap badge class for status
function getBadgeClass($status) {
    switch ($status) {
        case 'Pending': return 'warning';
        case 'Processing': return 'info';
        case 'Shipped': return 'primary';
        case 'Delivered': return 'success';
        case 'Cancelled': return 'danger';
        default: return 'secondary';
    }
}
?>