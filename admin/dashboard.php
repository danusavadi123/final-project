<?php
require_once('../includes/spinner.html');
require_once('../includes/session.php');
require_once('../includes/header.php');
require_once('./admin_navbar.php');
require_once('../config/db.php');

// Only admin access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Get user counts
$buyers = $conn->query("SELECT COUNT(*) AS count FROM users WHERE role = 'buyer'")->fetch_assoc()['count'];
$sellers = $conn->query("SELECT COUNT(*) AS count FROM users WHERE role = 'seller'")->fetch_assoc()['count'];

// Get product and order counts
$total_products = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
$total_orders = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];

// Get recent orders
$recent_orders = $conn->query("
    SELECT o.id, o.total_amount, o.order_status, o.order_date, u.name 
    FROM orders o 
    JOIN users u ON o.buyer_id = u.id 
    ORDER BY o.order_date DESC 
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Admin Dashboard</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
<style>
    /* Background and base font */
    body {
        background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
        color: #1f2933;
    }

    .heading{
        padding-top : 3rem;
    }

    /* Headers */
    h2, h4 {
        font-weight: 700;
        margin: 3rem auto;
        padding : 10px;
        color: #1f2933;
    }

    /* Flex rows */
    .row {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 3rem;
    }

    /* Columns */
    .col-md-3 {
        flex: 1 1 calc(25% - 1rem);
        min-width: 260px;
    }

    /* Card base style */
    .card-3d {
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 6px 18px rgba(31, 41, 51, 0.12);
        display: flex;
        align-items: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        color: #fff;
        cursor: default;
    }

    .card-3d:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 36px rgba(31, 41, 51, 0.18);
    }

    /* Individual card colors */
    .card {
        background-color: #4a90e2; /* blue */
    }

    /* Icon styling */
    .card .fa-3x {
        margin-right: 1.2rem;
        flex-shrink: 0;
    }

    /* Text inside cards */
    .card h6 {
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0 0 0.3rem 0;
        opacity: 0.85;
    }

    .card h4 {
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0;
    }

    /* Table styles */
    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 700px;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(31, 41, 51, 0.08);
    }

    thead tr {
        background-color: #334e68;
        color: #f0f4f8;
    }

    thead th, tbody td {
        padding: 14px 20px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
        text-align: left;
        font-weight: 500;
    }

    tbody tr:hover {
        background-color: #f0f4f8;
        transform: scale(1.01);
        transition: background-color 0.25s ease, transform 0.25s ease;
        box-shadow: 0 4px 12px rgba(31,41,51,0.06);
        cursor: default;
    }

    /* Badges for order status */
    .badge {
        font-size: 0.85rem;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 12px;
        display: inline-block;
        user-select: none;
        white-space: nowrap;
    }

    .badge-pending {
        background-color: #facc15; /* amber-400 */
        color: #78350f;
    }

    .badge-processed {
        background-color: #3b82f6; /* blue-500 */
        color: #e0e7ff;
    }

    .badge-shipped {
        background-color: #22d3ee; /* cyan-400 */
        color: #164e63;
    }

    .badge-delivered {
        background-color: #4ade80; /* green-400 */
        color: #166534;
    }

    .badge-cancelled {
        background-color: #f87171; /* red-400 */
        color: #7f1d1d;
    }

    .badge-default {
        background-color: #94a3b8; /* cool gray */
        color: #1e293b;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .col-md-3 {
            flex: 1 1 100%;
            min-width: auto;
        }

        table {
            min-width: 100%;
            font-size: 0.9rem;
        }
    }
</style>
</head>
<body>

<div class="container">
    <h2 class="text-center heading">Admin Dashboard</h2>

    <div class="row">
        <div class="col-md-3">
            <div class="card-3d card">
                <i class="fas fa-user fa-3x"></i>
                <div>
                    <h6>Buyers</h6>
                    <h4><?= htmlspecialchars($buyers) ?></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-3d card">
                <i class="fas fa-store fa-3x"></i>
                <div>
                    <h6>Sellers</h6>
                    <h4><?= htmlspecialchars($sellers) ?></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-3d card">
                <i class="fas fa-box-open fa-3x"></i>
                <div>
                    <h6>Products</h6>
                    <h4><?= htmlspecialchars($total_products) ?></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-3d card">
                <i class="fas fa-shopping-cart fa-3x"></i>
                <div>
                    <h6>Total Orders</h6>
                    <h4><?= htmlspecialchars($total_orders) ?></h4>
                </div>
            </div>
        </div>
    </div>

    <h4>Recent Orders</h4>
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Buyer Name</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = $recent_orders->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($order['id']) ?></td>
                        <td><?= htmlspecialchars($order['name']) ?></td>
                        <td>₹<?= number_format($order['total_amount'], 2) ?></td>
                        <td>
                            <span class="badge 
                                <?php
                                    switch (strtolower($order['order_status'])) {
                                        case 'pending': echo 'badge-pending'; break;
                                        case 'processed': echo 'badge-processed'; break;
                                        case 'shipped': echo 'badge-shipped'; break;
                                        case 'delivered': echo 'badge-delivered'; break;
                                        case 'cancelled': echo 'badge-cancelled'; break;
                                        default: echo 'badge-default';
                                    }
                                ?>
                            ">
                                <?= ucfirst(htmlspecialchars($order['order_status'])) ?>
                            </span>
                        </td>
                        <td><?= date("d M Y, h:i A", strtotime($order['order_date'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>
</body>
</html>
