<?php
session_start();

$score = $_SESSION['score'];
$total = $_SESSION['total'];

$recommendations =
$_SESSION['recommendations'] ?? [];

$percentage =
round(($score/$total)*100);
?>

<!DOCTYPE html>
<html>
<head>

<title>Assessment Result</title>

<link href="../assets/css/style.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>

<body>

<div class="container py-5">

<div class="card">

<h2>Your Result</h2>

<h4 class="mb-4">

Score:

<?php echo $score; ?>

/

<?php echo $total; ?>

(<?php echo $percentage; ?>%)

</h4>

<?php if(count($recommendations)>0){ ?>

<h3 class="mb-4">

Recommended Learning Resources

</h3>

<?php foreach($recommendations as $item){ ?>

<div class="alert alert-warning">

<h5>

Failed Question:

</h5>

<p>

<?php echo $item['question']; ?>

</p>

<hr>

<strong>

<?php echo ucfirst($item['type']); ?>

</strong>

<br>

<?php echo $item['title']; ?>

<br><br>

<a href="<?php echo $item['link']; ?>"
target="_blank"
class="btn btn-primary">

Open Resource

</a>

</div>

<?php } ?>

<?php } ?>

<a href="../dashboard.php"
class="btn btn-custom text-white">

Return Dashboard

</a>

</div>

</div>

</body>
</html>