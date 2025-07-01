<?php
session_start();
require 'db.php';

// Check if the user is an admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle search
$search_results = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_name = $_POST['search_name'];

    // Search users by name
    $sql = "SELECT * FROM Users WHERE name ILIKE $1"; // Use ILIKE for case-insensitive search
    $stmt = pg_prepare($conn, "search_users", $sql);
    $search_term = "%$search_name%";
    $result = pg_execute($conn, "search_users", array($search_term));

    // Fetch results
    if ($result) {
        while ($row = pg_fetch_assoc($result)) {
            $search_results[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Users</title>
    <link rel="stylesheet" href="search_user.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">Admin Panel</div>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="add_user.php">Add User</a>
        <a href="delete_user.php">Delete User</a>
        <a href="add_coupon.php">Add Coupon</a>
        <a href="search_user.php">Search User</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="content">
        <div class="container">
            <h1>Search Users</h1>
            <form method="POST" action="">
                <input type="text" name="search_name" placeholder="Enter name to search" required>
                <button type="submit">Search</button>
            </form>
            
            <?php if (count($search_results) > 0): ?>
                <h2>Search Results</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Mobile No</th>
                            <th>Username</th>
                            <th>Balance</th>
                            <th>Level</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($search_results as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                <td><?php echo htmlspecialchars($user['mobile_no']); ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['balance']); ?></td>
                                <td><?php echo htmlspecialchars($user['level']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
