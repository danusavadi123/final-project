<?php
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('./admin_navbar.php');
require_once('../config/db.php');

// Restrict access to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Handle user deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $user_id = intval($_GET['delete']);

    // Prevent admin deletion
    $check = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $check->bind_param("i", $user_id);
    $check->execute();
    $result = $check->get_result();
    $user = $result->fetch_assoc();

    if ($user && $user['role'] !== 'admin') {
        // Delete orders by or with user
        $stmt1 = $conn->prepare("DELETE FROM orders WHERE buyer_id = ? OR seller_id = ?");
        $stmt1->bind_param("ii", $user_id, $user_id);
        $stmt1->execute();
        $stmt1->close();

        // Delete products from seller
        $stmt2 = $conn->prepare("DELETE FROM products WHERE seller_id = ?");
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();
        $stmt2->close();

        // Finally delete the user
        $stmt3 = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt3->bind_param("i", $user_id);
        $stmt3->execute();
        $stmt3->close();
    }

    header("Location: manage_users.php");
    exit();
}

// Role filter
$role_filter = $_GET['role'] ?? 'all';
$sql = "SELECT id, name, email, role, created_at FROM users";
if (in_array($role_filter, ['buyer', 'seller'])) {
    $sql .= " WHERE role = ?";
}

$sql .= " ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);

if (in_array($role_filter, ['buyer', 'seller'])) {
    $stmt->bind_param("s", $role_filter);
}

$stmt->execute();
$users = $stmt->get_result();
?>

<!-- Styles and Assets -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
    body, html {
        height: 100%;
        margin: 0;
         background: linear-gradient(to right, #eff3ff, #c6dbef);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .canvas-container {
        min-height: 100vh;
        padding: 40px 25px;
         background: rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        margin: 30px auto;
        width: 95%;
        box-shadow: 0 0 20px rgba(0,0,0,0.15);
    }

    .navbar-custom {
        position : fixed;
        top : 0;
        width : 100%;
        z-index: 1000;
    }

    h3 {
        font-weight: 700;
        color: #08519c;
        text-align: center;
        margin-bottom: 30px;
        text-shadow: 1px 1px 4px rgba(0,0,0,0.3);
    }

    .btn-outline-dark.active {
        background-color: #ffffff;
        color: #000;
        border-color: #000;
        font-weight: bold;
    }

    .btn-outline-dark:hover {
        transform: scale(1.05);
    }

    .table-3d {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .table-3d tbody tr:hover {
        transform: scale(1.01);
        background-color: #f8f9fa;
        box-shadow: 0 8px 16px rgba(0,0,0,0.08);
    }

    .filter-box {
        background: rgba(255, 255, 255, 0.8);
        padding: 10px 15px;
        border-radius: 12px;
        margin-bottom: 20px;
        text-align: center;
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }

    .badge {
        font-size: 0.9rem;
        padding: 6px 10px;
        border-radius: 10px;
    }

    .btn-danger {
        transition: transform 0.2s ease;
    }

    .btn-danger:hover {
        transform: scale(1.05);
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
        min-width: 1000px;
    }
</style>

<!-- Content Section -->
<div class="canvas-container">
    <h3><i class="fa-solid fa-people-roof me-2"></i> Manage Users</h3>

    <div class="filter-box">
        <a href="?role=all" class="btn btn-outline-dark <?= $role_filter === 'all' ? 'active' : '' ?>">All</a>
        <a href="?role=buyer" class="btn btn-outline-dark <?= $role_filter === 'buyer' ? 'active' : '' ?>">Buyers</a>
        <a href="?role=seller" class="btn btn-outline-dark <?= $role_filter === 'seller' ? 'active' : '' ?>">Sellers</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle table-3d">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Registered On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($user = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'seller' ? 'success' : 'primary') ?>">
                                <?= ucfirst($user['role']) ?>
                            </span>
                        </td>
                        <td><?= date("d M Y", strtotime($user['created_at'])) ?></td>
                        <td>
                            <?php if ($user['role'] !== 'admin'): ?>
                                <a href="?delete=<?= $user['id'] ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Delete this user and all associated products and orders?')">
                                   <i class="fas fa-trash-alt"></i> Delete
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Protected</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>
