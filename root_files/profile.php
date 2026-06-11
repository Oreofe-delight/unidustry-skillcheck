<?php
include("includes/user_auth.php");

$user_id = $_SESSION['user_id'];
$message = "";
$message_type = "success";

// Handle Profile Update
if(isset($_POST['update_profile'])) {
    // Verify CSRF token
    if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = "Security verification failed.";
        $message_type = "danger";
    } else {
        $fullname = trim($_POST['fullname']);
        $phone = trim($_POST['phone']);
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $institution = trim($_POST['institution']);
        $faculty = trim($_POST['faculty']);
        $department = trim($_POST['department']);
        $level = $_POST['level'];
        $skills_interest = trim($_POST['skills_interest']);
        
        $update_stmt = mysqli_prepare($conn, "
            UPDATE users SET 
            fullname = ?, phone = ?, gender = ?, dob = ?, 
            institution = ?, faculty = ?, department = ?, 
            level = ?, skills_interest = ? 
            WHERE id = ?
        ");
        mysqli_stmt_bind_param($update_stmt, "sssssssssi", 
            $fullname, $phone, $gender, $dob, 
            $institution, $faculty, $department, 
            $level, $skills_interest, $user_id
        );
        
        if(mysqli_stmt_execute($update_stmt)) {
            $_SESSION['fullname'] = $fullname;
            $message = "Profile updated successfully!";
            $message_type = "success";
        } else {
            $message = "Error updating profile. Please try again.";
            $message_type = "danger";
        }
    }
}

// Handle Password Change
if(isset($_POST['change_password'])) {
    if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = "Security verification failed.";
        $message_type = "danger";
    } else {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Get current password from database
        $pass_stmt = mysqli_prepare($conn, "SELECT password FROM users WHERE id = ?");
        mysqli_stmt_bind_param($pass_stmt, "i", $user_id);
        mysqli_stmt_execute($pass_stmt);
        $pass_result = mysqli_stmt_get_result($pass_stmt);
        $user_pass = mysqli_fetch_assoc($pass_result);
        
        if(!password_verify($current_password, $user_pass['password'])) {
            $message = "Current password is incorrect.";
            $message_type = "danger";
        } elseif(strlen($new_password) < 8) {
            $message = "New password must be at least 8 characters.";
            $message_type = "danger";
        } elseif($new_password !== $confirm_password) {
            $message = "New passwords do not match.";
            $message_type = "danger";
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_pass_stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE id = ?");
            mysqli_stmt_bind_param($update_pass_stmt, "si", $hashed_password, $user_id);
            
            if(mysqli_stmt_execute($update_pass_stmt)) {
                $message = "Password changed successfully!";
                $message_type = "success";
            } else {
                $message = "Error changing password. Please try again.";
                $message_type = "danger";
            }
        }
    }
}

// Handle Profile Image Upload
if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $filename = $_FILES['profile_image']['name'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if(!in_array($ext, $allowed)) {
        $message = "Only JPG, PNG, and GIF images are allowed.";
        $message_type = "danger";
    } else {
        $new_filename = "user_" . $user_id . "_" . time() . "." . $ext;
        $upload_path = "uploads/profiles/" . $new_filename;
        
        // Create directory if not exists
        if(!is_dir("uploads/profiles")) {
            mkdir("uploads/profiles", 0777, true);
        }
        
        if(move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
            // Delete old image if not default
            $img_stmt = mysqli_prepare($conn, "SELECT profile_image FROM users WHERE id = ?");
            mysqli_stmt_bind_param($img_stmt, "i", $user_id);
            mysqli_stmt_execute($img_stmt);
            $img_result = mysqli_stmt_get_result($img_stmt);
            $old_img = mysqli_fetch_assoc($img_result);
            
            if($old_img['profile_image'] && file_exists("uploads/profiles/" . $old_img['profile_image'])) {
                unlink("uploads/profiles/" . $old_img['profile_image']);
            }
            
            $update_img_stmt = mysqli_prepare($conn, "UPDATE users SET profile_image = ? WHERE id = ?");
            mysqli_stmt_bind_param($update_img_stmt, "si", $new_filename, $user_id);
            
            if(mysqli_stmt_execute($update_img_stmt)) {
                $message = "Profile image updated successfully!";
                $message_type = "success";
            }
        } else {
            $message = "Error uploading image.";
            $message_type = "danger";
        }
    }
}

// Fetch updated user data
$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Get assessment statistics
$stats_stmt = mysqli_prepare($conn, "
    SELECT 
        COUNT(CASE WHEN category = 'technical' THEN 1 END) as tech_count,
        COUNT(CASE WHEN category = 'soft' THEN 1 END) as soft_count,
        AVG(CASE WHEN category = 'technical' THEN percentage END) as tech_avg,
        AVG(CASE WHEN category = 'soft' THEN percentage END) as soft_avg,
        MAX(percentage) as best_score,
        MIN(percentage) as worst_score
    FROM results 
    WHERE user_id = ?
");
mysqli_stmt_bind_param($stats_stmt, "i", $user_id);
mysqli_stmt_execute($stats_stmt);
$stats_result = mysqli_stmt_get_result($stats_stmt);
$stats = mysqli_fetch_assoc($stats_result);

// If no results yet, set default values
if(!$stats || $stats['tech_count'] === null) {
    $stats = [
        'tech_count' => 0,
        'soft_count' => 0,
        'tech_avg' => 0,
        'soft_avg' => 0,
        'best_score' => 0,
        'worst_score' => 0
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Skill Assessment System</title>
    
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        .profile-container {
            max-width: 1000px;
            margin: 0 auto;
        }
        .profile-card {
            background: white;
            border-radius: 25px;
            padding: 30px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #6c63ff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .avatar-upload {
            position: relative;
            margin-top: -40px;
        }
        .avatar-upload-btn {
            background: #6c63ff;
            color: white;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 2px solid white;
        }
        .avatar-upload-btn:hover {
            background: #4e54c8;
        }
        .stat-card {
            background: linear-gradient(135deg, #f8f9fa, #fff);
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            border: 1px solid #e0e7ff;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            color: #6c63ff;
        }
        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
        }
        .info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #6c757d;
        }
        .info-value {
            font-size: 1rem;
            font-weight: 500;
            color: #2d3748;
            word-break: break-word;
        }
        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #4e54c8;
            border-left: 4px solid #6c63ff;
            padding-left: 12px;
            margin-bottom: 20px;
        }
        .edit-icon {
            cursor: pointer;
            color: #6c63ff;
            transition: color 0.2s;
        }
        .edit-icon:hover {
            color: #4e54c8;
        }
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 10px 20px;
        }
        .nav-tabs .nav-link.active {
            color: #6c63ff;
            border-bottom: 3px solid #6c63ff;
            background: transparent;
        }
        .form-control-static {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 8px;
        }
    </style>
</head>

<body style="background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%); min-height: 100vh;">
    <div class="container py-4">
        <div class="profile-container">
            
            <!-- Back Button -->
            <a href="dashboard.php" class="btn btn-link text-decoration-none mb-3">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
            
            <!-- Alert Messages -->
            <?php if($message != ""): ?>
            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                <i class="fas fa-<?php echo $message_type == 'success' ? 'check-circle' : 'exclamation-circle'; ?> me-2"></i>
                <?php echo h($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs mb-4" id="profileTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="view-tab" data-bs-toggle="tab" data-bs-target="#view" type="button" role="tab">
                        <i class="fas fa-user me-2"></i>View Profile
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit" type="button" role="tab">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security" type="button" role="tab">
                        <i class="fas fa-lock me-2"></i>Security
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats" type="button" role="tab">
                        <i class="fas fa-chart-line me-2"></i>Statistics
                    </button>
                </li>
            </ul>
            
            <!-- Tab Content -->
            <div class="tab-content">
                
                <!-- VIEW PROFILE TAB -->
                <div class="tab-pane fade show active" id="view" role="tabpanel">
                    <div class="profile-card">
                        <!-- Profile Header -->
                        <div class="text-center">
                            <div class="position-relative d-inline-block">
                                <img src="<?php echo !empty($user['profile_image']) && file_exists("uploads/profiles/" . $user['profile_image']) ? "uploads/profiles/" . $user['profile_image'] : "assets/images/devOreofe.jpg"; ?>" 
                                     class="profile-avatar" id="profileImage">
                                <div class="avatar-upload">
                                    <label class="avatar-upload-btn" id="uploadBtn" style="cursor: pointer;">
                                        <i class="fas fa-camera fa-sm"></i>
                                        <input type="file" id="imageUpload" style="display: none;" accept="image/*">
                                    </label>
                                </div>
                            </div>
                            <h4 class="mt-3 mb-0"><?php echo h($user['fullname']); ?></h4>
                            <small class="text-muted">
                                <?php echo h($user['student_id']); ?> • 
                                <?php echo h($user['level'] ?: 'Level not set'); ?>
                            </small>
                        </div>
                        
                        <hr>
                        
                        <!-- Personal Information -->
                        <div class="row">
                            <div class="col-12 mb-3">
                                <h6 class="section-title">Personal Information</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-label">Email Address</div>
                                <div class="info-value"><?php echo h($user['email']); ?></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-label">Phone Number</div>
                                <div class="info-value"><?php echo h($user['phone']) ?: '<span class="text-muted">Not provided</span>'; ?></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-label">Gender</div>
                                <div class="info-value"><?php echo h($user['gender']) ?: '<span class="text-muted">Not specified</span>'; ?></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-label">Date of Birth</div>
                                <div class="info-value"><?php echo $user['dob'] ? date('F j, Y', strtotime($user['dob'])) : '<span class="text-muted">Not provided</span>'; ?></div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Academic Information -->
                        <div class="row">
                            <div class="col-12 mb-3">
                                <h6 class="section-title">Academic Information</h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-label">Institution</div>
                                <div class="info-value"><?php echo h($user['institution']) ?: '<span class="text-muted">Not provided</span>'; ?></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-label">Faculty</div>
                                <div class="info-value"><?php echo h($user['faculty']) ?: '<span class="text-muted">Not provided</span>'; ?></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-label">Department</div>
                                <div class="info-value"><?php echo h($user['department']) ?: '<span class="text-muted">Not provided</span>'; ?></div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="info-label">Level</div>
                                <div class="info-value"><?php echo h($user['level']) ?: '<span class="text-muted">Not set</span>'; ?></div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <!-- Skills Interest -->
                        <div class="mb-3">
                            <h6 class="section-title">Technical Interests</h6>
                            <div class="info-value">
                                <?php 
                                $skills = explode(',', $user['skills_interest'] ?? '');
                                if(!empty($user['skills_interest'])):
                                    foreach($skills as $skill):
                                        $skill = trim($skill);
                                        if($skill):
                                ?>
                                    <span class="badge bg-light text-dark border me-1 mb-1 py-2 px-3"><?php echo h($skill); ?></span>
                                <?php 
                                        endif;
                                    endforeach;
                                else:
                                    echo '<span class="text-muted">No skills added yet</span>';
                                endif;
                                ?>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button class="btn btn-outline-primary" onclick="document.getElementById('edit-tab').click()">
                                <i class="fas fa-edit me-2"></i> Edit Profile
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- EDIT PROFILE TAB -->
                <div class="tab-pane fade" id="edit" role="tabpanel">
                    <div class="profile-card">
                        <h5 class="mb-4"><i class="fas fa-user-edit me-2" style="color: #6c63ff;"></i> Edit Profile Information</h5>
                        
                        <form method="POST" action="">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="fullname" class="form-control" value="<?php echo h($user['fullname']); ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control" value="<?php echo h($user['phone']); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="">Select Gender</option>
                                        <option value="Male" <?php echo $user['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo $user['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                                        <option value="Other" <?php echo $user['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="date" name="dob" class="form-control" value="<?php echo $user['dob']; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Institution</label>
                                    <input type="text" name="institution" class="form-control" value="<?php echo h($user['institution']); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Faculty</label>
                                    <input type="text" name="faculty" class="form-control" value="<?php echo h($user['faculty']); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Department</label>
                                    <input type="text" name="department" class="form-control" value="<?php echo h($user['department']); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Level</label>
                                    <select name="level" class="form-select">
                                        <option value="">Select Level</option>
                                        <option <?php echo $user['level'] == '100 Level' ? 'selected' : ''; ?>>100 Level</option>
                                        <option <?php echo $user['level'] == '200 Level' ? 'selected' : ''; ?>>200 Level</option>
                                        <option <?php echo $user['level'] == '300 Level' ? 'selected' : ''; ?>>300 Level</option>
                                        <option <?php echo $user['level'] == '400 Level' ? 'selected' : ''; ?>>400 Level</option>
                                        <option <?php echo $user['level'] == '500 Level' ? 'selected' : ''; ?>>500 Level</option>
                                    </select>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label">Skills / Technical Interests</label>
                                    <textarea name="skills_interest" class="form-control" rows="3" placeholder="e.g., PHP, Python, JavaScript, Database, UI/UX"><?php echo h($user['skills_interest']); ?></textarea>
                                    <small class="text-muted">Separate skills with commas</small>
                                </div>
                            </div>
                            
                            <button type="submit" name="update_profile" class="btn btn-custom text-white w-100 py-2">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- SECURITY TAB (Change Password) -->
                <div class="tab-pane fade" id="security" role="tabpanel">
                    <div class="profile-card">
                        <h5 class="mb-4"><i class="fas fa-key me-2" style="color: #6c63ff;"></i> Change Password</h5>
                        
                        <form method="POST" action="" id="passwordForm">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password" name="current_password" class="form-control" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="new_password" id="new_password" class="form-control" required>
                                <div class="form-text">Must be at least 8 characters long</div>
                                <div class="password-strength mt-1" id="passwordStrength" style="height: 3px; width: 0; background: #28a745; border-radius: 3px;"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                                <div id="passwordMatch" class="form-text"></div>
                            </div>
                            
                            <button type="submit" name="change_password" class="btn btn-custom text-white w-100 py-2">
                                <i class="fas fa-lock me-2"></i> Change Password
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- STATISTICS TAB -->
                <div class="tab-pane fade" id="stats" role="tabpanel">
                    <div class="profile-card">
                        <h5 class="mb-4"><i class="fas fa-chart-simple me-2" style="color: #6c63ff;"></i> Assessment Statistics</h5>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-6 col-md-3">
                                <div class="stat-card">
                                    <div class="stat-value"><?php echo $stats['tech_count'] ?? 0; ?></div>
                                    <div class="stat-label">Technical Tests</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-card">
                                    <div class="stat-value"><?php echo $stats['soft_count'] ?? 0; ?></div>
                                    <div class="stat-label">Soft Skills Tests</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-card">
                                    <div class="stat-value"><?php echo round($stats['tech_avg'] ?? 0); ?>%</div>
                                    <div class="stat-label">Technical Avg</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="stat-card">
                                    <div class="stat-value"><?php echo round($stats['soft_avg'] ?? 0); ?>%</div>
                                    <div class="stat-label">Soft Skills Avg</div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if(($stats['tech_count'] ?? 0) > 0 || ($stats['soft_count'] ?? 0) > 0): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Overall Performance:</strong>
                            <?php 
                            $overall_avg = (($stats['tech_avg'] ?? 0) + ($stats['soft_avg'] ?? 0)) / 2;
                            if($overall_avg >= 70):
                                echo "Excellent! You're performing above average.";
                            elseif($overall_avg >= 50):
                                echo "Good progress! Keep practicing to improve further.";
                            else:
                                echo "Keep working hard! Review your weak areas and retake assessments.";
                            endif;
                            ?>
                        </div>
                        <?php else: ?>
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-clipboard-list me-2"></i>
                            No assessments taken yet. <a href="assessment/assessment.php?type=technical" class="alert-link">Start your first assessment</a>
                        </div>
                        <?php endif; ?>
                        
                        <div class="text-center mt-3">
                            <a href="assessment/assessment.php?type=technical" class="btn btn-outline-primary me-2">
                                <i class="fas fa-code me-2"></i> Take Technical Test
                            </a>
                            <a href="assessment/assessment.php?type=soft" class="btn btn-outline-success">
                                <i class="fas fa-users me-2"></i> Take Soft Skills Test
                            </a>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <!-- Image Upload Form (Hidden) -->
    <form id="imageUploadForm" method="POST" enctype="multipart/form-data" style="display: none;">
        <input type="file" name="profile_image" id="profileImageInput" accept="image/jpeg,image/png,image/gif">
    </form>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Profile Image Upload
        document.getElementById('uploadBtn')?.addEventListener('click', function() {
            document.getElementById('profileImageInput').click();
        });
        
        document.getElementById('profileImageInput')?.addEventListener('change', function(e) {
            if(e.target.files.length > 0) {
                const formData = new FormData();
                formData.append('profile_image', e.target.files[0]);
                formData.append('csrf_token', '<?php echo $_SESSION['csrf_token']; ?>');
                
                fetch(window.location.href, {
                    method: 'POST',
                    body: formData
                }).then(response => response.text())
                  .then(() => window.location.reload());
            }
        });
        
        // Password strength meter
        document.getElementById('new_password')?.addEventListener('input', function() {
            const password = this.value;
            const strengthDiv = document.getElementById('passwordStrength');
            let strength = 0;
            
            if(password.length >= 8) strength++;
            if(password.match(/[a-z]+/)) strength++;
            if(password.match(/[A-Z]+/)) strength++;
            if(password.match(/[0-9]+/)) strength++;
            if(password.match(/[$@#&!]+/)) strength++;
            
            const width = (strength / 5) * 100;
            const colors = ['#dc3545', '#ffc107', '#28a745'];
            
            if(strength <= 2) strengthDiv.style.backgroundColor = colors[0];
            else if(strength <= 4) strengthDiv.style.backgroundColor = colors[1];
            else strengthDiv.style.backgroundColor = colors[2];
            
            strengthDiv.style.width = width + '%';
        });
        
        // Password confirmation check
        document.getElementById('confirm_password')?.addEventListener('input', function() {
            const password = document.getElementById('new_password').value;
            const confirm = this.value;
            const matchDiv = document.getElementById('passwordMatch');
            
            if(confirm === '') {
                matchDiv.innerHTML = '';
            } else if(password === confirm) {
                matchDiv.innerHTML = '<span class="text-success">✓ Passwords match</span>';
            } else {
                matchDiv.innerHTML = '<span class="text-danger">✗ Passwords do not match</span>';
            }
        });
        
        // Form validation for password change
        document.getElementById('passwordForm')?.addEventListener('submit', function(e) {
            const password = document.getElementById('new_password').value;
            const confirm = document.getElementById('confirm_password').value;
            
            if(password !== confirm) {
                e.preventDefault();
                alert('New passwords do not match!');
                return false;
            }
            if(password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long!');
                return false;
            }
            return true;
        });
    </script>
</body>
</html>