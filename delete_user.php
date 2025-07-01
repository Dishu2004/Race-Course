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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Print POST data for debugging
    echo '<pre>' . print_r($_POST, true) . '</pre>';

    $username = $_POST['username'];

    // Validate input
    if (empty($username)) {
        $message = "Username is required.";
    } else {
        // Delete user from the database
        $sql = "DELETE FROM Users WHERE username = $1";
        
        // Prepare and execute query
        $stmt = pg_prepare($conn, "delete_user", $sql);
        
        if ($stmt) {
            $result = pg_execute($conn, "delete_user", array($username));
            
            if ($result) {
                $message = "User deleted successfully.";
            } else {
                $message = "Error deleting user: " . pg_last_error($conn);
            }
        } else {
            $message = "Error preparing statement: " . pg_last_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>

   <link rel="stylesheet" href="style.css">
</head>
<body>
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
    <div class="container">
        <h1>Delete User</h1>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username of user to delete" required>
            <button type="submit">Delete User</button>
        </form>
        <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
    </div>
</body>
</html>


