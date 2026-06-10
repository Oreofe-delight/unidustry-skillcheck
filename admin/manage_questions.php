<?php
include("../includes/admin_auth.php");

$result = mysqli_query(
$conn,
"SELECT * FROM questions ORDER BY id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>

<title>Manage Questions</title>

<link href="../assets/css/style.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container py-5">

<div class="d-flex justify-content-between mb-4">

<h2>Manage Questions</h2>

<a href="add_question.php"
class="btn btn-success">

Add Question

</a>

</div>

<table class="table table-bordered">

<thead>

<tr>

<th>ID</th>
<th>Category</th>
<th>Question</th>
<th>Actions</th>

</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['category']; ?></td>

<td><?php echo h($row['question']); ?></td>

<td>

<a href="edit_question.php?id=<?php echo $row['id']; ?>"
class="btn btn-primary btn-sm">

Edit

</a>

<a href="delete_question.php?id=<?php echo $row['id']; ?>"
class="btn btn-danger btn-sm">

Delete

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</body>
</html>