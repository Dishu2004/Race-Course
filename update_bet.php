<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    die('Unauthorized access.');
}

$user_id = $_SESSION['user_id'];
$amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
$bethorse = isset($_POST['bethorse']) ? intval($_POST['bethorse']) : 0;
$num_lap = isset($_POST['num_lap']) ? intval($_POST['num_lap']) : 1;

// Debugging output
error_log("Amount: " . $amount);
error_log("Bethorse: " . $bethorse);
error_log("Num_lap: " . $num_lap);

if ($amount <= 0 || $bethorse <= 0 || $num_lap <= 0) {
    die('Invalid input.');
}

// Fetch the current balance
$sql = "SELECT balance FROM Users WHERE user_id = $1";
$stmt = pg_prepare($conn, "get_balance", $sql);
$result = pg_execute($conn, "get_balance", array($user_id));

if ($row = pg_fetch_assoc($result)) {
    $current_balance = $row['balance'];
} else {
    die('User not found.');
}

// Check if user has enough balance
if ($current_balance < $amount) {
    die('Not enough funds.');
}

// Simulate the race result
$winningHorse = rand(1, 4); // Replace this with actual race result logic
$result = $winningHorse == $bethorse ? 'win' : 'lose';

// Calculate the amount after deduction
if ($result == 'win') {
    $amount_after_deduction = $amount * 2; // Double the bet amount on win
    $new_balance = $current_balance + $amount_after_deduction;
} else {
    $amount_after_deduction = 0;
    $new_balance = $current_balance - $amount;
}

// Update the user’s balance
$update_sql = "UPDATE Users SET balance = $1 WHERE user_id = $2";
$update_stmt = pg_prepare($conn, "update_balance", $update_sql);
pg_execute($conn, "update_balance", array($new_balance, $user_id));

// Insert the bet record into the Bets table
$bet_sql = "INSERT INTO Bets (user_id, horse_id, bet_amount, bet_date, bet_time, result, amount_after_deduction) 
            VALUES ($1, $2, $3, NOW(), CURRENT_TIME, $4, $5)";
$bet_stmt = pg_prepare($conn, "insert_bet", $bet_sql);
pg_execute($conn, "insert_bet", array($user_id, $bethorse, $amount, $result, $amount_after_deduction));

pg_close($conn);

echo "Bet processed successfully.";
?>
