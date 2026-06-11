<?php
include("../includes/admin_auth.php");

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Get total count
$count_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM questions");
$total_questions = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_questions / $limit);

// Get questions with pagination
$result = mysqli_query($conn, "SELECT * FROM questions ORDER BY id DESC LIMIT $limit OFFSET $offset");

// Handle delete
if(isset($_GET['delete']) && isset($_GET['csrf_token'])) {
    if($_GET['csrf_token'] === $_SESSION['csrf_token']) {
        $delete_id = (int)$_GET['delete'];
        $delete_stmt = mysqli_prepare($conn, "DELETE FROM questions WHERE id = ?");
        mysqli_stmt_bind_param($delete_stmt, "i", $delete_id);
        if(mysqli_stmt_execute($delete_stmt)) {
            $message = "Question deleted successfully!";
            header("Location: manage_questions.php?msg=deleted");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Questions | Admin Panel</title>
    
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="dashboard-layout">
    <?php include("sidebar.php"); ?>
    
    <main class="main-content">
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-list me-2 text-primary"></i> Manage Questions</h2>
                <a href="add_question.php" class="btn btn-custom text-white">
                    <i class="fas fa-plus-circle me-2"></i> Add New Question
                </a>
                <a href="bulk_upload.php" class="btn btn-info text-white ms-2">
                    <i class="fas fa-upload me-2"></i> Bulk Upload
                </a>
            </div>
            
            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i> Question deleted successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <div class="bg-white rounded-4 shadow-sm overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%">ID</th>
                                <th style="width: 10%">Category</th>
                                <th style="width: 50%">Question</th>
                                <th style="width: 20%">Options</th>
                                <th style="width: 15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($result) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td>
                                        <span class="badge <?php echo $row['category'] == 'technical' ? 'bg-primary' : 'bg-success'; ?>">
                                            <?php echo ucfirst($row['category']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars(substr($row['question'], 0, 80)) . (strlen($row['question']) > 80 ? '...' : ''); ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark">1: <?php echo htmlspecialchars(substr($row['option1'], 0, 20)); ?></span><br>
                                        <small class="text-muted">Correct: Option <?php echo $row['correct_answer']; ?></small>
                                    </td>
                                    <td>
                                        <a href="edit_question.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="delete_question.php?id=<?php echo $row['id']; ?>&csrf_token=<?php echo $_SESSION['csrf_token']; ?>" 
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this question? This action cannot be undone.')">
                                        <i class="fas fa-trash"></i> Delete
</a>

                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="fas fa-database fa-3x text-muted mb-3 d-block"></i>
                                        <p class="text-muted">No questions found. <a href="add_question.php">Add your first question</a></p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <?php if($total_pages > 1): ?>
                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <div class="text-muted small">
                        Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $limit, $total_questions); ?> of <?php echo $total_questions; ?> questions
                    </div>
                    <nav>
                        <ul class="pagination mb-0">
                            <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
                            </li>
                            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                            <?php endfor; ?>
                            <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
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