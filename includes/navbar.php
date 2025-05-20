<?php
$homeLink = '../index.php';
$about = '../about.php';
$contact = '../contact.php';

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'buyer') {
        $homeLink = '../buyer/dashboard.php';
        $about = '../buyer/about.php';
        $contact = '../buyer/contact.php';
    } elseif ($_SESSION['role'] === 'seller') {
        $homeLink = '../seller/dashboard.php';
        $about = '../seller/about.php';
        $contact = '../seller/contact.php';
    }
}
?>
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
  <link rel="stylesheet" href="../assets/css/navbar.css">
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar">
  <div class="logo-img">
    <a href="<?= $homeLink ?>">
  <img src="../assets/images/logo.png" alt="Logo" width="110" height="80" >
  </a>
  </div>

  <div class="menu-toggle" id="menu-toggle">
    <i class="fas fa-bars"></i>
  </div>

  <!-- Search bar: Only for buyers -->
  <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'buyer'): ?>
  <div class="search-container">
    <form class="search-wrapper" action="../buyer/view_products.php" method="GET">
      <input type="text" name="search" class="search-input" id="search" placeholder="Search..." required>
      <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
    </form>
    <div class="suggestionbox" id="suggestions">
      <ul id="dropdata"></ul>
    </div>
  </div>
  <?php endif; ?>

  <div class="navbar-menu" id="navbar-menu">
    <ul>
      <li><a href="<?= $homeLink ?>">Home</a></li>
      <li><a href="<?= $about ?>">About</a></li>
      <li><a href="<?= $contact ?>">Contact</a></li>
    </ul>

    <div class="icons">
      <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'buyer'): ?>
        <!-- Cart Icon for Buyer -->
        <div class="cart-icon">
          <i class="fas fa-shopping-cart"></i>
          <span class="badge" id="cart-count">0</span>
        </div>
      <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'seller'): ?>
        <!-- Notification Icon for Seller -->
        <div class="notification-icon">
  <i class="fas fa-bell"></i>
  <span class="badge" id="notification-count">0</span>
</div>



      <?php endif; ?>

      <!-- Profile Dropdown -->
      <div class="dropdown" id="profile-dropdown">
      <?php
$firstLetter = '';
if (isset($_SESSION['name']) && !empty($_SESSION['name'])) {
    $firstLetter = strtoupper($_SESSION['name'][0]);
}
?>
<div class="avatar" id="avatar">
  <?= $firstLetter ?>
</div>

        <div class="dropdown-menu" id="dropdown-menu">
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'buyer'): ?>
            <a href="../buyer/profile.php"><i class="fas fa-user"></i> Profile</a>
            <a href="../buyer/order_history.php"><i class="fas fa-box"></i> Orders</a>
          <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'seller'): ?>
            <a href="../seller/profile.php"><i class="fas fa-user"></i> Profile</a>
            <a href="../seller/orders.php"><i class="fas fa-box"></i> Orders</a>
          <?php endif; ?>
          <a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- Cart Popup (Only for buyers) -->
<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'buyer'): ?>
<div id="cart-popup" class="cart-popup">
  <div class="cart-popup-content">
    <span class="close-btn" id="close-cart">&times;</span>
    <h2>Your Cart</h2>
    <div id="cart-items"></div>
    <div class="cart-actions">
      <a href="./place_order.php" class="btn btn-success">Order</a>
    </div>
  </div>
</div>
<?php endif; ?>

<!-- Notification Popup for Sellers -->
<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller'): ?>
<div id="notification-popup" class="notification-popup" style="display: none;">
  <div class="notification-content">
    <span class="close-btn" id="close-notification">&times;</span>
    <h2>Pending Orders</h2>
    <div id="notification-items"></div>
  </div>
</div>
<?php endif; ?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="../assets/script/navbar.js"></script>
</body>
