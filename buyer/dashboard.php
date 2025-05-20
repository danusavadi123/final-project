<?php
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
    header("Location: ../auth/login.php");
    exit();
}

$buyer_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0; 
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            background-color: #f4f6f9;
        }

        .hero {
           height: 100vh;
    background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.6)),
                url('../assets/images/hero.jpg') center/cover no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
    padding: 0 20px;
        }

        .hero h1 {
            font-size: 4rem;
    font-weight: 800;
    animation: slideFromTop 1s ease forwards;
    opacity: 0;
        }

        .hero p {
            font-size: 1.5rem;
    background: white;
    padding: 10px 20px;
    color: #E53935;
    display: inline-block;
    font-weight: bold;
    margin-top: 20px;
    animation: fadeIn 2s ease forwards;
    opacity: 0;
    animation-delay: 0.5s;
        }

       @keyframes slideFromTop {
    0% {
        transform: translateY(-100%);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes fadeIn {
    0% { opacity: 0; }
    100% { opacity: 1; }
}

        #feature {
            padding: 50px 5%;
            background: white;
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: space-between;
        }

        .fe-box {
            flex: 1 1 150px;
            max-width: 180px;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease-in-out;
        }

        .fe-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
        }

        .fe-box img {
            width: 80%;
            margin-bottom: 10px;
        }

        .fe-box h6 {
            font-size: 14px;
            font-weight: 600;
            background-color:  #d1e8f2;;
            color: #088178;
            border-radius: 4px;
            padding: 8px;
        }

        .fe-box:nth-child(2) h6 { background: #f6dbf6; }
        .fe-box:nth-child(3) h6 { background: #cdebbc;}
        .fe-box:nth-child(4) h6 { background:#a5d2ff; }
        .fe-box:nth-child(5) h6 { background:#cca4ec; }
        .fe-box:nth-child(6) h6 { background: #fff2e5; }

        .section-title {
            text-align: center;
            font-size: 2rem;
            font-weight: 700;
            margin: 50px 0 20px;
            color: #333;
        }

        .card {
            border: none;
            transition: all 0.3s ease-in-out;
            border-radius: 10px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .card-img-top {
            height: 200px;
            object-fit: contain;
        }

        .price {
            font-size: 1.1rem;
        }

        .old-price {
            text-decoration: line-through;
            color: #999;
        }

        .new-price {
            color: #e63946;
            font-weight: bold;
        }
    </style>
</head>
<body>

<section class="hero">
    <div>
        <h1>Welcome<br>Back</h1>
        <p>Find Joy in Every Purchase!!</p>
    </div>
</section>

<section id="feature">
    <div class="fe-box"><img src="https://i.postimg.cc/PrN2Y6Cv/f1.png"><h6>Free Shipping</h6></div>
    <div class="fe-box"><img src="https://i.postimg.cc/qvycxW4q/f2.png"><h6>Online Order</h6></div>
    <div class="fe-box"><img src="https://i.postimg.cc/1Rdphyz4/f3.png"><h6>Save Money</h6></div>
    <div class="fe-box"><img src="https://i.postimg.cc/GpYc2JFZ/f4.png"><h6>Promotions</h6></div>
    <div class="fe-box"><img src="https://i.postimg.cc/4yFCwmv6/f5.png"><h6>Happy Sell</h6></div>
    <div class="fe-box"><img src="https://i.postimg.cc/gJN1knTC/f6.png"><h6>24/7 Support</h6></div>
</section>

<?php
$product_query = "SELECT * FROM products ORDER BY id DESC LIMIT 12";
$product_result = mysqli_query($conn, $product_query);
?>

<section class="container">
    <h2 class="section-title">Featured Products</h2>
    <div class="row">
        <?php if (mysqli_num_rows($product_result) > 0): ?>
            <?php while ($product = mysqli_fetch_assoc($product_result)): 
                $originalPrice = $product['price'];
                $discount = $product['discount'];
                $finalPrice = $originalPrice - ($originalPrice * $discount / 100);
            ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100">
                        <img src="../uploads/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="price">
                                <span class="old-price">₹<?= number_format($originalPrice, 2) ?></span>
                                <span class="new-price ms-2">₹<?= number_format($finalPrice, 2) ?></span>
                            </p>
                            <p class="text-success">You save <?= $discount ?>%</p>
                            <a href="product_details.php?id=<?= $product['id'] ?>" class="btn btn-outline-primary w-100">View</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12"><div class="alert alert-warning">No products found.</div></div>
        <?php endif; ?>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php require_once('../includes/footer.php'); ?>
