<?php
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/db.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'buyer') {
    header('Location: ../auth/login.php');
    exit;
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $role = 'buyer';
    $created_at = date('Y-m-d H:i:s');

    $sql = "INSERT INTO contact_messages (name, email, message, created_at, role) 
            VALUES ('$name', '$email', '$message', '$created_at', '$role')";

    if (mysqli_query($conn, $sql)) {
        $msg = "Your message has been sent successfully!";
    } else {
        $msg = "Error: Could not send message.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us - Buyer</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container my-5">
  <h2>Contact Support</h2>
  <?php if ($msg): ?>
    <div class="alert alert-info"><?= $msg ?></div>
  <?php endif; ?>
  <form method="POST">
    <div class="mb-3">
      <label class="form-label">Your Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Your Email</label>
      <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Message</label>
      <textarea name="message" rows="5" class="form-control" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Send Message</button>
  </form>
</div>
</body>
</html>
