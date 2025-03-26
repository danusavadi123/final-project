<?php
// Manage Users - Admin can view and manage all users

include '../includes/session.php';
checkRole('admin'); // Only admins can access this page
include '../config/database.php';

// Get all users from the database
$query = "SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($query);

// Handle role update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'], $_POST['role'])) {
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];

    $update_query = "UPDATE users SET role = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $role, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "User role updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating user role. Try again.";
    }

    header("Location: manage_users.php");
    exit();
}

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "User deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting user. Try again.";
    }

    header("Location: manage_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2>Manage Users</h2>

    <?php if (isset($_SESSION['success'])) { ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php } ?>

    <?php if (isset($_SESSION['error'])) { ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php } ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Registered At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['username']); ?></td>
                    <td><?= htmlspecialchars($row['email']); ?></td>
                    <td>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="user_id" value="<?= $row['id']; ?>">
                            <select name="role" class="form-select d-inline w-auto">
                                <option value="buyer" <?= $row['role'] == 'buyer' ? 'selected' : ''; ?>>Buyer</option>
                                <option value="seller" <?= $row['role'] == 'seller' ? 'selected' : ''; ?>>Seller</option>
                                <option value="admin" <?= $row['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </form>
                    </td>
                    <td><?= date("d M Y", strtotime($row['created_at'])); ?></td>
                    <td>
                        <?php if ($row['role'] !== 'admin') { ?>
                            <a href="manage_users.php?delete_id=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>

</body>
</html>