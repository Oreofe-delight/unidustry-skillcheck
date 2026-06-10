<?php
include("includes/admin_auth.php");

$totalStudents =
mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) total FROM users WHERE role='student'"
))['total'];

$totalQuestions =
mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) total FROM questions"
))['total'];

$totalResults =
mysqli_fetch_assoc(
mysqli_query(
$conn,
"SELECT COUNT(*) total FROM results"
))['total'];
?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Dashboard</title>

<link href="../assets/css/style.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body>

<div class="dashboard-layout">

<aside class="sidebar">

<div class="sidebar-logo text-center">

<img src="../assets/images/logo-icon.png"
class="sidebar-img">

<h4>Admin Panel</h4>

</div>

<div class="sidebar-links">

<a href="dashboard.php" class="active">
<i class="fas fa-home"></i>
Dashboard
</a>

<a href="add_question.php">
<i class="fas fa-plus-circle"></i>
Add Question
</a>

<a href="manage_questions.php">
<i class="fas fa-list"></i>
Manage Questions
</a>

<a href="../logout.php">
<i class="fas fa-sign-out-alt"></i>
Logout
</a>

</div>

</aside>

<main class="main-content">

<div class="topbar">

<h3>Administrator Dashboard</h3>

</div>

<div class="container-fluid py-4">

<div class="row g-4">

<div class="col-md-4">
<div class="stats-card">
<div>
<h5>Students</h5>
<h2><?php echo $totalStudents; ?></h2>
</div>
<i class="fas fa-users"></i>
</div>
</div>

<div class="col-md-4">
<div class="stats-card">
<div>
<h5>Questions</h5>
<h2><?php echo $totalQuestions; ?></h2>
</div>
<i class="fas fa-question-circle"></i>
</div>
</div>

<div class="col-md-4">
<div class="stats-card">
<div>
<h5>Assessments Taken</h5>
<h2><?php echo $totalResults; ?></h2>
</div>
<i class="fas fa-chart-line"></i>
</div>
</div>

</div>

</div>

</main>

</div>

</body>
</html>