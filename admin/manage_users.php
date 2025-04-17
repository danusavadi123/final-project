<?php
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('./admin_navbar.php');
require_once('../config/db.php');

// Only admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Delete user and related data
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $user_id = intval($_GET['delete']);

    // Prevent deleting self or other admins
    $check = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $check->bind_param("i", $user_id);
    $check->execute();
    $result = $check->get_result();
    $user = $result->fetch_assoc();
    if ($user && $user['role'] !== 'admin') {
        // Delete related orders
        $stmt1 = $conn->prepare("DELETE FROM orders WHERE buyer_id = ? OR seller_id = ?");
        $stmt1->bind_param("ii", $user_id, $user_id);
        $stmt1->execute();

        // Delete related products
        $stmt2 = $conn->prepare("DELETE FROM products WHERE seller_id = ?");
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();

        // Delete user
        $stmt3 = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt3->bind_param("i", $user_id);
        $stmt3->execute();
    }

    header("Location: manage_users.php");
    exit();
}

// Filter toggle
$role_filter = $_GET['role'] ?? 'all';

$sql = "SELECT id, name, email, role, created_at FROM users";
if ($role_filter === 'buyer' || $role_filter === 'seller') {
    $sql .= " WHERE role = ?";
}
$sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
if ($role_filter === 'buyer' || $role_filter === 'seller') {
    $stmt->bind_param("s", $role_filter);
}
$stmt->execute();
$users = $stmt->get_result();
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<div class="container mt-5">
    <h2>Manage Users</h2>
    <hr>

    <!-- Filter Buttons -->
    <div class="mb-3">
        <a href="?role=all" class="btn btn-outline-dark <?= $role_filter === 'all' ? 'active' : '' ?>">All</a>
        <a href="?role=buyer" class="btn btn-outline-primary <?= $role_filter === 'buyer' ? 'active' : '' ?>">Buyers</a>
        <a href="?role=seller" class="btn btn-outline-success <?= $role_filter === 'seller' ? 'active' : '' ?>">Sellers</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
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
                        <td><?= $user['id']; ?></td>
                        <td><?= htmlspecialchars($user['name']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'seller' ? 'success' : 'primary'); ?>">
                                <?= ucfirst($user['role']); ?>
                            </span>
                        </td>
                        <td><?= date("d M Y", strtotime($user['created_at'])); ?></td>
                        <td>
                            <?php if ($user['role'] !== 'admin'): ?>
                                <a href="?delete=<?= $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete user and all associated products/orders?')">Delete</a>
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
