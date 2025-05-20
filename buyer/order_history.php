<?php
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/db.php');


if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Optional: handle "just placed" flag for success messages
if (isset($_SESSION['order_just_placed'])) {
    echo "<script>alert('🎉 Your order was placed successfully!');</script>";
    unset($_SESSION['order_just_placed']);
}


$buyer_id = $_SESSION['user_id'];

// Cancel Order Logic
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/navbar.css">
  <style>
    body {
      background: linear-gradient(135deg, #6baed6, #eff3ff);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    h2 {
      padding-top : 4rem;
      font-weight: 700;
      text-align: center;
      margin: 40px 0;
      color: #333;
    }

    .order-card {
      background: #fff;
      border-radius: 20px;
      padding: 20px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
      margin-bottom: 30px;
      transition: transform 0.3s ease;
    }

    .order-card:hover {
      transform: scale(1.01);
    }

    .product-img {
      width: 70px;
      height: 70px;
      object-fit: cover;
      border-radius: 15px;
    }

    .status-tracker {
      display: flex;
      justify-content: space-between;
      margin-top: 15px;
    }

    .status-step {
      flex: 1;
      text-align: center;
      font-size: 0.85rem;
      position: relative;
      color: #bbb;
    }

    .status-step::before {
      content: '';
      display: block;
      margin: 0 auto 6px;
      width: 15px;
      height: 15px;
      background: #ccc;
      border-radius: 50%;
    }

    .status-step.active-step {
      color: #3182bd;
      font-weight: bold;
    }

    .status-step.active-step::before {
      background: #3182bd;
    }

    .status-step:not(:last-child)::after {
      content: '';
      position: absolute;
      top: 7px;
      right: -50%;
      width: 100%;
      height: 3px;
      background: #ccc;
      z-index: -1;
    }

    .status-step.active-step:not(:last-child)::after {
      background: #3182bd;
    }

    .badge.cancelled-badge {
      background-color: #08519c;
      color: white;
    }

    .btn-danger {
      border-radius: 30px;
      padding: 6px 14px;
    }

    .btn-primary {
      border-radius: 30px;
      padding: 10px 25px;
      background: linear-gradient(45deg, #6baed6, #3182bd);
      border: none;
    }

    .btn-primary:hover {
      transform: scale(1.05);
    }

    .no-orders {
      text-align: center;
      margin-top: 80px;
    }
  </style>
</head>
<body>

<div class="container my-5">
  <h2>Your Order History</h2>

  <?php if ($result && mysqli_num_rows($result) > 0): ?>
    <?php while ($order = mysqli_fetch_assoc($result)): ?>
      <div class="order-card">
        <div class="row align-items-center">
          <div class="col-md-1 col-3">
            <img src="../uploads/<?= htmlspecialchars($order['product_image']) ?>" class="product-img" alt="<?= htmlspecialchars($order['product_name']) ?>">
          </div>
          <div class="col-md-4 col-9">
            <strong><?= htmlspecialchars($order['product_name']) ?></strong><br>
            <small class="text-muted">Order ID: <?= $order['id'] ?></small>
          </div>
          <div class="col-md-2 col-6">
            Qty: <?= $order['quantity'] ?>
          </div>
          <div class="col-md-2 col-6">
            ₹<?= number_format($order['total_amount'], 2) ?>
          </div>
          <div class="col-md-3 text-end">
            <span class="badge <?= $order['order_status'] === 'Cancelled' ? 'cancelled-badge' : 'bg-info text-dark' ?>">
              <?= $order['order_status'] ?>
            </span><br>
            <small class="text-muted"><?= date('d M Y', strtotime($order['order_date'])) ?></small>
            <?php if (!empty($order['expected_delivery'])): ?>
              <div class="text-success small">Expected by: <?= date('d M Y', strtotime($order['expected_delivery'])) ?></div>
            <?php endif; ?>

            <?php if (!in_array($order['order_status'], ['Delivered', 'Cancelled'])): ?>
              <form method="POST" class="mt-2">
                <input type="hidden" name="cancel_order_id" value="<?= $order['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this order?')">Cancel</button>
              </form>
            <?php endif; ?>
          </div>
        </div>

        <!-- Order Progress Tracker -->
        <?php if ($order['order_status'] !== 'Cancelled'): ?>
        <div class="status-tracker">
          <?php
            $steps = ['Pending', 'Processed', 'Shipped', 'Delivered'];
            $current = array_search($order['order_status'], $steps);
            if ($current === false) $current = -1;
          ?>
          <?php foreach ($steps as $index => $step): ?>
            <div class="status-step <?= $index <= $current ? 'active-step' : '' ?>">
              <?= $step ?>
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
