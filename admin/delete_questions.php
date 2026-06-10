<?php

include("../includes/admin_auth.php");

$id = intval($_GET['id']);

mysqli_query(
$conn,
"DELETE FROM questions
WHERE id='$id'"
);

header(
"Location: manage_questions.php"
);