<?php
// session.php - Manages user session authentication

session_start();

// Redirect if the user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

// Function to check user role
function checkRole($role) {
    if ($_SESSION["role"] !== $role) {
        header("Location: ../auth/login.php");
        exit();
    }
}
?>