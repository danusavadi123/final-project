<?php
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/db.php');

// Ensure only buyer has access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    header("Location: ../auth/login.php");
    exit();
}

$buyer_id = $_SESSION['user_id'];?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Hero Section</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/buyer_dashboard.css">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:200,400,800,900&display=swap');

$poppins: 'Poppins', sans-serif;
$body-color: blue;

body {
  margin: 0;
  padding: 0;
  background: #333;
  font-family: $poppins;
  display: flex;
  justify-content: center;
}

.hero {
  background: #133A53 url('../assets/images/hero.jpg') no-repeat center center;
  background-size: cover;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: left;
  height: 100vh;
  width: 100vw;

  .overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;

    .content {
      display: flex;
      flex-direction: column;
      justify-content: center;
      height: 100vh;
      width: 70vw;
      margin: auto;
      transform-origin: left;
      animation: reveal 1s ease-in-out forwards;
      position: relative;
      z-index: 4;

      h1 {
        font-size: 90px;
        line-height: 0.9;
        margin-bottom: 0;
        color: white;
      }

      p {
  font-size: 28px;
  color: #E53935;
  font-weight: bold;
  background-color:white;
  Width:max-content;
}

    }

    &::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: #ff6700;
      z-index: 3;
      animation: reveal 0.5s reverse forwards;
      animation-delay: 0.5s;
      transform-origin: left;
    }

    &::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: #133A53;
      z-index: 2;
      animation: reveal 0.7s reverse forwards;
      animation-delay: 0.7s;
      transform-origin: left;
    }
  }
}

@keyframes reveal {
  0% {
    transform: scaleX(0);
  }

  100% {
    transform: scaleX(1);
  }
}

    </style>
</head>
<body>
    <?php
    // Add PHP logic here if needed
    ?>

<section class="hero">
  <div class="overlay">
      <div class="content">
        <h1>Welcome<br>Back</h1>
        <p>Find Joy in Every Purchase!!</p>
      </div>
  </div>
</section>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>



<?php require_once('../includes/footer.php'); ?>