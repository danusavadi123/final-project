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
        $message = "<div class='alert alert-success'>Product deleted successfully.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Unable to delete product.</div>";
    }
}

// Fetch all products for this seller
$stmt = $conn->prepare("SELECT * FROM products WHERE seller_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
body {
    background: url('../assets/images/gradiantimg.jpg') no-repeat center center fixed;
    background-size: cover;
    margin: 0;
    padding: 0;
}

h3 {
    color: #fff;
    font-weight: 700;
    margin-bottom: 20px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.container-custom {
    max-width: 900px;
    margin: auto;
    padding-top: 20px;
    padding-bottom: 20px;
}

.product-card {
    background: rgba(255, 255, 255, 0.9); /* make card slightly transparent */
    border-radius: 20px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.1), 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
    border: none;
    padding-bottom: 10px;
}

.product-card:hover {
    transform: scale(1.05) rotateY(5deg);
    box-shadow: 0 20px 30px rgba(0,0,0,0.2), 0 8px 12px rgba(0,0,0,0.15);
}

.product-image {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
}

.card-body {
    padding: 10px;
}

.card-body h5 {
    font-weight: 600;
    color: #007bff;
    font-size: 1rem;
    margin-bottom: 8px;
}

.card-body p {
    margin-bottom: 6px;
    font-size: 0.9rem;
}

.btn-action {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    padding: 4px 8px;
    font-size: 0.8rem;
}

.btn-action:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.2);
}

.card-footer {
    background: #f8f9fa;
    border-top: none;
    padding: 8px 10px;
}
</style>

<div class="container container-custom mt-4">
    <h3 class="text-center">Manage Your Products</h3>
    <?php echo $message; ?>

    <div class="row mt-3">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card product-card">
                        <img src="../uploads/<?php echo htmlspecialchars($row['image']); ?>" class="product-image">
                        <div class="card-body text-center">
                            <h5><?php echo htmlspecialchars($row['name']); ?></h5>
                            <p><strong>Price:</strong> ₹<?php echo number_format($row['price'], 2); ?></p>
                            <p><strong>Discount:</strong> <?php echo $row['discount']; ?>%</p>
                            <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
                            <p><small><i class="fas fa-calendar"></i> <?php echo date('d M Y', strtotime($row['created_at'])); ?></small></p>
                        </div>
                        <div class="card-footer d-flex justify-content-between">
                            <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning btn-action"><i class="fas fa-edit"></i> Edit</a>
                            <a href="manage_products.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger btn-action"
                               onclick="return confirm('Are you sure you want to delete this product?');"><i class="fas fa-trash"></i> Delete</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <p class="text-white">No products found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>
