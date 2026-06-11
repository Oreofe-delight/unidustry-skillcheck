<?php
include("../includes/admin_auth.php");

// Get statistics with proper counting
$stats = [];

// Total students
$student_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'student'");
$stats['students'] = mysqli_fetch_assoc($student_result)['total'];

// Total questions
$question_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM questions");
$stats['questions'] = mysqli_fetch_assoc($question_result)['total'];

// Technical questions count
$tech_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM questions WHERE category = 'technical'");
$stats['technical'] = mysqli_fetch_assoc($tech_result)['total'];

// Soft skills questions count
$soft_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM questions WHERE category = 'softskills'");
$stats['soft'] = mysqli_fetch_assoc($soft_result)['total'];

// Total assessments taken
$result_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM results");
$stats['assessments'] = mysqli_fetch_assoc($result_result)['total'];

// Recent activities
$recent_activities = mysqli_query($conn, "
    SELECT 
        r.id,
        u.fullname,
        r.category,
        r.percentage,
        r.created_at
    FROM results r
    JOIN users u ON r.user_id = u.id
    ORDER BY r.created_at DESC
    LIMIT 5
");

// Pass rate
$pass_result = mysqli_query($conn, "
    SELECT 
        COUNT(CASE WHEN percentage >= 70 THEN 1 END) as passed,
        COUNT(*) as total
    FROM results
");
$pass_data = mysqli_fetch_assoc($pass_result);
$pass_rate = $pass_data['total'] > 0 ? round(($pass_data['passed'] / $pass_data['total']) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Skill Assessment</title>
    
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: transform 0.2s;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        .stat-number {
            font-size: 2.2rem;
            font-weight: 700;
            color: #4e54c8;
        }
        .recent-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
        }
        .dashboard-panel {
            text-align: center;
            padding: 25px;
            background: white;
            border-radius: 15px;
            height: 100%;
            transition: all 0.3s;
        }
        .dashboard-panel:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .quick-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            border-radius: 15px;
        }
    </style>
</head>
<body>

<div class="dashboard-layout">
    <?php include("sidebar.php"); ?>
    
    <main class="main-content">
        <div class="topbar">
            <div>
                <h3>Administrator Dashboard</h3>
                <p>Welcome back, <?php echo h($_SESSION['fullname'] ?? 'Admin'); ?></p>
            </div>
            <div>
                <span class="badge bg-primary px-3 py-2">
                    <i class="fas fa-calendar-alt me-1"></i> <?php echo date('F j, Y'); ?>
                </span>
            </div>
        </div>
        
        <div class="container-fluid py-4">
            <!-- Stats Row -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="stat-card text-center">
                        <div class="stat-icon text-primary">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <div class="stat-number"><?php echo $stats['students']; ?></div>
                        <div class="text-muted">Total Students</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card text-center">
                        <div class="stat-icon text-success">
                            <i class="fas fa-question-circle fa-2x"></i>
                        </div>
                        <div class="stat-number"><?php echo $stats['questions']; ?></div>
                        <div class="text-muted">Total Questions</div>
                        <small class="text-muted">(<?php echo $stats['technical']; ?> Tech, <?php echo $stats['soft']; ?> Soft)</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card text-center">
                        <div class="stat-icon text-warning">
                            <i class="fas fa-clipboard-list fa-2x"></i>
                        </div>
                        <div class="stat-number"><?php echo $stats['assessments']; ?></div>
                        <div class="text-muted">Assessments Taken</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card text-center">
                        <div class="stat-icon text-info">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                        <div class="stat-number"><?php echo $pass_rate; ?>%</div>
                        <div class="text-muted">Overall Pass Rate</div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions Row -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="dashboard-panel">
                        <div class="quick-icon bg-primary bg-opacity-10">
                            <i class="fas fa-plus-circle fa-2x text-primary"></i>
                        </div>
                        <h5>Add Question</h5>
                        <p class="small text-muted">Create new technical or soft skill questions</p>
                        <a href="add_question.php" class="btn btn-sm btn-custom text-white">Open</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-panel">
                        <div class="quick-icon bg-success bg-opacity-10">
                            <i class="fas fa-edit fa-2x text-success"></i>
                        </div>
                        <h5>Manage Questions</h5>
                        <p class="small text-muted">Edit, update or delete existing questions</p>
                        <a href="manage_questions.php" class="btn btn-sm btn-custom text-white">Open</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-panel">
                        <div class="quick-icon bg-warning bg-opacity-10">
                            <i class="fas fa-user-graduate fa-2x text-warning"></i>
                        </div>
                        <h5>Students</h5>
                        <p class="small text-muted">View registered students and profiles</p>
                        <a href="students.php" class="btn btn-sm btn-custom text-white">Open</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="dashboard-panel">
                        <div class="quick-icon bg-info bg-opacity-10">
                            <i class="fas fa-chart-bar fa-2x text-info"></i>
                        </div>
                        <h5>Results</h5>
                        <p class="small text-muted">View student scores and performance</p>
                        <a href="results.php" class="btn btn-sm btn-custom text-white">Open</a>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activities -->
            <div class="row">
                <div class="col-12">
                    <div class="dashboard-panel" style="text-align: left;">
                        <h5 class="mb-3">
                            <i class="fas fa-history me-2 text-primary"></i> Recent Assessments
                        </h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Category</th>
                                        <th>Score</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($activity = mysqli_fetch_assoc($recent_activities)): ?>
                                    <tr>
                                        <td><?php echo h($activity['fullname']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $activity['category'] == 'technical' ? 'bg-primary' : 'bg-success'; ?>">
                                                <?php echo ucfirst($activity['category']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo round($activity['percentage']); ?>%</td>
                                        <td><?php echo date('M d, Y', strtotime($activity['created_at'])); ?></td>
                                        <td>
                                            <a href="view_result.php?id=<?php echo $activity['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                    <?php if(mysqli_num_rows($recent_activities) == 0): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No assessments taken yet</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>