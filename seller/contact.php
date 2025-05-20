<?php
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/db.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'seller') {
    header('Location: ../auth/login.php');
    exit;
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $role = 'seller';
    $created_at = date('Y-m-d H:i:s');

    $sql = "INSERT INTO contact_messages (name, email, message, created_at, role) 
            VALUES ('$name', '$email', '$message', '$created_at', '$role')";

    if (mysqli_query($conn, $sql)) {
        $msg = "✅ Your message has been sent successfully!";
    } else {
        $msg = "❌ Error: Could not send message.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Support - Seller</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap & Font Awesome -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/navbar.css">
  <style>
    body {
      background: linear-gradient(135deg, #6baed6, #eff3ff);
      font-family: 'Poppins', sans-serif;
      padding: 50px 20px;
      min-height: 100vh;
    }

    .contact-box {
      max-width: 700px;
      margin: 4rem auto;
      background: #fff;
      padding: 40px 30px;
      border-radius: 20px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
      animation: fadeIn 0.6s ease;
    }

    h2 {
      font-weight: 700;
      text-align: center;
      margin-bottom: 25px;
      color: #333;
    }

    label {
      font-weight: 500;
      color: #555;
    }

    .form-control {
      border-radius: 10px;
      border: 1px solid #ccc;
      transition: all 0.3s ease;
      box-shadow: none;
    }

    .form-control:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 10px rgba(13, 110, 253, 0.2);
    }

    .btn-send {
      width: 100%;
      background: #3182bd;
      border: none;
      color: #fff;
      padding: 12px;
      border-radius: 50px;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .btn-send:hover {
      transform: translateY(-2px);
      background-color: #246e9c;
    }

    .alert {
      border-radius: 12px;
      font-size: 0.95rem;
      text-align: center;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

  <div class="contact-box">
    <h2><i class="fas fa-headset me-2"></i>Contact Seller Support</h2>

    <?php if ($msg): ?>
      <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-3">
        <label for="name" class="form-label">Your Name</label>
        <input type="text" id="name" name="name" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Your Email</label>
        <input type="email" id="email" name="email" class="form-control" required>
      </div>

      <div class="mb-3">
        <label for="message" class="form-label">Your Message</label>
        <textarea id="message" name="message" rows="5" class="form-control" required></textarea>
      </div>

      <button type="submit" class="btn btn-send">Send Message</button>
    </form>
  </div>

  <?php require_once('../includes/footer.php'); ?>
</body>
</html>
