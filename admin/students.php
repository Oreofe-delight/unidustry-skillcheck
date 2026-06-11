<?php
include("../includes/admin_auth.php");

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Build query with prepared statement for security
if($search != '') {
    $count_query = "SELECT COUNT(*) as total FROM users WHERE role='student' AND (fullname LIKE ? OR email LIKE ? OR student_id LIKE ?)";
    $count_stmt = mysqli_prepare($conn, $count_query);
    $search_param = "%$search%";
    mysqli_stmt_bind_param($count_stmt, "sss", $search_param, $search_param, $search_param);
    mysqli_stmt_execute($count_stmt);
    $total_result = mysqli_stmt_get_result($count_stmt);
    $total_students = mysqli_fetch_assoc($total_result)['total'];
    
    $query = "SELECT * FROM users WHERE role='student' AND (fullname LIKE ? OR email LIKE ? OR student_id LIKE ?) ORDER BY id DESC LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssii", $search_param, $search_param, $search_param, $limit, $offset);
} else {
    $count_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='student'");
    $total_students = mysqli_fetch_assoc($count_result)['total'];
    
    $query = "SELECT * FROM users WHERE role='student' ORDER BY id DESC LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
}

mysqli_stmt_execute($stmt);
$students = mysqli_stmt_get_result($stmt);
$total_pages = ceil($total_students / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Management | Admin Panel</title>
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="dashboard-layout">
    <?php include("sidebar.php"); ?>
    
    <main class="main-content">
        <div class="container-fluid py-4">
            <div class="admin-card">
                <div class="admin-header">
                    <h2><i class="fas fa-user-graduate me-2 text-primary"></i> Students Management</h2>
                    <p>View and manage all registered students</p>
                </div>
                
                <!-- Search Form -->
                <form method="GET" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-8">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Search by name, email or student ID..."
                                   value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-custom text-white w-100">
                                <i class="fas fa-search me-1"></i> Search
                            </button>
                        </div>
                        <?php if($search): ?>
                        <div class="col-md-2">
                            <a href="students.php" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-times me-1"></i> Clear
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </form>
                
                <!-- Students Table -->
                <div class="table-responsive">
                    <table class="table admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Student ID</th>
                                <th>Level</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($students) > 0): ?>
                                <?php while($student = mysqli_fetch_assoc($students)): ?>
                                <tr>
                                    <td><?php echo $student['id']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($student['fullname']); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($student['department'] ?: 'No department'); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($student['email']); ?></td>
                                    <td><code><?php echo htmlspecialchars($student['student_id']); ?></code></td>
                                    <td><?php echo htmlspecialchars($student['level'] ?: 'Not set'); ?></td>
                                    <td>
                                        <a href="student_details.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="delete_student.php?id=<?php echo $student['id']; ?>&csrf_token=<?php echo $_SESSION['csrf_token']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Delete this student? All their results will also be deleted.')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-users fa-3x mb-3 d-block"></i>
                                        No students found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if($total_pages > 1): ?>
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        Showing <?php echo ($offset + 1); ?> to <?php echo min($offset + $limit, $total_students); ?> of <?php echo $total_students; ?> students
                    </div>
                    <nav>
                        <ul class="pagination mb-0">
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Previous</a>
                            </li>
                            <?php for($i = 1; $i <= $total_pages && $i <= 10; $i++): ?>
                            <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                            </li>
                            <?php endfor; ?>
                            <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>