<?php
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/database.php');

// Admin access only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch all products with seller name
$sql = "SELECT p.id, p.name AS product_name, p.price, p.created_at, p.status, u.name AS seller_name 
        FROM products p
        JOIN users u ON p.seller_id = u.id
        ORDER BY p.created_at DESC";
$products = $conn->query($sql);
?>

<div class="container mt-5">
    <h2>All Product Listings</h2>
    <hr>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Product</th>
                <th>Seller</th>
                <th>Price</th>
                <th>Status</th>
                <th>Listed On</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $products->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['seller_name']); ?></td>
                    <td>₹<?php echo number_format($row['price'], 2); ?></td>
                    <td>
                        <span class="badge bg-<?php echo $row['status'] === 'active' ? 'success' : 'secondary'; ?>">
                            <?php echo ucfirst($row['status']); ?>
                        </span>
                    </td>
                    <td><?php echo date("d M Y", strtotime($row['created_at'])); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php require_once('../includes/footer.php'); ?>