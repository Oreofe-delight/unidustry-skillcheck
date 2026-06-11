<?php
// Get current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <div class="sidebar-logo text-center">
        <img src="../assets/images/logo-icon.png" class="sidebar-img">
        <h4>Admin Panel</h4>
    </div>
    <div class="sidebar-links">
        <a href="dashboard.php" class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="add_question.php" class="<?php echo $current_page == 'add_question.php' ? 'active' : ''; ?>">
            <i class="fas fa-plus-circle"></i> Add Question
        </a>
        <a href="manage_questions.php" class="<?php echo $current_page == 'manage_questions.php' ? 'active' : ''; ?>">
            <i class="fas fa-list"></i> Manage Questions
        </a>
        <a href="students.php" class="<?php echo $current_page == 'students.php' ? 'active' : ''; ?>">
            <i class="fas fa-user-graduate"></i> Students
        </a>
        <a href="results.php" class="<?php echo $current_page == 'results.php' ? 'active' : ''; ?>">
            <i class="fas fa-chart-bar"></i> Results
        </a>
        <a href="../logout.php">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</aside>