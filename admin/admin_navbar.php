<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand fw-bold" href="/admin/dashboard.php">
            <i class="fas fa-crown text-warning me-2"></i>AdminPanel
        </a>

        <!-- Toggler for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="./dashboard.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./manage_products.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./manage_users.php">Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./manage_orders.php">Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./contact_messages.php">Messages</a>
                </li>
            </ul>

            <!-- Profile dropdown -->
           <!-- Profile dropdown -->
<ul class="navbar-nav ms-auto">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
            <?php
            $firstLetter = '';
            if (isset($_SESSION['name']) && !empty($_SESSION['name'])) {
                $firstLetter = strtoupper($_SESSION['name'][0]);
            }
            ?>
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width:32px; height:32px; font-weight:bold;">
              <?= $firstLetter ?>
            </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="./admin_profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="../auth/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
        </ul>
    </li>
</ul>

        </div>
    </div>
</nav>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
