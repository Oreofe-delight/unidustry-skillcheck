<?php
include(__DIR__ . "/../includes/admin_auth.php");

$id = intval($_GET['id']);

mysqli_query(
$conn,
"DELETE FROM users WHERE id='$id'"
);

header("Location: students.php");
exit();