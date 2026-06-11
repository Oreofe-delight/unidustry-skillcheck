<?php
include("includes/user_auth.php");
include("includes/db.php");

// Get dynamic stats - MATCHING YOUR DATABASE STRUCTURE
$user_id = $_SESSION['user_id'];

// Technical tests count (using 'category' column)
$tech_count = 0;
$tech_stmt = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM results WHERE user_id = ? AND category = 'technical'");
mysqli_stmt_bind_param($tech_stmt, "i", $user_id);
mysqli_stmt_execute($tech_stmt);
$tech_result = mysqli_stmt_get_result($tech_stmt);
if($row = mysqli_fetch_assoc($tech_result)) $tech_count = $row['count'];

// Soft skills tests count (using 'category' column)
$soft_count = 0;
$soft_stmt = mysqli_prepare($conn, "SELECT COUNT(*) as count FROM results WHERE user_id = ? AND category = 'soft'");
mysqli_stmt_bind_param($soft_stmt, "i", $user_id);
mysqli_stmt_execute($soft_stmt);
$soft_result = mysqli_stmt_get_result($soft_stmt);
if($row = mysqli_fetch_assoc($soft_result)) $soft_count = $row['count'];

// Profile completion
$profile_fields = ['phone', 'gender', 'dob', 'institution', 'faculty', 'department', 'level'];
$completed = 0;
foreach($profile_fields as $field) {
    if(!empty($user_data[$field])) $completed++;
}
$profile_percentage = round(($completed / count($profile_fields)) * 100);

// Get average scores (using 'category' column)
$avg_technical = 0;
$avg_soft = 0;

$avg_tech_stmt = mysqli_prepare($conn, "SELECT AVG(percentage) as avg_score FROM results WHERE user_id = ? AND category = 'technical'");
mysqli_stmt_bind_param($avg_tech_stmt, "i", $user_id);
mysqli_stmt_execute($avg_tech_stmt);
$avg_tech_result = mysqli_stmt_get_result($avg_tech_stmt);
if($row = mysqli_fetch_assoc($avg_tech_result)) $avg_technical = round($row['avg_score']);

$avg_soft_stmt = mysqli_prepare($conn, "SELECT AVG(percentage) as avg_score FROM results WHERE user_id = ? AND category = 'soft'");
mysqli_stmt_bind_param($avg_soft_stmt, "i", $user_id);
mysqli_stmt_execute($avg_soft_stmt);
$avg_soft_result = mysqli_stmt_get_result($avg_soft_stmt);
if($row = mysqli_fetch_assoc($avg_soft_result)) $avg_soft = round($row['avg_score']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Skill Assessment System</title>
    
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="dashboard-layout">
    
    <button id="menu-toggle" class="hamburger">☰</button>
    
    <aside class="sidebar">
        <div class="sidebar-logo text-center">
            <img src="assets/images/logo-icon.png" class="sidebar-img">
            <h4>Unidustry SkillCheck</h4>
        </div>
        <div class="sidebar-links">
            <a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
            <a href="assessment/assessment.php?type=technical"><i class="fas fa-code"></i> Technical Test</a>
            <a href="assessment/assessment.php?type=soft"><i class="fas fa-users"></i> Soft Skills</a>
            <a href="assessment/coding_list.php" class="btn btn-custom text-white"><i class="fas fa-code me-2"></i> Coding Challenges</a>
            <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </aside>
    <div class="sidebar-overlay"></div>
    
    <main class="main-content">
        <div class="topbar">
            <div>
                <h3>Welcome back, <?php echo h($_SESSION['fullname']); ?></h3>
                <p>Track your assessments and skill growth</p>
            </div>
            <div class="d-none d-md-block">
                <span class="badge bg-primary rounded-pill px-3 py-2">
                    <i class="fas fa-calendar-alt me-1"></i> <?php echo date('F j, Y'); ?>
                </span>
            </div>
        </div>
        
        <div class="container-fluid py-4">
            <!-- Stats Row -->
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="stats-card">
                        <div>
                            <h5>Technical Tests</h5>
                            <h2><?php echo $tech_count; ?></h2>
                            <small class="text-muted">Avg Score: <?php echo $avg_technical; ?>%</small>
                        </div>
                        <i class="fas fa-code"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div>
                            <h5>Soft Skills Tests</h5>
                            <h2><?php echo $soft_count; ?></h2>
                            <small class="text-muted">Avg Score: <?php echo $avg_soft; ?>%</small>
                        </div>
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div>
                            <h5>Profile Status</h5>
                            <h2><?php echo $profile_percentage; ?>%</h2>
                            <small class="text-muted">Profile Completion</small>
                        </div>
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
            </div>
            
            <!-- Assessment Cards -->
            <div class="row mt-4 g-4">
                <div class="col-lg-6">
                    <div class="dashboard-panel">
                        <img src="assets/images/tech.png" class="panel-img">
                        <h4>Technical Assessment</h4>
                        <p>Evaluate coding, problem-solving, and technical reasoning skills.</p>
                        <a href="assessment/assessment.php?type=technical" class="btn btn-custom text-white">
                            <i class="fas fa-play me-2"></i> Start Assessment
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="dashboard-panel">
                        <img src="assets/images/soft.png" class="panel-img">
                        <h4>Soft Skills Assessment</h4>
                        <p>Test communication, leadership, teamwork, and adaptability skills.</p>
                        <a href="assessment/assessment.php?type=soft" class="btn btn-custom text-white">
                            <i class="fas fa-play me-2"></i> Start Assessment
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="dashboard-panel" style="text-align: left;">
                        <h4 class="mb-4"><i class="fas fa-clock me-2" style="color: #6c63ff;"></i> Recent Activity</h4>
                        <?php
                        // Using your actual column names: created_at (not date_taken)
                        $recent_stmt = mysqli_prepare($conn, "SELECT * FROM results WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
                        mysqli_stmt_bind_param($recent_stmt, "i", $user_id);
                        mysqli_stmt_execute($recent_stmt);
                        $recent_results = mysqli_stmt_get_result($recent_stmt);
                        
                        if(mysqli_num_rows($recent_results) > 0) {
                            echo '<div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Assessment</th>
                                            <th>Score</th>
                                            <th>Percentage</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                            while($result = mysqli_fetch_assoc($recent_results)) {
                                $status_class = ($result['percentage'] ?? 0) >= 70 ? 'success' : (($result['percentage'] ?? 0) >= 50 ? 'warning' : 'danger');
                                $status_text = ($result['percentage'] ?? 0) >= 70 ? 'Passed' : (($result['percentage'] ?? 0) >= 50 ? 'Review' : 'Failed');
                                $category_name = ($result['category'] == 'technical') ? 'Technical Assessment' : 'Soft Skills Assessment';
                                echo '<tr>
                                    <td>' . $category_name . '</td>
                                    <td>' . ($result['score'] ?? 0) . ' / ' . ($result['total_questions'] ?? 0) . '</td>
                                    <td>' . round($result['percentage'] ?? 0) . '%' . '</td>
                                    <td><span class="badge bg-' . $status_class . '">' . $status_text . '</span></td>
                                    <td>' . date('M d, Y', strtotime($result['created_at'])) . '</td>
                                    <td><a href="assessment/result.php?id=' . $result['id'] . '" class="btn btn-sm btn-outline-primary">View Details</a></td>
                                </tr>';
                            }
                            echo '</tbody>
                                </table>
                            </div>';
                        } else {
                            echo '<div class="text-center py-4">
                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No assessments taken yet.</p>
                                <a href="assessment/assessment.php?type=technical" class="btn btn-custom">Start Your First Assessment</a>
                            </div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuBtn = document.getElementById("menu-toggle");
    const sidebar = document.querySelector(".sidebar");
    const overlay = document.querySelector(".sidebar-overlay");
    
    if(menuBtn) {
        menuBtn.addEventListener("click", function(){
            sidebar.classList.toggle("active");
            if(overlay) overlay.classList.toggle("active");
        });
    }
    
    if(overlay) {
        overlay.addEventListener("click", function(){
            sidebar.classList.remove("active");
            overlay.classList.remove("active");
        });
    }
    
    window.addEventListener('resize', function() {
        if(window.innerWidth > 768) {
            sidebar.classList.remove("active");
            if(overlay) overlay.classList.remove("active");
        }
    });
});
</script>

</body>
</html>