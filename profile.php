<?php
include("includes/user_auth.php");

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn,
"SELECT * FROM users WHERE id='$user_id'");

$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>

<title>My Profile</title>

<link href="assets/css/style.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="auth-wrapper">

<div class="card">

<h2 class="mb-4 text-center">My Profile</h2>

<hr>

<h4><?php echo $user['fullname']; ?></h4>

<p>
<strong>Email:</strong>
<?php echo $user['email']; ?>
</p>

<p>
<strong>Student ID:</strong>
<?php echo $user['student_id']; ?>
</p>

<p>
<strong>Phone:</strong>
<?php echo $user['phone']; ?>
</p>

<p>
<strong>Gender:</strong>
<?php echo $user['gender']; ?>
</p>

<p>
<strong>Date of Birth:</strong>
<?php echo $user['dob']; ?>
</p>

<hr>

<h5>Academic Information</h5>

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

<p>
<strong>Level:</strong>
<?php echo $user['level']; ?>
</p>

<p>
<strong>Skill Interests:</strong>
<?php echo $user['skills_interest']; ?>
</p>

<a href="dashboard.php"
class="btn btn-custom w-100 mt-3">
Back to Dashboard
</a>

</div>
</div>

</body>
</html>