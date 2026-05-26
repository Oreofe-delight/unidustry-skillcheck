<?php
include("../includes/admin_auth.php");

if(isset($_POST['submit'])){

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "<script>alert('CSRF verification failed');</script>";
    } else {
        $category = $_POST['category'] ?? '';
        $question = $_POST['question'] ?? '';
        $o1 = $_POST['o1'] ?? '';
        $o2 = $_POST['o2'] ?? '';
        $o3 = $_POST['o3'] ?? '';
        $o4 = $_POST['o4'] ?? '';
        $correct = intval($_POST['correct'] ?? 0);
        $link = $_POST['link'] ?? '';

        if (!empty($category) && !empty($question) && !empty($o1) && !empty($o2) && !empty($o3) && !empty($o4) && $correct >= 1 && $correct <= 4) {
            $stmt = mysqli_prepare($conn, "INSERT INTO questions 
            (category, question, option1, option2, option3, option4, correct_option, resource_link)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sssssiis", $category, $question, $o1, $o2, $o3, $o4, $correct, $link);

            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Question added');</script>";
            } else {
                echo "<script>alert('Failed to add question');</script>";
            }
        } else {
            echo "<script>alert('Please fill in all fields correctly');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Question</title>
<link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>

<div class="auth-wrapper">
<div class="card">

<h3>Add Question</h3>

<form method="POST">
<input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token']); ?>">

<select name="category" class="form-control mb-3">
    <option value="technical">Technical</option>
    <option value="softskills">Soft Skills</option>
</select>

<textarea name="question" class="form-control mb-3" placeholder="Enter question"></textarea>

<input type="text" name="o1" class="form-control mb-2" placeholder="Option 1">
<input type="text" name="o2" class="form-control mb-2" placeholder="Option 2">
<input type="text" name="o3" class="form-control mb-2" placeholder="Option 3">
<input type="text" name="o4" class="form-control mb-2" placeholder="Option 4">

<input type="number" name="correct" class="form-control mb-3" placeholder="Correct option (1-4)">

<input type="text" name="link" class="form-control mb-3" placeholder="Resource link">

<button name="submit" class="btn btn-custom w-100">Add Question</button>

</form>

</div>
</div>

</body>
</html>