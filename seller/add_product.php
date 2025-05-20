<?php
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/db.php');

// Restrict access to sellers only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../auth/login.php");
    exit();
}

// Handle form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $discount = intval($_POST['discount']);
    $category = trim($_POST['category']);
    $seller_id = $_SESSION['user_id'];

    // Handle image upload
    $image = $_FILES['image']['name'];
    $target_dir = "../uploads/";
    $target_file = $target_dir . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

    // Insert product
    $stmt = $conn->prepare("INSERT INTO products (seller_id, name, description, price, discount, image, category, created_at)
                            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("issdiss", $seller_id, $title, $description, $price, $discount, $image, $category);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success text-center'>🎉 Product added successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger text-center'>❌ Something went wrong. Please try again.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product - Seller</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap & Font Awesome -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../assets/css/navbar.css">

  <style>
    body {
      background: linear-gradient(135deg, #6baedc, #eff3ff);
      font-family: 'Poppins', sans-serif;
      padding: 40px 20px;
      min-height: 100vh;
    }

    .upload-box {
      max-width: 750px;
      margin: 4rem auto;
      background: #ffffff;
      padding: 40px 30px;
      border-radius: 20px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
      animation: slideFade 0.6s ease;
    }

    h3 {
      font-weight: 700;
      margin-bottom: 25px;
      text-align: center;
      color: #3182bd;
    }

    .form-label {
      font-weight: 500;
    }

    .form-control {
      border-radius: 10px;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-color: #3182bd;
      box-shadow: 0 0 10px rgba(13, 110, 253, 0.2);
    }

    .btn-primary {
       background: #3182bd;
      border: none;
      border-radius: 30px;
      font-weight: 500;
      padding: 10px;
      width: 100%;
    transition: all 0.3s ease-in-out;
    }

    .btn-primary:hover {
      background: #246e9c;
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    }

    .alert {
      border-radius: 12px;
      font-size: 0.95rem;
    }

    @keyframes slideFade {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<div class="upload-box">
  <h3><i class="fas fa-box-open me-2"></i>Add a New Product</h3>
  
  <?= $message ?>

  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="title" class="form-label">Product Title</label>
      <input type="text" class="form-control" id="title" name="title" required>
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">Product Description</label>
      <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
    </div>

    <div class="mb-3">
      <label for="category" class="form-label">Category</label>
      <select class="form-control" id="category" name="category" required>
        <option value="" disabled selected>Select a category</option>
        <option value="Cakes">Cakes</option>
        <option value="Crafts">Crafts</option>
        <option value="Clothing">Clothing</option>
        <option value="Jewelry">Jewelry</option>
        <option value="Accessories">Accessories</option>
        <option value="Gifts">Gifts</option>
        <option value="Home Decor">Home Decor</option>
        <option value="Art">Art</option>
        <option value="Toys">Toys</option>
        <option value="Other">Other</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="price" class="form-label">Price (₹)</label>
      <input type="number" step="0.01" min="1" class="form-control" id="price" name="price" required>
    </div>

    <div class="mb-3">
      <label for="discount" class="form-label">Discount (%)</label>
      <input type="number" step="0.01" min="0" max="99" class="form-control" id="discount" name="discount" required>
    </div>

    <div class="mb-3">
      <label for="image" class="form-label">Product Image</label>
      <input class="form-control" type="file" id="image" name="image" accept="image/*" required>
    </div>

    <button type="submit" class="btn btn-primary">Add Product</button>
  </form>
</div>

<?php require_once('../includes/footer.php'); ?>
</body>
</html>
