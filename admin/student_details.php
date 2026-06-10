<?php
include(__DIR__ . "/../includes/admin_auth.php");

$id = intval($_GET['id']);

$user =
mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT * FROM users WHERE id='$id'"
));

$results =
mysqli_query($conn,"
SELECT *
FROM results
WHERE user_id='$id'
ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>

<title>Student Profile</title>

<link href="../assets/css/style.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container py-5">

<div class="card">

<h2>
<?php echo $user['fullname']; ?>
</h2>

<hr>

<p>
<strong>Email:</strong>
<?php echo $user['email']; ?>
</p>

<p>
<strong>Student ID:</strong>
<?php echo $user['student_id']; ?>
</p>

<p>
<strong>Institution:</strong>
<?php echo $user['institution']; ?>
</p>

<p>
<strong>Faculty:</strong>
<?php echo $user['faculty']; ?>
</p>

<p>
<strong>Department:</strong>
<?php echo $user['department']; ?>
</p>

<hr>

<h4>
Assessment History
</h4>

<table class="table">

<tr>

<th>Category</th>
<th>Score</th>
<th>Percentage</th>
<th>Date</th>

</tr>

<?php while($row=mysqli_fetch_assoc($results)){ ?>

<tr>

<td>
<?php echo ucfirst($row['category']); ?>
</td>

<td>
<?php echo $row['score']; ?>
/
<?php echo $row['total_questions']; ?>
</td>

<td>
<?php echo $row['percentage']; ?>%
</td>

<td>
<?php echo $row['created_at']; ?>
</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>