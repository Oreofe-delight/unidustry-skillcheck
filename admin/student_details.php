<?php
include("../includes/admin_auth.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id <= 0) {
    header("Location: students.php");
    exit();
}

// Get student details
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ? AND role = 'student'");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$user_result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($user_result);

if(!$user) {
    header("Location: students.php");
    exit();
}

// Get assessment results
$results_stmt = mysqli_prepare($conn, "SELECT * FROM results WHERE user_id = ? ORDER BY created_at DESC");
mysqli_stmt_bind_param($results_stmt, "i", $id);
mysqli_stmt_execute($results_stmt);
$results = mysqli_stmt_get_result($results_stmt);

// Calculate statistics
$total_assessments = 0;
$total_percentage = 0;
$results_data = [];

while($row = mysqli_fetch_assoc($results)) {
    $total_assessments++;
    $total_percentage += $row['percentage'];
    $results_data[] = $row;
}
$avg_score = $total_assessments > 0 ? round($total_percentage / $total_assessments) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile | Admin Panel</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #f0f2f5;
            font-family: 'Poppins', sans-serif;
        }
        
        .dashboard-layout {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: linear-gradient(135deg, #4e54c8, #6c63ff);
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            padding: 25px 0;
            overflow-y: auto;
            z-index: 100;
        }
        
        .sidebar-logo {
            text-align: center;
            padding: 0 20px 25px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 25px;
        }
        
        .sidebar-img {
            height: 55px;
            margin-bottom: 10px;
        }
        
        .sidebar-logo h4 {
            font-size: 18px;
            margin: 0;
        }
        
        .sidebar-links a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 25px;
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            transition: all 0.3s;
            margin: 5px 0;
        }
        
        .sidebar-links a:hover,
        .sidebar-links a.active {
            background: rgba(255,255,255,0.15);
            color: white;
            border-left: 3px solid white;
        }
        
        .sidebar-links a i {
            width: 22px;
            font-size: 16px;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 260px;
            width: 100%;
            padding: 20px 30px;
        }
        
        /* Top Bar */
        .top-bar {
            background: white;
            border-radius: 15px;
            padding: 20px 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .top-bar h2 {
            font-size: 22px;
            margin: 0;
            color: #2d3748;
        }
        
        /* Cards */
        .profile-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .profile-header {
            background: linear-gradient(135deg, #4e54c8, #6c63ff);
            border-radius: 20px;
            padding: 30px;
            color: white;
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #4e54c8;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .section-title i {
            margin-right: 10px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6c757d;
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 15px;
            font-weight: 500;
            color: #2d3748;
        }
        
        /* Stats Boxes */
        .stats-row {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }
        
        .stat-box {
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            flex: 1;
        }
        
        .stat-number {
            font-size: 28px;
            font-weight: 700;
        }
        
        .stat-label {
            font-size: 12px;
            opacity: 0.9;
        }
        
        /* Table Styles */
        .table-responsive {
            overflow-x: auto;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .data-table thead tr {
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }
        
        .data-table th {
            padding: 14px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: #4e54c8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .data-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }
        
        .data-table tbody tr:hover {
            background: #f8f9ff;
        }
        
        /* Badges */
        .badge-tech {
            background: #007bff;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }
        
        .badge-soft {
            background: #6f42c1;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }
        
        .badge-pass {
            background: #28a745;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }
        
        .badge-fail {
            background: #dc3545;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }
        
        .score-high {
            color: #28a745;
            font-weight: 600;
        }
        
        .score-low {
            color: #dc3545;
            font-weight: 600;
        }
        
        .btn-view {
            background: #4e54c8;
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-view:hover {
            background: #3a3f9e;
            color: white;
        }
        
        .btn-outline {
            background: transparent;
            border: 1px solid #dee2e6;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            color: #6c757d;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-outline:hover {
            background: #f8f9fa;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        /* Hamburger Menu */
        .hamburger {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1001;
            background: #4e54c8;
            border: none;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            font-size: 20px;
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s;
            }
            .sidebar.active {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            .hamburger {
                display: block;
            }
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<button class="hamburger" id="menuToggle">☰</button>

<div class="dashboard-layout">
    <aside class="sidebar">
        <div class="sidebar-logo">
            <img src="../assets/images/logo-icon.png" class="sidebar-img">
            <h4>Admin Panel</h4>
        </div>
        <div class="sidebar-links">
            <a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
            <a href="add_question.php"><i class="fas fa-plus-circle"></i> Add Question</a>
            <a href="manage_questions.php"><i class="fas fa-list"></i> Manage Questions</a>
            <a href="students.php"><i class="fas fa-user-graduate"></i> Students</a>
            <a href="results.php"><i class="fas fa-chart-bar"></i> Results</a>
            <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </aside>
    
    <main class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div>
                <h2><i class="fas fa-user-graduate me-2 text-primary"></i> Student Profile</h2>
                <p class="text-muted mb-0">View detailed student information and assessment history</p>
            </div>
            <a href="students.php" class="btn-outline">
                <i class="fas fa-arrow-left me-1"></i> Back to Students
            </a>
        </div>
        
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2><?php echo htmlspecialchars($user['fullname']); ?></h2>
                    <p class="mb-1"><i class="fas fa-envelope me-2"></i> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p><i class="fas fa-id-card me-2"></i> <?php echo htmlspecialchars($user['student_id']); ?></p>
                </div>
                <div class="col-md-4">
                    <div class="stats-row">
                        <div class="stat-box">
                            <div class="stat-number"><?php echo $total_assessments; ?></div>
                            <div class="stat-label">Assessments</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-number"><?php echo $avg_score; ?>%</div>
                            <div class="stat-label">Avg Score</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <!-- Personal Information Column -->
            <div class="col-md-6">
                <div class="profile-card">
                    <h5 class="section-title"><i class="fas fa-user"></i> Personal Information</h5>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Phone Number</div>
                            <div class="info-value"><?php echo htmlspecialchars($user['phone'] ?: 'Not provided'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Gender</div>
                            <div class="info-value"><?php echo htmlspecialchars($user['gender'] ?: 'Not specified'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Date of Birth</div>
                            <div class="info-value"><?php echo $user['dob'] ? date('F j, Y', strtotime($user['dob'])) : 'Not provided'; ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Registered On</div>
                            <div class="info-value"><?php echo date('M d, Y', strtotime($user['created_at'] ?? 'now')); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Academic Information Column -->
            <div class="col-md-6">
                <div class="profile-card">
                    <h5 class="section-title"><i class="fas fa-graduation-cap"></i> Academic Information</h5>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Institution</div>
                            <div class="info-value"><?php echo htmlspecialchars($user['institution'] ?: 'Not provided'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Faculty</div>
                            <div class="info-value"><?php echo htmlspecialchars($user['faculty'] ?: 'Not provided'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Department</div>
                            <div class="info-value"><?php echo htmlspecialchars($user['department'] ?: 'Not provided'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Skill Interests</div>
                            <div class="info-value"><?php echo htmlspecialchars($user['skills_interest'] ?: 'Not specified'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Assessment History Table -->
        <div class="profile-card">
            <h5 class="section-title"><i class="fas fa-chart-line"></i> Assessment History</h5>
            
            <?php if(count($results_data) > 0): ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 15%">Category</th>
                            <th style="width: 10%">Score</th>
                            <th style="width: 10%">Out of</th>
                            <th style="width: 15%">Percentage</th>
                            <th style="width: 15%">Status</th>
                            <th style="width: 15%">Date</th>
                            <th style="width: 15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sn = 1; foreach($results_data as $row): ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td>
                                <span class="<?php echo $row['category'] == 'technical' ? 'badge-tech' : 'badge-soft'; ?>">
                                    <?php echo ucfirst($row['category']); ?>
                                </span>
                            </td>
                            <td><strong><?php echo round($row['score']); ?></strong></td>
                            <td><?php echo round($row['total_questions']); ?></td>
                            <td>
                                <strong class="<?php echo $row['percentage'] >= 70 ? 'score-high' : 'score-low'; ?>">
                                    <?php echo round($row['percentage']); ?>%
                                </strong>
                            </td>
                            <td>
                                <span class="<?php echo $row['percentage'] >= 70 ? 'badge-pass' : 'badge-fail'; ?>">
                                    <?php echo $row['percentage'] >= 70 ? 'PASSED' : 'FAILED'; ?>
                                </span>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="view_result.php?id=<?php echo $row['id']; ?>" class="btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="alert alert-info text-center py-4">
                <i class="fas fa-clipboard-list fa-2x mb-2 d-block"></i>
                No assessments taken yet.
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>" class="btn-outline">
                <i class="fas fa-envelope me-1"></i> Send Email
            </a>
            <a href="delete_student.php?id=<?php echo $user['id']; ?>&csrf_token=<?php echo $_SESSION['csrf_token']; ?>" 
               class="btn-outline" style="color: #dc3545; border-color: #dc3545;"
               onclick="return confirm('Delete this student? All their results will also be deleted.')">
                <i class="fas fa-trash me-1"></i> Delete Student
            </a>
        </div>
    </main>
</div>

<script>
    // Mobile menu toggle
    const menuBtn = document.getElementById('menuToggle');
    const sidebar = document.querySelector('.sidebar');
    
    if(menuBtn) {
        menuBtn.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const isClickInsideSidebar = sidebar.contains(event.target);
        const isClickOnMenuBtn = menuBtn.contains(event.target);
        
        if (!isClickInsideSidebar && !isClickOnMenuBtn && window.innerWidth <= 768) {
            sidebar.classList.remove('active');
        }
    });
</script>

</body>
</html>