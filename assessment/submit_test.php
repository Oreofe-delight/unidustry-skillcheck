<?php
session_start();
include("../includes/db.php");

$user_id = $_SESSION['user_id'];
$category = $_POST['category'];
$total = $_POST['total'];

$score = 0;

$result = mysqli_query($conn, "SELECT * FROM questions WHERE category='$category'");

while($row = mysqli_fetch_assoc($result)){

    $qid = $row['id'];
    $correct = $row['correct_answer'];

    $selected = isset($_POST["q$qid"]) ? $_POST["q$qid"] : 0;

    $is_correct = ($selected == $correct) ? 1 : 0;

    if($is_correct){
        $score++;
    }

    // STORE EACH ANSWER
    mysqli_query($conn, "INSERT INTO user_answers 
    (user_id, question_id, selected_option, correct_option, is_correct)
    VALUES ('$user_id','$qid','$selected','$correct','$is_correct')");
}

$percentage = ($score / $total) * 100;

mysqli_query($conn,"
INSERT INTO results
(
user_id,
category,
score,
total_questions,
percentage
)
VALUES
(
'$user_id',
'$category',
'$score',
'$total',
'$percentage'
)
");

$_SESSION['score'] = $score;
$_SESSION['total'] = $total;

header("Location: results.php");
exit();
?>