<?php
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'buyer') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Us - Buyer</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container my-5">
    <h1 class="mb-4">Welcome, Smart Shopper! 🛍️</h1>
    
    <p>
      We’re thrilled to have you shopping with us! This platform connects you directly with a variety of trusted sellers and local businesses, all in one place. Our goal? Make your shopping experience smooth, fun, and rewarding.
    </p>

    <h4>What You Can Do:</h4>
    <ul>
      <li>Browse a wide range of products</li>
      <li>Compare prices and read descriptions</li>
      <li>Add to cart and place secure orders</li>
      <li>Track your orders in real-time</li>
    </ul>

    <p>Need help or support? Our customer service is always ready to assist you. 💬</p>
    
    <hr>
    <footer class="text-muted small">Thank you for choosing us as your shopping destination! 🚀</footer>
  </div>
</body>
</html>
