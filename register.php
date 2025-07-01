<?php
include 'db.php'; // Database connection file

// Initialize error message variable
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure all the required fields are set
    if (isset($_POST['name'], $_POST['mobile_no'], $_POST['username'], $_POST['password'])) {
        
        $name = trim($_POST['name']);
        $mobile_no = trim($_POST['mobile_no']);
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        
        // Validate if any of the fields are empty
        if (empty($name) || empty($mobile_no) || empty($username) || empty($password)) {
            $error_msg = 'Please fill all the fields.';
        } else {
            // Check if the username or mobile number already exists
            $sql = "SELECT * FROM Users WHERE username = $1 OR mobile_no = $2";
            $stmt = pg_prepare($conn, "check_user", $sql);
            $result = pg_execute($conn, "check_user", array($username, $mobile_no));

            if (pg_num_rows($result) > 0) {
                $error_msg = 'Username or mobile number already exists!';
            } else {
                // Insert new user after password hashing
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO Users (name, mobile_no, username, password_hash) VALUES ($1, $2, $3, $4)";
                $stmt = pg_prepare($conn, "insert_user", $sql);

                if (pg_execute($conn, "insert_user", array($name, $mobile_no, $username, $password_hash))) {
                    header('Location: login.php'); // Redirect to login page after successful registration
                    exit(); // Prevent further execution after redirect
                } else {
                    $error_msg = 'Error: ' . pg_last_error($conn);
                }
            }

            pg_free_result($result);
            pg_close($conn);
        }
    } else {
        $error_msg = 'Please fill all the fields.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Betting System</title>
    <link rel="stylesheet" href="register.css"> <!-- External CSS link -->
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        
        <!-- Display error message if exists -->
        <?php if ($error_msg != ''): ?>
            <div class="error-msg"><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="mobile_no">Mobile No:</label>
            <input type="text" id="mobile_no" name="mobile_no" required>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Register</button>
        </form>
        
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>
