<?php
session_start();
include("includes/db.php");

$message = "";

if(isset($_POST['login'])){

    $email_or_id = $_POST['email_or_id'];
    $password = $_POST['password'];

    $query = mysqli_query($conn,

    "SELECT * FROM users
    WHERE email='$email_or_id'
    OR student_id='$email_or_id'");

    if(mysqli_num_rows($query) > 0){

        $user = mysqli_fetch_assoc($query);

        if(password_verify($password, $user['password'])){

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

<h2 class="text-center mb-4">
Login
</h2>

<?php if($message != ""){ ?>

<div class="alert alert-danger">
<?php echo $message; ?>
</div>

<?php } ?>

<form method="POST">

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
class="btn btn-custom w-100">

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