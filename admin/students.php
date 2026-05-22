<?php
include("../includes/admin_auth.php");

/* FETCH STUDENTS */
$students = mysqli_query($conn,
"SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>

<title>Manage Students</title>

<link href="../assets/css/style.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container py-5">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2>Registered Students</h2>

<a href="dashboard.php" class="btn btn-dark">
Back to Dashboard
</a>

</div>

<!-- SEARCH BOX -->
<input type="text"
id="searchInput"
class="form-control mb-4"
placeholder="Search student...">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead class="table-dark">

<tr>

<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Student ID</th>
<th>Institution</th>
<th>Department</th>
<th>Level</th>
<th>Phone</th>

</tr>

</thead>

<tbody id="studentTable">

<?php while($row = mysqli_fetch_assoc($students)){ ?>

<tr>

<td><?php echo $row['id']; ?></td>

<td><?php echo $row['fullname']; ?></td>

<td><?php echo $row['email']; ?></td>

<td><?php echo $row['student_id']; ?></td>

<td><?php echo $row['institution']; ?></td>

<td><?php echo $row['department']; ?></td>

<td><?php echo $row['level']; ?></td>

<td><?php echo $row['phone']; ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

<!-- SIMPLE SEARCH SCRIPT -->
<script>

const searchInput = document.getElementById("searchInput");

searchInput.addEventListener("keyup", function(){

let filter = searchInput.value.toLowerCase();

let rows = document.querySelectorAll("#studentTable tr");

rows.forEach(row => {

let text = row.innerText.toLowerCase();

row.style.display = text.includes(filter)
? ""
: "none";

});

});

</script>

</body>
</html>