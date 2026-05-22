<?php
include("../includes/user_auth.php");

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "
SELECT q.question, q.option1, q.option2, q.option3, q.option4,
       q.correct_option, q.resource_link,
       ua.selected_option, ua.is_correct
FROM user_answers ua
JOIN questions q ON ua.question_id = q.id
WHERE ua.user_id = '$user_id'
ORDER BY ua.id DESC
LIMIT 10
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Detailed Results</title>

<link href="../assets/css/style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="auth-wrapper">
<div class="card">

<h3 class="mb-4 text-center">Detailed Feedback</h3>

<?php while($row = mysqli_fetch_assoc($query)){ ?>

<div class="mb-4 p-3 border rounded">

<p><strong><?php echo $row['question']; ?></strong></p>

<?php
$options = [
    1 => $row['option1'],
    2 => $row['option2'],
    3 => $row['option3'],
    4 => $row['option4']
];

foreach($options as $key => $value){
    
    $style = "";

    if($key == $row['correct_option']){
        $style = "color:green; font-weight:bold;";
    }

    if($key == $row['selected_option'] && !$row['is_correct']){
        $style = "color:red; font-weight:bold;";
    }

    echo "<p style='$style'> $value </p>";
}
?>

<?php if(!$row['is_correct']){ ?>
    <p class="text-danger">Incorrect ❌</p>
    <a href="<?php echo $row['resource_link']; ?>" target="_blank">
        Learn more here
    </a>
<?php } else { ?>
    <p class="text-success">Correct ✅</p>
<?php } ?>

</div>

<?php } ?>

<a href="../dashboard.php" class="btn btn-custom w-100">Back to Dashboard</a>

</div>
</div>

</body>
</html>