<?php
include("../includes/user_auth.php");

// Get assessment type from URL
$category = isset($_GET['type']) && $_GET['type'] == 'soft' ? 'softskills' : 'technical';
$page_title = $category == 'technical' ? 'Technical Assessment' : 'Soft Skills Assessment';

// Map category to database value
$db_category = $category == 'technical' ? 'technical' : 'softskills';

// Fetch questions
$result = mysqli_query($conn, "SELECT * FROM questions WHERE category = '$db_category' ORDER BY id");
$questions = [];
while($row = mysqli_fetch_assoc($result)) {
    $questions[] = $row;
}
$total_questions = count($questions);
$time_limit = $category == 'technical' ? 30 : 20; // 30 mins for technical, 20 for soft skills
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
        .timer-box {
            background: linear-gradient(135deg, #4e54c8, #6c63ff);
            color: white;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 1.5rem;
            font-weight: bold;
            text-align: center;
            position: sticky;
            top: 20px;
            z-index: 100;
        }
        .timer-box.warning {
            background: linear-gradient(135deg, #dc3545, #ff6b6b);
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .question-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        .question-number {
            background: #6c63ff;
            color: white;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }
        .option-label {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 12px 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }
        .option-label:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }
        .option-label input {
            margin-right: 12px;
            width: 18px;
            height: 18px;
        }
        .option-label.selected {
            background: #e8eaff;
            border-left: 4px solid #6c63ff;
        }
        .nav-buttons {
            position: sticky;
            bottom: 20px;
            background: white;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
            margin-top: 30px;
        }
        .progress {
            height: 8px;
            border-radius: 10px;
        }
        .progress-bar {
            background: linear-gradient(90deg, #4e54c8, #6c63ff);
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        .question-counter {
            font-size: 14px;
            color: #6c63ff;
            font-weight: 500;
        }
        .btn-option {
            background: white;
            border: 2px solid #e0e7ff;
            border-radius: 12px;
            padding: 12px 20px;
            width: 100%;
            text-align: left;
            transition: all 0.2s;
        }
        .btn-option:hover {
            border-color: #6c63ff;
            background: #f8f9ff;
        }
        .btn-option.active {
            border-color: #6c63ff;
            background: #e8eaff;
        }
        .saved-indicator {
            font-size: 12px;
            color: #28a745;
            margin-left: 10px;
        }
    </style>
</head>

<body class="assessment-page">
    <div class="auth-wrapper">
        <div class="assessment-card" style="max-width: 900px; width: 100%;">
            
            <!-- Header with Timer -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                <div>
                    <h4 class="mb-1"><?php echo $page_title; ?></h4>
                    <p class="text-muted mb-0">
                        <i class="fas fa-question-circle me-1"></i>
                        <span id="totalQuestions"><?php echo $total_questions; ?></span> questions
                    </p>
                </div>
                <div class="timer-box" id="timerBox">
                    <i class="fas fa-clock me-2"></i>
                    <span id="timer"><?php echo $time_limit; ?>:00</span>
                </div>
            </div>
            
            <!-- Progress Bar -->
            <div class="mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="question-counter">
                        <i class="fas fa-chart-line me-1"></i>
                        Progress
                    </span>
                    <span class="question-counter" id="progressPercent">0%</span>
                </div>
                <div class="progress">
                    <div class="progress-bar" id="progressBar" style="width: 0%"></div>
                </div>
            </div>
            
            <form method="POST" action="submit_test.php" id="assessmentForm">
                <input type="hidden" name="category" value="<?php echo $category; ?>">
                <input type="hidden" name="total" value="<?php echo $total_questions; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="time_spent" id="timeSpent" value="">
                
                <div id="questionsContainer">
                    <?php foreach($questions as $index => $q): ?>
                    <div class="question-card" id="q_<?php echo $q['id']; ?>" style="display: <?php echo $index == 0 ? 'block' : 'none'; ?>">
                        <div class="d-flex align-items-center mb-3">
                            <span class="question-number"><?php echo $index + 1; ?></span>
                            <h5 class="mb-0"><?php echo h($q['question']); ?></h5>
                        </div>
                        
                        <div class="mt-3">
                            <?php 
                            $options = [
                                1 => $q['option1'],
                                2 => $q['option2'],
                                3 => $q['option3'],
                                4 => $q['option4']
                            ];
                            foreach($options as $val => $option): 
                                if(empty($option)) continue;
                            ?>
                            <div class="option-label" onclick="selectOption(this, <?php echo $q['id']; ?>, <?php echo $val; ?>)">
                                <input type="radio" 
                                       name="q<?php echo $q['id']; ?>" 
                                       value="<?php echo $val; ?>"
                                       id="q<?php echo $q['id']; ?>_<?php echo $val; ?>"
                                       onchange="saveAnswer(<?php echo $q['id']; ?>, <?php echo $val; ?>)">
                                <label for="q<?php echo $q['id']; ?>_<?php echo $val; ?>" class="mb-0" style="cursor: pointer; width: 100%;">
                                    <?php echo h($option); ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="text-end mt-3">
                            <small class="text-muted" id="saved_<?php echo $q['id']; ?>">
                                <i class="far fa-save"></i> Not answered
                            </small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Navigation Buttons -->
                <div class="nav-buttons">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary" id="prevBtn" onclick="changeQuestion(-1)" disabled>
                            <i class="fas fa-chevron-left me-2"></i> Previous
                        </button>
                        <div>
                            <span class="badge bg-secondary px-3 py-2" id="currentQDisplay">1</span>
                            <span class="text-muted"> / <?php echo $total_questions; ?></span>
                        </div>
                        <button type="button" class="btn btn-outline-primary" id="nextBtn" onclick="changeQuestion(1)">
                            Next <i class="fas fa-chevron-right ms-2"></i>
                        </button>
                    </div>
                    
                    <div class="text-center mt-3 pt-2 border-top">
                        <button type="submit" class="btn btn-custom text-white px-5 py-2" onclick="return confirmSubmit()">
                            <i class="fas fa-paper-plane me-2"></i> Submit Test
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Configuration
        const totalQuestions = <?php echo $total_questions; ?>;
        const timeLimit = <?php echo $time_limit; ?>; // in minutes
        let currentQuestion = 0;
        let timeRemaining = timeLimit * 60; // in seconds
        let timerInterval;
        let answers = {};
        
        // Load saved answers from localStorage
        function loadSavedAnswers() {
            const saved = localStorage.getItem('assessment_answers_<?php echo $category; ?>');
            if(saved) {
                answers = JSON.parse(saved);
                // Restore selected options
                for(const [qId, val] of Object.entries(answers)) {
                    const radio = document.querySelector(`input[name="q${qId}"][value="${val}"]`);
                    if(radio) {
                        radio.checked = true;
                        const optionDiv = radio.closest('.option-label');
                        if(optionDiv) optionDiv.classList.add('selected');
                        document.getElementById(`saved_${qId}`).innerHTML = '<i class="fas fa-check-circle text-success"></i> Saved';
                    }
                }
                updateProgress();
            }
        }
        
        // Save answer to localStorage
        function saveAnswer(questionId, value) {
            answers[questionId] = value;
            localStorage.setItem('assessment_answers_<?php echo $category; ?>', JSON.stringify(answers));
            document.getElementById(`saved_${questionId}`).innerHTML = '<i class="fas fa-check-circle text-success"></i> Saved';
            updateProgress();
        }
        
        // Select option by clicking the div
        function selectOption(element, questionId, value) {
            const radio = element.querySelector('input');
            if(radio) {
                radio.checked = true;
                saveAnswer(questionId, value);
            }
            // Remove selected class from siblings
            const parent = element.parentElement;
            const options = parent.querySelectorAll('.option-label');
            options.forEach(opt => opt.classList.remove('selected'));
            element.classList.add('selected');
        }
        
        // Update progress bar
        function updateProgress() {
            const answeredCount = Object.keys(answers).length;
            const percent = (answeredCount / totalQuestions) * 100;
            document.getElementById('progressBar').style.width = percent + '%';
            document.getElementById('progressPercent').innerText = Math.round(percent) + '%';
        }
        
        // Navigate between questions
        function changeQuestion(direction) {
            // Hide current question
            const questions = document.querySelectorAll('.question-card');
            questions[currentQuestion].style.display = 'none';
            
            // Update current question index
            currentQuestion += direction;
            
            // Show new question
            questions[currentQuestion].style.display = 'block';
            
            // Update navigation buttons
            document.getElementById('prevBtn').disabled = currentQuestion === 0;
            document.getElementById('nextBtn').disabled = currentQuestion === totalQuestions - 1;
            
            // Update current question display
            document.getElementById('currentQDisplay').innerText = currentQuestion + 1;
        }
        
        // Timer functionality
        function startTimer() {
            timerInterval = setInterval(function() {
                if(timeRemaining <= 0) {
                    clearInterval(timerInterval);
                    document.getElementById('timerBox').classList.add('warning');
                    document.getElementById('timer').innerHTML = '0:00';
                    alert('Time is up! Submitting your assessment...');
                    document.getElementById('assessmentForm').submit();
                } else {
                    timeRemaining--;
                    const minutes = Math.floor(timeRemaining / 60);
                    const seconds = timeRemaining % 60;
                    document.getElementById('timer').innerHTML = `${minutes}:${seconds.toString().padStart(2, '0')}`;
                    
                    // Warning when 1 minute left
                    if(timeRemaining <= 60) {
                        document.getElementById('timerBox').classList.add('warning');
                    }
                    
                    // Store time spent
                    const timeSpent = (timeLimit * 60) - timeRemaining;
                    document.getElementById('timeSpent').value = timeSpent;
                }
            }, 1000);
        }
        
        // Confirm submission
        function confirmSubmit() {
            const answeredCount = Object.keys(answers).length;
            if(answeredCount < totalQuestions) {
                const confirmMsg = `You have answered ${answeredCount} out of ${totalQuestions} questions.\n\nAre you sure you want to submit?`;
                return confirm(confirmMsg);
            }
            return confirm('Are you sure you want to submit your assessment?');
        }
        
        // Load saved answers on page load
        loadSavedAnswers();
        
        // Start timer
        startTimer();
        
        // Warn before leaving page
        window.addEventListener('beforeunload', function(e) {
            if(Object.keys(answers).length > 0 && timeRemaining > 0) {
                e.preventDefault();
                e.returnValue = 'You have unsaved answers. Are you sure you want to leave?';
            }
        });
        
        // Save answers before form submission
        document.getElementById('assessmentForm').addEventListener('submit', function() {
            localStorage.removeItem('assessment_answers_<?php echo $category; ?>');
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>