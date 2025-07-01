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


// Fetch transaction history
$transaction_sql = "SELECT bet_id, horse_id, bet_amount, bet_date, bet_time, result, amount_after_deduction 
                    FROM bets WHERE user_id = $1 ORDER BY bet_date DESC, bet_time DESC LIMIT 20";
$transaction_result = pg_query_params($conn, $transaction_sql, array($user_id));
$transactions = pg_fetch_all($transaction_result);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Betting System</title>
    <link rel="stylesheet" href="dashboard.css">
 <style>
.transaction-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.transaction-table th, .transaction-table td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

.transaction-table th {
    background-color: #28a745;
    color: #fff;
}

.transaction-table tr:nth-child(even) {
    background-color: #f4f4f4;
}

.transaction-table tr:hover {
    background-color: #ddd;
}

.win {
    color: green;
    font-weight: bold;
}

.lose {
    color: red;
    font-weight: bold;
}
</style>
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
                    

                      <h2>Transaction History</h2>
        <table class="transaction-table">
            <tr>
                <th>Bet ID</th>
                <th>Horse ID</th>
                <th>Bet Amount</th>
                <th>Date</th>
                <th>Time</th>
                <th>Result</th>
                <th>Final Amount</th>
            </tr>
            <?php if ($transactions): ?>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($transaction['bet_id']); ?></td>
                        <td><?php echo htmlspecialchars($transaction['horse_id']); ?></td>
                        <td>₹<?php echo number_format($transaction['bet_amount'], 2); ?></td>
                        <td><?php echo date('Y-m-d', strtotime($transaction['bet_date'])); ?></td>
                        <td><?php echo date('H:i:s', strtotime($transaction['bet_time'])); ?></td>
                        <td class="<?php echo ($transaction['result'] == 'win') ? 'win' : 'lose'; ?>">
                            <?php echo ucfirst($transaction['result']); ?>
                        </td>
                        <td>₹<?php echo number_format($transaction['amount_after_deduction'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No transactions found.</td>
                </tr>
            <?php endif; ?>
        </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
