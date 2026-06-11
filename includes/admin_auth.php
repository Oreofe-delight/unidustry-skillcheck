<?php
session_start();
include_once __DIR__ . "/db.php";

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Check if user has admin role from session
if($_SESSION['role'] != 'admin') {
    // Student trying to access admin area - destroy session and redirect
    session_destroy();
    header("Location: ../login.php?error=unauthorized");
    exit();
}

// EXTRA SECURITY: Verify role from database (prevents session tampering)
$check_stmt = mysqli_prepare($conn, "SELECT role FROM users WHERE id = ?");
mysqli_stmt_bind_param($check_stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);
$user_data = mysqli_fetch_assoc($check_result);

if(!$user_data || $user_data['role'] != 'admin') {
    // Database says user is not admin - force logout
    session_destroy();
    header("Location: ../login.php?error=unauthorized");
    exit();
}

// Optional: Get admin details for use in admin pages
$admin_stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ? AND role = 'admin'");
mysqli_stmt_bind_param($admin_stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($admin_stmt);
$admin_result = mysqli_stmt_get_result($admin_stmt);
$admin = mysqli_fetch_assoc($admin_result);

// Store admin name in session if not already set
if(!isset($_SESSION['fullname']) && $admin) {
    $_SESSION['fullname'] = $admin['fullname'];
}
?>