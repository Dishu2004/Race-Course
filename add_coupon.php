<?php
session_start();
require 'db.php';

// Check if the user is an admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = $_POST['code'];
    $amount = $_POST['amount'];
    $valid_from = $_POST['valid_from'];
    $valid_to = $_POST['valid_to'];

    // Prepare and execute SQL statement
    $sql = "INSERT INTO Coupons (code, amount, valid_from, valid_to) VALUES ($1, $2, $3, $4)";
    $stmt = pg_prepare($conn, "insert_coupon", $sql);
    $result = pg_execute($conn, "insert_coupon", array($code, $amount, $valid_from, $valid_to));

    if ($result) {
        echo "<script>alert('Coupon added successfully!'); window.location.href = 'admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to add coupon. Please try again.'); window.location.href = 'admin_dashboard.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Coupon - Betting System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar for navigation -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Admin Dashboard</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="admin_dashboard.php"><span class="icon">&#8962;</span> Dashboard</a></li>
                <li><a href="show_users.php"><span class="icon">&#128100;</span> Show Users</a></li>
                <li><a href="add_coupon.php"><span class="icon">&#128176;</span> Add Coupon</a></li>
                <li><a href="search_user.php"><span class="icon">&#128269;</span> Search User</a></li>
                <li><a href="logout.php"><span class="icon">&#128682;</span> Logout</a></li>
            </ul>
        </div>

        <!-- Main content -->
        <div class="main-content">
            <div class="container">
                <h1>Add Coupon</h1>
                <form action="add_coupon.php" method="post">
                    <label for="code">Coupon Code:</label>
                    <input type="text" id="code" name="code" required>

                    <label for="amount">Amount to Add to Wallet:</label>
                    <input type="number" id="amount" name="amount" step="0.01" min="0" required>

                    <label for="valid_from">Valid From:</label>
                    <input type="date" id="valid_from" name="valid_from" required>

                    <label for="valid_to">Valid To:</label>
                    <input type="date" id="valid_to" name="valid_to" required>

                    <button type="submit">Add Coupon</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
