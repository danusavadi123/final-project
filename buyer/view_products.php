<?php
require_once('../includes/spinner.html');
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
  <title>Search Results</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap & Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(to right, #eff3ff, #ffffff);
    }

    h2 {
      font-weight: 700;
      color: #22577a;
      margin-bottom: 30px;
    }

    .product-card {
      transition: all 0.3s ease-in-out;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
      height: 100%;
    }

    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .card-img-top {
      height: 200px;
      object-fit: contain;
      background: #f8f9fa;
    }

    .card-title {
      font-size: 1.1rem;
      font-weight: 600;
    }

    .price {
      font-size: 1rem;
    }

    .product-discount {
      font-size: 0.9rem;
      color: #2e7d32;
    }

    .btn-view {
      border-radius: 50px;
    }

    .alert-warning {
      text-align: center;
      font-weight: 500;
    }

    @media (max-width: 576px) {
      h2 {
        font-size: 1.4rem;
      }
    }
  </style>
</head>
<body>

<div class="container my-5">
  <h2 class="text-center">Results for "<em><?= htmlspecialchars($search) ?></em>"</h2>

  <div class="row mt-4">
    <?php if (count($products) > 0): ?>
      <?php foreach ($products as $product): ?>
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
          <div class="card product-card">
            <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
            <div class="card-body d-flex flex-column justify-content-between">
              <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
              <?php
                  $originalPrice = $product['price'];
                  $discount = $product['discount'];
                  $finalPrice = $originalPrice - ($originalPrice * $discount / 100);
              ?>
              <p class="mb-1 price">
                <span class="text-muted text-decoration-line-through">₹<?= number_format($originalPrice, 2) ?></span>
                <span class="fw-bold text-danger ms-2">₹<?= number_format($finalPrice, 2) ?></span>
              </p>
              <p class="product-discount">You save <?= $discount ?>%</p>
              <a href="product_details.php?id=<?= $product['id'] ?>" class="btn btn-outline-primary w-100 btn-view mt-2">View</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="col-12">
        <div class="alert alert-warning mt-4">
          No products found for "<strong><?= htmlspecialchars($search) ?></strong>"
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
