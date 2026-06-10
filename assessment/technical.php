<?php
include("../includes/user_auth.php");

$result = mysqli_query($conn, "SELECT * FROM questions WHERE category='technical'");
?>

<!DOCTYPE html>
<html>
<head>
<title>Technical Test</title>
<link href="../assets/css/style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="assessment-page">

<div class="auth-wrapper">
<div class="assessment-card">

<h4 class="mb-4">Technical Assessment</h4>

<form method="POST" action="submit_test.php">

<input type="hidden" name="category" value="technical">

<?php
$count = 0;
while($row = mysqli_fetch_assoc($result)){
$count++;
?>

<div class="question">

    <div class="question-title">
        <?php echo $count.". ".$row['question']; ?>
    </div>

    <label class="d-block mb-2">
        <input type="radio"
        name="q<?php echo $row['id']; ?>"
        value="1">

        <?php echo $row['option1']; ?>
    </label>

    <label class="d-block mb-2">
        <input type="radio"
        name="q<?php echo $row['id']; ?>"
        value="2">

        <?php echo $row['option2']; ?>
    </label>

    <label class="d-block mb-2">
        <input type="radio"
        name="q<?php echo $row['id']; ?>"
        value="3">

        <?php echo $row['option3']; ?>
    </label>

    <label class="d-block mb-3">
        <input type="radio"
        name="q<?php echo $row['id']; ?>"
        value="4">

        <?php echo $row['option4']; ?>
    </label>

</div>

<?php } ?>

<input type="hidden" name="total" value="<?php echo $count; ?>">

<button class="btn btn-custom w-100 text-white">Submit Test</button>

</form>

</div>
</div>

</body>
</html>