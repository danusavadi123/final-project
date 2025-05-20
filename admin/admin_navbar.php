<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    .navbar-custom {
        background-color : #08519c;
        box-shadow : 0 4px 6px rgba(0, 0, 0, 0.1);
        position : fixed;
        top : 0;
        width : 100%;
        z-index: 1000;
    }

    .navbar-brand {
        color : #ffffff !important;
        font-weight : 700;
        font-size : 1.3rem;
    }

    .navbar-brand i {
        color: #ffd700; 
    }

    .navbar-nav .nav-link {
        border-radius : 10%;
        color: #eff3ff !important; 
        transition: color 0.3s ease;
    }

    .navbar-nav .nav-link:hover {
        background-color : #3182bd;
        color : #ffffff !important;
    }

    .dropdown-menu {
        background-color : #ffffff;
        border : 1px solid #c6dbef; 
        box-shadow : 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .dropdown-menu .dropdown-item {
        color: #08519c; 
        transition: background-color 0.2s ease, color 0.2s ease;
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #c6dbef; 
        color: #000;
    }

    .navbar-toggler {
        border-color: #ffffff;
    }

    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba%28255,255,255,1%29' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
    }

    .profile-initial {
        width: 32px;
        height: 32px;
        background-color: #3182bd;
        color: #ffffff;
        font-weight: bold;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>


<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="./dashboard.php">
            <i class="fas fa-crown me-2"></i>Admin Panel
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="./dashboard.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="./manage_products.php">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="./manage_users.php">Users</a></li>
                <li class="nav-item"><a class="nav-link" href="./manage_orders.php">Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="./contact_messages.php">Messages</a></li>
            </ul>

            <!-- Profile Dropdown -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <?php
                        $firstLetter = '';
                        if (isset($_SESSION['name']) && !empty($_SESSION['name'])) {
                            $firstLetter = strtoupper($_SESSION['name'][0]);
                        }
                        ?>
                        <div class="profile-initial me-2"><?= $firstLetter ?></div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
