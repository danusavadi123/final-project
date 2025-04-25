<?php
session_start();
require_once('../includes/spinner.html');
require_once('../config/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$buyer_id = $_SESSION['user_id'];
$cart_items = [];
$total_price = 0;

// 🔍 Check if this is a single product order (via "Order Now")
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
    // 🛒 Cart order
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .product-img {
      width: 60px;
      height: 60px;
      object-fit: cover;
    }
    .summary-box {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
    }
    .discount-text {
      font-size: 0.875rem;
      color: #dc3545;
    }
  </style>
</head>
<body>
<div class="container my-5">
  <h2 class="mb-4">Place Your Order</h2>

  <form action="submit_order.php" method="POST">
    <!-- Pass order mode -->
    <input type="hidden" name="single_order" value="<?= $single_order ? '1' : '0' ?>">

    <?php if ($single_order): ?>
      <input type="hidden" name="product_id" value="<?= $product_id ?>">
      <input type="hidden" name="quantity" value="<?= $quantity ?>">
    <?php endif; ?>

    <div class="row">
      <div class="col-md-7">
        <h5>Delivery Address</h5>
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
      </div>

      <div class="col-md-5">
        <h5>Order Summary</h5>
        <div class="summary-box mb-3">
          <?php foreach ($cart_items as $item): ?>
            <?php
              $original = $item['price'];
              $discount = $item['discount'];
              $final = $original * (1 - $discount / 100);
            ?>
            <div class="d-flex justify-content-between mb-2 align-items-center">
              <img src="../uploads/<?= htmlspecialchars($item['image']) ?>" class="product-img me-2">
              <div>
                <?= htmlspecialchars($item['name']) ?><br>
                <small>Qty: <?= $item['quantity'] ?> x ₹<?= number_format($final, 2) ?></small>
                <?php if ($discount > 0): ?>
                  <br><span class="discount-text">Discount: <?= $discount ?>% off</span>
                <?php endif; ?>
              </div>
              <div class="text-end fw-semibold">₹<?= number_format($final * $item['quantity'], 2) ?></div>
            </div>
          <?php endforeach; ?>
          <hr>
          <div class="d-flex justify-content-between fw-bold">
            <span>Total:</span>
            <span id="totalPrice">₹<?= number_format($total_price, 2) ?></span>
          </div>
        </div>
        <button type="submit" class="btn btn-success w-100">Place Order</button>
      </div>
    </div>
  </form>
</div>
</body>
</html>
