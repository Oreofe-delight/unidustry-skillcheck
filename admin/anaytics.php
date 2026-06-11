<?php
include("../includes/admin_auth.php");

// Get statistics for charts
$user_id = $_SESSION['user_id'];

// Get overall statistics
$stats_query = mysqli_query($conn, "
    SELECT 
        COUNT(DISTINCT user_id) as total_students,
        COUNT(*) as total_assessments,
        AVG(percentage) as avg_score,
        SUM(CASE WHEN percentage >= 70 THEN 1 ELSE 0 END) as passed_count
    FROM results
");

$overall = mysqli_fetch_assoc($stats_query);

// Get weekly activity (last 7 days)
$weekly_stmt = mysqli_prepare($conn, "
    SELECT 
        DATE(created_at) as date,
        COUNT(*) as count,
        AVG(percentage) as avg_score
    FROM results
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    GROUP BY DATE(created_at)
    ORDER BY date ASC
");
mysqli_stmt_execute($weekly_stmt);
$weekly_results = mysqli_stmt_get_result($weekly_stmt);

$weekly_dates = [];
$weekly_counts = [];
$weekly_scores = [];
while($row = mysqli_fetch_assoc($weekly_results)) {
    $weekly_dates[] = date('M d', strtotime($row['date']));
    $weekly_counts[] = $row['count'];
    $weekly_scores[] = round($row['avg_score']);
}

// Get category performance
$category_stmt = mysqli_prepare($conn, "
    SELECT 
        category,
        COUNT(*) as total,
        AVG(percentage) as avg_score
    FROM results
    GROUP BY category
");
mysqli_stmt_execute($category_stmt);
$category_results = mysqli_stmt_get_result($category_stmt);

$categories = [];
$category_counts = [];
$category_scores = [];
while($row = mysqli_fetch_assoc($category_results)) {
    $categories[] = ucfirst($row['category']);
    $category_counts[] = $row['total'];
    $category_scores[] = round($row['avg_score']);
}

// Get top performing students
$top_students = mysqli_query($conn, "
    SELECT 
        u.fullname,
        u.student_id,
        AVG(r.percentage) as avg_score,
        COUNT(r.id) as assessments_taken
    FROM results r
    JOIN users u ON r.user_id = u.id
    GROUP BY r.user_id
    ORDER BY avg_score DESC
    LIMIT 10
");

// Get pass/fail distribution
$pass_fail = mysqli_query($conn, "
    SELECT 
        SUM(CASE WHEN percentage >= 70 THEN 1 ELSE 0 END) as passed,
        SUM(CASE WHEN percentage < 70 THEN 1 ELSE 0 END) as failed
    FROM results
");
$pf = mysqli_fetch_assoc($pass_fail);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard | Admin Panel</title>
    
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
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.7;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #4e54c8;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
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
            color: #2d3748;
            border-left: 4px solid #4e54c8;
            padding-left: 12px;
        }
    </style>
</head>
<body>

<div class="dashboard-layout">
    <?php include("sidebar.php"); ?>
    
    <main class="main-content">
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-chart-line me-2 text-primary"></i> Analytics Dashboard</h2>
                <button onclick="window.print()" class="btn btn-outline-secondary">
                    <i class="fas fa-print me-1"></i> Print Report
                </button>
            </div>
            
            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-card text-center">
                        <i class="fas fa-users stat-icon text-primary"></i>
                        <div class="stat-value"><?php echo $overall['total_students']; ?></div>
                        <div class="stat-label">Total Students</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card text-center">
                        <i class="fas fa-clipboard-list stat-icon text-success"></i>
                        <div class="stat-value"><?php echo $overall['total_assessments']; ?></div>
                        <div class="stat-label">Total Assessments</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card text-center">
                        <i class="fas fa-chart-line stat-icon text-warning"></i>
                        <div class="stat-value"><?php echo round($overall['avg_score']); ?>%</div>
                        <div class="stat-label">Average Score</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card text-center">
                        <i class="fas fa-trophy stat-icon text-danger"></i>
                        <div class="stat-value"><?php echo $overall['passed_count']; ?></div>
                        <div class="stat-label">Passed Assessments</div>
                    </div>
                </div>
            </div>
            
            <!-- Charts Row 1 -->
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-title">
                            <i class="fas fa-calendar-week me-2 text-primary"></i> Weekly Activity (Last 7 Days)
                        </div>
                        <canvas id="weeklyChart" height="250"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-title">
                            <i class="fas fa-chart-pie me-2 text-primary"></i> Pass vs Fail Distribution
                        </div>
                        <canvas id="passFailChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Charts Row 2 -->
            <div class="row">
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-title">
                            <i class="fas fa-chart-bar me-2 text-primary"></i> Category Performance
                        </div>
                        <canvas id="categoryChart" height="250"></canvas>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-container">
                        <div class="chart-title">
                            <i class="fas fa-chart-line me-2 text-primary"></i> Category Average Scores
                        </div>
                        <canvas id="categoryScoreChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Top Students Table -->
            <div class="chart-container">
                <div class="chart-title">
                    <i class="fas fa-medal me-2 text-warning"></i> Top Performing Students
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Rank</th>
                                <th>Student Name</th>
                                <th>Student ID</th>
                                <th>Average Score</th>
                                <th>Assessments</th>
                            </thead>
                        <tbody>
                            <?php $rank = 1; while($student = mysqli_fetch_assoc($top_students)): ?>
                            <tr>
                                <td>
                                    <?php if($rank == 1): ?>
                                        🥇
                                    <?php elseif($rank == 2): ?>
                                        🥈
                                    <?php elseif($rank == 3): ?>
                                        🥉
                                    <?php else: ?>
                                        <?php echo $rank; ?>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($student['fullname']); ?></td>
                                <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                <td>
                                    <strong class="text-success"><?php echo round($student['avg_score']); ?>%</strong>
                                </td>
                                <td><?php echo $student['assessments_taken']; ?></td>
                            </tr>
                            <?php $rank++; endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // Weekly Activity Chart (Line)
    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($weekly_dates); ?>,
            datasets: [{
                label: 'Assessments Taken',
                data: <?php echo json_encode($weekly_counts); ?>,
                borderColor: '#4e54c8',
                backgroundColor: 'rgba(78, 84, 200, 0.1)',
                tension: 0.3,
                fill: true
            }, {
                label: 'Average Score (%)',
                data: <?php echo json_encode($weekly_scores); ?>,
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                tension: 0.3,
                fill: true,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: { title: { display: true, text: 'Number of Assessments' } },
                y1: { position: 'right', title: { display: true, text: 'Average Score (%)' }, min: 0, max: 100 }
            }
        }
    });
    
    // Pass/Fail Chart (Doughnut)
    const pfCtx = document.getElementById('passFailChart').getContext('2d');
    new Chart(pfCtx, {
        type: 'doughnut',
        data: {
            labels: ['Passed (≥70%)', 'Failed (<70%)'],
            datasets: [{
                data: [<?php echo $pf['passed']; ?>, <?php echo $pf['failed']; ?>],
                backgroundColor: ['#28a745', '#dc3545'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
    
    // Category Performance (Bar)
    const catCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(catCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($categories); ?>,
            datasets: [{
                label: 'Number of Assessments',
                data: <?php echo json_encode($category_counts); ?>,
                backgroundColor: '#4e54c8',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });
    
    // Category Scores (Bar)
    const scoreCtx = document.getElementById('categoryScoreChart').getContext('2d');
    new Chart(scoreCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($categories); ?>,
            datasets: [{
                label: 'Average Score (%)',
                data: <?php echo json_encode($category_scores); ?>,
                backgroundColor: '#28a745',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: { min: 0, max: 100, title: { display: true, text: 'Score (%)' } }
            }
        }
    });
</script>

</body>
</html>