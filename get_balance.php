<?php
// get_balance.php
header('Content-Type: application/json');
session_start();

// Include database connection
require 'db.php'; // Ensure this file establishes the PostgreSQL connection

// Check if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Prepare and execute query
$sql = "SELECT balance FROM Users WHERE user_id = $1";
$stmt = pg_prepare($conn, "get_balance", $sql);

if ($stmt === false) {
    echo json_encode(['error' => 'Prepare failed']);
    exit();
}

// Execute the query
$result = pg_execute($conn, "get_balance", array($user_id));

if ($result === false) {
    echo json_encode(['error' => 'Query execution failed']);
    exit();
}

// Fetch the balance
$row = pg_fetch_assoc($result);

if ($row) {
    $balance = $row['balance'];
    echo json_encode(['balance' => $balance]);
} else {
    echo json_encode(['error' => 'User not found']);
}

// Close the connection
pg_close($conn);
?>
