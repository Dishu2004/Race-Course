<?php
session_start();
require 'db.php'; // Database connection file

// Check if the user is an admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Get admin info
$admin_id = $_SESSION['admin_id'];
$sql = "SELECT username FROM Admins WHERE admin_id = $1";
$result = pg_query_params($conn, $sql, array($admin_id));

if ($result) {
    $admin = pg_fetch_assoc($result);
} else {
    echo "Error: Unable to fetch admin details.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">
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
                <li><a href="add_user.php"><span class="icon">&#43;</span> Add User</a></li>
                <li><a href="delete_user.php"><span class="icon">&#128465;</span> Delete User</a></li>
                <li><a href="add_coupon.php"><span class="icon">&#128176;</span> Add Coupon</a></li>
                <li><a href="search_user.php"><span class="icon">&#128269;</span> Search User</a></li>
                <li><a href="logout.php"><span class="icon">&#128682;</span> Logout</a></li>
            </ul>
        </div>

        <!-- Main content -->
        <div class="main-content">
            <div class="container">
                <h1>Welcome, <?php echo htmlspecialchars($admin['username']); ?>!</h1>
                <p>Select an option from the sidebar to manage users and coupons.</p>
            </div>
        </div>
    </div>
</body>
</html>
