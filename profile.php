<?php
include("includes/user_auth.php");

$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare(
$conn,
"SELECT * FROM users WHERE id=?"
);

mysqli_stmt_bind_param($stmt,"i",$user_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);

if(!$user){
    die("User profile not found.");
}
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

<h2 class="text-center mb-3">My Profile</h2>

<?php
$profile_image = !empty($user['profile_image'])
    ? "uploads/profiles/" . $user['profile_image']
    : "assets/images/devOreofe.jpg";
?>

<!-- PROFILE HEADER -->
<div class="text-center mb-4">

    <img src="<?php echo $profile_image; ?>"
         class="profile-avatar">

    <h4 class="mt-3 mb-0">
        <?php echo h($user['fullname']); ?>
    </h4>

    <small class="text-muted">
        <?php echo h($user['department']); ?>
    </small>

</div>

<hr>

<!-- PERSONAL INFO GRID -->
<div class="row">

    <div class="col-md-6 mb-3">
        <strong>Email</strong>
        <div><?php echo h($user['email']); ?></div>
    </div>

    <div class="col-md-6 mb-3">
        <strong>Student ID</strong>
        <div><?php echo h($user['student_id']); ?></div>
    </div>

    <div class="col-md-6 mb-3">
        <strong>Phone</strong>
        <div><?php echo h($user['phone']); ?></div>
    </div>

    <div class="col-md-6 mb-3">
        <strong>Gender</strong>
        <div><?php echo h($user['gender']); ?></div>
    </div>

    <div class="col-md-6 mb-3">
        <strong>Date of Birth</strong>
        <div><?php echo h($user['dob']); ?></div>
    </div>

</div>

<hr>

<!-- ACADEMIC INFO -->
<h5 class="mb-3">Academic Information</h5>

<div class="row">

    <div class="col-md-6 mb-3">
        <strong>Institution</strong>
        <div><?php echo h($user['institution']); ?></div>
    </div>

    <div class="col-md-6 mb-3">
        <strong>Faculty</strong>
        <div><?php echo h($user['faculty']); ?></div>
    </div>

    <div class="col-md-6 mb-3">
        <strong>Department</strong>
        <div><?php echo h($user['department']); ?></div>
    </div>

    <div class="col-md-6 mb-3">
        <strong>Level</strong>
        <div><?php echo h($user['level']); ?></div>
    </div>

</div>

<hr>

<!-- SKILLS -->
<div class="mb-3">
    <strong>Skill Interests</strong>
    <p class="mt-1">
        <?php echo h($user['skills_interest']); ?>
    </p>
</div>

<a href="dashboard.php"
   class="btn btn-custom w-100 mt-2">

Back to Dashboard

</a>

</div>
</div>

</body>
</html>