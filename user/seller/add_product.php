<?php
// Add Product Page - Allows sellers to add products

include '../includes/session.php';
checkRole('seller'); // Only sellers can access this page
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seller_id = $_SESSION['user_id']; // Get seller's ID from session
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = $_POST["price"];
    $category = trim($_POST["category"]);
    $location = trim($_POST["location"]);

    // Image upload handling
    $image = "";
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "../uploads/";
        $image = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }

    // Insert product into database
    $query = "INSERT INTO products (seller_id, name, description, price, category, location, image) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("issdsss", $seller_id, $name, $description, $price, $category, $location, $image);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Product added successfully!";
    } else {
        $_SESSION['error'] = "Error adding product. Try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Add Product</h2>

    <?php if (isset($_SESSION['error'])) { ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php } ?>

    <?php if (isset($_SESSION['success'])) { ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php } ?>

    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price (in ₹)</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" name="category" id="category" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" id="location" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Upload Image</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Add Product</button>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </form>
</div>

</body>
</html>