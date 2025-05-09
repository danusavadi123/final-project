<?php
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/db.php');

// Only sellers can edit
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../auth/login.php");
    exit();
}

$seller_id = $_SESSION['user_id'];
$message = "";

// Validate product ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_products.php");
    exit();
}

$product_id = intval($_GET['id']);

// Fetch product
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
$stmt->bind_param("ii", $product_id, $seller_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: manage_products.php");
    exit();
}

$product = $result->fetch_assoc();

// Handle form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $discount = intval($_POST['discount']);
    $category = trim($_POST['category']);

    // If image uploaded
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . '_' . basename($_FILES['image']['name']);
        $target = "../uploads/" . $image_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);

        // Update with image
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, discount=?, category=?, image=? WHERE id=? AND seller_id=?");
        $stmt->bind_param("sdissii", $name, $price, $discount, $category, $image_name, $product_id, $seller_id);
    } else {
        // Update without image
        $stmt = $conn->prepare("UPDATE products SET name=?, price=?, discount=?, category=? WHERE id=? AND seller_id=?");
        $stmt->bind_param("sdisii", $name, $price, $discount, $category, $product_id, $seller_id);
    }

    if ($stmt->execute()) {
        header("Location: manage_products.php?msg=updated");
        exit();
    } else {
        $message = "<div class='alert alert-danger'>Failed to update product.</div>";
    }
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

<div class="container my-5">
    <h2>Edit Product</h2>
    <?php echo $message; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Price (₹)</label>
            <input type="number" name="price" step="0.01" min="1" class="form-control" value="<?php echo $product['price']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Discount (%)</label>
            <input type="number" name="discount" min="0" max="100" class="form-control" value="<?php echo $product['discount']; ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-control" required>
                <option value="" disabled>Select a category</option>
                <?php
                $categories = ["Cakes", "Crafts", "Clothing", "Jewelry", "Accessories", "Gifts", "Home Decor", "Art", "Toys", "Other"];
                foreach ($categories as $cat) {
                    $selected = ($product['category'] === $cat) ? 'selected' : '';
                    echo "<option value='$cat' $selected>$cat</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Change Product Image</label><br>
            <img src="../uploads/<?php echo htmlspecialchars($product['image']); ?>" width="150" class="mb-2"><br>
            <input type="file" name="image" class="form-control">
            <small class="text-muted">Leave blank to keep current image</small>
        </div>

        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="manage_products.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php require_once('../includes/footer.php'); ?>
