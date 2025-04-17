<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>LocalConnect - Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap & Font Awesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheer" href="../assets/css/index.css">
</head>
<body>

  <!-- 🔒 Fixed Header -->
  <div class="fixed-header">
    <div class="brand">LocalConnect</div>
    <div class="auth-buttons">
      <a href="auth/login.php" class="btn btn-light btn-sm"><i class="fas fa-sign-in-alt"></i> Login</a>
      <a href="auth/register.php" class="btn btn-primary btn-sm"><i class="fas fa-user-plus"></i> Register</a>
    </div>
  </div>

  <!-- 🏠 Hero Section -->
  <div class="hero">
    <div class="hero-content">
      <h1>Connecting Buyers & Sellers Locally</h1>
      <p>Welcome to LocalConnect — an e-commerce platform designed to empower local businesses and help buyers discover products nearby.</p>
    </div>
  </div>

  <!-- 🛠 Services Section -->
  <div class="services">
    <h2 class="mb-5">Our Services</h2>
    <div class="row justify-content-center">
      <div class="col-md-3 mx-3 mb-4 service-card">
        <i class="fas fa-store fa-3x text-primary mb-3"></i>
        <h5>For Sellers</h5>
        <p>List your products, reach local buyers, and grow your business with easy-to-use seller tools.</p>
      </div>
      <div class="col-md-3 mx-3 mb-4 service-card">
        <i class="fas fa-shopping-basket fa-3x text-success mb-3"></i>
        <h5>For Buyers</h5>
        <p>Browse products from trusted local sellers and get them delivered quickly to your doorstep.</p>
      </div>
      <div class="col-md-3 mx-3 mb-4 service-card">
        <i class="fas fa-map-marked-alt fa-3x text-warning mb-3"></i>
        <h5>Local Discovery</h5>
        <p>Support your neighborhood. Discover shops and businesses around you through our smart search.</p>
      </div>
    </div>
  </div>

  

</body>
</html>
<?php require_once('./includes/footer.php'); ?>