<?php
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('./admin_navbar.php');
require_once('../config/db.php');

// Redirect if not logged in or not admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];
$success = $error = "";

// Fetch admin info
$stmt = $conn->prepare("SELECT name, email, role, created_at FROM users WHERE id = ?");
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Handle password update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    // Get current password hash
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();
    $stmt->close();

    // Validate current password
    if (!password_verify($current, $hashed_password)) {
        $error = "Current password is incorrect.";
    } elseif ($new !== $confirm) {
        $error = "New passwords do not match.";
    } elseif (strlen($new) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        // Update password
        $new_hash = password_hash($new, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $new_hash, $admin_id);
        if ($stmt->execute()) {
            $success = "Password updated successfully!";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #eff3ff, #c6dbef);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 50px;
        }

         .navbar-custom {
        position : fixed;
        top : 0;
        width : 100%;
        z-index: 1000;
    }
    

        h2 {
            font-weight: 700;
            text-align: center;
            margin: 30px auto;
            color: #08519c;
        }

        .card {
            border: 1px solid #c6dbef;
            border-radius: 20px;
            background-color: #ffffff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
             padding-top : 3rem;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            font-size: 1rem;
            font-weight: 600;
            background-color: #3182bd;
            color: #ffffff;
            padding: 1rem;
        }

        label {
            font-weight: 600;
            color: #08519c;
        }

        .form-control {
            border-radius: 12px;
            border: 1px solid #c6dbef;
            transition: border 0.3s, box-shadow 0.3s;
        }

        .form-control:focus {
            border-color: #6baed6;
            box-shadow: 0 0 10px rgba(49, 130, 189, 0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3182bd, #6baed6);
            border: none;
            border-radius: 30px;
            font-weight: 600;
            padding: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(49, 130, 189, 0.4);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #08519c, #3182bd);
            box-shadow: 0 10px 25px rgba(49, 130, 189, 0.6);
            transform: scale(1.03);
        }

        .alert {
            border-radius: 12px;
        }

        p {
            margin-bottom: 10px;
            color: #333;
        }

        strong {
            color: #08519c;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Admin Profile</h2>
    <hr>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="row">
        <!-- Profile Info -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">Profile Details</div>
                <div class="card-body">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($admin['name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($admin['email']); ?></p>
                    <p><strong>Role:</strong> <?php echo ucfirst($admin['role']); ?></p>
                    <p><strong>Registered On:</strong> <?php echo date("d M Y", strtotime($admin['created_at'])); ?></p>
                </div>
            </div>
        </div>

        <!-- Change Password -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Change Password</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>
</body>
</html>
