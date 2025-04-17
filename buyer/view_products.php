<?php
require_once('../config/db.php');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

$products = [];

// Fetch the matching product first
$main_query = "SELECT * FROM products WHERE name = '$search' LIMIT 1";
$main_result = mysqli_query($conn, $main_query);

if ($main_result && mysqli_num_rows($main_result) > 0) {
    $main_product = mysqli_fetch_assoc($main_result);
    $products[] = $main_product;

    $category = $main_product['category'];

    // Fetch other products from the same category excluding the searched one
    $related_query = "SELECT * FROM products WHERE category = '$category' AND name != '$search'";
    $related_result = mysqli_query($conn, $related_query);

    while ($row = mysqli_fetch_assoc($related_result)) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Products</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .product-card {
      transition: transform 0.2s ease-in-out;
    }
    .product-card:hover {
      transform: scale(1.02);
    }
    .card-img-top {
      height: 200px;
      object-fit: contain;
    }
    .product-price {
      font-weight: bold;
      font-size: 1.1rem;
    }

    .product-discount {
      font-size: 0.9rem;
      color: #28a745;
      font-weight: 500;
    }

  </style>
</head>
<body>
<div class="container my-5">
  <h2 class="mb-4">Results for "<em><?= htmlspecialchars($search) ?></em>"</h2>
  <div class="row">

    <?php if (count($products) > 0): ?>
      <?php foreach ($products as $product): ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
          <div class="card product-card h-100">
            <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
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

            <a href="product_details.php?id=<?= $product['id'] ?>" class="btn btn-outline-primary w-100">View</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-warning">No products found for "<strong><?= htmlspecialchars($search) ?></strong>"</div>
      </div>
    <?php endif; ?>

  </div>
</div>
</body>
</html>