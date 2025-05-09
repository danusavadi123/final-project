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

// Handle form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $discount = intval($_POST['discount']);
    $category = trim($_POST['category']);
    $seller_id = $_SESSION['user_id'];

    // Handle image upload
    $image = $_FILES['image']['name'];
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

    // Insert product
    $stmt = $conn->prepare("INSERT INTO products (seller_id, name, description, price, discount, image, category, created_at)
                            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("issdiss", $seller_id, $title, $description, $price, $discount, $image, $category);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>Product added successfully.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Something went wrong. Please try again.</div>";
    }
}
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<div class="container mt-5">
    <h3>Add New Product</h3>
    <?php echo $message; ?>

    <form method="POST" enctype="multipart/form-data" class="mt-4">
        <div class="mb-3">
            <label for="title" class="form-label">Product Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Product Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>

        <div class="mb-3">
        <label for="category" class="form-label">Category</label>
        <select class="form-control" id="category" name="category" required>
        <option value="" disabled selected>Select a category</option>
        <option value="Cakes">Cakes</option>
        <option value="Crafts">Crafts</option>
        <option value="Clothing">Clothing</option>
        <option value="Jewelry">Jewelry</option>
        <option value="Accessories">Accessories</option>
        <option value="Gifts">Gifts</option>
        <option value="Home Decor">Home Decor</option>
        <option value="Art">Art</option>
        <option value="Toys">Toys</option>
        <option value="Other">Other</option>
        </select>
        </div>


        <div class="mb-3">
            <label for="price" class="form-label">Price (₹)</label>
            <input type="number" step="0.01" min="1" class="form-control" id="price" name="price" required>
        </div>
        <div class="mb-3">
            <label for="discount" class="form-label">Discount (%)</label>
            <input type="number" step="0.01" min="1" class="form-control" id="discount" name="discount" required>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <input class="form-control" type="file" id="image" name="image" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>
</div>

<?php require_once('../includes/footer.php'); ?>