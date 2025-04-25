<?php
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Us - Seller</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
  <div class="container my-5">
    <h1 class="mb-4">Hello, Partner in Sales! 👋</h1>

    <p>
      We’re excited to help you grow your business! This platform is designed to give sellers like you access to a wider customer base, streamline order management, and increase revenue with minimal hassle.
    </p>

    <h4>With your seller dashboard, you can:</h4>
    <ul>
      <li>List and manage your products easily</li>
      <li>Get notified on new orders in real-time</li>
      <li>View buyer information and track order status</li>
      <li>Monitor your sales performance</li>
    </ul>

    <p>We're here to help you succeed. Reach out to our support team anytime for guidance or feedback. 🤝</p>

    <hr>
    <footer class="text-muted small">Thank you for being a part of our seller community! 🚀</footer>
  </div>
</body>
</html>
