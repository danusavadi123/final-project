<?php
// Payment Failure - Handles failed payments

include '../includes/session.php';
checkRole('buyer'); // Only buyers can access this page

$_SESSION['error'] = "Payment failed. Please try again.";
header("Location: checkout.php");
exit();
?>