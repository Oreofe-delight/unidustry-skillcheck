<?php
include("../includes/admin_auth.php");

// Get overall statistics
$total_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'student'"))['total'];
$total_assessments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM results"))['total'];
$avg_score = mysqli_fetch_assoc(mysqli_query($conn, "SELECT AVG(percentage) as avg FROM results"))['avg'] ?? 0;
$passed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM results WHERE percentage >= 70"))['total'];

// Get weekly activity (last 7 days)
$weekly_data = [];
for($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM results WHERE DATE(created_at) = '$date'"))['total'];
    $weekly_data['dates'][] = date('M d', strtotime($date));
    $weekly_data['counts'][] = $count;
}

// Get category performance
$tech_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total, AVG(percentage) as avg FROM results WHERE category = 'technical'"));
$soft_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total, AVG(percentage) as avg FROM results WHERE category = 'soft'"));

// Get top students
$top_students = mysqli_query($conn, "
    SELECT u.fullname, u.student_id, AVG(r.percentage) as avg_score, COUNT(r.id) as tests 
    FROM results r 
    JOIN users u ON r.user_id = u.id 
    GROUP BY r.user_id 
    ORDER BY avg_score DESC 
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics | Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            text-align: center;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #4e54c8;
        }
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .chart-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 20px;
            border-left: 4px solid #4e54c8;
            padding-left: 12px;
        }
        .sidebar {
            width: 260px;
            background: linear-gradient(135deg, #4e54c8, #6c63ff);
            color: white;
            position: fixed;
            height: 100vh;
            padding: 20px;
        }
        .main-content {
            margin-left: 260px;
            padding: 20px;
        }
        .sidebar-links a {
            display: block;
            color: white;
            padding: 12px;
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 5px;
        }
        .sidebar-links a:hover, .sidebar-links a.active {
            background: rgba(255,255,255,0.2);
        }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: 0.3s; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="text-center mb-4">
        <img src="../assets/images/logo-icon.png" style="height: 50px;">
        <h5>Admin Panel</h5>
    </div>
    <div class="sidebar-links">
        <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
        <a href="add_question.php"><i class="fas fa-plus-circle"></i> Add Question</a>
        <a href="manage_questions.php"><i class="fas fa-list"></i> Manage Questions</a>
        <a href="students.php"><i class="fas fa-user-graduate"></i> Students</a>
        <a href="results.php"><i class="fas fa-chart-bar"></i> Results</a>
        <a href="analytics.php" class="active"><i class="fas fa-chart-line"></i> Analytics</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-chart-line me-2 text-primary"></i> Analytics Dashboard</h2>
        <button onclick="window.print()" class="btn btn-secondary"><i class="fas fa-print"></i> Print</button>
    </div>
    
    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="stat-card">
                <i class="fas fa-users fa-2x text-primary"></i>
                <div class="stat-value"><?php echo $total_students; ?></div>
                <div>Total Students</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <i class="fas fa-clipboard-list fa-2x text-success"></i>
                <div class="stat-value"><?php echo $total_assessments; ?></div>
                <div>Assessments Taken</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <i class="fas fa-chart-line fa-2x text-warning"></i>
                <div class="stat-value"><?php echo round($avg_score); ?>%</div>
                <div>Average Score</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <i class="fas fa-trophy fa-2x text-danger"></i>
                <div class="stat-value"><?php echo $passed; ?></div>
                <div>Passed Assessments</div>
            </div>
        </div>
    </div>
    
    <!-- Charts -->
    <div class="row">
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title"><i class="fas fa-calendar-week"></i> Weekly Activity</div>
                <canvas id="weeklyChart" height="200"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-container">
                <div class="chart-title"><i class="fas fa-chart-pie"></i> Category Performance</div>
                <canvas id="categoryChart" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Top Students -->
    <div class="chart-container">
        <div class="chart-title"><i class="fas fa-medal"></i> Top Performing Students</div>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>Rank</th><th>Student</th><th>Student ID</th><th>Avg Score</th><th>Tests</th></thead>
                <tbody>
                    <?php $rank = 1; while($s = mysqli_fetch_assoc($top_students)): ?>
                    <tr><td><?php echo $rank++; ?></td><td><?php echo htmlspecialchars($s['fullname']); ?></td><td><?php echo $s['student_id']; ?></td><td><strong><?php echo round($s['avg_score']); ?>%</strong></td><td><?php echo $s['tests']; ?></td></tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
new Chart(document.getElementById('weeklyChart'), {
    type: 'line',
    data: {
        labels: <?php echo json_encode($weekly_data['dates']); ?>,
        datasets: [{
            label: 'Assessments',
            data: <?php echo json_encode($weekly_data['counts']); ?>,
            borderColor: '#4e54c8',
            fill: true
        }]
    }
});

new Chart(document.getElementById('categoryChart'), {
    type: 'bar',
    data: {
        labels: ['Technical', 'Soft Skills'],
        datasets: [{
            label: 'Average Score (%)',
            data: [<?php echo round($tech_data['avg'] ?? 0); ?>, <?php echo round($soft_data['avg'] ?? 0); ?>],
            backgroundColor: ['#4e54c8', '#6c63ff']
        }]
    }
});
</script>
</body>
</html>