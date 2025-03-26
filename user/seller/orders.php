<?php
// Manage Orders - Sellers can view and update orders

include '../includes/session.php';
checkRole('seller'); // Only sellers can access this page
include '../config/database.php';

// Get orders for the logged-in seller
$seller_id = $_SESSION['user_id'];
$query = "SELECT o.*, p.name AS product_name, p.image, u.username AS buyer_name, u.email AS buyer_email 
          FROM orders o 
          JOIN products p ON o.product_id = p.id 
          JOIN users u ON o.buyer_id = u.id 
          WHERE o.seller_id = ? ORDER BY o.created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle order status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $update_query = "UPDATE orders SET status = ? WHERE id = ? AND seller_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sii", $status, $order_id, $seller_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Order status updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating order status. Try again.";
    }

    header("Location: orders.php");
    exit();
}
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
    <h2>Manage Orders</h2>

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
                <th>Quantity</th>
                <th>Total Price (₹)</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Update Status</th>
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
                    <td><?= $row['quantity']; ?></td>
                    <td>₹<?= $row['total_price']; ?></td>
                    <td><?= date("d M Y", strtotime($row['created_at'])); ?></td>
                    <td>
                        <span class="badge bg-<?= getBadgeClass($row['status']); ?>">
                            <?= $row['status']; ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($row['status'] != 'Delivered' && $row['status'] != 'Cancelled') { ?>
                            <form method="POST">
                                <input type="hidden" name="order_id" value="<?= $row['id']; ?>">
                                <select name="status" class="form-select mb-2">
                                    <option value="Processing" <?= $row['status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="Shipped" <?= $row['status'] == 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="Delivered" <?= $row['status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                            </form>
                        <?php } ?>
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