<?php
// Order History - Buyers can track their orders

include '../includes/session.php';
checkRole('buyer'); // Only buyers can access this page
include '../config/database.php';

// Get orders for the logged-in buyer
$buyer_id = $_SESSION['user_id'];
$query = "SELECT o.*, p.name AS product_name, p.image FROM orders o 
          JOIN products p ON o.product_id = p.id WHERE o.buyer_id = ? 
          ORDER BY o.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $buyer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>My Orders</h2>

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