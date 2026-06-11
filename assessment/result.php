<?php
session_start();
include("../includes/db.php");

$score = $_SESSION['score'];
$total = $_SESSION['total'];
$recommendations = $_SESSION['recommendations'] ?? [];

$percentage = round(($score/$total)*100);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assessment Result</title>
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg, #4e54c8, #8f94fb); min-height: 100vh;">

<div class="container py-5">
    <div class="card p-4" style="border-radius: 20px; max-width: 800px; margin: 0 auto;">
        <div class="text-center">
            <img src="../assets/images/logo-full.png" style="height: 60px;">
            <h2 class="mt-3">Your Result</h2>
        </div>

        <div class="text-center my-4">
            <div style="font-size: 3rem; font-weight: bold; color: #4e54c8;">
                <?php echo $score; ?> / <?php echo $total; ?>
            </div>
            <div class="mt-2">
                <span class="badge <?php echo $percentage >= 70 ? 'bg-success' : 'bg-danger'; ?> px-3 py-2">
                    <?php echo $percentage; ?>% - <?php echo $percentage >= 70 ? 'PASSED' : 'NEEDS IMPROVEMENT'; ?>
                </span>
            </div>
        </div>

        <?php if(count($recommendations) > 0): ?>
        <div class="mt-4">
            <h4 class="mb-3">Recommended Learning Resources</h4>
            <?php foreach($recommendations as $item): ?>
            <div class="alert alert-warning">
                <strong>Question:</strong> <?php echo htmlspecialchars($item['question']); ?>
                <hr>
                <strong><?php echo ucfirst($item['type']); ?>:</strong>
                <?php echo htmlspecialchars($item['title']); ?>
                <br><br>
                <a href="<?php echo $item['link']; ?>" target="_blank" class="btn btn-primary btn-sm">
                    Open Resource
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="alert alert-success text-center">
            <i class="fas fa-trophy"></i> Excellent! No recommendations needed.
        </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="../dashboard.php" class="btn btn-custom text-white">Back to Dashboard</a>
        </div>
    </div>
</div>

</body>
</html>