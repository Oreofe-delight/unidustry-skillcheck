<?php
include("includes/db.php");

$message = "";

if(isset($_POST['login'])){

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = "CSRF verification failed.";
    } else {
        $email_or_id = $_POST['email_or_id'];
        $password = $_POST['password'];

        $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ? OR student_id = ?");
        mysqli_stmt_bind_param($stmt, "ss", $email_or_id, $email_or_id);
        mysqli_stmt_execute($stmt);
        $query = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($query) > 0){

            $user = mysqli_fetch_assoc($query);

            if(password_verify($password, $user['password'])){

                // Regenerate session ID to prevent Session Fixation
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['fullname'] = $user['fullname'];

                if($user['role'] == 'admin'){

                    header("Location: admin/dashboard.php");

                } else {

                    header("Location: dashboard.php");

                }

                exit();

            } else {

                $message = "Incorrect Password";

            }

        } else {

            $message = "Account Not Found";

        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Login</title>

<link href="assets/css/style.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="auth-wrapper">

<div class="card">

<div class="text-center mb-4">

    <img src="assets/images/logo-full.png"
         alt="SkillCheck Logo"
         style="height:80px;">

    <h2 class="mt-2">
        Login
    </h2>

</div>

<?php if($message != ""){ ?>

<div class="alert alert-danger">
<?php echo h($message); ?>
</div>

<?php } ?>

<form method="POST">
<input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token']); ?>">

<input type="text"
name="email_or_id"
class="form-control mb-3"
placeholder="Email or Student ID"
required>

<input type="password"
name="password"
class="form-control mb-3"
placeholder="Password"
required>

<button name="login"
class="btn btn-custom w-100 text-white">

Login

</button>

</form>

<div class="text-center mt-3">

Don't have an account?

<a href="register.php">
Register
</a>

</div>

</div>

</div>

</body>
</html>