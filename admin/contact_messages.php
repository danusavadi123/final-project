<?php
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('./admin_navbar.php');
require_once('../config/db.php');

// Ensure only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Fetch contact messages
$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/contact_messages.css">
</head>
<body>
<div class="container my-5">
    <h3 class="mb-4">Contact Messages</h3>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="message-card">
                <div class="d-flex justify-content-between">
                    <h6><?= htmlspecialchars($row['name']) ?> 
                        <span class="badge bg-secondary text-light"><?= htmlspecialchars(ucfirst($row['role'])) ?></span>
                    </h6>
                    <small><?= date('d M Y, h:i A', strtotime($row['created_at'])) ?></small>
                </div>
                <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
                <p class="mb-0"><strong>Message:</strong> <?= nl2br(htmlspecialchars($row['message'])) ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-info">No contact messages found.</div>
    <?php endif; ?>
</div>
</body>
</html>
