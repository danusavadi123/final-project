<?php
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/database.php');

// Ensure only buyer can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    header("Location: ../auth/login.php");
    exit();
}

// Get categories for dropdown filter
$categoryQuery = "SELECT DISTINCT category FROM products WHERE is_active = 1";
$categoryResult = $conn->query($categoryQuery);

// Search and filter logic
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$sql = "SELECT p.*, u.name AS seller_name 
        FROM products p 
        JOIN users u ON p.seller_id = u.id 
        WHERE p.is_active = 1";

if (!empty($search)) {
    $sql .= " AND (p.name LIKE '%$search%' OR p.description LIKE '%$search%')";
}

if (!empty($category)) {
    $sql .= " AND p.category = '$category'";
}

$sql .= " ORDER BY p.created_at DESC";

$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h3>Browse Products</h3>
    <form method="GET" class="row g-3 my-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
        </div>
        <div class="col-md-3">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <?php while ($row = $categoryResult->fetch_assoc()): ?>
                    <option value="<?php echo $row['category']; ?>" <?php if ($category == $row['category']) echo 'selected'; ?>>
                        <?php echo ucfirst($row['category']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <div class="row">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($product = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if ($product['image']): ?>
                            <img src="../uploads/<?php echo $product['image']; ?>" class="card-img-top" alt="Product Image">
                        <?php else: ?>
                            <img src="../assets/no-image.png" class="card-img-top" alt="No Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text"><?php echo substr(htmlspecialchars($product['description']), 0, 100); ?>...</p>
                            <p><strong>₹<?php echo number_format($product['price'], 2); ?></strong></p>
                            <p class="text-muted small">By: <?php echo htmlspecialchars($product['seller_name']); ?></p>
                            <a href="product_details.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-primary w-100">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p>No products found based on your search.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>