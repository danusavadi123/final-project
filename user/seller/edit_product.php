<?php
// Edit Product - Sellers can update their product details

include '../includes/session.php';
checkRole('seller'); // Only sellers can access this page
include '../config/database.php';

// Check if product ID is provided
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Invalid product ID.";
    header("Location: manage_products.php");
    exit();
}

$product_id = $_GET['id'];
$seller_id = $_SESSION['user_id'];

// Fetch product details
$query = "SELECT * FROM products WHERE id = ? AND seller_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $product_id, $seller_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Redirect if product not found
if (!$product) {
    $_SESSION['error'] = "Product not found.";
    header("Location: manage_products.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = $_POST["price"];
    $category = trim($_POST["category"]);
    $location = trim($_POST["location"]);

    // Handle image upload (if updated)
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "../uploads/";
        $image = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    } else {
        $image = $product['image']; // Keep the old image
    }

    // Update product in database
    $update_query = "UPDATE products SET name=?, description=?, price=?, category=?, location=?, image=? WHERE id=? AND seller_id=?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssdsssii", $name, $description, $price, $category, $location, $image, $product_id, $seller_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Product updated successfully!";
        header("Location: manage_products.php");
        exit();
    } else {
        $_SESSION['error'] = "Error updating product. Try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Edit Product</h2>

    <?php if (isset($_SESSION['error'])) { ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php } ?>

    <form action="edit_product.php?id=<?= $product_id; ?>" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($product['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" required><?= htmlspecialchars($product['description']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price (₹)</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" value="<?= $product['price']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" name="category" id="category" class="form-control" value="<?= htmlspecialchars($product['category']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" id="location" class="form-control" value="<?= htmlspecialchars($product['location']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <input type="file" name="image" id="image" class="form-control">
            <p>Current Image: <img src="../uploads/<?= $product['image']; ?>" width="50" height="50"></p>
        </div>
        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="manage_products.php" class="btn btn-secondary">Back to Manage Products</a>
    </form>
</div>

</body>
</html>