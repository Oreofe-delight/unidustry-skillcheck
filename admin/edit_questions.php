<?php
include(__DIR__ . "/../includes/admin_auth.php");

$id = intval($_GET['id']);

$query =
mysqli_query(
$conn,
"SELECT * FROM questions WHERE id='$id'"
);

$question = mysqli_fetch_assoc($query);

if(isset($_POST['update_question'])){

    $category = $_POST['category'];
    $question_text = $_POST['question'];

    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];

    $correct_answer = $_POST['correct_answer'];

    $recommendation_type = $_POST['recommendation_type'];
    $recommendation_title = $_POST['recommendation_title'];
    $recommendation_link = $_POST['recommendation_link'];

    mysqli_query($conn,"
    UPDATE questions SET

    category='$category',
    question='$question_text',

    option1='$option1',
    option2='$option2',
    option3='$option3',
    option4='$option4',

    correct_answer='$correct_answer',

    recommendation_type='$recommendation_type',
    recommendation_title='$recommendation_title',
    recommendation_link='$recommendation_link'

    WHERE id='$id'
    ");

    header("Location: manage_questions.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Edit Question</title>

<link href="../assets/css/style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div class="container py-5">

<h2>Edit Question</h2>

<form method="POST">

<div class="mb-3">

<label>Category</label>

<select name="category" class="form-select">

<option value="technical"
<?php if($question['category']=="technical") echo "selected"; ?>>
Technical
</option>

<option value="softskills"
<?php if($question['category']=="softskills") echo "selected"; ?>>
Soft Skills
</option>

</select>

</div>

<div class="mb-3">

<label>Question</label>

<textarea
name="question"
class="form-control"
required><?php echo $question['question']; ?></textarea>

</div>

<div class="mb-3">

<label>Option 1</label>

<input type="text"
name="option1"
value="<?php echo $question['option1']; ?>"
class="form-control">

</div>

<div class="mb-3">

<label>Option 2</label>

<input type="text"
name="option2"
value="<?php echo $question['option2']; ?>"
class="form-control">

</div>

<div class="mb-3">

<label>Option 3</label>

<input type="text"
name="option3"
value="<?php echo $question['option3']; ?>"
class="form-control">

</div>

<div class="mb-3">

<label>Option 4</label>

<input type="text"
name="option4"
value="<?php echo $question['option4']; ?>"
class="form-control">

</div>

<div class="mb-3">

<label>Correct Answer</label>

<select
name="correct_answer"
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
value="<?php echo $question['recommendation_title']; ?>"
class="form-control">

</div>

<div class="mb-3">

<label>Recommendation Link</label>

<input
type="text"
name="recommendation_link"
value="<?php echo $question['recommendation_link']; ?>"
class="form-control">

</div>

<button
name="update_question"
class="btn btn-custom text-white">

Update Question

</button>

</form>

</div>

</body>
</html>