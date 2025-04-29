<?php
// register.php - Handles user registration

include '../config/db.php'; // Include database connection
require_once('../includes/spinner.html');

// Start session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $role = $_POST["role"]; // Role: buyer, seller

    // Validate inputs
    if (empty($name) || empty($email) || empty($password) || empty($role)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: register.php");
        exit();
    }

    // Check if email already exists
    $check_query = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Email already registered.";
        header("Location: register.php");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $insert_query = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful! Please log in.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Registration failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Local Marketplace</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Open+Sans:wght@200;300;400;500;600;700&display=swap");
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Open Sans", sans-serif; }
    body {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      width: 100%;
      padding: 0 10px;
      position: relative;
      overflow: hidden;
    }
    body::before {
      content: "";
      position: absolute;
      width: 100%;
      height: 100%;
      background: url("../assets/images/istockphoto-1247569904-612x612.jpg") no-repeat center center/cover;
      background-color: #000;
      z-index: -1;
    }
    .wrapper {
      width: 400px;
      border-radius: 8px;
      padding: 30px;
      text-align: center;
      border: 1px solid rgba(255, 255, 255, 0.5);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
    }
    form {
      display: flex;
      flex-direction: column;
    }
    h2 {
      font-size: 2rem;
      margin-bottom: 20px;
      color: #fff;
    }
    .input-field {
      position: relative;
      border-bottom: 2px solid #ccc;
      margin: 15px 0;
    }
    .input-field label {
      position: absolute;
      top: 50%;
      left: 0;
      transform: translateY(-50%);
      color: #fff;
      font-size: 16px;
      pointer-events: none;
      transition: 0.15s ease;
    }
    .input-field input, .input-field select {
      width: 100%;
      height: 40px;
      background: transparent;
      border: none;
      outline: none;
      font-size: 16px;
      color: #fff;
    }
    .input-field input:focus~label, .input-field input:valid~label,
    .input-field select:focus~label, .input-field select:valid~label {
      font-size: 0.8rem;
      top: 10px;
      transform: translateY(-120%);
    }
    .wrapper a {
      color: #efefef;
      text-decoration: none;
    }
    .wrapper a:hover {
      text-decoration: underline;
    }
    button {
      background: #fff;
      color: #000;
      font-weight: 600;
      border: none;
      padding: 12px 20px;
      cursor: pointer;
      border-radius: 3px;
      font-size: 16px;
      border: 2px solid transparent;
      transition: 0.3s ease;
      margin-top: 20px;
    }
    button:hover {
      color: #fff;
      border-color: #fff;
      background: rgba(255, 255, 255, 0.15);
    }
    .register {
      text-align: center;
      margin-top: 30px;
      color: #fff;
    }
    select option {
  color: #000; /* Change this to whatever color you want */
  background-color: #fff; /* Optional: background color inside dropdown */
}
  </style>
</head>
<body>

<div class="wrapper">
  <?php if (isset($_SESSION['error'])) { ?>
    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php } ?>
  <?php if (isset($_SESSION['success'])) { ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
  <?php } ?>

  <form action="register.php" method="POST">
    <h2>Register</h2>

    <div class="input-field">
      <input type="text" name="name" id="name" required>
      <label for="name">Enter your full name</label>
    </div>

    <div class="input-field">
      <input type="email" name="email" id="email" required>
      <label for="email">Enter your email</label>
    </div>

    <div class="input-field">
      <input type="password" name="password" id="password" required>
      <label for="password">Enter your password</label>
    </div>

    <div class="input-field">
      <select name="role" id="role" required>
        <option value="" disabled selected hidden>Select your role</option>
        <option value="buyer">Buyer</option>
        <option value="seller">Seller</option>
      </select>
      <label for="role">Register as</label>
    </div>

    <button type="submit">Register</button>

    <div class="register">
      <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
  </form>
</div>

</body>
</html>
