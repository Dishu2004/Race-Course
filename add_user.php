<?php
session_start();
require 'db.php';

// Check if the user is an admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form data
    $name = trim($_POST['name']);
    $mobile_no = trim($_POST['mobile_no']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($name) || empty($mobile_no) || empty($username) || empty($password)) {
        $message = "All fields are required.";
    } else {
        // Hash password
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $balance = 0.00; // Default balance
        $level = 1; // Default level

        // Prepare and execute the SQL statement
        $sql = "INSERT INTO Users (name, mobile_no, username, password_hash, balance, level) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssssdi", $name, $mobile_no, $username, $password_hash, $balance, $level);
            if ($stmt->execute()) {
                $message = "User added successfully.";
            } else {
                $message = "Error adding user: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Error preparing statement: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
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
   
    <div class="container">
        <h1>Add User</h1>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="mobile_no" placeholder="Mobile No" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Add User</button>
        </form>
        <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>
    </div>
</body>
</html>
