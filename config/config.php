<?php

// Site-wide constants
define('SITE_NAME', 'Local Marketplace');
define('BASE_URL', 'http://localhost/FINAL-PROJECT/'); // Change according to your project folder

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Error Reporting - you can disable this in production
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Currency
define('CURRENCY_SYMBOL', '₹');


define('RAZORPAY_KEY_ID', 'rzp_test_g6EblohbAvjqdG');
define('RAZORPAY_KEY_SECRET', 'C1yySJ5jG6dxoShLB7brzuOL');

?>