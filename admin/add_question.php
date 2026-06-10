<?php
include(__DIR__ . "/../includes/admin_auth.php");

$message = "";

if(isset($_POST['add_question'])){

    $category = $_POST['category'];
    $question = $_POST['question'];

    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];

    $correct_answer = $_POST['correct_answer'];

    $recommendation_type = $_POST['recommendation_type'];
    $recommendation_title = $_POST['recommendation_title'];
    $recommendation_link = $_POST['recommendation_link'];

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO questions
        (
            category,
            question,
            option1,
            option2,
            option3,
            option4,
            correct_answer,
            recommendation_type,
            recommendation_title,
            recommendation_link
        )
        VALUES(?,?,?,?,?,?,?,?,?,?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssssssisss",
        $category,
        $question,
        $option1,
        $option2,
        $option3,
        $option4,
        $correct_answer,
        $recommendation_type,
        $recommendation_title,
        $recommendation_link
    );

    mysqli_stmt_execute($stmt);

    $message = "Question added successfully";
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Add Question</title>

<link href="../assets/css/style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="dashboard-layout">

<?php include("sidebar.php"); ?>

<main class="main-content">

<div class="container-fluid py-4">
<h2 class="mb-4">Add Question</h2>

<?php if($message){ ?>
<div class="alert alert-success">
    <?php echo $message; ?>
</div>
<?php } ?>

<form method="POST">

<div class="mb-3">
<label>Category</label>

<select name="category" class="form-select" required>

<option value="technical">Technical</option>

<option value="softskills">Soft Skills</option>

</select>

</div>

<div class="mb-3">
<label>Question</label>

<textarea
name="question"
class="form-control"
required></textarea>

</div>

<div class="mb-3">
<label>Option 1</label>
<input type="text" name="option1" class="form-control" required>
</div>

<div class="mb-3">
<label>Option 2</label>
<input type="text" name="option2" class="form-control" required>
</div>

<div class="mb-3">
<label>Option 3</label>
<input type="text" name="option3" class="form-control" required>
</div>

<div class="mb-3">
<label>Option 4</label>
<input type="text" name="option4" class="form-control" required>
</div>

<div class="mb-3">
<label>Correct Answer</label>

<select name="correct_answer"
class="form-select">

<option value="1">Option 1</option>
<option value="2">Option 2</option>
<option value="3">Option 3</option>
<option value="4">Option 4</option>

</select>

</div>

<hr>

<h4>Learning Recommendation</h4>

<div class="mb-3">

<label>Recommendation Type</label>

<select
name="recommendation_type"
class="form-select">

<option value="youtube">YouTube</option>

<option value="w3schools">W3Schools</option>

<option value="freecodecamp">FreeCodeCamp</option>

</select>

</div>

<div class="mb-3">

<label>Recommendation Title</label>

<input
type="text"
name="recommendation_title"
class="form-control">

</div>

<div class="mb-3">

<label>Recommendation Link</label>

<input
type="url"
name="recommendation_link"
class="form-control">

</div>

<button
name="add_question"
class="btn btn-custom text-white">

Add Question

</button>

</form>

</div>
</main>
</div>

</body>
</html>