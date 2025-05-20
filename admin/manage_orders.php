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
$updated_order_id = null;

$statuses = ['Pending', 'Processed', 'Shipped', 'Delivered'];

// Handle POST update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'] ?? '';
    $expected_delivery = $_POST['expected_delivery'] ?? '';

    if (!in_array($status, $statuses)) {
        $message = "Invalid status value.";
        $message_type = "error";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $expected_delivery)) {
        $message = "Invalid delivery date format.";
        $message_type = "error";
    } else {
        $stmt = $conn->prepare("SELECT order_date FROM orders WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->bind_result($order_date);
        $stmt->fetch();
        $stmt->close();

        if ($expected_delivery >= $order_date) {
            $stmt = $conn->prepare("UPDATE orders SET order_status = ?, expected_delivery = ? WHERE id = ?");
            $stmt->bind_param("ssi", $status, $expected_delivery, $order_id);
            if ($stmt->execute()) {
                $message = "Order updated successfully!";
                $message_type = "success";
                $updated_order_id = $order_id;
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
}

// Filter logic
$status_filter = $_GET['status'] ?? '';
$where_clause = $status_filter ? "WHERE o.order_status = ?" : '';

$sql = "SELECT o.id, o.total_amount, o.order_status, o.order_date, o.expected_delivery,
               o.payment_method,o.contact_number,
               b.name AS buyer_name, 
               s.name AS seller_name, p.name AS product_title
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

<style>
    body, html {
        height: 100%;
        margin: 0;
        background: linear-gradient(to right, #eff3ff, #c6dbef);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .navbar-custom {
        position : fixed;
        top : 0;
        width : 100%;
        z-index: 1000;
    }

    .canvas-container {
        min-height: 100vh;
        padding: 40px 20px;
        background: rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        margin: 30px auto;
        width: 95%;
    }

    h3 {
        color: #08519c;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        font-weight: 700;
    }

    .table-3d {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
    }

    .table-3d tbody tr:hover {
        transform: scale(1.02);
        background-color: #f1f1f1;
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .btn-primary:hover {
        transform: scale(1.1);
    }

    .form-select:hover, .form-control:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .filter-box {
        background: rgba(255, 255, 255, 0.8);
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
        min-width: 1000px;
    }
</style>

<div class="canvas-container">
    <h3 class="mb-4 text-center"><i class="fas fa-truck-fast me-2"></i>Manage Orders</h3>

    <!-- Alerts -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $message_type === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Filter Dropdown -->
    <form method="GET" class="filter-box">
        <div class="row g-2 align-items-center justify-content-center">
            <div class="col-auto">
                <label for="status" class="form-label">Filter by Status:</label>
            </div>
            <div class="col-auto">
                <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                    <option value="">All</option>
                    <?php foreach ($statuses as $s): ?>
                        <option value="<?= $s ?>" <?= ($status_filter === $s) ? 'selected' : '' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>

    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle table-3d">
                <thead class="table-dark">
                    <tr>
                        <th>#Order ID</th>
                        <th>Buyer</th>
                        <th>Contact</th>
                        <th>Seller</th>
                        <th>Product</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Expected Delivery</th>
                        <th>Ordered On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $result->fetch_assoc()): ?>
                        <form method="POST">
                            <tr class="<?= ($updated_order_id == $order['id']) ? 'table-success' : '' ?>">
                                <td><?= $order['id'] ?></td>
                                <td><?= htmlspecialchars($order['buyer_name']) ?></td>
                                <td><?= htmlspecialchars($order['contact_number'] ?? '') ?></td>
                                <td><?= htmlspecialchars($order['seller_name']) ?></td>
                                <td><?= htmlspecialchars($order['product_title']) ?></td>
                                <td>₹<?= number_format($order['total_amount'], 2) ?></td>
                                <td><?= htmlspecialchars($order['payment_method']) ?></td>
                                <td>
                                    <select name="status" class="form-select form-select-sm">
                                        <?php foreach ($statuses as $s): ?>
                                            <option value="<?= $s ?>" <?= ($order['order_status'] === $s) ? 'selected' : '' ?>><?= $s ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="date" name="expected_delivery"
                                           class="form-control form-control-sm"
                                           value="<?= htmlspecialchars($order['expected_delivery']) ?>"
                                           min="<?= htmlspecialchars(date('Y-m-d', strtotime($order['order_date']))) ?>">
                                </td>
                                <td><?= date('d M Y', strtotime($order['order_date'])) ?></td>
                                <td>
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-primary" onclick="this.disabled=true; this.form.submit();">
                                        <i class="fas fa-save me-1"></i>Update
                                    </button>
                                </td>
                            </tr>
                        </form>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">No orders found.</div>
    <?php endif; ?>
</div>

<?php require_once('../includes/footer.php'); ?>
