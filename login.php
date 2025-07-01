<?php
session_start();
include 'db.php'; // Database connection file


// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ensure username and password are not empty
    if (!empty($username) && !empty($password)) {
        // Check if the user exists
        $sql = "SELECT * FROM Users WHERE username = $1"; 
        $result = pg_query_params($conn, $sql, array($username));

        if ($result) {
            if (pg_num_rows($result) > 0) {
                $user = pg_fetch_assoc($result);
                
                // Verify password
                if (password_verify($password, $user['password_hash'])) {
                    // Set session variable on successful login
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
		    
                    header('Location: dashboard.php');
                    exit; // Ensure to exit after redirect
                } else {
                    $error_message = "Invalid password!"; // Password mismatch
                }
            } else {
                $error_message = "No user found!"; // Username not found in the database
            }
        } else {
            $error_message = "Error: Unable to execute the query."; // Query error
        }

        pg_free_result($result);
    } else {
        $error_message = "Please fill in both fields."; // Form validation error
    }

    pg_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Squid Game Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <div class="logo-container">
            <div class="logo-symbol"></div> <!-- Custom CSS symbol -->
            <h1>Login</h1>
        </div>

        <!-- Display error message if any -->
        <?php if (isset($error_message)): ?>
            <div class="error-msg"><?= htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form action="login.php" method="post">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="login-btn">Login</button>
        </form>

        <div class="links">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
            <p>Admin? <a href="admin_login.php">Admin login</a></p>
        </div>
    </div>
</body>
</html>
