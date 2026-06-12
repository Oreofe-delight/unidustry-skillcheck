<?php
include("../includes/admin_auth.php");

// Check CSRF token
if(!isset($_GET['csrf_token']) || $_GET['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Security verification failed.");
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id > 0) {
    $stmt = mysqli_prepare($conn, "DELETE FROM questions WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}

header("Location: manage_questions.php?msg=deleted");
exit();
?>