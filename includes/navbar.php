<?php
// Global Navbar - appears on all pages
include_once __DIR__ . '/../config/config.php';
include_once __DIR__ . '/session.php';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= BASE_URL ?>/index.php">Local Marketplace</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if (isLoggedIn()): ?>
                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/admin/dashboard.php">Admin Dashboard</a>
                        </li>
                    <?php elseif ($_SESSION['user']['role'] === 'seller'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/seller/dashboard.php">Seller Dashboard</a>
                        </li>
                    <?php elseif ($_SESSION['user']['role'] === 'buyer'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= BASE_URL ?>/buyer/dashboard.php">Buyer Dashboard</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/auth/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/auth/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>/auth/register.php">Register</a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/about.php">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/contact.php">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>