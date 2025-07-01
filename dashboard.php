<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session only if it hasn't started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'db.php'; // Database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "No user logged in.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT name, balance FROM Users WHERE user_id = $1";
$result = pg_query_params($conn, $sql, array($user_id));

if (!$result) {
    die("Error in query: " . pg_last_error($conn));
}

if (pg_num_rows($result) > 0) {
    $user = pg_fetch_assoc($result);
} else {
    die("User not found.");
}

// Fetch user's rank
$sql = "
    SELECT COUNT(*) + 1 AS rank
    FROM Users
    WHERE balance > $1
";
$result = pg_query_params($conn, $sql, array($user['balance']));
if (!$result) {
    die("Error in query: " . pg_last_error($conn));
}
$rank_data = pg_fetch_assoc($result);
$user_rank = $rank_data['rank'];

// Fetch leaderboard excluding the current user
$sql = "
    SELECT u.user_id, u.name, u.balance, ul.level_name
    FROM Users u
    JOIN UserLevels ul ON u.balance BETWEEN ul.min_balance AND COALESCE(ul.max_balance, u.balance)
    WHERE u.user_id != $1
    ORDER BY u.balance DESC
    LIMIT 10
";
$result = pg_query_params($conn, $sql, array($user_id));
if (!$result) {
    die("Error in query: " . pg_last_error($conn));
}
$leaderboard = pg_fetch_all($result);

// Fetch available coupons
$sql = "
    SELECT code, valid_from, valid_to, amount
    FROM Coupons
    WHERE is_used = FALSE AND valid_to >= CURRENT_DATE
    ORDER BY created_at DESC
    LIMIT 5
";
$result = pg_query($conn, $sql);
if (!$result) {
    die("Error in query: " . pg_last_error($conn));
}
$coupons = pg_fetch_all($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Betting System</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Dashboard</h2>
            </div>
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><span class="icon">&#8962;</span> Home</a></li>
                <li><a href="profile.php"><span class="icon">&#128100;</span> Profile</a></li>
                <li><a href="wallet.php"><span class="icon">&#128179;</span> Wallet</a></li>
                <li><a href="game.html"><span class="icon">&#127918;</span> Play</a></li>
		<li><a href="transiction.php"><span class="icon">&#128176;</span> Transiction</a></li>
                <li><a href="logout.php"><span class="icon">&#128682;</span> Logout</a></li>
            </ul>
        </div>

        <div class="main-content">
            <div class="container">
                <div class="dashboard-box">
                    <h1>Welcome to Your Dashboard</h1>
                    <div class="dashboard-info">
                        <p>Welcome, <strong><?php echo htmlspecialchars($user['name']); ?></strong>!</p>
                        <p>Your Balance: <strong>₹<?php echo number_format($user['balance'], 2); ?></strong></p>
                    </div>
                    <div class="leaderboard">
                        <h2>Leaderboard</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>Name</th>
                                    <th>Balance</th>
                                    <th>Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $user_rank; ?></td>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td>₹<?php echo number_format($user['balance'], 2); ?></td>
                                    <td>Current Level</td>
                                </tr>
                                <?php
                                $rank = $user_rank + 1; // Start ranking after the current user
                                foreach($leaderboard as $row) {
                                    echo "<tr>
                                        <td>{$rank}</td>
                                        <td>{$row['name']}</td>
                                        <td>₹" .number_format($row['balance'], 2) . "</td>
                                        <td>{$row['level_name']}</td>
                                    </tr>";
                                    $rank++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="coupons">
                        <h2>Available Coupons</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Coupon Code</th>
                                    <th>Amount</th>
                                    <th>Valid From</th>
                                    <th>Valid To</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($coupons as $coupon) {
                                    echo "<tr>
                                        <td>{$coupon['code']}</td>
                                        <td>₹" . number_format($coupon['amount'], 2) . "</td>
                                        <td>" . date('d M Y', strtotime($coupon['valid_from'])) . "</td>
                                        <td>" . date('d M Y', strtotime($coupon['valid_to'])) . "</td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
