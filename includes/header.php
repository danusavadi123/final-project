<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include config file
require_once __DIR__ . '/../config/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Local Marketplace | Home-Based Businesses</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/home-business-platform/assets/css/bootstrap.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/home-business-platform/assets/css/style.css">

    <!-- Favicon -->
    <link rel="icon" href="/home-business-platform/assets/images/favicon.png" type="image/png">

    <!-- Meta -->
    <meta name="description" content="A Local Marketplace Platform for Home-Based Businesses to connect buyers and sellers.">
    <meta name="author" content="Your Team Name">
</head>
<body>

<!-- Page wrapper -->
<div class="wrapper">