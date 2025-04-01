<?php
// Delete Product - Sellers can remove their products

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

// Check if the product belongs to the seller
$query = "SELECT image FROM products WHERE id = ? AND seller_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $product_id, $seller_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    $_SESSION['error'] = "Unauthorized action.";
    header("Location: manage_products.php");
    exit();
}

// Delete product image from the uploads folder
if (!empty($product['image'])) {
    $image_path = "../uploads/" . $product['image'];
    if (file_exists($image_path)) {
        unlink($image_path);
    }
}

// Delete product from the database
$delete_query = "DELETE FROM products WHERE id = ? AND seller_id = ?";
$stmt = $conn->prepare($delete_query);
$stmt->bind_param("ii", $product_id, $seller_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Product deleted successfully!";
} else {
    $_SESSION['error'] = "Error deleting product. Try again.";
}

header("Location: manage_products.php");
exit();
?>