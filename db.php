<?php
$host = "localhost";
$port = "5432";
$dbname = "horse";
$user = "postgres";
$password = "root1234"; // Use the new password

// Create a connection string
$conn_string = "host=$host port=$port dbname=$dbname user=$user password=$password";

// Try to establish a connection
$conn = pg_connect($conn_string);

if (!$conn) {
    echo "Error: Unable to connect to PostgreSQL\n";
    echo "Debugging error: " . pg_last_error();
    exit;
}
else
{
//echo "Connected successfully!";
}
?>
