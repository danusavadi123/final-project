<?php
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('./admin_navbar.php');
require_once('../config/db.php');

// Ensure only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];
    $expected_delivery = $_POST['expected_delivery'];

    // Fetch the order_date first
    $stmt = $conn->prepare("SELECT order_date FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->bind_result($order_date);
    $stmt->fetch();
    $stmt->close();

    if ($expected_delivery >= $order_date) {
        // Update if expected_delivery is valid
        $stmt = $conn->prepare("UPDATE orders SET order_status = ?, expected_delivery = ? WHERE id = ?");
        $stmt->bind_param("ssi", $status, $expected_delivery, $order_id);
        if ($stmt->execute()) {
            $message = "Order updated successfully!";
            $message_type = "success";
        } else {
            $message = "Failed to update the order.";
            $message_type = "error";
        }
        $stmt->close();
    } else {
        $message = "Expected Delivery Date cannot be before the Order Date.";
        $message_type = "error";
    }
}



// Filtering logic
$status_filter = $_GET['status'] ?? '';
$where_clause = $status_filter ? "WHERE o.order_status = ?" : '';

// Fetch all orders with JOINs
$sql = "SELECT o.id, o.total_amount, o.order_status, o.order_date, o.expected_delivery,
               b.name AS buyer_name, s.name AS seller_name, p.name AS product_title
        FROM orders o
        JOIN users b ON o.buyer_id = b.id
        JOIN users s ON o.seller_id = s.id
        JOIN products p ON o.product_id = p.id
        $where_clause
        ORDER BY o.order_date DESC";

$stmt = $conn->prepare($sql);
if ($status_filter) {
    $stmt->bind_param("s", $status_filter);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<div class="container mt-5">
    <h3 class="mb-4">Manage Orders</h3>

    <!-- Filter Dropdown -->
    <form method="GET" class="mb-3">
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <label for="status" class="form-label">Filter by Status:</label>
            </div>
            <div class="col-auto">
                <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="Pending" <?= ($status_filter == 'Pending') ? 'selected' : '' ?>>Pending</option>
                    <option value="Processed" <?= ($status_filter == 'Processed') ? 'selected' : '' ?>>Processed</option>
                    <option value="Shipped" <?= ($status_filter == 'Shipped') ? 'selected' : '' ?>>Shipped</option>
                    <option value="Delivered" <?= ($status_filter == 'Delivered') ? 'selected' : '' ?>>Delivered</option>
                </select>
            </div>
        </div>
    </form>

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
                        <th>Expected Delivery</th>
                        <th>Ordered On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $result->fetch_assoc()): ?>
                        <tr>
                            <form method="POST">
                                <td><?= $order['id'] ?></td>
                                <td><?= htmlspecialchars($order['buyer_name']) ?></td>
                                <td><?= htmlspecialchars($order['seller_name']) ?></td>
                                <td><?= htmlspecialchars($order['product_title']) ?></td>
                                <td>₹<?= number_format($order['total_amount'], 2) ?></td>
                                <td>
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="Pending" <?= ($order['order_status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
                                        <option value="Processed" <?= ($order['order_status'] == 'Processed') ? 'selected' : '' ?>>Processed</option>
                                        <option value="Shipped" <?= ($order['order_status'] == 'Shipped') ? 'selected' : '' ?>>Shipped</option>
                                        <option value="Delivered" <?= ($order['order_status'] == 'Delivered') ? 'selected' : '' ?>>Delivered</option>
                                    </select>
                                </td>
                                <td>
                                <input type="date" 
       name="expected_delivery" 
       class="form-control form-control-sm"
       value="<?= htmlspecialchars($order['expected_delivery']) ?>"
       min="<?= htmlspecialchars(date('Y-m-d', strtotime($order['order_date']))) ?>">

                                </td>
                                <td><?= date('d M Y', strtotime($order['order_date'])) ?></td>
                                <td>
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                </td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">No orders found.</div>
    <?php endif; ?>
</div>
<?php if (!empty($message)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            alert("<?= $message ?>");
        });
    </script>
<?php endif; ?>


<?php require_once('../includes/footer.php'); ?>
