<?php
include("../includes/admin_auth.php");

$result_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($result_id <= 0) {
    header("Location: results.php");
    exit();
}

// Get result details
$stmt = mysqli_prepare($conn, "
    SELECT 
        r.*,
        u.fullname,
        u.student_id,
        u.email
    FROM results r
    INNER JOIN users u ON u.id = r.user_id
    WHERE r.id = ?
");
mysqli_stmt_bind_param($stmt, "i", $result_id);
mysqli_stmt_execute($stmt);
$result_data = mysqli_stmt_get_result($stmt);
$result = mysqli_fetch_assoc($result_data);

if(!$result) {
    header("Location: results.php");
    exit();
}

// Get user answers - MATCHING YOUR ACTUAL TABLE STRUCTURE
// No assessment_category column, so we just get answers for this user
// and match with questions
$answers_stmt = mysqli_prepare($conn, "
    SELECT 
        ua.*,
        q.question,
        q.option1, q.option2, q.option3, q.option4,
        q.correct_answer
    FROM user_answers ua
    INNER JOIN questions q ON q.id = ua.question_id
    WHERE ua.user_id = ?
    ORDER BY ua.id ASC
");
mysqli_stmt_bind_param($answers_stmt, "i", $result['user_id']);
mysqli_stmt_execute($answers_stmt);
$answers = mysqli_stmt_get_result($answers_stmt);

$answers_list = [];
while($ans = mysqli_fetch_assoc($answers)) {
    $answers_list[] = $ans;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result Details | Admin Panel</title>
    
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .result-summary {
            background: linear-gradient(135deg, #4e54c8, #6c63ff);
            color: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 25px;
        }
        .result-stat {
            text-align: center;
            padding: 15px;
            background: rgba(255,255,255,0.15);
            border-radius: 15px;
        }
        .result-stat .number {
            font-size: 2rem;
            font-weight: bold;
        }
        .question-review {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #6c63ff;
        }
        .question-review.correct {
            border-left-color: #28a745;
            background: #f0fff4;
        }
        .question-review.wrong {
            border-left-color: #dc3545;
            background: #fff5f5;
        }
        .user-option {
            background: white;
            border-radius: 8px;
            padding: 10px;
            margin-top: 10px;
        }
        .correct-answer {
            color: #28a745;
            font-weight: 500;
        }
        .wrong-answer {
            color: #dc3545;
            text-decoration: line-through;
        }
    </style>
</head>
<body>

<div class="dashboard-layout">
    <?php include("sidebar.php"); ?>
    
    <main class="main-content">
        <div class="container-fluid py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-file-alt me-2 text-primary"></i> Result Details</h2>
                <div>
                    <a href="results.php" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Back to Results
                    </a>
                    <button onclick="window.print()" class="btn btn-secondary">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                </div>
            </div>
            
            <!-- Result Summary -->
            <div class="result-summary">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h4><?php echo htmlspecialchars($result['fullname']); ?></h4>
                        <p class="mb-1">
                            <i class="fas fa-envelope me-2"></i> <?php echo htmlspecialchars($result['email']); ?>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-id-card me-2"></i> <?php echo htmlspecialchars($result['student_id']); ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-4">
                                <div class="result-stat">
                                    <div class="number"><?php echo $result['score']; ?>/<?php echo $result['total_questions']; ?></div>
                                    <div class="small">Score</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="result-stat">
                                    <div class="number"><?php echo round($result['percentage']); ?>%</div>
                                    <div class="small">Percentage</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="result-stat">
                                    <div class="number"><?php echo $result['percentage'] >= 70 ? 'PASS' : 'FAIL'; ?></div>
                                    <div class="small">Status</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <small>
                        <i class="fas fa-calendar-alt me-1"></i> 
                        Completed: <?php echo date('F j, Y, g:i A', strtotime($result['created_at'])); ?>
                    </small>
                    <span class="badge bg-info ms-2">
                        <?php echo ucfirst($result['category']); ?> Assessment
                    </span>
                </div>
            </div>
            
            <!-- Question Analysis -->
            <div class="admin-card">
                <h4 class="mb-4"><i class="fas fa-question-circle me-2 text-primary"></i> Question Analysis</h4>
                
                <?php if(count($answers_list) > 0): ?>
                    <?php 
                    $q_num = 1; 
                    $displayed = 0;
                    foreach($answers_list as $ans): 
                        // Only show up to the total questions in this result
                        if($displayed >= $result['total_questions']) break;
                        $displayed++;
                    ?>
                    <div class="question-review <?php echo ($ans['is_correct'] == 1) ? 'correct' : 'wrong'; ?>">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>Question <?php echo $q_num; ?>:</strong>
                                <p class="mb-2 mt-1"><?php echo htmlspecialchars($ans['question']); ?></p>
                            </div>
                            <span class="badge <?php echo ($ans['is_correct'] == 1) ? 'bg-success' : 'bg-danger'; ?>">
                                <?php echo ($ans['is_correct'] == 1) ? 'CORRECT' : 'WRONG'; ?>
                            </span>
                        </div>
                        
                        <div class="user-option">
                            <small class="text-muted">User's Answer:</small>
                            <div>
                                <?php 
                                $user_option = 'option' . $ans['selected_option'];
                                echo htmlspecialchars($ans[$user_option] ?? 'Not answered');
                                ?>
                            </div>
                            
                            <?php if($ans['is_correct'] != 1): ?>
                            <div class="mt-2">
                                <small class="text-muted">Correct Answer:</small>
                                <div class="text-success">
                                    <?php 
                                    $correct_option = 'option' . $ans['correct_answer'];
                                    echo htmlspecialchars($ans[$correct_option]);
                                    ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php $q_num++; endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-info-circle fa-2x mb-2 d-block"></i>
                        No answer details available for this assessment.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>