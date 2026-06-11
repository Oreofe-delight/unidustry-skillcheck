<?php
session_start();
include("../includes/db.php");

$user_id = $_SESSION['user_id'];
$category = $_POST['category'];
$total = $_POST['total'];

$score = 0;

$recommendations = [];

$result = mysqli_query($conn, "SELECT * FROM questions WHERE category='$category'");

while($row = mysqli_fetch_assoc($result)){

    $qid = $row['id'];
    $correct = $row['correct_answer'];

    $selected = isset($_POST["q$qid"]) ? $_POST["q$qid"] : 0;

    $is_correct = ($selected == $correct) ? 1 : 0;

    if(!$is_correct){

    $recommendations[] = [

        'question' => $row['question'],

        'type' => $row['recommendation_type'],

        'title' => $row['recommendation_title'],

        'link' => $row['recommendation_link']

    ];
}

    if($is_correct){
        $score++;
    }

    // STORE EACH ANSWER
    mysqli_query($conn, "INSERT INTO user_answers 
    (user_id, question_id, selected_option, correct_option, is_correct)
    VALUES ('$user_id','$qid','$selected','$correct','$is_correct')");
}

$percentage = ($score / $total) * 100;

// ✅ ADDED: Insert into results with created_at
mysqli_query($conn,"
INSERT INTO results
(
user_id,
category,
score,
total_questions,
percentage,
created_at        
)
VALUES
(
'$user_id',
'$category',
'$score',
'$total',
'$percentage',
NOW()               
)
");

$_SESSION['score'] = $score;
$_SESSION['total'] = $total;

$_SESSION['recommendations'] = $recommendations;

header("Location: result.php");  // Note: changed from results.php to result.php
exit();
?>