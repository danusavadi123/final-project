<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register - Local Marketplace</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
    }

    body {
      display: flex;
      height: 100vh;
      overflow: hidden;
    }

    .container {
      width: 100%;
      background: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px;
    }

    .form-box {
      width: 100%;
      max-width: 400px;
      padding: 40px;
      border-radius : 16px;
      box-shadow: 0 0 25px rgba(0,0,0,0.05);
    }

    .form-box h2 {
      font-size: 2rem;
      font-weight: 600;
      color: #000;
      margin-bottom: 8px;
    }

    .form-box p {
      font-size: 0.9rem;
      color: #555;
      margin-bottom: 20px;
    }

    .form-box input,
    .form-box select {
      width: 100%;
      padding: 12px;
      margin-bottom: 20px;
      border: none;
      border-radius: 6px;
      background:  #eff3ff;
      font-size: 0.95rem;
    }

    .form-box button {
      width: 100%;
      background:#9ecae1;
      border: none;
      padding: 12px;
      border-radius: 25px;
      color: #fff;
      font-weight: bold;
      font-size: 0.95rem;
      cursor: pointer;
      transition: 0.3s ease;
    }


   
    .form-box button:hover {
      background-color: #6baed6;
    }
    .form-box .login-link {
      margin-top: 20px;
      font-size: 0.85rem;
      text-align: center;
    }

    .form-box .login-link a {
      color: #6baed6;
      font-weight: 600;
      text-decoration: none;
    }
    .logo {
      display: block;
      margin: 0 auto 0 auto;
      width: 120px;
      height: auto;
      padding-top:20px;
    }

  
    @media (max-width: 768px) {
      .container {
        flex-direction: column;
      }

      .left-panel, .right-panel {
        width: 100%;
        height: 50%;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="left-panel">
      <form class="form-box" action="register.php" method="POST">
        <img src="../assets/images/logo.png" alt="Logo" class="logo">
        <h2>Register Here</h2>

        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="test@mydomain.com" required>
        <input type="password" name="password" placeholder="Password" required>

        <select name="role" required>
          <option value="" disabled selected hidden>Select Role</option>
          <option value="buyer">Buyer</option>
          <option value="seller">Seller</option>
        </select>

        <button type="submit">REGISTER →</button>

        <div class="login-link">
          <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
      </form>
    </div>

  </div>
</body>
</html>
