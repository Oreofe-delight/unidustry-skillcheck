<?php
include("includes/admin_auth.php");

$questions =
mysqli_query(
$conn,
"SELECT * FROM questions
ORDER BY id DESC"
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

<h2>Manage Questions</h2>

<table class="table table-bordered">

<thead>

<tr>

<th>ID</th>
<th>Category</th>
<th>Question</th>
<th>Correct</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($questions)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td>
<?php echo $row['category']; ?>
</td>

<td>
<?php echo $row['question']; ?>
</td>

<td>
<?php echo $row['correct_answer']; ?>
</td>

<td>

<a
href="delete_question.php?id=<?php echo $row['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete Question?')">

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