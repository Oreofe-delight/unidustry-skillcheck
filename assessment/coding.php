<?php
include("../includes/user_auth.php");

// Get coding challenges from database
$challenge_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Fetch coding challenge
$stmt = mysqli_prepare($conn, "SELECT * FROM coding_challenges WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $challenge_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$challenge = mysqli_fetch_assoc($result);

if(!$challenge) {
    header("Location: coding_list.php");
    exit();
}

// Get all challenges for sidebar
$all_challenges = mysqli_query($conn, "SELECT id, title FROM coding_challenges ORDER BY difficulty_level");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coding Challenge | <?php echo htmlspecialchars($challenge['title']); ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&display=swap" rel="stylesheet">
    
    <style>
        body {
            background: #1e1e2e;
            font-family: 'Poppins', sans-serif;
        }
        
        .coding-container {
            display: flex;
            height: 100vh;
        }
        
        .sidebar-challenges {
            width: 280px;
            background: #2d2d3d;
            color: white;
            padding: 20px;
            overflow-y: auto;
        }
        
        .challenge-item {
            background: #3d3d4d;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .challenge-item:hover, .challenge-item.active {
            background: #4e54c8;
        }
        
        .difficulty-easy { color: #28a745; }
        .difficulty-medium { color: #ffc107; }
        .difficulty-hard { color: #dc3545; }
        
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .problem-panel {
            background: white;
            padding: 25px;
            overflow-y: auto;
            height: 50%;
            border-bottom: 2px solid #dee2e6;
        }
        
        .editor-panel {
            background: #1a1a2e;
            height: 50%;
            display: flex;
            flex-direction: column;
        }
        
        .code-editor {
            flex: 1;
            background: #1a1a2e;
            color: #00ff9d;
            font-family: 'Fira Code', monospace;
            font-size: 14px;
            padding: 15px;
            border: none;
            resize: none;
            outline: none;
        }
        
        .output-panel {
            background: #0d0d1a;
            color: #fff;
            padding: 15px;
            height: 150px;
            overflow-y: auto;
            font-family: 'Fira Code', monospace;
            font-size: 13px;
        }
        
        .toolbar {
            background: #2d2d3d;
            padding: 10px 20px;
            display: flex;
            gap: 10px;
        }
        
        .btn-run {
            background: #28a745;
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
        }
        
        .btn-submit {
            background: #4e54c8;
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
        }
        
        .test-case {
            background: #2d2d3d;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
        }
        
        .test-case.passed {
            border-left: 4px solid #28a745;
        }
        
        .test-case.failed {
            border-left: 4px solid #dc3545;
        }
        
        .status-icon {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

<div class="coding-container">
    <!-- Challenges Sidebar -->
    <div class="sidebar-challenges">
        <h5 class="mb-3"><i class="fas fa-code me-2"></i> Coding Challenges</h5>
        <?php while($item = mysqli_fetch_assoc($all_challenges)): ?>
        <div class="challenge-item <?php echo $item['id'] == $challenge_id ? 'active' : ''; ?>" 
             onclick="window.location.href='coding.php?id=<?php echo $item['id']; ?>'">
            <div class="d-flex justify-content-between align-items-center">
                <strong><?php echo htmlspecialchars($item['title']); ?></strong>
                <span class="difficulty-<?php echo $challenge['difficulty_level']; ?>">
                    <?php echo ucfirst($challenge['difficulty_level']); ?>
                </span>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Problem Panel -->
        <div class="problem-panel">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h3><?php echo htmlspecialchars($challenge['title']); ?></h3>
                    <span class="badge bg-<?php 
                        echo $challenge['difficulty_level'] == 'easy' ? 'success' : 
                            ($challenge['difficulty_level'] == 'medium' ? 'warning' : 'danger'); 
                    ?> mb-3">
                        <?php echo ucfirst($challenge['difficulty_level']); ?>
                    </span>
                </div>
                <div class="text-end">
                    <small class="text-muted">Points: <?php echo $challenge['points']; ?></small>
                </div>
            </div>
            
            <div class="mt-3">
                <h6>Problem Description:</h6>
                <p><?php echo nl2br(htmlspecialchars($challenge['description'])); ?></p>
            </div>
            
            <div class="mt-3">
                <h6>Sample Input:</h6>
                <pre class="bg-light p-2 rounded"><?php echo htmlspecialchars($challenge['sample_input']); ?></pre>
            </div>
            
            <div class="mt-2">
                <h6>Sample Output:</h6>
                <pre class="bg-light p-2 rounded"><?php echo htmlspecialchars($challenge['sample_output']); ?></pre>
            </div>
            
            <div class="mt-3">
                <h6>Constraints:</h6>
                <p><?php echo nl2br(htmlspecialchars($challenge['constraints'])); ?></p>
            </div>
        </div>
        
        <!-- Code Editor Panel -->
        <div class="editor-panel">
            <div class="toolbar">
                <button class="btn-run" id="runCodeBtn">
                    <i class="fas fa-play me-2"></i> Run Code
                </button>
                <button class="btn-submit" id="submitCodeBtn">
                    <i class="fas fa-check-circle me-2"></i> Submit Solution
                </button>
                <button class="btn btn-secondary" id="resetCodeBtn">
                    <i class="fas fa-undo me-2"></i> Reset
                </button>
                <select id="languageSelect" class="form-select w-auto">
                    <option value="python">Python 3</option>
                    <option value="javascript">JavaScript</option>
                    <option value="php">PHP</option>
                </select>
            </div>
            
            <textarea id="codeEditor" class="code-editor" placeholder="Write your code here..."><?php 
echo htmlspecialchars($challenge['starter_code'] ?? 
'# Write your solution here

def solve():
    # Your code here
    pass

if __name__ == "__main__":
    solve()');
?></textarea>
            
            <div class="output-panel" id="outputPanel">
                <div class="text-muted">Click "Run Code" to test your solution...</div>
            </div>
        </div>
    </div>
</div>

<script>
const runBtn = document.getElementById('runCodeBtn');
const submitBtn = document.getElementById('submitCodeBtn');
const resetBtn = document.getElementById('resetCodeBtn');
const codeEditor = document.getElementById('codeEditor');
const outputPanel = document.getElementById('outputPanel');
const languageSelect = document.getElementById('languageSelect');
const challengeId = <?php echo $challenge_id; ?>;

// Run Code
runBtn.addEventListener('click', async function() {
    const code = codeEditor.value;
    const language = languageSelect.value;
    
    outputPanel.innerHTML = '<div class="text-info"><i class="fas fa-spinner fa-spin me-2"></i> Running code...</div>';
    
    try {
        const response = await fetch('run_code.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ code: code, language: language, challenge_id: challengeId })
        });
        const result = await response.json();
        
        if(result.success) {
            outputPanel.innerHTML = `
                <div class="text-success"><i class="fas fa-check-circle me-2"></i> Output:</div>
                <pre style="color: #fff; margin-top: 10px;">${escapeHtml(result.output)}</pre>
            `;
        } else {
            outputPanel.innerHTML = `
                <div class="text-danger"><i class="fas fa-exclamation-circle me-2"></i> Error:</div>
                <pre style="color: #ff6b6b; margin-top: 10px;">${escapeHtml(result.error)}</pre>
            `;
        }
    } catch(error) {
        outputPanel.innerHTML = `<div class="text-danger">Error: ${error.message}</div>`;
    }
});

// Submit Code
submitBtn.addEventListener('click', async function() {
    const code = codeEditor.value;
    const language = languageSelect.value;
    
    outputPanel.innerHTML = '<div class="text-info"><i class="fas fa-spinner fa-spin me-2"></i> Submitting and testing...</div>';
    
    try {
        const response = await fetch('submit_code.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ code: code, language: language, challenge_id: challengeId })
        });
        const result = await response.json();
        
        if(result.success) {
            let html = `<div class="text-success"><i class="fas fa-trophy me-2"></i> ${result.message}</div>`;
            
            if(result.test_results) {
                html += '<div class="mt-3"><strong>Test Results:</strong></div>';
                for(const test of result.test_results) {
                    html += `
                        <div class="test-case ${test.passed ? 'passed' : 'failed'}">
                            <div class="d-flex justify-content-between">
                                <span>Test Case ${test.id}</span>
                                <span class="status-icon">
                                    ${test.passed ? '✅' : '❌'}
                                </span>
                            </div>
                            <div class="small text-muted">Expected: ${test.expected}</div>
                            <div class="small text-muted">Got: ${test.got}</div>
                        </div>
                    `;
                }
            }
            
            outputPanel.innerHTML = html;
            
            if(result.score) {
                showNotification(`You scored ${result.score} points!`, 'success');
            }
        } else {
            outputPanel.innerHTML = `<div class="text-danger">${result.message}</div>`;
        }
    } catch(error) {
        outputPanel.innerHTML = `<div class="text-danger">Error: ${error.message}</div>`;
    }
});

// Reset Code
resetBtn.addEventListener('click', function() {
    const defaultCode = `# Write your solution here

def solve():
    # Your code here
    pass

if __name__ == "__main__":
    solve()`;
    codeEditor.value = defaultCode;
    outputPanel.innerHTML = '<div class="text-muted">Code reset. Click "Run Code" to test...</div>';
});

// Save code to localStorage
function saveCode() {
    localStorage.setItem(`code_${challengeId}_${languageSelect.value}`, codeEditor.value);
}

function loadCode() {
    const saved = localStorage.getItem(`code_${challengeId}_${languageSelect.value}`);
    if(saved) {
        codeEditor.value = saved;
    }
}

codeEditor.addEventListener('input', saveCode);
languageSelect.addEventListener('change', loadCode);
loadCode();

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} position-fixed top-0 end-0 m-3`;
    notification.innerHTML = message;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}
</script>

</body>
</html>