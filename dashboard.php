<?php
include("includes/user_auth.php");
?>

<!DOCTYPE html>
<html>
<head>

<title>Dashboard</title>

<link href="assets/css/style.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container py-5">

<h2>
Welcome,
<?php echo $_SESSION['fullname']; ?>
</h2>

<div class="row mt-4">

<div class="col-md-4 mb-4">

<div class="card p-4">

<h4>Technical Test</h4>

<a href="assessment/technical.php"
class="btn btn-primary">

Start

</a>

</div>

</div>

<div class="col-md-4 mb-4">

<div class="card p-4">

<h4>Soft Skills</h4>

<a href="assessment/softskills.php"
class="btn btn-success">

Start

</a>

</div>

</div>

<div class="col-md-4 mb-4">

<div class="card p-4">

<h4>Profile</h4>

<a href="profile.php"
class="btn btn-dark">

View Profile

</a>

</div>

</div>

</div>

<a href="logout.php"
class="btn btn-danger">

Logout

</a>

</div>

</body>
</html>