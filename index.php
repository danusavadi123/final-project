<?php
session_start();
require_once('./config/database.php');
require_once('./includes/header.php');
require_once('./includes/navbar.php');
?>

<!-- Banner Section -->
<section class="py-5 text-center bg-light">
    <div class="container">
        <h1 class="display-4">Welcome to Local Marketplace</h1>
        <p class="lead">Connecting you with local home-based businesses like bakers, tailors, artists, and more.</p>
        <a href="auth/register.php" class="btn btn-primary btn-lg">Get Started</a>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-4">Explore Categories</h2>
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm">
                    <img src="assets/images/bakery.jpg" class="card-img-top" alt="Bakery">
                    <div class="card-body">
                        <h5 class="card-title">Bakery</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm">
                    <img src="assets/images/tailor.jpg" class="card-img-top" alt="Tailoring">
                    <div class="card-body">
                        <h5 class="card-title">Tailoring</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm">
                    <img src="assets/images/art.jpg" class="card-img-top" alt="Art & Crafts">
                    <div class="card-body">
                        <h5 class="card-title">Art & Crafts</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card shadow-sm">
                    <img src="assets/images/beauty.jpg" class="card-img-top" alt="Beauty Services">
                    <div class="card-body">
                        <h5 class="card-title">Beauty Services</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-primary text-white text-center">
    <div class="container">
        <h2>Join Our Community of Homepreneurs</h2>
        <p>Start selling your services or shop from trusted local sellers.</p>
        <a href="auth/register.php" class="btn btn-light">Register Now</a>
    </div>
</section>

<?php
require_once('./includes/footer.php');
?>