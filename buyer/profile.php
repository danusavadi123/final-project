<?php
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('../includes/navbar.php');
require_once('../config/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'buyer') {
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
            $message = "✅ Profile updated successfully.";
        } else {
            $message = "❌ Failed to update profile. Please try again.";
        }

        $stmt->close();
    } else {
        $message = "⚠️ Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buyer Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="../assets/css/navbar.css">
    <style>
        body {
            background: linear-gradient(135deg, rgba(37, 166, 240, 0.3), rgba(255, 255, 255, 0.5));
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-box {
            width: 100%;
            max-width: 450px;
            background: #ffffff;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.6s ease-in-out;
        }

        h2 {
            font-weight: 700;
            margin-bottom: 30px;
            text-align: center;
            color: #343a40;
        }

        label {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .form-control {
            border-radius: 12px;
            padding: 10px 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid #ced4da;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        .btn-primary {
            background: linear-gradient(45deg, #007bff, #00c6ff);
            border: none;
            border-radius: 30px;
            padding: 12px;
            font-weight: 600;
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.3);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 123, 255, 0.5);
        }

        .alert {
            border-radius: 12px;
            padding: 12px;
            font-size: 0.95rem;
            text-align: center;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="profile-box">
    <h2>Buyer Profile</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($currentName) ?>" required>
        </div>

        <div class="mb-4">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Enter new password" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Update Profile</button>
    </form>
</div>

</body>
</html>

<?php require_once('../includes/footer.php'); ?>
