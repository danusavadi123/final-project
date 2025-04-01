<?php
// Browse Products - Buyers can search and view available products

include '../includes/session.php';
checkRole('buyer'); // Only buyers can access this page
include '../config/database.php';

// Initialize search filters
$category = isset($_GET['category']) ? trim($_GET['category']) : "";
$location = isset($_GET['location']) ? trim($_GET['location']) : "";
$min_price = isset($_GET['min_price']) ? $_GET['min_price'] : "";
$max_price = isset($_GET['max_price']) ? $_GET['max_price'] : "";

// Build search query
$query = "SELECT * FROM products WHERE 1";
$params = [];
$types = "";

if (!empty($category)) {
    $query .= " AND category LIKE ?";
    $params[] = "%$category%";
    $types .= "s";
}
if (!empty($location)) {
    $query .= " AND location LIKE ?";
    $params[] = "%$location%";
    $types .= "s";
}
if (!empty($min_price)) {
    $query .= " AND price >= ?";
    $params[] = $min_price;
    $types .= "d";
}
if (!empty($max_price)) {
    $query .= " AND price <= ?";
    $params[] = $max_price;
    $types .= "d";
}

// Prepare statement
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Products</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Browse Products</h2>

    <!-- Search Form -->
    <form action="browse_products.php" method="GET" class="row g-3">
        <div class="col-md-3">
            <input type="text" name="category" class="form-control" placeholder="Category" value="<?= htmlspecialchars($category); ?>">
        </div>
        <div class="col-md-3">
            <input type="text" name="location" class="form-control" placeholder="Location" value="<?= htmlspecialchars($location); ?>">
        </div>
        <div class="col-md-2">
            <input type="number" name="min_price" class="form-control" placeholder="Min Price" value="<?= htmlspecialchars($min_price); ?>">
        </div>
        <div class="col-md-2">
            <input type="number" name="max_price" class="form-control" placeholder="Max Price" value="<?= htmlspecialchars($max_price); ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <hr>

    <!-- Display Products -->
    <div class="row">
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="../uploads/<?= $row['image']; ?>" class="card-img-top" alt="<?= htmlspecialchars($row['name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['name']); ?></h5>
                        <p class="card-text"><?= htmlspecialchars($row['description']); ?></p>
                        <p><strong>₹<?= $row['price']; ?></strong></p>
                        <p>Category: <?= htmlspecialchars($row['category']); ?></p>
                        <p>Location: <?= htmlspecialchars($row['location']); ?></p>
                        <a href="view_product.php?id=<?= $row['id']; ?>" class="btn btn-success">View Details</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>

</body>
</html>