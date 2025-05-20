<?php
require_once('../includes/spinner.html');
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
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $messageContent = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($messageContent)) {
        $msg = "⚠️ Please fill in all the fields before submitting.";
    } else {
        $name = mysqli_real_escape_string($conn, $name);
        $email = mysqli_real_escape_string($conn, $email);
        $messageContent = mysqli_real_escape_string($conn, $messageContent);
        $role = 'buyer';
        $created_at = date('Y-m-d H:i:s');

        $sql = "INSERT INTO contact_messages (name, email, message, created_at, role) 
                VALUES ('$name', '$email', '$messageContent', '$created_at', '$role')";

        if (mysqli_query($conn, $sql)) {
            $msg = "✅ Your message has been sent successfully!";
        } else {
            $msg = "❌ Error: Could not send message. Please try again.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us - Buyer</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/navbar.css">

  <style>
    body {
      background: linear-gradient(135deg, #6baed6, #eff3ff);
      font-family: 'Poppins', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
      min-height: 100vh;
    }

    .contact-card {
      margin-top:4rem;
      background: #ffffff;
      padding: 35px;
      border-radius: 20px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
      max-width: 600px;
      width: 100%;
      animation: fadeInUp 0.5s ease;
    }

    h2 {
      font-weight: 700;
      color: #3182bd;
      text-align: center;
      margin-bottom: 30px;
    }

    label {
      font-weight: 500;
      margin-bottom: 5px;
    }

    .form-control {
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .btn-primary {
      background-color: #3182bd;
      border: none;
      border-radius: 50px;
      font-weight: 500;
      padding: 10px 0;
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background-color: #246e9c;
      transform: translateY(-1px);
    }

    .alert {
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
      text-align: center;
      font-weight: 500;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 576px) {
      .contact-card {
        padding: 25px;
      }

      h2 {
        font-size: 1.7rem;
      }
    }
  </style>
</head>
<body>

<div class="contact-card">
  <h2><i class="fa-solid fa-envelope-open-text me-2"></i>Contact Support</h2>

  <?php if ($msg): ?>
    <div class="alert alert-info"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <form method="POST" novalidate>
    <div class="mb-3">
      <label for="name">Your Name</label>
      <input type="text" id="name" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="email">Your Email</label>
      <input type="email" id="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
      <label for="message">Message</label>
      <textarea id="message" name="message" rows="5" class="form-control" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary w-100">Send Message</button>
  </form>
</div>

</body>
</html>
