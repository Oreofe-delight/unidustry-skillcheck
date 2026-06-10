<?php
include("../includes/admin_auth.php");

$result_id = intval($_GET['id']);

$result =
mysqli_fetch_assoc(
mysqli_query($conn,"
SELECT
results.*,
users.fullname,
users.student_id
FROM results

INNER JOIN users
ON users.id = results.user_id

WHERE results.id='$result_id'
")
);

$user_id = $result['user_id'];

$answers = mysqli_query($conn,"
SELECT
user_answers.*,
questions.question

FROM user_answers

INNER JOIN questions
ON questions.id=user_answers.question_id

WHERE user_answers.user_id='$user_id'

ORDER BY user_answers.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>

<title>Result Details</title>

<link href="../assets/css/style.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body class="admin-page">

<div class="dashboard-layout">

<?php include("sidebar.php"); ?>

<main class="main-content">

<div class="container-fluid py-4">

<div class="admin-card">

<h2>
Result Details
</h2>

<hr>

<p>

<strong>Name:</strong>

<?php echo $result['fullname']; ?>

</p>

<p>

<strong>Student ID:</strong>

<?php echo $result['student_id']; ?>

</p>

<p>

<strong>Category:</strong>

<?php echo ucfirst($result['category']); ?>

</p>

<p>

<strong>Score:</strong>

<?php echo $result['score']; ?>

/

<?php echo $result['total_questions']; ?>

</p>

<p>

<strong>Percentage:</strong>

<?php echo round($result['percentage']); ?>%

</p>

<hr>

<h4>
Question Analysis
</h4>

<table class="table admin-table">

<tr>

<th>Question</th>
<th>Status</th>

</tr>

<?php while($a=mysqli_fetch_assoc($answers)){ ?>

<tr>

<td>
<?php echo $a['question']; ?>
</td>

<td>

<?php
if($a['is_correct']){
echo "<span class='badge bg-success'>Correct</span>";
}else{
echo "<span class='badge bg-danger'>Wrong</span>";
}
?>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</main>

</div>

</body>
</html>