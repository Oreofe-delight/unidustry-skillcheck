<?php
include("../includes/admin_auth.php");

// Generate CSRF token
if(empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id <= 0) {
    header("Location: manage_questions.php");
    exit();
}

// Fetch question - SECURE
$stmt = mysqli_prepare($conn, "SELECT * FROM questions WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$question = mysqli_fetch_assoc($result);

if(!$question) {
    header("Location: manage_questions.php");
    exit();
}

$message = "";
$message_type = "";

if(isset($_POST['update_question'])){
    
    // Verify CSRF
    if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = "Security verification failed!";
        $message_type = "danger";
    } else {
        $category = $_POST['category'];
        $question_text = trim($_POST['question']);
        $option1 = trim($_POST['option1']);
        $option2 = trim($_POST['option2']);
        $option3 = trim($_POST['option3']);
        $option4 = trim($_POST['option4']);
        $correct_answer = $_POST['correct_answer'];
        $recommendation_type = $_POST['recommendation_type'] ?? null;
        $recommendation_title = trim($_POST['recommendation_title'] ?? '');
        $recommendation_link = trim($_POST['recommendation_link'] ?? '');
        
        // Validation
        $errors = [];
        if(empty($question_text)) $errors[] = "Question is required";
        if(empty($option1)) $errors[] = "Option 1 is required";
        if(empty($option2)) $errors[] = "Option 2 is required";
        if(empty($option3)) $errors[] = "Option 3 is required";
        if(empty($option4)) $errors[] = "Option 4 is required";
        
        if(empty($errors)) {
            $update_stmt = mysqli_prepare($conn, "
                UPDATE questions SET 
                    category = ?, 
                    question = ?, 
                    option1 = ?, 
                    option2 = ?, 
                    option3 = ?, 
                    option4 = ?, 
                    correct_answer = ?,
                    recommendation_type = ?,
                    recommendation_title = ?,
                    recommendation_link = ?
                WHERE id = ?
            ");
            
            mysqli_stmt_bind_param($update_stmt, "ssssssisssi", 
                $category, $question_text, $option1, $option2, $option3, $option4, 
                $correct_answer, $recommendation_type, $recommendation_title, 
                $recommendation_link, $id
            );
            
            if(mysqli_stmt_execute($update_stmt)) {
                $message = "Question updated successfully!";
                $message_type = "success";
                // Refresh data
                $stmt = mysqli_prepare($conn, "SELECT * FROM questions WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "i", $id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $question = mysqli_fetch_assoc($result);
            } else {
                $message = "Error updating question: " . mysqli_error($conn);
                $message_type = "danger";
            }
        } else {
            $message = implode("<br>", $errors);
            $message_type = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question | Admin Panel</title>
    
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
                <h2><i class="fas fa-edit me-2 text-warning"></i> Edit Question</h2>
                <a href="manage_questions.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Questions
                </a>
            </div>
            
            <?php if($message): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show">
                <i class="fas fa-<?php echo $message_type == 'success' ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="bg-white p-4 rounded-4 shadow-sm">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Category</label>
                        <select name="category" class="form-select" required>
                            <option value="technical" <?php echo $question['category'] == 'technical' ? 'selected' : ''; ?>>Technical</option>
                            <option value="softskills" <?php echo $question['category'] == 'softskills' ? 'selected' : ''; ?>>Soft Skills</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Question</label>
                    <textarea name="question" class="form-control" rows="3" required><?php echo htmlspecialchars($question['question']); ?></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Option 1</label>
                        <input type="text" name="option1" class="form-control" value="<?php echo htmlspecialchars($question['option1']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Option 2</label>
                        <input type="text" name="option2" class="form-control" value="<?php echo htmlspecialchars($question['option2']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Option 3</label>
                        <input type="text" name="option3" class="form-control" value="<?php echo htmlspecialchars($question['option3']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Option 4</label>
                        <input type="text" name="option4" class="form-control" value="<?php echo htmlspecialchars($question['option4']); ?>" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-semibold">Correct Answer</label>
                    <select name="correct_answer" class="form-select" required>
                        <option value="1" <?php echo $question['correct_answer'] == 1 ? 'selected' : ''; ?>>Option 1</option>
                        <option value="2" <?php echo $question['correct_answer'] == 2 ? 'selected' : ''; ?>>Option 2</option>
                        <option value="3" <?php echo $question['correct_answer'] == 3 ? 'selected' : ''; ?>>Option 3</option>
                        <option value="4" <?php echo $question['correct_answer'] == 4 ? 'selected' : ''; ?>>Option 4</option>
                    </select>
                </div>
                
                <hr>
                <h5 class="mb-3">Learning Recommendation (Optional)</h5>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Resource Type</label>
                        <select name="recommendation_type" class="form-select">
                            <option value="">None</option>
                            <option value="youtube" <?php echo ($question['recommendation_type'] ?? '') == 'youtube' ? 'selected' : ''; ?>>YouTube</option>
                            <option value="w3schools" <?php echo ($question['recommendation_type'] ?? '') == 'w3schools' ? 'selected' : ''; ?>>W3Schools</option>
                            <option value="freecodecamp" <?php echo ($question['recommendation_type'] ?? '') == 'freecodecamp' ? 'selected' : ''; ?>>FreeCodeCamp</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Resource Title</label>
                        <input type="text" name="recommendation_title" class="form-control" value="<?php echo htmlspecialchars($question['recommendation_title'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Resource Link</label>
                        <input type="url" name="recommendation_link" class="form-control" value="<?php echo htmlspecialchars($question['recommendation_link'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" name="update_question" class="btn btn-warning px-4">
                        <i class="fas fa-save me-2"></i> Update Question
                    </button>
                    <a href="manage_questions.php" class="btn btn-secondary px-4">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>