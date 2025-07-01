<?php
session_start();
require 'db.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is an admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    
    $sql = "DELETE FROM Users WHERE user_id = $1";
    $result = pg_prepare($conn, "delete_user", $sql);
    $result = pg_execute($conn, "delete_user", array($delete_id));
    
    if ($result) {
        $message = "User deleted successfully.";
    } else {
        $message = "Error deleting user: " . pg_last_error($conn);
    }
}

// Fetch all users from the database
$sql = "SELECT user_id, name, mobile_no, username, balance, level FROM Users";
$result = pg_query($conn, $sql);

if ($result === FALSE) {
    $message = "Error fetching users: " . pg_last_error($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Users</title>
    <link rel="stylesheet" href="show_users.css">
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
                <h1>Show Users</h1>
                <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
                <div class="user-table">
                    <table>
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Mobile No</th>
                                <th>Username</th>
                                <th>Balance</th>
                                <th>Level</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result && pg_num_rows($result) > 0) {
                                while ($row = pg_fetch_assoc($result)) {
                                    echo "<tr>
                                            <td>{$row['user_id']}</td>
                                            <td>{$row['name']}</td>
                                            <td>{$row['mobile_no']}</td>
                                            <td>{$row['username']}</td>
                                            <td>₹{$row['balance']}</td>
                                            <td>{$row['level']}</td>
                                            <td><a href='show_users.php?delete_id={$row['user_id']}' onclick='return confirm(\"Are you sure you want to delete this user?\");'>Delete</a></td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No users found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
