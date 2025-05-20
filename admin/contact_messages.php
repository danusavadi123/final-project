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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/contact_messages.css">
    <style>
        body, html {
    height: 100%;
    margin: 0;
    background: linear-gradient(135deg, #eff3ff, #c6dbef);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.navbar-custom {
        position : fixed;
        top : 0;
        width : 100%;
        z-index: 1000;
    }
.canvas-container {
    min-height: 100vh;
    padding: 40px 20px;
     background: rgba(0, 0, 0, 0.2);
    box-shadow: inset 0 0 25px rgba(0, 0, 0, 0.05);
    border-radius: 16px;
    margin: 30px auto;
    width: 95%;
}

h3 {
    text-align: center;
    font-weight: 700;
    color: #08519c;
    margin-bottom: 30px;
}

.message-card {
    background: #ffffff;
    border-left: 5px solid #3182bd;
    border-radius: 12px;
    padding: 20px 25px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.message-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 25px rgba(49, 130, 189, 0.15);
    background-color: #f7fbff;
}

.badge-secondary {
    background-color: #6baed6;
    font-size: 0.8rem;
    padding: 5px 10px;
}

small {
    color: #6c757d;
}

.alert-info {
    background-color: #dbe9f5;
    color: #08519c;
    border: 1px solid #9ecae1;
    text-align: center;
    padding: 15px;
    border-radius: 10px;
}

p {
    color: #333;
    margin-bottom: 6px;
}

strong {
    color: #08519c;
}

    </style>
</head>
<body>
<div class="canvas-container">
    <h3 class="mb-4 text-center "><i class="fa-solid fa-message"></i></i>  Contact Messages</h3>

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
