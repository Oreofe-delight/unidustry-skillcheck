<?php
include("../includes/admin_auth.php");

$message = "";
$message_type = "";

// Handle CSV Upload
if(isset($_POST['upload_questions'])) {
    
    // Check CSRF token
    if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = "Security verification failed!";
        $message_type = "danger";
    } elseif(empty($_FILES['csv_file']['name'])) {
        $message = "Please select a CSV file to upload.";
        $message_type = "danger";
    } else {
        $file = $_FILES['csv_file']['tmp_name'];
        $filename = $_FILES['csv_file']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if($ext != 'csv') {
            $message = "Only CSV files are allowed.";
            $message_type = "danger";
        } else {
            $handle = fopen($file, "r");
            $header = fgetcsv($handle); // Skip header row
            
            $success_count = 0;
            $error_count = 0;
            $errors = [];
            
            while(($row = fgetcsv($handle)) !== false) {
                // Skip empty rows
                if(empty(array_filter($row))) continue;
                
                // Expecting: category, question, option1, option2, option3, option4, correct_answer
                $category = trim($row[0] ?? '');
                $question = trim($row[1] ?? '');
                $option1 = trim($row[2] ?? '');
                $option2 = trim($row[3] ?? '');
                $option3 = trim($row[4] ?? '');
                $option4 = trim($row[5] ?? '');
                $correct_answer = trim($row[6] ?? '');
                
                // Validate
                if(empty($category) || empty($question) || empty($option1) || empty($option2) || empty($option3) || empty($option4) || empty($correct_answer)) {
                    $error_count++;
                    $errors[] = "Missing data in row: " . json_encode($row);
                    continue;
                }
                
                if(!in_array($category, ['technical', 'softskills'])) {
                    $error_count++;
                    $errors[] = "Invalid category '$category' for question: " . substr($question, 0, 50);
                    continue;
                }
                
                if(!in_array($correct_answer, ['1', '2', '3', '4'])) {
                    $error_count++;
                    $errors[] = "Invalid correct_answer '$correct_answer' for question: " . substr($question, 0, 50);
                    continue;
                }
                
                // Insert question
                $stmt = mysqli_prepare($conn, "
                    INSERT INTO questions (category, question, option1, option2, option3, option4, correct_answer) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                mysqli_stmt_bind_param($stmt, "sssssss", $category, $question, $option1, $option2, $option3, $option4, $correct_answer);
                
                if(mysqli_stmt_execute($stmt)) {
                    $success_count++;
                } else {
                    $error_count++;
                    $errors[] = "Database error: " . mysqli_error($conn);
                }
            }
            fclose($handle);
            
            if($success_count > 0) {
                $message = "Successfully uploaded $success_count questions!";
                $message_type = "success";
                if($error_count > 0) {
                    $message .= " ($error_count errors)";
                }
            } else {
                $message = "No questions were uploaded. Please check your CSV format.";
                $message_type = "danger";
            }
            
            // Store errors in session for display
            if(!empty($errors)) {
                $_SESSION['upload_errors'] = $errors;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Upload Questions | Admin Panel</title>
    
    <link href="../assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .upload-area {
            border: 2px dashed #6c63ff;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s;
            cursor: pointer;
        }
        .upload-area:hover {
            background: #f0f0ff;
            border-color: #4e54c8;
        }
        .upload-area.dragover {
            background: #e8eaff;
            border-color: #4e54c8;
        }
        .preview-table {
            max-height: 400px;
            overflow-y: auto;
        }
        .csv-format {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            font-family: monospace;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="dashboard-layout">
    <?php include("sidebar.php"); ?>
    
    <main class="main-content">
        <div class="container-fluid py-4">
            <div class="admin-card">
                <div class="admin-header d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h2><i class="fas fa-upload me-2 text-primary"></i> Bulk Upload Questions</h2>
                        <p>Upload multiple questions at once using a CSV file</p>
                    </div>
                    <a href="add_question.php" class="btn btn-outline-secondary">
                        <i class="fas fa-plus-circle me-1"></i> Add Single Question
                    </a>
                </div>
                
                <!-- Alert Messages -->
                <?php if($message): ?>
                <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show">
                    <i class="fas fa-<?php echo $message_type == 'success' ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>
                
                <?php if(isset($_SESSION['upload_errors']) && !empty($_SESSION['upload_errors'])): ?>
                <div class="alert alert-warning">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i> Upload Errors:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach($_SESSION['upload_errors'] as $err): ?>
                        <li><?php echo htmlspecialchars($err); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php unset($_SESSION['upload_errors']); ?>
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- CSV Format Guide -->
                        <div class="mb-4">
                            <h5 class="mb-3"><i class="fas fa-info-circle me-2 text-primary"></i> CSV Format Guide</h5>
                            <div class="csv-format">
                                <strong>Required columns (in this order):</strong><br>
                                <code>category,question,option1,option2,option3,option4,correct_answer</code>
                                <hr class="my-2">
                                <strong>Example row:</strong><br>
                                <code>technical,"What does HTML stand for?","Hyper Text Markup Language","Home Tool Markup Language","High Text Machine Language","Hyper Transfer Markup Language",1</code>
                                <hr class="my-2">
                                <strong>Category values:</strong> <code>technical</code> or <code>softskills</code><br>
                                <strong>Correct answer:</strong> <code>1</code>, <code>2</code>, <code>3</code>, or <code>4</code>
                            </div>
                        </div>
                        
                        <!-- Download Sample CSV -->
                        <div class="mb-4">
                            <a href="download_sample_csv.php" class="btn btn-outline-primary">
                                <i class="fas fa-download me-2"></i> Download Sample CSV Template
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <!-- Upload Form -->
                        <form method="POST" enctype="multipart/form-data" id="uploadForm">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            
                            <div class="upload-area" id="uploadArea">
                                <i class="fas fa-cloud-upload-alt fa-4x text-primary mb-3"></i>
                                <h5>Drag & Drop CSV File Here</h5>
                                <p class="text-muted">or click to browse</p>
                                <input type="file" name="csv_file" id="csvFile" accept=".csv" style="display: none;">
                                <div id="fileName" class="mt-2 text-success"></div>
                            </div>
                            
                            <div class="mt-3">
                                <button type="submit" name="upload_questions" class="btn btn-custom text-white w-100 py-2">
                                    <i class="fas fa-upload me-2"></i> Upload Questions
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Preview Section -->
                <div class="mt-4" id="previewSection" style="display: none;">
                    <hr>
                    <h5 class="mb-3"><i class="fas fa-eye me-2 text-primary"></i> CSV Preview</h5>
                    <div class="table-responsive preview-table">
                        <table class="table table-bordered table-sm" id="previewTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Category</th>
                                    <th>Question</th>
                                    <th>Option 1</th>
                                    <th>Option 2</th>
                                    <th>Option 3</th>
                                    <th>Option 4</th>
                                    <th>Correct</th>
                                </tr>
                            </thead>
                            <tbody id="previewBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
// Drag and drop functionality
const uploadArea = document.getElementById('uploadArea');
const csvInput = document.getElementById('csvFile');
const fileName = document.getElementById('fileName');
const previewSection = document.getElementById('previewSection');
const previewBody = document.getElementById('previewBody');

uploadArea.addEventListener('click', () => {
    csvInput.click();
});

uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('dragover');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('dragover');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    const file = e.dataTransfer.files[0];
    if(file && file.name.endsWith('.csv')) {
        csvInput.files = e.dataTransfer.files;
        displayFileName(file.name);
        previewCSV(file);
    } else {
        alert('Please drop a valid CSV file');
    }
});

csvInput.addEventListener('change', (e) => {
    if(e.target.files[0]) {
        displayFileName(e.target.files[0].name);
        previewCSV(e.target.files[0]);
    }
});

function displayFileName(name) {
    fileName.innerHTML = `<i class="fas fa-check-circle me-1"></i> Selected: ${name}`;
}

function previewCSV(file) {
    const reader = new FileReader();
    reader.onload = function(e) {
        const content = e.target.result;
        const rows = content.split('\n');
        const previewRows = rows.slice(0, 6); // Show first 5 data rows + header
        
        previewBody.innerHTML = '';
        
        for(let i = 1; i < previewRows.length; i++) {
            if(previewRows[i].trim() === '') continue;
            const cols = previewRows[i].split(',');
            if(cols.length >= 7) {
                const row = previewBody.insertRow();
                row.insertCell(0).innerHTML = cols[0]?.replace(/"/g, '') || '';
                row.insertCell(1).innerHTML = cols[1]?.replace(/"/g, '').substring(0, 50) || '';
                row.insertCell(2).innerHTML = cols[2]?.replace(/"/g, '') || '';
                row.insertCell(3).innerHTML = cols[3]?.replace(/"/g, '') || '';
                row.insertCell(4).innerHTML = cols[4]?.replace(/"/g, '') || '';
                row.insertCell(5).innerHTML = cols[5]?.replace(/"/g, '') || '';
                row.insertCell(6).innerHTML = cols[6]?.replace(/"/g, '') || '';
            }
        }
        
        previewSection.style.display = 'block';
    };
    reader.readAsText(file);
}
</script>

</body>
</html>