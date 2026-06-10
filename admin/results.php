<?php
include("../includes/admin_auth.php");

$query = mysqli_query($conn,"
SELECT
results.*,
users.fullname,
users.student_id
FROM results

INNER JOIN users
ON results.user_id = users.id

ORDER BY results.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>

<title>Assessment Results</title>

<link href="../assets/css/style.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body class="admin-page">

<div class="dashboard-layout">

<?php include("sidebar.php"); ?>

<main class="main-content">

<div class="container-fluid py-4">

<div class="admin-card">

<div class="admin-header">

<h2>
Assessment Results
</h2>

<p>
View all student assessment attempts
</p>

</div>

<table class="table admin-table">

<thead>

<tr>

<th>Student</th>
<th>Student ID</th>
<th>Category</th>
<th>Score</th>
<th>Percentage</th>
<th>Date</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($query)){ ?>

<tr>

<td>
<?php echo $row['fullname']; ?>
</td>

<td>
<?php echo $row['student_id']; ?>
</td>

<td>
<?php echo ucfirst($row['category']); ?>
</td>

<td>

<?php echo $row['score']; ?>

/

<?php echo $row['total_questions']; ?>

</td>

<td>

<?php echo round($row['percentage']); ?>%

</td>

<td>

<?php echo date(
"d M Y",
strtotime($row['created_at'])
);
?>

</td>

<td>

<a
href="view_result.php?id=<?php echo $row['id']; ?>"
class="btn btn-primary btn-sm">

View

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</main>

</div>

</body>
</html>