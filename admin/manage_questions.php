<?php
include(__DIR__ . "/../includes/admin_auth.php");

$result =
mysqli_query(
$conn,
"SELECT * FROM questions ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>

<title>Manage Questions</title>

<link href="../assets/css/style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="dashboard-layout">

<?php include("sidebar.php"); ?>

<main class="main-content">

<div class="container-fluid py-4">
<h2 class="mb-4">Manage Questions</h2>

<table class="table table-bordered">

<tr>

<th>ID</th>

<th>Category</th>

<th>Question</th>

<th>Action</th>

</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['category']; ?></td>

<td><?php echo $row['question']; ?></td>

<td>

<a
href="edit_question.php?id=<?php echo $row['id']; ?>"
class="btn btn-warning btn-sm">

Edit

</a>

<a
href="delete_question.php?id=<?php echo $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this question?')">

Delete

</a>

</td>

</tr>

<?php } ?>

</table>

</div>
</main>
</div>

</body>
</html>