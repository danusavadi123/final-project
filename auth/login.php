<?php 
// login.php - Handles user login
session_start();
include '../config/db.php';
require_once('../includes/spinner.html');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and Password are required.";
        header("Location: login.php");
        exit();
    }

    $query = "SELECT id, name, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            session_regenerate_id(true);
            $_SESSION["user_id"] = $id;
            $_SESSION["name"] = $name;
            $_SESSION["role"] = $role;

            $stmt->close();
            $conn->close();

            if ($role == "buyer") {
                header("Location: ../buyer/dashboard.php");
            } elseif ($role == "seller") {
                header("Location: ../seller/dashboard.php");
            } elseif ($role == "admin") {
                header("Location: ../admin/dashboard.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "Invalid email or password.";
        }
    } else {
        $_SESSION['error'] = "No account found with this email.";
    }
    $stmt->close();
    $conn->close();
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Local Marketplace</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }

    body {
      display: flex;
      min-height: 100vh;
      display: flex;
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

    .form-box input {
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
      margin-bottom: 20px;
    }


    @media (max-width: 768px) {
      body {
        flex-direction: column;
      }

      .left-section, .right-section {
        width: 100%;
        height: auto;
      }

      .form-box {
        box-shadow: none;
      }
    }
  </style>
</head>
<body>

    <div class="form-box">
      <img src="../assets/images/logo.png" alt="Logo" class="logo">
      <p>Welcome back 👋</p>
      <h1>Sing in</h1>
      <?php if (isset($_SESSION['error'])): ?>
        <div class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
      <?php endif; ?>
      <form method="POST" action="login.php">
        <input type="email" name="email" placeholder="test@mydomain.com" required>
        <input type="password" name="password" placeholder="Enter password" required>
        <button type="submit">SIGN IN →</button>
      </form>
      <div class="footer-text">
        <p>Don't have an account? <a href="register.php">Sign up</a></p>
      </div>
    </div>
  </div>

</body>
</html>
