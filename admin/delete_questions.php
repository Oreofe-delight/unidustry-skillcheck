<?php
include("../includes/admin_auth.php");

if(isset($_GET['id'])){

    $id = intval($_GET['id']);

    $stmt = mysqli_prepare(
    $conn,
    "DELETE FROM questions WHERE id=?"
    );

    mysqli_stmt_bind_param($stmt,"i",$id);

    mysqli_stmt_execute($stmt);
}

header("Location: manage_questions.php");
exit();
?>