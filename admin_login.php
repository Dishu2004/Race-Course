<?php
session_start();
require 'db.php'; // Database connection file

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username and password from the POST request
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL query to fetch the admin by username
    $sql = "SELECT * FROM Admins WHERE username = $1";
    $result = pg_prepare($conn, "get_admin", $sql);
    $result = pg_execute($conn, "get_admin", array($username));

    if ($result) {
        // Fetch the admin data from the query result
        $admin = pg_fetch_assoc($result);

        // Verify the password
        if ($admin && password_verify($password, $admin['password_hash'])) {
            // Login successful, create session
            $_SESSION['admin_id'] = $admin['admin_id'];
            header('Location: admin_dashboard.php'); // Redirect to the admin dashboard
            exit(); // Make sure to stop further script execution after the redirect
        } else {
            $error = "Invalid username or password.";
        }

        pg_free_result($result); // Free the result after use
    } else {
        $error = "Error executing query.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin_login.css"> <!-- External CSS link -->
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <!-- Display error message if exists -->
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    </div>
</body>
</html>
