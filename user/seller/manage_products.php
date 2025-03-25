<?php
// Manage Products - Sellers can view, edit, and delete their products

include '../includes/session.php';
checkRole('seller'); // Only sellers can access this page
include '../config/database.php';

// Get the logged-in seller's products
$seller_id = $_SESSION['user_id'];
$query = "SELECT * FROM products WHERE seller_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $seller_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Your Products</h2>

    <?php if (isset($_SESSION['success'])) { ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php } ?>

    <?php if (isset($_SESSION['error'])) { ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php } ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Description</th>
                <th>Price (₹)</th>
                <th>Category</th>
                <th>Location</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= htmlspecialchars($row['description']); ?></td>
                    <td><?= $row['price']; ?></td>
                    <td><?= htmlspecialchars($row['category']); ?></td>
                    <td><?= htmlspecialchars($row['location']); ?></td>
                    <td><img src="../uploads/<?= $row['image']; ?>" width="50" height="50"></td>
                    <td>
                        <a href="edit_product.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_product.php?id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>

</body>
</html>