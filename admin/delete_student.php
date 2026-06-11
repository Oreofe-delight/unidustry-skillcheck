<?php
include("../includes/admin_auth.php");

// Check CSRF token
if(!isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Security verification failed.");
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id > 0) {
    // First delete related records
    // Delete user answers
    $answers_stmt = mysqli_prepare($conn, "DELETE FROM user_answers WHERE user_id = ?");
    mysqli_stmt_bind_param($answers_stmt, "i", $id);
    mysqli_stmt_execute($answers_stmt);
    
    // Delete results
    $results_stmt = mysqli_prepare($conn, "DELETE FROM results WHERE user_id = ?");
    mysqli_stmt_bind_param($results_stmt, "i", $id);
    mysqli_stmt_execute($results_stmt);
    
    // Delete feedback
    $feedback_stmt = mysqli_prepare($conn, "DELETE FROM feedback WHERE user_id = ?");
    mysqli_stmt_bind_param($feedback_stmt, "i", $id);
    mysqli_stmt_execute($feedback_stmt);
    
    // Finally delete user
    $user_stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ? AND role = 'student'");
    mysqli_stmt_bind_param($user_stmt, "i", $id);
    mysqli_stmt_execute($user_stmt);
}

header("Location: students.php?msg=deleted");
exit();
?>