<?php
session_start();
require 'db.php'; // Include your PostgreSQL connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT * FROM Users WHERE user_id = $1";
$result = pg_query_params($conn, $sql, array($user_id));

// Check if the query executed successfully
if (!$result) {
    die("Error in query: " . pg_last_error($conn));
}

$user = pg_fetch_assoc($result);

if (!$user) {
    die("User not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Betting System</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <div class="sidebar">
        <a href="dashboard.php" class="sidebar-link"><span class="icon dashboard-icon"></span> Dashboard</a>
        <a href="profile.php" class="sidebar-link"><span class="icon profile-icon"></span> Profile</a>
        <a href="wallet.php" class="sidebar-link"><span class="icon wallet-icon"></span> Wallet</a>
        <a href="game.html" class="sidebar-link"><span class="icon game-icon"></span> Play</a>
        <a href="logout.php" class="sidebar-link"><span class="icon logout-icon"></span> Logout</a>
    </div>
    <div class="main-content">
        <h1>Profile</h1>
        <div class="profile-info">
            <div class="profile-header">
                <img src="pic.jpeg" alt="User Image" class="user-image">
                <div class="profile-details">
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                    <p><strong>Mobile Number:</strong> <?php echo htmlspecialchars($user['mobile_no']); ?></p>
                    <p><strong>Level:</strong> <?php echo htmlspecialchars($user['level']); ?></p>
                    <p><strong>Wallet Amount:</strong> ₹<?php echo htmlspecialchars($user['balance']); ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
