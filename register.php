<?php
session_start();

// Helper function
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include("includes/db.php");

$message = "";
$message_type = "danger";

if (isset($_POST['register'])) {
    
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = "Security verification failed. Please try again.";
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
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Validation
        $errors = [];
        if (empty($fullname)) $errors[] = "Full name is required";
        if (empty($email)) $errors[] = "Email is required";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
        if (empty($student_id)) $errors[] = "Student ID is required";
        if (empty($password_plain)) $errors[] = "Password is required";
        if (strlen($password_plain) < 8) $errors[] = "Password must be at least 8 characters";
        if ($password_plain !== $confirm_password) $errors[] = "Passwords do not match";
        
        if (!empty($errors)) {
            $message = implode("<br>", $errors);
        } else {
            // Check existing user
            $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE student_id = ? OR email = ?");
            mysqli_stmt_bind_param($stmt, "ss", $student_id, $email);
            mysqli_stmt_execute($stmt);
            $check = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($check) > 0) {
                $message = "User already exists with this Student ID or Email!";
            } else {
                $password = password_hash($password_plain, PASSWORD_DEFAULT);
                $role = 'student'; // Default role
                
                $stmt_insert = mysqli_prepare($conn, "
                    INSERT INTO users (fullname, email, student_id, phone, gender, dob, institution, faculty, department, level, skills_interest, password, role) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                mysqli_stmt_bind_param($stmt_insert, "sssssssssssss", 
                    $fullname, $email, $student_id, $phone, $gender, $dob, 
                    $institution, $faculty, $department, $level, $skills_interest, $password, $role
                );
                
                if (mysqli_stmt_execute($stmt_insert)) {
                    $message = "Registration successful! Redirecting to login...";
                    $message_type = "success";
                    echo '<meta http-equiv="refresh" content="3;url=login.php">';
                } else {
                    $message = "Error occurred during registration. Please try again.";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Skill Assessment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .auth-card {
            max-width: 900px;
            width: 100%;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 40px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.15);
        }
        .auth-wrapper {
            min-height: 100vh;
            padding: 40px 20px;
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
        }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px;
        }
        .password-strength {
            height: 4px;
            margin-top: 5px;
            border-radius: 2px;
            transition: all 0.3s;
        }
    </style>
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card mx-auto">
        <div class="text-center mb-4">
            <img src="assets/images/logo-full.png" style="height: 70px;">
            <h3 class="mt-3">Create Account</h3>
            <p class="text-muted">Join Unidustry SkillCheck to assess your skills</p>
        </div>
        
        <?php if($message != ""){ ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show">
                <i class="fas fa-<?php echo $message_type == 'success' ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php } ?>
        
        <form method="POST" id="registerForm">
            <input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token']); ?>">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="fullname" class="form-control" placeholder="Enter your full name" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email Address *</label>
                    <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Student ID *</label>
                    <input type="text" name="student_id" class="form-control" placeholder="e.g., 2020/XXXXXX" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" name="phone" class="form-control" placeholder="e.g., 080XXXXXXXX">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="">Select Gender</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Prefer not to say</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="dob" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Institution</label>
                    <input type="text" name="institution" class="form-control" placeholder="University of Ilorin">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Faculty</label>
                    <input type="text" name="faculty" class="form-control" placeholder="Communication and Information Sciences">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Department</label>
                    <input type="text" name="department" class="form-control" placeholder="Computer Science">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Level</label>
                    <select name="level" class="form-select">
                        <option value="">Select Level</option>
                        <option>100 Level</option>
                        <option>200 Level</option>
                        <option>300 Level</option>
                        <option>400 Level</option>
                        <option>500 Level</option>
                    </select>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Skills / Tech Interests</label>
                    <textarea name="skills_interest" class="form-control" rows="2" placeholder="e.g., PHP, Python, JavaScript, Database Management"></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Minimum 8 characters" required>
                    <div class="password-strength" id="passwordStrength"></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Confirm Password *</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Re-enter password" required>
                </div>
                <div class="col-12 mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="termsCheck" required>
                        <label class="form-check-label" for="termsCheck">
                            I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                        </label>
                    </div>
                </div>
            </div>
            
            <button type="submit" name="register" class="btn btn-custom w-100 py-2">
                <i class="fas fa-user-plus me-2"></i> Register
            </button>
        </form>
        
        <p class="text-center mt-4">
            Already have an account? <a href="login.php" class="fw-bold">Login here</a>
        </p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Password strength meter
document.getElementById('password')?.addEventListener('input', function() {
    const password = this.value;
    const strengthDiv = document.getElementById('passwordStrength');
    let strength = 0;
    if(password.length >= 8) strength++;
    if(password.match(/[a-z]+/)) strength++;
    if(password.match(/[A-Z]+/)) strength++;
    if(password.match(/[0-9]+/)) strength++;
    if(password.match(/[$@#&!]+/)) strength++;
    
    const colors = ['#dc3545', '#ffc107', '#28a745'];
    const widths = ['25%', '50%', '100%'];
    if(strength <= 2) {
        strengthDiv.style.backgroundColor = colors[0];
        strengthDiv.style.width = widths[0];
    } else if(strength <= 4) {
        strengthDiv.style.backgroundColor = colors[1];
        strengthDiv.style.width = widths[1];
    } else {
        strengthDiv.style.backgroundColor = colors[2];
        strengthDiv.style.width = widths[2];
    }
});

// Confirm password validation
document.getElementById('registerForm')?.addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    const terms = document.getElementById('termsCheck').checked;
    
    if(password !== confirm) {
        e.preventDefault();
        alert('Passwords do not match!');
        return false;
    }
    if(!terms) {
        e.preventDefault();
        alert('Please agree to the Terms of Service');
        return false;
    }
    return true;
});
</script>
</body>
</html>