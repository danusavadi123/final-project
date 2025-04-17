<?php
// Database connection configuration

// Database credentials
$host = "localhost";
$db_name = "home_marketplace"; 
$username = "root";
$password = "";

// Establish connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check for connection errors
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Set character encoding
$conn->set_charset("utf8");


?>