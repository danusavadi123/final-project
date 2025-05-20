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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Fonts & Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  
  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/navbar.css">

  <style>
    body {
      background: linear-gradient(135deg, #6baedc, #eff3ff);
      font-family: 'Poppins', sans-serif;
      padding: 50px 20px;
      min-height: 100vh;
    }

    .about-box {
      max-width: 800px;
      margin: 4rem auto;
      background: #ffffff;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
      animation: fadeIn 0.6s ease;
    }

    h2 {
      font-weight: 700;
      color: #333;
      text-align: center;
      margin-bottom: 30px;
    }

    p {
      color: #555;
      font-size: 1.05rem;
      line-height: 1.7;
    }

    .list {
      list-style: none;
      margin-top: 20px;
      padding-left: 20px;
    }

    .list li {
      margin-bottom: 12px;
      color: #444;
      font-size: 1.05rem;
    }

    .list li::before {
      content: "\f00c";
      font-family: "Font Awesome 6 Free";
      font-weight: 900;
      margin-right: 10px;
      color: #3182bd;
    }

    .btn-contact {
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

    .btn-contact:hover {
       background: #246e9c;
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
  </style>
</head>
<body>

  <div class="about-box">
    <h2><i class="fa-solid fa-user-tie me-2"></i>About Us - Seller Portal</h2>

    <p>Welcome to your Seller Dashboard! </p>

    <p>This platform is tailored to empower sellers by providing tools to manage your products, connect with buyers, and monitor your business performance — all in one place.</p>

    <ul class="list">
      <li>List, edit, and remove products effortlessly</li>
      <li>Get real-time notifications on new orders</li>
      <li>View and manage buyer orders and delivery status</li>
      <li>Track your monthly sales performance and analytics</li>
    </ul>

    <p>We’re committed to helping you succeed as a seller. If you have any questions, feedback, or need support — we're just a click away.</p>

    <div class="text-center">
      <a href="../seller/contact.php" class="btn-contact">
        <i class="fas fa-headset me-2"></i>Contact Support
      </a>
    </div>

    <footer class="mt-4">Thank you for being a valuable part of our seller community. Let's grow together! </footer>
  </div>

</body>
</html>
