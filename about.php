<?php
session_start();
require_once('./config/database.php');
require_once('./includes/header.php');
require_once('./includes/navbar.php');
?>

<section class="py-5 bg-light text-center">
    <div class="container">
        <h1 class="display-5">About Us</h1>
        <p class="lead">Empowering home-based entrepreneurs and connecting them with local customers.</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4">
                <img src="assets/images/about-us.jpg" alt="About Marketplace" class="img-fluid rounded shadow">
            </div>
            <div class="col-md-6">
                <h3>Our Vision</h3>
                <p>We aim to create a platform where local artisans, home-based service providers, and micro-businesses can thrive by reaching a wider customer base without needing a physical storefront.</p>

                <h3 class="mt-4">What We Offer</h3>
                <ul>
                    <li>Easy registration for sellers and buyers</li>
                    <li>Product and service listings</li>
                    <li>Search and filter by location, price, and category</li>
                    <li>Safe and secure online ordering and payment</li>
                    <li>Admin-managed marketplace to ensure quality and trust</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="py-5 text-center bg-primary text-white">
    <div class="container">
        <h2>Ready to get started?</h2>
        <a href="auth/register.php" class="btn btn-light btn-lg mt-2">Join as Buyer or Seller</a>
    </div>
</section>

<?php
require_once('./includes/footer.php');
?>