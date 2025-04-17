<?php

session_start();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Optional: You can define role-based access helpers here too
function isSeller() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'seller';
}

function isBuyer() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'buyer';
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

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