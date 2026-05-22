<?php
include("../includes/db.php");

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM questions WHERE id='$id'");

header("Location: manage_questions.php");
?>