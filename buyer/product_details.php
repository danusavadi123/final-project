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


// Get similar products (same category, excluding current product)
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
  <style>
    .product-image {
      width: 100%;
      max-height: 400px;
      object-fit: contain;
    }
    .product-price {
      font-size: 1.4rem;
      font-weight: bold;
    }
    .product-discount {
      color: green;
      font-weight: 500;
    }
    .similar-products .card-img-top {
      height: 180px;
      object-fit: contain;
    }
    .similar-products .card:hover {
      transform: scale(1.02);
      transition: 0.3s;
    }
  </style>
</head>
<body>

<div class="container my-5">
  <div class="row">
    <div class="col-md-5">
      <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="product-image img-fluid border">
    </div>
    <div class="col-md-7">
      <h2><?= htmlspecialchars($product['name']) ?></h2>
      <p class="text-muted">Sold by: <strong><?= htmlspecialchars($seller_name) ?></strong></p>
      <p class="text-muted"><?= htmlspecialchars($product['description']) ?></p>
      <?php
         $originalPrice = $product['price'];
         $discount = $product['discount'];
         $finalPrice = $originalPrice - ($originalPrice * $discount / 100);
      ?>

         <p class="mb-1">
            <span class="text-muted text-decoration-line-through">₹<?= number_format($originalPrice, 2) ?></span>
            <span class="fw-bold text-danger ms-2">₹<?= number_format($finalPrice, 2) ?></span>
        </p>
        <p class="product-discount">You save <?= $discount ?>%</p>
        

      <div class="mt-4">
      <form action="add_to_cart.php" method="POST" class="mb-3">
  <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
  
  <div class="input-group mb-3" style="max-width: 200px;">
  <button class="btn btn-outline-secondary" type="button" id="decreaseQty">−</button>
  <input type="number" name="quantity" value="1" min="1" max="99" class="form-control text-center" id="quantityInput" required>
  <button class="btn btn-outline-secondary" type="button" id="increaseQty">+</button>
</div>


  <button type="submit" class="btn btn-outline-primary mt-2">Add to Cart</button>
</form>

<form action="../orders/place_order.php" method="GET">
  <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
  <input type="hidden" name="quantity" value="1" id="order-now-qty">
  <button type="submit" class="btn btn-success">Order Now</button>
</form>
      </div>
    </div>
  </div>

  <?php if (mysqli_num_rows($similar_result) > 0): ?>
  <hr class="my-5">
  <h4>Similar Products</h4>
  <div class="row similar-products">
    <?php while ($similar = mysqli_fetch_assoc($similar_result)): ?>
    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
      <div class="card h-100">
        <img src="../uploads/<?= htmlspecialchars($similar['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($similar['name']) ?>">
        <div class="card-body">
          <h6 class="card-title"><?= htmlspecialchars($similar['name']) ?></h6>
          <p class="product-price">₹<?= $similar['price'] ?></p>
          <p class="product-discount">Discount: <?= $similar['discount'] ?>%</p>
          <a href="product_details.php?id=<?= $similar['id'] ?>" class="btn btn-sm btn-outline-primary w-100">View</a>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
  <?php endif; ?>
</div>

  <script>
     var $j = jQuery.noConflict();
  $j("form[action='add_to_cart.php']").submit(function (e) {
  e.preventDefault();
  var form = $j(this);
  var productId = form.find("input[name='product_id']").val();
  var quantity = form.find("input[name='quantity']").val();

  $j.ajax({
    url: "../buyer/cart_handler.php?action=add",
    method: "POST",
    data: { product_id: productId, quantity: quantity },
    success: function (res) {
      if (res.status === 'success') {
        $j("#cart-count").text(res.count);
      }
    }
  });
});

  const qtyInput = document.getElementById('quantityInput');
  const increaseBtn = document.getElementById('increaseQty');
  const decreaseBtn = document.getElementById('decreaseQty');
  const orderNowQty = document.getElementById('order-now-qty'); // sync hidden input

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
</script>

</body>
</html>