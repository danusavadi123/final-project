<?php
require_once('../includes/session.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Failed</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container text-center mt-5">
        <div class="card shadow p-4">
            <h2 class="text-danger">Payment Failed</h2>
            <p>Unfortunately, your transaction could not be completed.</p>
            <p>Please try again or contact support if the issue persists.</p>
            <div class="mt-4">
                <a href="../buyer/dashboard.php" class="btn btn-primary">Go to Dashboard</a>
                <a href="../orders/place_order.php" class="btn btn-warning">Retry Payment</a>
            </div>
        </div>
    </div>
</body>
</html>