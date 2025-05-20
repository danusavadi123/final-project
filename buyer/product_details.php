<?php
require_once('../includes/spinner.html');
require_once('../config/db.php');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Product not found!";
    exit;
}

$product_id = intval($_GET['id']);
$query = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Product not found!";
    exit;
}

$product = mysqli_fetch_assoc($result);
$seller_id = $product['seller_id'];
$seller_query = "SELECT name FROM users WHERE id = $seller_id";
$seller_result = mysqli_query($conn, $seller_query);

$seller_name = "Unknown Seller";
if ($seller_result && mysqli_num_rows($seller_result) > 0) {
    $seller_data = mysqli_fetch_assoc($seller_result);
    $seller_name = $seller_data['name'];
}

// Get similar products
$category = $product['category'];
$similar_query = "SELECT * FROM products WHERE category = '$category' AND id != $product_id LIMIT 4";
$similar_result = mysqli_query($conn, $similar_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($product['name']) ?> - Product Details</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f9fafb;
    }

    .product-image {
      width: 100%;
      max-height: 400px;
      object-fit: contain;
      border-radius: 8px;
      background: #fff;
      padding: 10px;
    }

    .product-price {
      font-size: 1.3rem;
      font-weight: 700;
    }

    .product-discount {
      color: #2e7d32;
      font-weight: 500;
    }

    .btn-outline-primary,
    .btn-success {
      border-radius: 50px;
    }

    .similar-products .card {
      transition: transform 0.3s ease-in-out;
      border-radius: 12px;
      overflow: hidden;
      height: 100%;
    }

    .similar-products .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
    }

    .similar-products .card-img-top {
      height: 180px;
      object-fit: contain;
      background: #f8f9fa;
    }

    .quantity-input-group {
      max-width: 200px;
    }

    @media (max-width: 576px) {
      .product-price {
        font-size: 1.1rem;
      }
    }
  </style>
</head>
<body>

<div class="container my-5">
  <div class="row g-5">
    <div class="col-md-5">
      <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image img-fluid">
    </div>
    <div class="col-md-7">
      <h2 class="fw-bold"><?= htmlspecialchars($product['name']) ?></h2>
      <p class="text-muted">Sold by: <strong><?= htmlspecialchars($seller_name) ?></strong></p>
      <p><?= htmlspecialchars($product['description']) ?></p>

      <?php
        $originalPrice = $product['price'];
        $discount = $product['discount'];
        $finalPrice = $originalPrice - ($originalPrice * $discount / 100);
      ?>
      <p class="mb-1">
        <span class="text-muted text-decoration-line-through">₹<?= number_format($originalPrice, 2) ?></span>
        <span class="text-danger ms-2 product-price">₹<?= number_format($finalPrice, 2) ?></span>
      </p>
      <p class="product-discount">You save <?= $discount ?>%</p>

      <div class="mt-4">
        <form action="add_to_cart.php" method="POST" class="mb-3">
          <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
          <div class="input-group quantity-input-group mb-3">
            <button class="btn btn-outline-secondary" type="button" id="decreaseQty">−</button>
            <input type="number" name="quantity" value="1" min="1" max="99" class="form-control text-center" id="quantityInput" required>
            <button class="btn btn-outline-secondary" type="button" id="increaseQty">+</button>
          </div>
          <button type="submit" class="btn btn-outline-primary w-50">Add to Cart</button>
        </form>

        <form action="./place_order.php" method="GET">
          <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
          <input type="hidden" name="quantity" value="1" id="order-now-qty">
          <button type="submit" class="btn btn-success w-50">Order Now</button>
        </form>
      </div>
    </div>
  </div>

  <?php if (mysqli_num_rows($similar_result) > 0): ?>
    <hr class="my-5">
    <h4 class="mb-4">Similar Products</h4>
    <div class="row similar-products">
      <?php while ($similar = mysqli_fetch_assoc($similar_result)): ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
          <div class="card">
            <img src="../uploads/<?= htmlspecialchars($similar['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($similar['name']) ?>">
            <div class="card-body d-flex flex-column justify-content-between">
              <h6 class="card-title"><?= htmlspecialchars($similar['name']) ?></h6>
              <p class="product-price mb-1">₹<?= number_format($similar['price'], 2) ?></p>
              <p class="product-discount mb-2">Discount: <?= $similar['discount'] ?>%</p>
              <a href="product_details.php?id=<?= $similar['id'] ?>" class="btn btn-sm btn-outline-primary mt-auto w-100">View</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php endif; ?>
</div>

<script>
  const qtyInput = document.getElementById('quantityInput');
  const increaseBtn = document.getElementById('increaseQty');
  const decreaseBtn = document.getElementById('decreaseQty');
  const orderNowQty = document.getElementById('order-now-qty');

  increaseBtn.addEventListener('click', () => {
    let value = parseInt(qtyInput.value);
    if (value < parseInt(qtyInput.max)) {
      qtyInput.value = value + 1;
      orderNowQty.value = qtyInput.value;
    }
  });

  decreaseBtn.addEventListener('click', () => {
    let value = parseInt(qtyInput.value);
    if (value > parseInt(qtyInput.min)) {
      qtyInput.value = value - 1;
      orderNowQty.value = qtyInput.value;
    }
  });

  qtyInput.addEventListener('input', () => {
    orderNowQty.value = qtyInput.value;
  });

  // AJAX Add to Cart
  document.querySelector("form[action='add_to_cart.php']").addEventListener('submit', function (e) {
    e.preventDefault();
    const form = this;
    const productId = form.querySelector("input[name='product_id']").value;
    const quantity = form.querySelector("input[name='quantity']").value;

    fetch("../buyer/cart_handler.php?action=add", {
      method: "POST",
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `product_id=${productId}&quantity=${quantity}`
    })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        document.getElementById("cart-count").textContent = data.count;
      }
    });
  });
</script>

</body>
</html>
