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

$message = '';
$message_type = '';

$categories = ["Cakes", "Crafts", "Clothing", "Jewelry", "Accessories", "Gifts", "Home Decor", "Art", "Toys", "Other"];

// Handle product deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $product_id = intval($_GET['delete']);

    // Delete related orders
    $stmt = $conn->prepare("DELETE FROM orders WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->close();

    // Then delete product
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    if ($stmt->execute()) {
        $message = "Product deleted successfully!";
        $message_type = "success";
    } else {
        $message = "Failed to delete product.";
        $message_type = "error";
    }
    $stmt->close();
}

// Filter logic
$category_filter = $_GET['category'] ?? '';
$where_clause = '';
$params = [];

if ($category_filter && in_array($category_filter, $categories)) {
    $where_clause = "WHERE p.category = ?";
    $params[] = $category_filter;
}

// Fetch products
$sql = "SELECT p.*, u.name AS seller_name 
        FROM products p 
        JOIN users u ON p.seller_id = u.id 
        $where_clause 
        ORDER BY p.created_at DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param("s", ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Styles & Dependencies -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    body, html {
        height: 100%;
        margin: 0;
        background: linear-gradient(to right, #eff3ff, #c6dbef);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .navbar-custom {
        position : fixed;
        top : 0;
        width : 100%;
        z-index: 1000;
    }

    .canvas-container {
        min-height: 100vh;
        padding: 40px 20px;
        background: rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(10px);
        box-shadow: inset 0 0 30px rgba(0, 0, 0, 0.1);
        border-radius: 20px;
        margin: 30px auto;
        width: 95%;
    }

    h3 {
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        font-weight: 700;
        color: #08519c;
    }

    .table-3d {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
    }

    .table-3d tbody tr:hover {
        transform: scale(1.02);
        background-color: #f1f1f1;
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }

    .btn-danger:hover {
        transform: scale(1.1);
    }

    .form-select:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .filter-box {
        background: rgba(255, 255, 255, 0.8);
        padding: 15px 20px;
        border-radius: 10px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
        min-width: 1000px;
    }
</style>

<div class="canvas-container">
    <h3 class="mb-4 text-center"><i class="fas fa-box-open me-2"></i>Manage Products</h3>

    <!-- Alerts -->
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $message_type === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Filter Form -->
    <form method="GET" class="filter-box">
        <div class="row g-2 align-items-center justify-content-center">
            <div class="col-auto">
                <label for="category" class="form-label">Filter by Category:</label>
            </div>
            <div class="col-auto">
                <select name="category" id="category" class="form-select" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>" <?= ($category_filter === $cat) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>

    <?php if ($result && $result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle table-3d">
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
                            <td><?= $product['id'] ?></td>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($product['category']) ?></td>
                            <td>₹<?= number_format($product['price'], 2) ?></td>
                            <td><?= htmlspecialchars($product['seller_name']) ?></td>
                            <td><?= date('d M Y', strtotime($product['created_at'])) ?></td>
                            <td>
                                <a href="?delete=<?= $product['id'] ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this product? This will also remove related orders.')">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">No products found.</div>
    <?php endif; ?>
</div>

<?php require_once('../includes/footer.php'); ?>
