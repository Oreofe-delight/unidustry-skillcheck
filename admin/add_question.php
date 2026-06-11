<?php
include("../includes/admin_auth.php");

$message = "";
$message_type = "";

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if(isset($_POST['add_question'])){
    
    if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = "Security verification failed!";
        $message_type = "danger";
    } else {
        $category = $_POST['category'];
        $question_type = $_POST['question_type'];
        $question = trim($_POST['question']);
        
        // Objective type fields
        $option1 = trim($_POST['option1'] ?? '');
        $option2 = trim($_POST['option2'] ?? '');
        $option3 = trim($_POST['option3'] ?? '');
        $option4 = trim($_POST['option4'] ?? '');
        $correct_answer = $_POST['correct_answer'] ?? null;
        
        // Theoretical/Coding type fields
        $expected_answer = trim($_POST['expected_answer'] ?? '');
        $code_snippet = trim($_POST['code_snippet'] ?? '');
        $language = $_POST['language'] ?? 'php';
        
        $recommendation_type = $_POST['recommendation_type'] ?? null;
        $recommendation_title = trim($_POST['recommendation_title'] ?? '');
        $recommendation_link = trim($_POST['recommendation_link'] ?? '');
        
        // Validation
        $errors = [];
        if(empty($question)) $errors[] = "Question is required";
        
        if($question_type == 'objective') {
            if(empty($option1) || empty($option2) || empty($option3) || empty($option4)) {
                $errors[] = "All options are required for objective questions";
            }
            if(empty($correct_answer)) $errors[] = "Correct answer is required";
        } else {
            if(empty($expected_answer)) $errors[] = "Expected answer is required for " . $question_type . " questions";
        }
        
        if(empty($errors)) {
            $stmt = mysqli_prepare($conn, "
                INSERT INTO questions 
                (category, question_type, question, option1, option2, option3, option4, correct_answer, 
                 expected_answer, code_snippet, language, recommendation_type, recommendation_title, recommendation_link) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            mysqli_stmt_bind_param($stmt, "sssssssissssss", 
                $category, $question_type, $question, $option1, $option2, $option3, $option4, 
                $correct_answer, $expected_answer, $code_snippet, $language,
                $recommendation_type, $recommendation_title, $recommendation_link
            );
            
            if(mysqli_stmt_execute($stmt)) {
                $message = "Question added successfully!";
                $message_type = "success";
                $_POST = [];
            } else {
                $message = "Error adding question: " . mysqli_error($conn);
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
    <title>Add Question | Admin Panel</title>
    
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .question-type-card {
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid #e0e7ff;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
        }
        .question-type-card:hover {
            border-color: #6c63ff;
            background: #f8f9ff;
        }
        .question-type-card.selected {
            border-color: #6c63ff;
            background: #e8eaff;
        }
        .code-preview {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 15px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="dashboard-layout">
    <?php include("sidebar.php"); ?>
    
    <main class="main-content">
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-plus-circle me-2 text-primary"></i> Add New Question</h2>
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
            
            <form method="POST" class="bg-white p-4 rounded-4 shadow-sm" id="questionForm">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <!-- Category & Question Type -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Category</label>
                        <select name="category" class="form-select" required>
                            <option value="technical">Technical</option>
                            <option value="softskills">Soft Skills</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Question Type</label>
                        <select name="question_type" id="questionType" class="form-select" required>
                            <option value="objective">Multiple Choice (Objective)</option>
                            <option value="theoretical">Theoretical / Essay</option>
                            <option value="coding">Coding Challenge</option>
                            <option value="debugging">Debugging Challenge</option>
                        </select>
                    </div>
                </div>
                
                <!-- Question Text -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Question</label>
                    <textarea name="question" class="form-control" rows="3" required><?php echo htmlspecialchars($_POST['question'] ?? ''); ?></textarea>
                </div>
                
                <!-- Objective Type Fields (shown by default) -->
                <div id="objectiveFields">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Option 1</label>
                            <input type="text" name="option1" class="form-control" value="<?php echo htmlspecialchars($_POST['option1'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Option 2</label>
                            <input type="text" name="option2" class="form-control" value="<?php echo htmlspecialchars($_POST['option2'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Option 3</label>
                            <input type="text" name="option3" class="form-control" value="<?php echo htmlspecialchars($_POST['option3'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Option 4</label>
                            <input type="text" name="option4" class="form-control" value="<?php echo htmlspecialchars($_POST['option4'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correct Answer</label>
                        <select name="correct_answer" class="form-select">
                            <option value="1">Option 1</option>
                            <option value="2">Option 2</option>
                            <option value="3">Option 3</option>
                            <option value="4">Option 4</option>
                        </select>
                    </div>
                </div>
                
                <!-- Theoretical/Coding Type Fields (hidden by default) -->
                <div id="theoreticalFields" style="display: none;">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Expected Answer (Keywords/Pattern)</label>
                        <textarea name="expected_answer" class="form-control" rows="4" placeholder="Enter expected keywords or answer pattern..."><?php echo htmlspecialchars($_POST['expected_answer'] ?? ''); ?></textarea>
                        <small class="text-muted">For coding/debugging, enter expected output or key phrases to match</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Code Snippet (Optional for theoretical questions)</label>
                        <textarea name="code_snippet" class="form-control code-preview" rows="8" placeholder="// Paste code here for coding/debugging questions&#10;function example() {&#10;    return true;&#10;}"><?php echo htmlspecialchars($_POST['code_snippet'] ?? ''); ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Programming Language</label>
                        <select name="language" class="form-select">
                            <option value="php">PHP</option>
                            <option value="javascript">JavaScript</option>
                            <option value="python">Python</option>
                            <option value="java">Java</option>
                            <option value="html">HTML/CSS</option>
                            <option value="sql">SQL</option>
                        </select>
                    </div>
                </div>
                
                <!-- Soft Skills Scenario Fields -->
                <div id="softSkillsFields" style="display: none;">
                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb me-2"></i>
                        For soft skills, describe a workplace scenario and ask how the student would respond.
                    </div>
                </div>
                
                <hr>
                <h5 class="mb-3">Learning Recommendation (Optional)</h5>
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Resource Type</label>
                        <select name="recommendation_type" class="form-select">
                            <option value="">None</option>
                            <option value="youtube">YouTube</option>
                            <option value="w3schools">W3Schools</option>
                            <option value="freecodecamp">FreeCodeCamp</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Resource Title</label>
                        <input type="text" name="recommendation_title" class="form-control" value="<?php echo htmlspecialchars($_POST['recommendation_title'] ?? ''); ?>">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Resource Link</label>
                        <input type="url" name="recommendation_link" class="form-control" value="<?php echo htmlspecialchars($_POST['recommendation_link'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" name="add_question" class="btn btn-custom text-white px-4">
                        <i class="fas fa-save me-2"></i> Add Question
                    </button>
                    <button type="reset" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-undo me-2"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<script>
    // Toggle fields based on question type
    const questionType = document.getElementById('questionType');
    const objectiveFields = document.getElementById('objectiveFields');
    const theoreticalFields = document.getElementById('theoreticalFields');
    const softSkillsFields = document.getElementById('softSkillsFields');
    const categorySelect = document.querySelector('select[name="category"]');
    
    function toggleFields() {
        const type = questionType.value;
        const category = categorySelect.value;
        
        // Hide all first
        objectiveFields.style.display = 'none';
        theoreticalFields.style.display = 'none';
        softSkillsFields.style.display = 'none';
        
        if(category == 'softskills') {
            softSkillsFields.style.display = 'block';
            objectiveFields.style.display = 'none';
            theoreticalFields.style.display = 'block';
        } else {
            if(type == 'objective') {
                objectiveFields.style.display = 'block';
            } else {
                theoreticalFields.style.display = 'block';
            }
        }
        
        // Make required fields optional based on type
        const options = document.querySelectorAll('input[name="option1"], input[name="option2"], input[name="option3"], input[name="option4"]');
        options.forEach(opt => opt.required = (type == 'objective'));
    }
    
    questionType.addEventListener('change', toggleFields);
    categorySelect.addEventListener('change', toggleFields);
    toggleFields();
</script>

</body>
</html>