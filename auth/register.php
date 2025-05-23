<?php
session_start();
include '../config/db.php';
require_once('../includes/spinner.html');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $role = $_POST["role"];

    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: register.php");
        exit();
    }

    // Check if user already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        $_SESSION['error'] = "Email is already registered.";
        $check->close();
        header("Location: register.php");
        exit();
    }
    $check->close();

    // Hash the password and insert user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful. You can now login.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Registration failed. Please try again.";
        header("Location: register.php");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - Local Marketplace</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }

    body {
      display: flex;
      min-height: 100vh;
      align-items: center;
      justify-content: center;
      background-color: #fff;
      padding: 2rem;
    }

    .form-box {
      background-color: #fff;
      padding: 40px;
      max-width: 400px;
      width: 100%;
      border-radius: 16px;
      box-shadow: 0 0 25px rgba(0,0,0,0.05);
    }

    .form-box h1 {
      font-size: 2rem;
      font-weight: 600;
      margin-bottom: 20px;
      color: #000;
    }

    .form-box p {
      margin-bottom: 10px;
      color: #666;
      font-size: 14px;
    }

    .form-box input, .form-box select {
      width: 100%;
      padding: 12px 16px;
      margin-bottom: 20px;
      border: 1px solid #c6dbef;
      background-color: #eff3ff;
      border-radius: 8px;
      font-size: 14px;
    }

    .form-box button {
      width: 100%;
      padding: 12px;
      background-color: #9ecae1;
      border: none;
      border-radius: 8px;
      color: #fff;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .form-box button:hover {
      background-color: #6baed6;
    }

    .form-box .footer-text {
      text-align: center;
      margin-top: 15px;
      font-size: 13px;
    }

    .form-box .footer-text a {
      color: #6baed6;
      font-weight: 500;
      text-decoration: none;
    }

    .error {
      color: red;
      font-size: 0.85rem;
      margin-bottom: 10px;
      text-align: center;
    }

    .logo {
      display: block;
      margin: 0 auto 20px auto;
      width: 120px;
      height: auto;
    }

    @media (max-width: 768px) {
      body {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

<div class="form-box">
  <img src="../assets/images/logo.png" alt="Logo" class="logo">
  <p>Join our marketplace</p>
  <h1>Sign up</h1>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <form method="POST" action="register.php">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="test@mydomain.com" required>
    <input type="password" name="password" placeholder="Enter password" required>

    <select name="role" required>
      <option value="" disabled selected hidden>Select Role</option>
      <option value="buyer">Buyer</option>
      <option value="seller">Seller</option>
    </select>

    <button type="submit">REGISTER →</button>
  </form>

  <div class="footer-text">
    <p>Already have an account? <a href="login.php">Sign in</a></p>
  </div>
</div>

</body>
</html>
