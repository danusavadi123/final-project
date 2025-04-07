<?php
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/database.php');

// Only buyers can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    header("Location: ../auth/login.php");
    exit();
}

$buyer_id = $_SESSION['user_id'];

// Fetch all orders by this buyer
$stmt = $conn->prepare("SELECT o.id, p.title, p.image, o.quantity, o.total_amount, o.order_date, o.status
                        FROM orders o
                        JOIN products p ON o.product_id = p.id
                        WHERE o.buyer_id = ?
                        ORDER BY o.order_date DESC");
$stmt->bind_param("i", $buyer_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container mt-5">
    <h3 class="mb-4">My Orders</h3>

    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Quantity</th>
                        <th>Total (₹)</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Track</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['title']); ?></td>
                            <td><img src="../uploads/<?php echo htmlspecialchars($order['image']); ?>" width="60" height="60" alt="Product Image"></td>
                            <td><?php echo $order['quantity']; ?></td>
                            <td><?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><?php echo date('d M Y, h:i A', strtotime($order['order_date'])); ?></td>
                            <td>
                                <?php
                                    $badge = 'secondary';
                                    if ($order['status'] === 'pending') $badge = 'warning';
                                    elseif ($order['status'] === 'shipped') $badge = 'info';
                                    elseif ($order['status'] === 'delivered') $badge = 'success';
                                    elseif ($order['status'] === 'cancelled') $badge = 'danger';
                                ?>
                                <span class="badge bg-<?php echo $badge; ?>"><?php echo ucfirst($order['status']); ?></span>
                            </td>
                            <td>
                                <a href="../orders/track_order.php?order_id=<?php echo $order['id']; ?>" class="btn btn-outline-primary btn-sm">Track</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted">You haven’t placed any orders yet.</p>
    <?php endif; ?>
</div>

<?php require_once('../includes/footer.php'); ?>