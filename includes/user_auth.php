<?php
session_start();

// XSS Protection Helper
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Optional: Get user data from database
include_once("db.php");
$user_id = $_SESSION['user_id'];
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user_data = mysqli_stmt_get_result($stmt)->fetch_assoc();

if(!$user_data) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Store additional user data in session if needed
$_SESSION['fullname'] = $user_data['fullname'];
$_SESSION['email'] = $user_data['email'];
$_SESSION['student_id'] = $user_data['student_id'];
?>