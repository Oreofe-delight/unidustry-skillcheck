<?php
include("../includes/admin_auth.php");

// Filters
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 15;
$offset = ($page - 1) * $limit;

// Build query
$where_clauses = [];
$params = [];
$types = "";

if($category_filter) {
    $where_clauses[] = "r.category = ?";
    $params[] = $category_filter;
    $types .= "s";
}
if($status_filter == 'pass') {
    $where_clauses[] = "r.percentage >= 70";
} elseif($status_filter == 'fail') {
    $where_clauses[] = "r.percentage < 70";
}

$where_sql = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";

// Count total
$count_sql = "SELECT COUNT(*) as total FROM results r $where_sql";
$count_stmt = mysqli_prepare($conn, $count_sql);
if(!empty($params)) {
    mysqli_stmt_bind_param($count_stmt, $types, ...$params);
}
mysqli_stmt_execute($count_stmt);
$total_results = mysqli_fetch_assoc(mysqli_stmt_get_result($count_stmt))['total'];
$total_pages = ceil($total_results / $limit);

// Get results
$sql = "
    SELECT 
        r.*,
        u.fullname,
        u.student_id,
        u.email
    FROM results r
    JOIN users u ON r.user_id = u.id
    $where_sql
    ORDER BY r.created_at DESC
    LIMIT ? OFFSET ?
";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$results = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Results | Admin Panel</title>
    
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
        .admin-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .admin-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        /* Filter Form */
        .filter-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 25px;
        }
        
        /* Table Styles */
        .table-responsive {
            overflow-x: auto;
        }
        
        .results-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }
        
        .results-table thead tr {
            background: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }
        
        .results-table th {
            padding: 14px 12px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            color: #4e54c8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .results-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }
        
        .results-table tbody tr:hover {
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
            background: #d4edda;
            color: #155724;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
            display: inline-block;
        }
        
        .score-medium {
            background: #fff3cd;
            color: #856404;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
            display: inline-block;
        }
        
        .score-low {
            background: #f8d7da;
            color: #721c24;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
            display: inline-block;
        }
        
        /* Buttons */
        .btn-custom {
            background: linear-gradient(135deg, #4e54c8, #6c63ff);
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
        }
        
        .btn-custom:hover {
            opacity: 0.9;
            color: white;
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
        
        .btn-outline-secondary {
            background: transparent;
            border: 1px solid #dee2e6;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            color: #6c757d;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            gap: 5px;
            list-style: none;
        }
        
        .pagination .page-item .page-link {
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            color: #4e54c8;
            text-decoration: none;
        }
        
        .pagination .page-item.active .page-link {
            background: #4e54c8;
            color: white;
            border-color: #4e54c8;
        }
        
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
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
        }
    </style>
</head>
<body>

<button class="hamburger" id="menuToggle">☰</button>

<div class="dashboard-layout">
    <?php include("sidebar.php"); ?>
    
    <main class="main-content">
        <div class="top-bar">
            <div>
                <h2><i class="fas fa-chart-bar me-2 text-primary"></i> Assessment Results</h2>
                <p class="text-muted mb-0">View all student assessment attempts and performance</p>
            </div>
            <button onclick="window.print()" class="btn-outline-secondary">
                <i class="fas fa-print me-1"></i> Print
            </button>
        </div>
        
        <div class="admin-card">
            <!-- Filters -->
            <form method="GET" class="filter-form">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <option value="technical" <?php echo $category_filter == 'technical' ? 'selected' : ''; ?>>Technical</option>
                            <option value="soft" <?php echo $category_filter == 'soft' ? 'selected' : ''; ?>>Soft Skills</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold small">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="pass" <?php echo $status_filter == 'pass' ? 'selected' : ''; ?>>Passed (≥70%)</option>
                            <option value="fail" <?php echo $status_filter == 'fail' ? 'selected' : ''; ?>>Failed (&lt;70%)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn-custom w-100">
                            <i class="fas fa-filter me-1"></i> Apply
                        </button>
                    </div>
                    <?php if($category_filter || $status_filter): ?>
                    <div class="col-md-2">
                        <a href="results.php" class="btn-outline-secondary w-100 d-block text-center">
                            <i class="fas fa-times me-1"></i> Clear
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </form>
            
            <!-- Results Table -->
            <div class="table-responsive">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th style="width: 25%">Student</th>
                            <th style="width: 15%">Student ID</th>
                            <th style="width: 12%">Category</th>
                            <th style="width: 10%">Score</th>
                            <th style="width: 10%">Percentage</th>
                            <th style="width: 10%">Status</th>
                            <th style="width: 10%">Date</th>
                            <th style="width: 8%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(mysqli_num_rows($results) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($results)): 
                                $score_class = $row['percentage'] >= 70 ? 'score-high' : ($row['percentage'] >= 50 ? 'score-medium' : 'score-low');
                                $category_display = ucfirst($row['category']);
                                $category_class = ($row['category'] == 'technical') ? 'badge-tech' : 'badge-soft';
                            ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['fullname']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($row['email']); ?></small>
                                </td>
                                <td><code><?php echo htmlspecialchars($row['student_id']); ?></code></td>
                                <td>
                                    <span class="<?php echo $category_class; ?>">
                                        <?php echo $category_display; ?>
                                    </span>
                                </td>
                                <td><strong><?php echo round($row['score']); ?></strong> / <?php echo round($row['total_questions']); ?></td>
                                <td>
                                    <span class="<?php echo $score_class; ?>">
                                        <?php echo round($row['percentage']); ?>%
                                    </span>
                                </td>
                                <td>
                                    <span class="<?php echo $row['percentage'] >= 70 ? 'badge-pass' : 'badge-fail'; ?>">
                                        <?php echo $row['percentage'] >= 70 ? 'PASSED' : 'FAILED'; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <a href="view_result.php?id=<?php echo $row['id']; ?>" class="btn-view">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-chart-line fa-3x mb-2 d-block"></i>
                                    No assessment results found.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if($total_pages > 1): ?>
            <div class="d-flex justify-content-between align-items-center mt-4 pt-2">
                <div class="text-muted small">
                    Showing <?php echo ($offset + 1); ?> to <?php echo min($offset + $limit, $total_results); ?> of <?php echo $total_results; ?> results
                </div>
                <nav>
                    <ul class="pagination mb-0">
                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&category=<?php echo urlencode($category_filter); ?>&status=<?php echo urlencode($status_filter); ?>">Previous</a>
                        </li>
                        <?php 
                        $start_page = max(1, $page - 2);
                        $end_page = min($total_pages, $page + 2);
                        for($i = $start_page; $i <= $end_page; $i++): 
                        ?>
                        <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&category=<?php echo urlencode($category_filter); ?>&status=<?php echo urlencode($status_filter); ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&category=<?php echo urlencode($category_filter); ?>&status=<?php echo urlencode($status_filter); ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <?php endif; ?>
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
        if(window.innerWidth <= 768) {
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnMenuBtn = menuBtn.contains(event.target);
            
            if (!isClickInsideSidebar && !isClickOnMenuBtn) {
                sidebar.classList.remove('active');
            }
        }
    });
</script>

</body>
</html>