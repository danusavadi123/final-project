<?php
session_start();
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$buyer_id = $_SESSION['user_id'];
$cart_items = [];
$total_price = 0;

if (isset($_GET['product_id']) && isset($_GET['quantity'])) {
    $product_id = (int) $_GET['product_id'];
    $quantity = (int) $_GET['quantity'];

    $res = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id");
    if ($res && mysqli_num_rows($res) > 0) {
        $product = mysqli_fetch_assoc($res);
        $discount = $product['discount'] ?? 0;
        $price_after_discount = $product['price'] * (1 - $discount / 100);
        $item_total = $price_after_discount * $quantity;
        $total_price = $item_total;

        $cart_items[] = [
            'product_id' => $product_id,
            'name'       => $product['name'],
            'image'      => $product['image'],
            'price'      => $product['price'],
            'quantity'   => $quantity,
            'discount'   => $discount
        ];

        $single_order = true;
    } else {
        echo "Product not found.";
        exit;
    }
} else {
    $sql = "SELECT c.product_id, c.quantity, p.name, p.price, p.image, p.discount 
            FROM cart c 
            JOIN products p ON c.product_id = p.id 
            WHERE c.buyer_id = $buyer_id";
    $res = mysqli_query($conn, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        while ($item = mysqli_fetch_assoc($res)) {
            $discount = $item['discount'] ?? 0;
            $price_after_discount = $item['price'] * (1 - $discount / 100);
            $item_total = $price_after_discount * $item['quantity'];
            $total_price += $item_total;

            $item['discount'] = $discount;
            $item['total_amount'] = $price_after_discount;
            $cart_items[] = $item;
        }
    } else {
        echo "<p>Your cart is empty. <a href='view_products.php'>Shop now</a></p>";
        exit;
    }

    $single_order = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Place Order</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/navbar.css">
  <style>
    body {
      background: linear-gradient(to right, #eff3ff, #c6dbef);
      font-family: 'Segoe UI', sans-serif;
    }

    .page-title {
      color: #08519c;
      font-weight: 700;
      text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
    }

    .card-floating {
      background: #fff;
      padding: 25px;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card-floating:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
    }

    .product-img {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
      transition: transform 0.2s ease;
    }

    .product-img:hover {
      transform: scale(1.1);
    }

    .form-control, .form-select {
      border-radius: 12px;
      border: 1px solid #ccc;
      box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    }

    .btn-success {
      border-radius: 50px;
      font-weight: bold;
      box-shadow: 0 4px 12px rgba(40,167,69,0.4);
      transition: 0.3s ease;
    }

    .btn-success:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(40,167,69,0.6);
    }

    .discount-text {
      color: #dc3545;
      font-size: 0.85rem;
    }

    .price-tag {
      font-weight: bold;
      color: #28a745;
    }

    @media (max-width: 768px) {
      .product-img {
        width: 50px;
        height: 50px;
      }
    }
  </style>
</head>
<body>
<div class="container py-5">
  <h2 class="mb-4 text-center page-title">🛒 Place Your Order</h2>

  <form action="submit_order.php" method="POST">
    <input type="hidden" name="single_order" value="<?= $single_order ? '1' : '0' ?>">
    <?php if ($single_order): ?>
      <input type="hidden" name="product_id" value="<?= $product_id ?>">
      <input type="hidden" name="quantity" value="<?= $quantity ?>">
    <?php endif; ?>

    <div class="row g-4">
      <!-- Delivery Address -->
      <div class="col-lg-7">
        <div class="card-floating h-100">
          <h5 class="mb-4"><i class="fas fa-map-marker-alt me-2"></i>Delivery Address</h5>
          <div class="mb-3">
            <label class="form-label">Address</label>
            <input type="text" name="address" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Pincode</label>
            <input type="text" name="pincode" class="form-control" required pattern="\d{6}" title="Enter a 6-digit pincode">
          </div>
          <div class="mb-3">
            <label class="form-label">Contact Number</label>
            <input type="tel" name="contact_number" class="form-control" required pattern="\d{10}" title="Enter a 10-digit phone number">
          </div>
        </div>
      </div>

      <!-- Order Summary & Payment -->
      <div class="col-lg-5">
        <div class="card-floating h-100">
          <h5 class="mb-4"><i class="fas fa-receipt me-2"></i>Order Summary</h5>
          <?php foreach ($cart_items as $item): ?>
            <?php
              $original = $item['price'];
              $discount = $item['discount'];
              $final = $original * (1 - $discount / 100);
            ?>
            <div class="d-flex mb-3 align-items-center">
              <img src="../uploads/<?= htmlspecialchars($item['image']) ?>" class="product-img me-3">
              <div class="flex-grow-1">
                <strong><?= htmlspecialchars($item['name']) ?></strong><br>
                <small>Qty: <?= $item['quantity'] ?> × ₹<?= number_format($final, 2) ?></small>
                <?php if ($discount > 0): ?>
                  <br><span class="discount-text">Discount: <?= $discount ?>%</span>
                <?php endif; ?>
              </div>
              <div class="text-end price-tag">₹<?= number_format($final * $item['quantity'], 2) ?></div>
            </div>
          <?php endforeach; ?>
          <hr>
          <div class="d-flex justify-content-between fs-5 fw-semibold mb-3">
            <span>Total</span>
            <span class="text-success">₹<?= number_format($total_price, 2) ?></span>
          </div>

          <!-- Payment Method Dropdown -->
          <div class="mb-3">
            <label class="form-label">Payment Method</label>
            <select name="payment_method" class="form-select" required>
              <option value="" disabled selected>Select payment method</option>
              <option value="cod">Cash on Delivery</option>
              <option value="razorpay">Razorpay</option>
            </select>
          </div>

          <button type="submit" class="btn btn-success w-100 mt-2">Place Order</button>
        </div>
      </div>
    </div>
  </form>
</div>
</body>
</html>
