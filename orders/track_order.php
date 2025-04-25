<?php
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/database.php');

// Only buyers can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['order_id'])) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Order ID missing.</div></div>";
    require_once('../includes/footer.php');
    exit();
}

$order_id = intval($_GET['order_id']);
$buyer_id = $_SESSION['user_id'];

// Get order details with product info
$stmt = $conn->prepare("SELECT o.*, p.title, p.image 
                        FROM orders o 
                        JOIN products p ON o.product_id = p.id 
                        WHERE o.id = ? AND o.buyer_id = ?");
$stmt->bind_param("ii", $order_id, $buyer_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='container mt-5'><div class='alert alert-warning'>Order not found or access denied.</div></div>";
    require_once('../includes/footer.php');
    exit();
}

$order = $result->fetch_assoc();
?>

<div class="container mt-5">
    <h3>Order Tracking</h3>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <img src="../uploads/<?php echo htmlspecialchars($order['image']); ?>" class="img-fluid" alt="Product Image">
        </div>
        <div class="col-md-8">
            <h4><?php echo htmlspecialchars($order['title']); ?></h4>
            <p><strong>Order ID:</strong> #<?php echo $order['id']; ?></p>
            <p><strong>Order Date:</strong> <?php echo date("d M Y, h:i A", strtotime($order['order_date'])); ?></p>
            <p><strong>Quantity:</strong> <?php echo $order['quantity']; ?></p>
            <p><strong>Total:</strong> ₹<?php echo number_format($order['total_amount'], 2); ?></p>
            <p><strong>Current Status:</strong> 
                <span class="badge bg-<?php 
                    switch ($order['status']) {
                        case 'pending': echo 'warning'; break;
                        case 'shipped': echo 'info'; break;
                        case 'delivered': echo 'success'; break;
                        case 'cancelled': echo 'danger'; break;
                        default: echo 'secondary';
                    }
                ?>">
                    <?php echo ucfirst($order['status']); ?>
                </span>
            </p>
        </div>
    </div>

    <hr>
    <h5>Order Timeline</h5>
    <ul class="list-group">
        <li class="list-group-item <?php echo ($order['status'] !== 'pending') ? 'list-group-item-success' : ''; ?>">
            Order Placed
        </li>
        <li class="list-group-item <?php echo ($order['status'] === 'shipped' || $order['status'] === 'delivered') ? 'list-group-item-info' : ''; ?>">
            Shipped
        </li>
        <li class="list-group-item <?php echo ($order['status'] === 'delivered') ? 'list-group-item-success' : ''; ?>">
            Delivered
        </li>
        <?php if ($order['status'] === 'cancelled'): ?>
            <li class="list-group-item list-group-item-danger">
                Cancelled
            </li>
        <?php endif; ?>
    </ul>
</div>

<?php require_once('../includes/footer.php'); ?>