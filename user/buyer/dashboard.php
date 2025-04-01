<?php
// Buyer Dashboard
include '../includes/session.php'; // Ensure user is logged in
checkRole('buyer'); // Restrict access to buyers only
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Dashboard</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Welcome, <?= $_SESSION['username']; ?>!</h2>
    <p>You are logged in as a Buyer.</p>

    <a href="browse_products.php" class="btn btn-primary">Browse Products</a>
    <a href="order_history.php" class="btn btn-secondary">Order History</a>
    <a href="../auth/logout.php" class="btn btn-danger">Logout</a>
</div>

</body>
</html>