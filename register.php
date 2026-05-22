<?php
include("includes/db.php");

$message = "";

if (isset($_POST['register'])) {

    $fullname = $_POST['fullname'];
$email = $_POST['email'];
$student_id = $_POST['student_id'];

$phone = $_POST['phone'];
$gender = $_POST['gender'];
$dob = $_POST['dob'];

$institution = $_POST['institution'];
$faculty = $_POST['faculty'];
$department = $_POST['department'];
$level = $_POST['level'];

$skills_interest = $_POST['skills_interest'];

$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE student_id='$student_id'");

    if (mysqli_num_rows($check) > 0) {
        $message = "User already exists!";
    } else {

       mysqli_query($conn, "
INSERT INTO users
(
fullname,
email,
student_id,
phone,
gender,
dob,
institution,
faculty,
department,
level,
skills_interest,
password
)

VALUES
(
'$fullname',
'$email',
'$student_id',
'$phone',
'$gender',
'$dob',
'$institution',
'$faculty',
'$department',
'$level',
'$skills_interest',
'$password'
)
");

        $message = "Registration successful!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(135deg,#4e54c8,#8f94fb);
}
.card{
    border-radius:15px;
}
</style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center vh-100">

<div class="card p-4 shadow" style="width:400px;">

    <div class="text-center mb-3">
        <img src="assets/images/logoa.png" style="height:60px;">
        <h4>Register</h4>
    </div>

    <?php if($message!=""){ ?>
        <div class="alert alert-info"><?php echo $message; ?></div>
    <?php } ?>

    <form method="POST">

        <input type="text" name="fullname" class="form-control mb-3" placeholder="Full Name" required>
        
        <input type="email" name="email" class="form-control mb-3" placeholder="Email Address" required>
        
        <input type="text" name="student_id" class="form-control mb-3" placeholder="Student ID" required>
        
        <input type="text" name="phone" class="form-control mb-3" placeholder="Phone Number">
        
        <select name="gender" class="form-control mb-3">
            
        <option value="">Select Gender</option>
        <option>Male</option>
        <option>Female</option>
        </select>
        
        <input type="date" name="dob" class="form-control mb-3">

        <input type="text" name="institution" class="form-control mb-3" placeholder="Institution">

<input type="text" name="faculty" class="form-control mb-3" placeholder="Faculty">

<input type="text" name="department" class="form-control mb-3" placeholder="Department">

<select name="level" class="form-control mb-3">
    <option value="">Select Level</option>
    <option>100 Level</option>
    <option>200 Level</option>
    <option>300 Level</option>
    <option>400 Level</option>
    <option>500 Level</option>
</select>

<textarea name="skills_interest" class="form-control mb-3"
placeholder="Skills/Tech Interests"></textarea>

<input type="password" name="password"
class="form-control mb-3"
placeholder="Password" required>

        <button name="register" class="btn btn-success w-100">Register</button>

    </form>

    <p class="text-center mt-3">
        Already have an account? <a href="login.php">Login</a>
    </p>

</div>

</div>

</body>
</html>