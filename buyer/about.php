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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/navbar.css">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #6baedc, #eff3ff);
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .about-container {
      margin-top: 4rem;
      background: #fff;
      padding: 40px 30px;
      max-width: 800px;
      width: 100%;
      border-radius: 20px;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
      animation: fadeInUp 0.6s ease-out;
    }

    h1 {
      font-weight: 700;
      font-size: 2.5rem;
      color: #3182bd;
      text-align: center;
      margin-bottom: 20px;
    }

    h4 {
      font-weight: 600;
      color: #3182bd;
      margin-top: 30px;
    }

    p {
      color: #444;
      font-size: 1.05rem;
      line-height: 1.6;
    }

    .list {
      list-style: none;
      padding-left: 0;
      margin-top: 20px;
    }

    .list li {
      font-size: 1.05rem;
      margin-bottom: 12px;
      position: relative;
      padding-left: 28px;
      color: #555;
    }

    .list li::before {
      content: "\f058";
      font-family: "Font Awesome 6 Free";
      font-weight: 900;
      position: absolute;
      left: 0;
      top: 2px;
      color: #3182bd;
    }

    .btn-support {
      margin-top: 30px;
      background: #3182bd;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 30px;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.3s ease-in-out;
      display: inline-block;
    }

    .btn-support:hover {
      background: #246e9c;
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }

  
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @media (max-width: 576px) {
      .about-container {
        padding: 30px 20px;
      }

      h1 {
        font-size: 2rem;
      }

      .btn-support {
        width: 100%;
        text-align: center;
      }
    }
  </style>
</head>
<body>

<div class="about-container">
  <h1>Welcome, Smart Shopper!</h1>

  <p>
    We’re thrilled to have you shopping with us! This platform connects you directly with trusted sellers and local businesses — all in one convenient place. Our mission is to make your shopping experience smooth, enjoyable, and rewarding.
  </p>

  <h4>What You Can Do:</h4>
  <ul class="list">
    <li>Browse a wide variety of top-quality products</li>
    <li>Compare prices and check detailed descriptions</li>
    <li>Add items to your cart and securely place orders</li>
    <li>Track your orders in real-time from your dashboard</li>
  </ul>

  <p>If you ever need help, our dedicated support team is always ready to assist you!</p>

  <div class="text-center">
    <a href="../buyer/contact.php" class="btn-support">
      <i class="fas fa-headset me-2"></i>Contact Support
    </a>
  </div>

  <footer class="mt-4">Thank you for choosing us as your go-to shopping destination! 🛍️</footer>
</div>

</body>
</html>
