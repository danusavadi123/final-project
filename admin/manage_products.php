<?php
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('./admin_navbar.php');
require_once('../config/db.php');

// Ensure only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Handle product deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $product_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_products.php");
    exit();
}

// Category filter logic
$category_filter = $_GET['category'] ?? '';
$where_clause = $category_filter ? "WHERE category = ?" : "";

// Fetch products
$sql = "SELECT p.*, u.name AS seller_name 
        FROM products p 
        JOIN users u ON p.seller_id = u.id 
        $where_clause 
        ORDER BY p.created_at DESC";

$stmt = $conn->prepare($sql);
if ($category_filter) {
    $stmt->bind_param("s", $category_filter);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<div class="container mt-5">
    <h3 class="mb-4">Manage Products</h3>

    <!-- Filter Form -->
    <form method="GET" class="mb-4">
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <label for="category" class="form-label">Filter by Category:</label>
            </div>
            <div class="col-auto">
                <select name="category" id="category" class="form-select" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <?php
                    $categories = ["Cakes", "Crafts", "Clothing", "Jewelry", "Accessories", "Gifts", "Home Decor", "Art", "Toys", "Other"];
                    foreach ($categories as $cat) {
                        $selected = ($category_filter === $cat) ? 'selected' : '';
                        echo "<option value=\"$cat\" $selected>$cat</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </form>

    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#Product ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Seller</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $product['id']; ?></td>
                            <td><?= htmlspecialchars($product['name']); ?></td>
                            <td><?= htmlspecialchars($product['category']); ?></td>
                            <td>₹<?= number_format($product['price'], 2); ?></td>
                            <td><?= htmlspecialchars($product['seller_name']); ?></td>
                            <td><?= date('d M Y', strtotime($product['created_at'])); ?></td>
                            <td>
                                <a href="?delete=<?= $product['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">No products found.</div>
    <?php endif; ?>
</div>

<?php require_once('../includes/footer.php'); ?>
