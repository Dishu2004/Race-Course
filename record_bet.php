<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized access.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$amount = isset($_POST['bet_amount']) ? floatval($_POST['bet_amount']) : 0;
$horse_id = isset($_POST['horse_id']) ? intval($_POST['horse_id']) : 0;
$num_lap = isset($_POST['num_lap']) ? intval($_POST['num_lap']) : 1;

if ($amount <= 0 || $horse_id <= 0 || $num_lap <= 0) {
    echo json_encode(['error' => 'Invalid input.']);
    exit();
}

// Fetch the current balance
$sql = "SELECT balance FROM Users WHERE user_id = $1";
$stmt = pg_prepare($conn, "get_balance", $sql);
$result = pg_execute($conn, "get_balance", array($user_id));

if ($row = pg_fetch_assoc($result)) {
    $current_balance = $row['balance'];
} else {
    echo json_encode(['error' => 'User not found.']);
    exit();
}

if ($current_balance < $amount) {
    echo json_encode(['error' => 'Not enough funds.']);
    exit();
}

// Simulate the race result
$winningHorse = rand(1, 4); // Replace with actual race result logic
$result = ($winningHorse == $horse_id) ? 'win' : 'lose';

// Calculate the amount after deduction
if ($result === 'win') {
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
pg_execute($conn, "insert_bet", array($user_id, $horse_id, $amount, $result, $amount_after_deduction));

pg_close($conn);

// Return the new balance
echo json_encode(['new_balance' => $new_balance]);
?>
