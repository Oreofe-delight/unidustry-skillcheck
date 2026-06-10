<?php
include(__DIR__ . "/../includes/admin_auth.php");

$result =
mysqli_query($conn,"
SELECT
results.*,
users.fullname

FROM results

JOIN users
ON users.id = results.user_id

ORDER BY results.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>

<title>Assessment Results</title>

<link href="../assets/css/style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div class="dashboard-layout">

<?php include("sidebar.php"); ?>

<main class="main-content">

<div class="container-fluid py-4">
<h2>Assessment Results</h2>

<table class="table table-bordered">

<tr>

<th>Student</th>
<th>Category</th>
<th>Score</th>
<th>Percentage</th>
<th>Date</th>

</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['fullname']; ?></td>

<td><?php echo ucfirst($row['category']); ?></td>

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
</main>
</div>

</body>
</html>