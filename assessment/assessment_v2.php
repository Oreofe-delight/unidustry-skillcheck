<?php
include("../includes/user_auth.php");

$category = isset($_GET['type']) && $_GET['type'] == 'soft' ? 'softskills' : 'technical';
$page_title = $category == 'technical' ? 'Technical Assessment' : 'Soft Skills Assessment';
$db_category = $category == 'technical' ? 'technical' : 'softskills';

// Get random questions based on type
$result = mysqli_query($conn, "SELECT * FROM questions WHERE category = '$db_category' ORDER BY RAND() LIMIT 10");
$questions = [];
while($row = mysqli_fetch_assoc($result)) {
    $questions[] = $row;
}
$total_questions = count($questions);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> | Skill Assessment</title>
    
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .question-container {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        .code-block {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 20px;
            border-radius: 12px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            overflow-x: auto;
            margin: 15px 0;
        }
        .answer-textarea {
            font-family: 'Courier New', monospace;
            font-size: 14px;
        }
        .option-label {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 12px 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .option-label:hover {
            background: #e9ecef;
        }
        .timer-box {
            position: sticky;
            top: 20px;
            background: linear-gradient(135deg, #4e54c8, #6c63ff);
            color: white;
            padding: 15px;
            border-radius: 15px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: bold;
            z-index: 100;
        }
    </style>
</head>
<body style="background: linear-gradient(135deg, #4e54c8, #8f94fb); min-height: 100vh; padding: 40px 0;">

<div class="container">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="timer-box mb-4">
                <i class="fas fa-clock me-2"></i>
                <span id="timer">30:00</span>
            </div>
            
            <form method="POST" action="submit_test_v2.php" id="assessmentForm">
                <input type="hidden" name="category" value="<?php echo $category; ?>">
                <input type="hidden" name="total" value="<?php echo $total_questions; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <?php foreach($questions as $index => $q): ?>
                <div class="question-container" id="q<?php echo $q['id']; ?>">
                    <h5>Question <?php echo $index + 1; ?></h5>
                    <p class="mt-2"><?php echo nl2br(htmlspecialchars($q['question'])); ?></p>
                    
                    <!-- Display code snippet if exists -->
                    <?php if(!empty($q['code_snippet'])): ?>
                    <div class="code-block">
                        <pre><?php echo htmlspecialchars($q['code_snippet']); ?></pre>
                    </div>
                    <?php endif; ?>
                    
                    <?php if($q['question_type'] == 'objective'): ?>
                        <!-- Objective: Multiple Choice -->
                        <div class="mt-3">
                            <div class="option-label" onclick="selectOption(this, <?php echo $q['id']; ?>, 1)">
                                <input type="radio" name="q<?php echo $q['id']; ?>" value="1" id="q<?php echo $q['id']; ?>_1">
                                <label for="q<?php echo $q['id']; ?>_1" class="mb-0 ms-2"><?php echo htmlspecialchars($q['option1']); ?></label>
                            </div>
                            <div class="option-label" onclick="selectOption(this, <?php echo $q['id']; ?>, 2)">
                                <input type="radio" name="q<?php echo $q['id']; ?>" value="2" id="q<?php echo $q['id']; ?>_2">
                                <label for="q<?php echo $q['id']; ?>_2" class="mb-0 ms-2"><?php echo htmlspecialchars($q['option2']); ?></label>
                            </div>
                            <div class="option-label" onclick="selectOption(this, <?php echo $q['id']; ?>, 3)">
                                <input type="radio" name="q<?php echo $q['id']; ?>" value="3" id="q<?php echo $q['id']; ?>_3">
                                <label for="q<?php echo $q['id']; ?>_3" class="mb-0 ms-2"><?php echo htmlspecialchars($q['option3']); ?></label>
                            </div>
                            <div class="option-label" onclick="selectOption(this, <?php echo $q['id']; ?>, 4)">
                                <input type="radio" name="q<?php echo $q['id']; ?>" value="4" id="q<?php echo $q['id']; ?>_4">
                                <label for="q<?php echo $q['id']; ?>_4" class="mb-0 ms-2"><?php echo htmlspecialchars($q['option4']); ?></label>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Theoretical/Coding/Debugging: Textarea input -->
                        <div class="mt-3">
                            <label class="form-label fw-semibold">Your Answer:</label>
                            <textarea name="q<?php echo $q['id']; ?>" class="form-control answer-textarea" rows="6" placeholder="Type your answer here..."></textarea>
                            <?php if($q['question_type'] == 'coding'): ?>
                            <small class="text-muted">Write your code solution here. Focus on logic and correctness.</small>
                            <?php elseif($q['question_type'] == 'debugging'): ?>
                            <small class="text-muted">Identify the bug and provide the corrected code.</small>
                            <?php else: ?>
                            <small class="text-muted">Provide a detailed explanation for your answer.</small>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-custom text-white px-5 py-2" onclick="return confirmSubmit()">
                        <i class="fas fa-paper-plane me-2"></i> Submit Assessment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let timeRemaining = 30 * 60; // 30 minutes
    const timerElement = document.getElementById('timer');
    
    function startTimer() {
        const interval = setInterval(() => {
            if(timeRemaining <= 0) {
                clearInterval(interval);
                alert('Time is up! Submitting your assessment...');
                document.getElementById('assessmentForm').submit();
            } else {
                timeRemaining--;
                const minutes = Math.floor(timeRemaining / 60);
                const seconds = timeRemaining % 60;
                timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            }
        }, 1000);
    }
    
    function selectOption(element, qid, value) {
        const radio = element.querySelector('input');
        if(radio) radio.checked = true;
        
        // Remove selected class from siblings
        const parent = element.parentElement;
        const options = parent.querySelectorAll('.option-label');
        options.forEach(opt => opt.classList.remove('selected'));
        element.classList.add('selected');
    }
    
    function confirmSubmit() {
        return confirm('Are you sure you want to submit your assessment?');
    }
    
    startTimer();
</script>

</body>
</html>