<?php
require_once('../includes/spinner.html');
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
           background: linear-gradient(135deg, #6baed6, #eff3ff);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .profile-container {
            max-width: 600px;
            margin: 60px auto;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            font-size: 1.2rem;
            letter-spacing: 1px;
        }

        h2 {
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
            color: #343a40;
        }

        label {
            font-weight: 600;
        }

        .btn-primary {
            background: linear-gradient(135deg, #007bff, #00c6ff);
            border: none;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.6);
        }

        .form-control {
            border-radius: 10px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid #ced4da;
            transition: box-shadow 0.2s ease;
        }

        .form-control:focus {
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.2);
            border-color: #80bdff;
        }

        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <h2>Seller Profile</h2>

    <?php if ($message): ?>
        <div class="alert alert-info text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header bg-dark text-white text-center">
            Update Your Details
        </div>
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label for="name">Name</label>
                    <input type="text" id="name" class="form-control" name="name" value="<?= htmlspecialchars($currentName) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="password">New Password</label>
                    <input type="password" id="password" class="form-control" name="password" placeholder="Enter new password" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Update Profile</button>
            </form>
        </div>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>
</body>
</html>
