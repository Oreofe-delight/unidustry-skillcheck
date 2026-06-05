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

<body>

<div class="auth-wrapper">
<div class="card">

<h4 class="mb-4">Technical Assessment</h4>

<form method="POST" action="submit_test.php">

<input type="hidden" name="category" value="technical">

<?php
$count = 0;
while($row = mysqli_fetch_assoc($result)){
$count++;
?>

<p><strong><?php echo $count.". ".$row['question']; ?></strong></p>

<input type="radio" name="q<?php echo $row['id']; ?>" value="1"> <?php echo $row['option1']; ?><br>
<input type="radio" name="q<?php echo $row['id']; ?>" value="2"> <?php echo $row['option2']; ?><br>
<input type="radio" name="q<?php echo $row['id']; ?>" value="3"> <?php echo $row['option3']; ?><br>
<input type="radio" name="q<?php echo $row['id']; ?>" value="4"> <?php echo $row['option4']; ?><br><br>

<?php } ?>

<input type="hidden" name="total" value="<?php echo $count; ?>">

<button class="btn btn-custom w-100 text-white">Submit Test</button>

</form>

</div>
</div>

</body>
</html>