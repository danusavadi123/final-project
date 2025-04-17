<?php
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../auth/login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$currentName = $_SESSION['name'];
$message = "";

// Update logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = trim($_POST['name']);
    $newPassword = trim($_POST['password']);

    if (!empty($newName) && !empty($newPassword)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET name = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssi", $newName, $hashedPassword, $userId);

        if ($stmt->execute()) {
            $_SESSION['name'] = $newName;
            $message = "Profile updated successfully.";
        } else {
            $message = "Failed to update profile.";
        }

        $stmt->close();
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-box {
            max-width: 500px;
            margin: 40px auto;
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
<div class="profile-box">
    <h2 class="text-center">Seller Profile</h2>
    <?php if ($message): ?>
        <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Name</label>
        <input type="text" class="form-control mb-3" name="name" value="<?= htmlspecialchars($currentName) ?>" required>

        <label>New Password</label>
        <input type="password" class="form-control mb-4" name="password" placeholder="Enter new password" required>

        <button type="submit" class="btn btn-primary w-100">Update Profile</button>
    </form>
</div>
</body>
</html>
<?php require_once('../includes/footer.php'); ?>