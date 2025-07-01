<?php
session_start();
require 'db.php'; // Include your PostgreSQL connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user balance using PostgreSQL
$sql = "SELECT balance FROM Users WHERE user_id = $1";
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
    <title>Wallet - Betting System</title>
    <link rel="stylesheet" href="wallet.css">
<style>

</style>
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
        <h1>Wallet</h1>
        <div class="wallet-info">
            <div class="wallet-balance">
                <p><strong>Current Balance:</strong></p>
                <h2>₹<?php echo number_format($user['balance'], 2); ?></h2>
            </div>
            <div class="wallet-actions">
                <form action="apply_coupon.php" method="POST">
                    <label for="coupon_code">Apply Coupon Code:</label>
                    <input type="text" id="coupon_code" name="coupon_code" placeholder="Enter coupon code" required>
                    <button type="submit">Apply</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
