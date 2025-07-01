<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $coupon_code = trim($_POST['coupon_code']); // Trim whitespace

    // Check if the coupon is valid and not used
    $sql = "SELECT * FROM Coupons WHERE code = $1 AND valid_from <= CURRENT_DATE AND valid_to >= CURRENT_DATE AND is_used = FALSE";
    $stmt = pg_prepare($conn, "check_coupon", $sql);
    $result = pg_execute($conn, "check_coupon", array($coupon_code));

    if ($row = pg_fetch_assoc($result)) {
        $amount = $row['amount'];

        // Update user wallet
        $update_sql = "UPDATE Users SET balance = balance + $1 WHERE user_id = $2";
        $update_stmt = pg_prepare($conn, "update_wallet", $update_sql);
        $update_result = pg_execute($conn, "update_wallet", array($amount, $user_id));

        if ($update_result) {
            // Mark coupon as used
            $update_coupon_sql = "UPDATE Coupons SET is_used = TRUE WHERE code = $1";
            $update_coupon_stmt = pg_prepare($conn, "update_coupon", $update_coupon_sql);
            $update_coupon_result = pg_execute($conn, "update_coupon", array($coupon_code));

            if ($update_coupon_result) {
                echo "<script>alert('Coupon applied successfully! Amount added to your wallet.'); window.location.href = 'wallet.php';</script>";
            } else {
                echo "<script>alert('Failed to mark coupon as used.'); window.location.href = 'wallet.php';</script>";
            }
        } else {
            echo "<script>alert('Failed to update wallet balance.'); window.location.href = 'wallet.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid or expired coupon code.'); window.location.href = 'wallet.php';</script>";
    }
}
?>
