<?php
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/db.php');

// Restrict access to sellers only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../auth/login.php");
    exit();
}

$seller_id = $_SESSION['user_id'];
$message = "";

// Handle deletion
if (isset($_GET['delete'])) {
    $product_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?");
    $stmt->bind_param("ii", $product_id, $seller_id);
    if ($stmt->execute()) {
        $message = "<div class='alert alert-success text-center'>✅ Product deleted successfully.</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>❌ Unable to delete product.</div>";
    }
}

// Fetch seller's products
$stmt = $conn->prepare("SELECT * FROM products WHERE seller_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Products</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap & Font Awesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/navbar.css">
  <style>
    body {
       : linear-gradient(135deg, #6baedc, #eff3ff);
      min-height: 100vh;
      font-family: 'Poppins', sans-serif;
      padding: 30px 20px;
    }

    .product-container {
      max-width: 1100px;
      margin: 5rem auto;
    }

    h3 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: 700;
      color: #343a40;
    }

    .card.product-card {
      border: none;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card.product-card:hover {
      transform: scale(1.04);
      box-shadow: 0 16px 40px rgba(0, 0, 0, 0.15);
    }

    .product-image {
      height: 200px;
      object-fit: cover;
      border-top-left-radius: 20px;
      border-top-right-radius: 20px;
    }

    .card-body {
      padding: 1rem;
    }

    .card-body h5 {
      font-size: 1.1rem;
      font-weight: 600;
      color: #0d6efd;
    }

    .card-body p {
      font-size: 0.9rem;
      margin-bottom: 6px;
    }

    .card-footer {
      background-color: #f8f9fa;
      padding: 10px;
      display: flex;
      justify-content: space-between;
    }

    .btn-action {
      font-size: 0.85rem;
      border-radius: 20px;
      padding: 5px 12px;
    }

    .btn-warning {
      background: #ffc107;
      color: #212529;
    }

    .btn-danger {
      background: #dc3545;
      color: #fff;
    }

    .alert {
      max-width: 700px;
      margin: 0 auto 20px;
      border-radius: 10px;
    }
  </style>
</head>
<body>

<div class="product-container">
  <h3><i class="fas fa-boxes-stacked me-2"></i>Your Uploaded Products</h3>
  <?= $message ?>

  <div class="row">
    <?php if ($result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
          <div class="card product-card">
            <img src="../uploads/<?= htmlspecialchars($row['image']) ?>" class="product-image" alt="Product Image">
            <div class="card-body text-center">
              <h5><?= htmlspecialchars($row['name']) ?></h5>
              <p><strong>Price:</strong> ₹<?= number_format($row['price'], 2) ?></p>
              <p><strong>Discount:</strong> <?= $row['discount'] ?>%</p>
              <p><strong>Category:</strong> <?= htmlspecialchars($row['category']) ?></p>
              <p><i class="fas fa-calendar-alt"></i> <?= date('d M Y', strtotime($row['created_at'])) ?></p>
            </div>
            <div class="card-footer">
              <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-action"><i class="fas fa-edit"></i> Edit</a>
              <a href="manage_products.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-action"
                 onclick="return confirm('Are you sure you want to delete this product?');"><i class="fas fa-trash-alt"></i> Delete</a>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div class="col-12 text-center">
        <p class="text-muted">You haven't added any products yet.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php require_once('../includes/footer.php'); ?>
</body>
</html>
