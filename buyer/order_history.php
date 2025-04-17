<?php
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$buyer_id = $_SESSION['user_id'];

// Handle cancel request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order_id'])) {
    $cancel_order_id = intval($_POST['cancel_order_id']);

    $check = $conn->prepare("SELECT id FROM orders WHERE id = ? AND buyer_id = ? AND order_status NOT IN ('Delivered', 'Cancelled')");
    $check->bind_param("ii", $cancel_order_id, $buyer_id);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows > 0) {
        $update = $conn->prepare("UPDATE orders SET order_status = 'Cancelled' WHERE id = ?");
        $update->bind_param("i", $cancel_order_id);
        $update->execute();
    }
}

$sql = "SELECT o.*, p.name AS product_name, p.image AS product_image
        FROM orders o
        JOIN products p ON o.product_id = p.id
        WHERE o.buyer_id = $buyer_id
        ORDER BY o.order_date DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Order History</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/buyer_order.css">
</head>
<body>
<div class="container my-5">
  <h2 class="mb-4">Your Order History</h2>

  <?php if ($result && mysqli_num_rows($result) > 0): ?>
    <?php while ($order = mysqli_fetch_assoc($result)): ?>
      <div class="order-card">
        <div class="row align-items-center">
          <div class="col-md-1 col-3">
            <img src="../uploads/<?= htmlspecialchars($order['product_image']) ?>" class="product-img" alt="<?= htmlspecialchars($order['product_name']) ?>">
          </div>
          <div class="col-md-4 col-9">
            <strong><?= htmlspecialchars($order['product_name']) ?></strong><br>
            <small>Order ID: <?= $order['id'] ?></small>
          </div>
          <div class="col-md-2 col-6">
            Qty: <?= $order['quantity'] ?>
          </div>
          <div class="col-md-2 col-6">
            ₹<?= number_format($order['total_amount'], 2) ?>
          </div>
          <div class="col-md-3 col-12 text-end">
            <span class="badge 
              <?= $order['order_status'] === 'Cancelled' ? 'cancelled-badge' : 'bg-info text-dark' ?>">
              <?= $order['order_status'] ?>
            </span><br>
            <small class="text-muted"><?= date('d M Y', strtotime($order['order_date'])) ?></small>
            <?php if (!empty($order['expected_delivery'])): ?>
              <div class="small text-success">Expected by: <?= date('d M Y', strtotime($order['expected_delivery'])) ?></div>
            <?php endif; ?>
            <?php if (!in_array($order['order_status'], ['Delivered', 'Cancelled'])): ?>
              <form method="POST" class="mt-2">
                <input type="hidden" name="cancel_order_id" value="<?= $order['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this order?')">Cancel</button>
              </form>
            <?php endif; ?>
          </div>
        </div>

        <!-- Order Status Tree -->
        <?php if (!in_array($order['order_status'], ['Cancelled'])): ?>
        <div class="status-tracker mt-3">
          <?php
            $steps = ['Pending', 'Processed', 'Shipped', 'Delivered'];
            $current = array_search($order['order_status'], $steps);
            if ($current === false) $current = -1;
          ?>
          <?php foreach ($steps as $i => $step): ?>
            <div class="status-step <?= $i <= $current ? 'active-step' : '' ?>">
              <span><?= $step ?></span>
            </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="no-orders">
      <h5>No orders found.</h5>
      <a href="view_products.php" class="btn btn-primary mt-3">Start Shopping</a>
    </div>
  <?php endif; ?>
</div>
</body>
</html>
