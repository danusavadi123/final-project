<?php
// login.php - Handles user login

session_start();
include '../config/db.php'; // Database connection
require_once('../includes/spinner.html');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // Validate input fields
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email and Password are required.";
        header("Location: login.php");
        exit();
    }

    // Check if the user exists in the database
    $query = "SELECT id, name, password, role FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // If user found, verify the password
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $name, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            session_regenerate_id(true); // Secure session
            $_SESSION["user_id"] = $id;
            $_SESSION["name"] = $name;
            $_SESSION["role"] = $role;

            $stmt->close();
            $conn->close();

            // Redirect based on user role
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Local Marketplace</title>
  <link rel="stylesheet" href="../assets/css/styles.css"> <!-- Your custom styles -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> <!-- Bootstrap -->
  <style>
    /* Glassmorphism + Background image */
    @import url("https://fonts.googleapis.com/css2?family=Open+Sans:wght@200;300;400;500;600;700&display=swap");
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Open Sans", sans-serif; }
    body { display: flex; align-items: center; justify-content: center; min-height: 100vh; width: 100%; padding: 0 10px; position: relative; overflow: hidden; }
    body::before {
      content: "";
      position: absolute;
      width: 100%;
      height: 100%;
      background: url("../assets/images/login2img.jpg") no-repeat center center/cover;
      background-color: #000;
      z-index: -1;
    }
    .wrapper {
      width: 400px;
      border-radius: 8px;
      padding: 30px;
      text-align: center;
      border: 1px solid rgba(255, 255, 255, 0.5);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
    }
    form { display: flex; flex-direction: column; }
    h2 { font-size: 2rem; margin-bottom: 20px; color: #fff; }
    .input-field { position: relative; border-bottom: 2px solid #ccc; margin: 15px 0; }
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
    .input-field input {
      width: 100%;
      height: 40px;
      background: transparent;
      border: none;
      outline: none;
      font-size: 16px;
      color: #fff;
    }
    .input-field input:focus~label,
    .input-field input:valid~label {
      font-size: 0.8rem;
      top: 10px;
      transform: translateY(-120%);
    }
    .forget {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin: 25px 0 35px 0;
      color: #fff;
    }
    #remember { accent-color: #fff; }
    .forget label { display: flex; align-items: center; }
    .forget label p { margin-left: 8px; }
    .wrapper a { color: #efefef; text-decoration: none; }
    .wrapper a:hover { text-decoration: underline; }
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
    }
    button:hover {
      color: #fff;
      border-color: #fff;
      background: rgba(255, 255, 255, 0.15);
    }
    .register { text-align: center; margin-top: 30px; color: #fff; }
  </style>
</head>
<body>

<div class="wrapper">
  <?php if (isset($_SESSION['error'])) { ?>
      <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php } ?>

  <form action="login.php" method="POST">
    <h2>Login</h2>
    <div class="input-field">
      <input type="email" name="email" id="email" required>
      <label for="email">Enter your email</label>
    </div>
    <div class="input-field">
      <input type="password" name="password" id="password" required>
      <label for="password">Enter your password</label>
    </div>
    <div class="forget">
      <label for="remember">
        <input type="checkbox" id="remember">
        <p>Remember me</p>
      </label>
      <a href="#">Forgot password?</a>
    </div>
    <button type="submit">Log In</button>
    <div class="register">
      <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
  </form>
</div>

</body>
</html>
