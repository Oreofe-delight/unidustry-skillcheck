<?php
include(__DIR__ . "/../includes/admin_auth.php");

$search = $_GET['search'] ?? '';

if($search != ''){

    $query = mysqli_query($conn,"
    SELECT *
    FROM users
    WHERE role='student'
    AND (
        fullname LIKE '%$search%'
        OR email LIKE '%$search%'
        OR student_id LIKE '%$search%'
    )
    ORDER BY id DESC
    ");

}else{

    $query = mysqli_query($conn,"
    SELECT *
    FROM users
    WHERE role='student'
    ORDER BY id DESC
    ");
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Students</title>

<link href="../assets/css/style.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container py-5">

<h2 class="mb-4">
Students Management
</h2>

<form method="GET" class="mb-4">

<div class="row">

<div class="col-md-10">

<input
type="text"
name="search"
class="form-control"
placeholder="Search Student Name, Email or Student ID">

</div>

<div class="col-md-2">

<button
class="btn btn-custom text-white w-100">

Search

</button>

</div>

</div>

</form>

<table class="table table-bordered">

<thead>

<tr>

<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Student ID</th>
<th>Action</th>

</tr>

</thead>

<tbody>

<?php while($student=mysqli_fetch_assoc($query)){ ?>

<tr>

<td>
<?php echo $student['id']; ?>
</td>

<td>
<?php echo $student['fullname']; ?>
</td>

<td>
<?php echo $student['email']; ?>
</td>

<td>
<?php echo $student['student_id']; ?>
</td>

<td>

<a
href="student_details.php?id=<?php echo $student['id']; ?>"
class="btn btn-primary btn-sm">

View

</a>

<a
href="delete_student.php?id=<?php echo $student['id']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this student?')">

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