<?php
session_start();

// Helper function for XSS protection
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include("includes/db.php");

$message = "";
$message_type = "danger";

if(isset($_POST['login'])){
    
    // Check CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = "Security verification failed. Please try again.";
    } else {
        $email_or_id = trim($_POST['email_or_id']);
        $password = $_POST['password'];
        
        // Validate input not empty
        if(empty($email_or_id) || empty($password)) {
            $message = "Please enter both email/ID and password.";
        } else {
            // Use prepared statement with case-insensitive matching for student_id
            // This handles different ID formats from different institutions
            $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ? OR LOWER(student_id) = LOWER(?)");
            mysqli_stmt_bind_param($stmt, "ss", $email_or_id, $email_or_id);
            mysqli_stmt_execute($stmt);
            $query = mysqli_stmt_get_result($stmt);
            
            if(mysqli_num_rows($query) > 0){
                $user = mysqli_fetch_assoc($query);
                
                // Check if account is locked
                $is_locked = false;
                if(!empty($user['locked_until']) && new DateTime() < new DateTime($user['locked_until'])) {
                    $is_locked = true;
                    $message = "Account is temporarily locked. Please try again after " . date('g:i A', strtotime($user['locked_until']));
                }
                
                if(!$is_locked && password_verify($password, $user['password'])){
                    // Successful login - reset attempts
                    $reset_stmt = mysqli_prepare($conn, "UPDATE users SET login_attempts = 0, locked_until = NULL WHERE id = ?");
                    mysqli_stmt_bind_param($reset_stmt, "i", $user['id']);
                    mysqli_stmt_execute($reset_stmt);
                    
                    // Regenerate session ID to prevent Session Fixation
                    session_regenerate_id(true);
                    
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['fullname'] = $user['fullname'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['student_id'] = $user['student_id'];
                    $_SESSION['login_time'] = time(); 
                    
                    // Handle "Remember Me"
                    if(isset($_POST['remember'])) {
                        $token = bin2hex(random_bytes(32));
                        setcookie('remember_token', $token, time() + (86400 * 30), "/");
                        $token_stmt = mysqli_prepare($conn, "UPDATE users SET remember_token = ? WHERE id = ?");
                        mysqli_stmt_bind_param($token_stmt, "si", $token, $user['id']);
                        mysqli_stmt_execute($token_stmt);
                    }
                    
                    // Redirect based on role
                    if($user['role'] == 'admin'){
                        header("Location: admin/dashboard.php");
                    } else {
                        header("Location: dashboard.php");
                    }
                    exit();
                } else {
                    // Failed login - increment attempts
                    $new_attempts = ($user['login_attempts'] ?? 0) + 1;
                    if($new_attempts >= 5) {
                        $locked_time = date('Y-m-d H:i:s', strtotime('+15 minutes'));
                        $update_stmt = mysqli_prepare($conn, "UPDATE users SET login_attempts = ?, locked_until = ? WHERE id = ?");
                        mysqli_stmt_bind_param($update_stmt, "isi", $new_attempts, $locked_time, $user['id']);
                        $message = "Too many failed attempts. Account locked for 15 minutes.";
                    } else {
                        $update_stmt = mysqli_prepare($conn, "UPDATE users SET login_attempts = ? WHERE id = ?");
                        mysqli_stmt_bind_param($update_stmt, "ii", $new_attempts, $user['id']);
                        $message = "Incorrect password. " . (5 - $new_attempts) . " attempts remaining.";
                    }
                    mysqli_stmt_execute($update_stmt);
                }
            } else {
                $message = "Account not found. Please check your Email/Student ID or register.";
                $message_type = "warning";
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
    <title>Login | Computing Skill Assessment System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="assets/css/style.css" rel="stylesheet">
    
    <style>
        .auth-card {
            max-width: 450px;
            width: 100%;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 40px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .input-group-text {
            background: transparent;
            border-right: none;
        }
        
        .form-control-with-icon {
            border-left: none;
        }
        
        .form-control-with-icon:focus {
            border-color: #6c63ff;
            box-shadow: none;
        }
        
        .btn-custom {
            background: linear-gradient(90deg, #4e54c8, #6c63ff);
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px;
            border-radius: 10px;
        }
        
        .btn-custom:hover {
            opacity: 0.9;
            color: white;
        }
        
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #4e54c8, #8f94fb);
        }
    </style>
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        
        <div class="text-center mb-4">
            <img src="assets/images/logo-full.png"
                 alt="SkillCheck Logo"
                 style="height: 80px;">
            <h2 class="mt-3 fw-bold" style="color: #4e54c8;">
                Welcome Back
            </h2>
            <p class="text-muted">Login with your Email or Student ID</p>
        </div>
        
        <?php if($message != ""){ ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <i class="fas fa-<?php echo ($message_type == 'danger' ? 'exclamation-triangle' : 'info-circle'); ?> me-2"></i>
                <?php echo h($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>
        
        <form method="POST" action="" id="loginForm">
            <input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token']); ?>">
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Email or Student ID</label>
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="fas fa-envelope text-muted"></i>
                    </span>
                    <input type="text"
                           name="email_or_id"
                           class="form-control form-control-with-icon"
                           placeholder="Email or Student ID"
                           value="<?php echo isset($_POST['email_or_id']) ? h($_POST['email_or_id']) : ''; ?>"
                           required>
                </div>
                <small class="text-muted">Enter your registered email address OR Student ID</small>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-white">
                        <i class="fas fa-lock text-muted"></i>
                    </span>
                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control form-control-with-icon"
                           placeholder="••••••••"
                           required>
                    <button type="button" class="btn btn-outline-secondary" id="togglePassword" style="border-left: none;">
                        <i class="far fa-eye-slash"></i>
                    </button>
                </div>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="rememberCheck">
                    <label class="form-check-label" for="rememberCheck">
                        Remember Me
                    </label>
                </div>
                <a href="forgot_password.php" class="text-decoration-none" style="color: #6c63ff;">
                    Forgot Password?
                </a>
            </div>
            
            <button type="submit" 
                    name="login" 
                    class="btn btn-custom w-100 py-2 fw-bold">
                <i class="fas fa-sign-in-alt me-2"></i> Login
            </button>
        </form>
        
        <div class="text-center mt-4 pt-2 border-top">
            <p class="mb-0 text-muted">
                Don't have an account? 
                <a href="register.php" class="fw-semibold text-decoration-none" style="color: #6c63ff;">
                    Register here
                </a>
            </p>
        </div>
        
        <div class="text-center mt-3">
            <small class="text-muted">
                <i class="fas fa-shield-alt me-1"></i> Secure assessment platform
            </small>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    
    if(togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }
    
    // Client-side form validation
    document.getElementById('loginForm')?.addEventListener('submit', function(e) {
        const emailInput = document.querySelector('input[name="email_or_id"]');
        const passwordInput = document.querySelector('input[name="password"]');
        
        if(emailInput.value.trim() === '') {
            e.preventDefault();
            alert('Please enter your email or student ID');
            emailInput.focus();
            return false;
        }
        
        if(passwordInput.value === '') {
            e.preventDefault();
            alert('Please enter your password');
            passwordInput.focus();
            return false;
        }
        
        return true;
    });
</script>

</body>
</html>