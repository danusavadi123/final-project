<?php
include_once '../includes/session.php';
include_once '../config/config.php';
include_once '../config/database.php';

redirectIfNotLoggedIn();

if ($_SESSION['user_role'] !== 'seller') {
    header("Location: " . BASE_URL . "/index.php");
    exit;
}

$seller_id = $_SESSION['user_id'];
$product_id = $_GET['id'] ?? null;

if (!$product_id) {
    header("Location: manage_products.php");
    exit;
}

// Fetch the product data
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
$stmt->execute([$product_id, $seller_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found or unauthorized access.");
}

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = $_POST['name'];
    $category    = $_POST['category'];
    $price       = $_POST['price'];
    $description = $_POST['description'];

    // Handle image update if uploaded
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . $_FILES['image']['name'];
        $image_path = '../uploads/' . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    } else {
        $image_name = $product['image']; // keep old image
    }

    $stmt = $conn->prepare("UPDATE products SET name = ?, category = ?, price = ?, description = ?, image = ? WHERE id = ? AND seller_id = ?");
    $success = $stmt->execute([$name, $category, $price, $description, $image_name, $product_id, $seller_id]);

    if ($success) {
        $message = "Product updated successfully.";
        // Refresh product data
        $stmt->execute([$product_id, $seller_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $message = "Failed to update product.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product</title>
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/bootstrap.min.css">
</head>
<body>
<?php include '../includes/navbar.php'; ?>

<div class="container my-5">
  <h2 class="mb-4 text-center">Edit Product</h2>

  <?php if ($message): ?>
    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form action="" method="POST" enctype="multipart/form-data" class="mx-auto" style="max-width: 600px;">
    <div class="mb-3">
      <label for="name" class="form-label">Product Name</label>
      <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
    </div>

    <div class="mb-3">
      <label for="category" class="form-label">Category</label>
      <input type="text" name="category" id="category" class="form-control" value="<?= htmlspecialchars($product['category']) ?>" required>
    </div>

    <div class="mb-3">
      <label for="price" class="form-label">Price (₹)</label>
      <input type="number" name="price" id="price" step="0.01" class="form-control" value="<?= htmlspecialchars($product['price']) ?>" required>
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea name="description" id="description" class="form-control" required><?= htmlspecialchars($product['description']) ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label">Current Image:</label><br>
      <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($product['image']) ?>" width="100" height="100">
    </div>

    <div class="mb-3">
      <label for="image" class="form-label">Change Image</label>
      <input type="file" name="image" id="image" class="form-control">
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-success">Update Product</button>
      <a href="manage_products.php" class="btn btn-secondary">Back</a>
    </div>
  </form>
</div>

<?php include '../includes/footer.php'; ?>
<script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>