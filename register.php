<?php
include("includes/db.php");

$message = "";

if (isset($_POST['register'])) {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = "CSRF verification failed.";
    } else {
        $fullname = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $student_id = trim($_POST['student_id'] ?? '');

        $phone = trim($_POST['phone'] ?? '');
        $gender = trim($_POST['gender'] ?? '');
        $dob = trim($_POST['dob'] ?? '');

        $institution = trim($_POST['institution'] ?? '');
        $faculty = trim($_POST['faculty'] ?? '');
        $department = trim($_POST['department'] ?? '');
        $level = trim($_POST['level'] ?? '');

        $skills_interest = trim($_POST['skills_interest'] ?? '');
        $password_plain = $_POST['password'] ?? '';

        if (empty($fullname) || empty($email) || empty($student_id) || empty($password_plain)) {
            $message = "Please fill in all required fields.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Invalid email format.";
        } elseif (strlen($password_plain) < 8) {
            $message = "Password must be at least 8 characters long.";
        } else {
            // Check if user already exists by student_id or email
            $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE student_id = ? OR email = ?");
            mysqli_stmt_bind_param($stmt, "ss", $student_id, $email);
            mysqli_stmt_execute($stmt);
            $check = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($check) > 0) {
                $message = "User already exists with this Student ID or Email!";
            } else {
                $password = password_hash($password_plain, PASSWORD_DEFAULT);

                $stmt_insert = mysqli_prepare($conn, "
                    INSERT INTO users 
                    (fullname, email, student_id, phone, gender, dob, institution, faculty, department, level, skills_interest, password) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                mysqli_stmt_bind_param($stmt_insert, "ssssssssssss", 
                    $fullname, $email, $student_id, $phone, $gender, $dob, 
                    $institution, $faculty, $department, $level, $skills_interest, $password
                );

                if (mysqli_stmt_execute($stmt_insert)) {
                    $message = "Registration successful!";
                } else {
                    $message = "Error occurred during registration. Please try again.";
                }
            }
        }
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
        <div class="alert alert-info"><?php echo h($message); ?></div>
    <?php } ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token']); ?>">

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