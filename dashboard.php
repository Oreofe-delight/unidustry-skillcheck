<?php
include("includes/user_auth.php");
?>

<!DOCTYPE html>
<html>
<head>

<title>Dashboard</title>

<link href="assets/css/style.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
rel="stylesheet">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>

<div class="dashboard-layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">

        <div class="sidebar-logo text-center">

            <img src="assets/images/logo-icon.png"
            class="sidebar-img">

            <h4>SkillCheck</h4>

        </div>

        <div class="sidebar-links">

            <a href="dashboard.php" class="active">
                <i class="fas fa-home"></i>
                Dashboard
            </a>

            <a href="assessment/technical.php">
                <i class="fas fa-code"></i>
                Technical Test
            </a>

            <a href="assessment/softskills.php">
                <i class="fas fa-users"></i>
                Soft Skills
            </a>

            <a href="profile.php">
                <i class="fas fa-user"></i>
                Profile
            </a>

            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>

        </div>

    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">

        <!-- TOPBAR -->
        <div class="topbar">

            <div>

                <h3>
                    Welcome back,
                    <?php echo h($_SESSION['fullname']); ?>
                </h3>

                <p>
                    Track your assessments and skill growth
                </p>

            </div>

        </div>

        <!-- STATISTICS -->
        <div class="container-fluid py-4">

            <div class="row g-4">

                <div class="col-md-4">

                    <div class="stats-card">

                        <div>
                            <h5>Technical Tests</h5>
                            <h2>01</h2>
                        </div>

                        <i class="fas fa-code"></i>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="stats-card">

                        <div>
                            <h5>Soft Skills Tests</h5>
                            <h2>01</h2>
                        </div>

                        <i class="fas fa-users"></i>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="stats-card">

                        <div>
                            <h5>Profile Status</h5>
                            <h2>100%</h2>
                        </div>

                        <i class="fas fa-user-check"></i>

                    </div>

                </div>

            </div>

            <!-- ASSESSMENT CARDS -->
            <div class="row mt-4 g-4">

                <div class="col-lg-6">

                    <div class="dashboard-panel">

                        <img src="assets/images/tech.png"
                        class="panel-img">

                        <h4>
                            Technical Assessment
                        </h4>

                        <p>
                            Evaluate coding, problem-solving,
                            and technical reasoning skills.
                        </p>

                        <a href="assessment/technical.php"
                        class="btn btn-custom">

                        Start Assessment

                        </a>

                    </div>

                </div>

                <div class="col-lg-6">

                    <div class="dashboard-panel">

                        <img src="assets/images/soft.png"
                        class="panel-img">

                        <h4>
                            Soft Skills Assessment
                        </h4>

                        <p>
                            Test communication, leadership,
                            teamwork, and adaptability skills.
                        </p>

                        <a href="assessment/softskills.php"
                        class="btn btn-custom">

                        Start Assessment

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </main>

</div>

</body>
</html>