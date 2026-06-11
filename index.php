<?php
session_start();

// If user is already logged in, redirect to appropriate dashboard
if(isset($_SESSION['user_id'])) {
    if($_SESSION['role'] == 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unidustry SkillCheck | Computing Skill Assessment System</title>
    
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Additional inline styles for better consistency */
        .btn-outline-light {
            border: 2px solid white;
            color: white;
            transition: all 0.3s;
        }
        .btn-outline-light:hover {
            background: white;
            color: #4e54c8;
            transform: translateY(-2px);
        }
        .feature-box {
            transition: transform 0.3s;
        }
        .feature-box:hover {
            transform: translateY(-10px);
        }
        .how-it-works-step {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s;
        }
        .how-it-works-step:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transform: translateY(-5px);
        }
        .step-number {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #4e54c8, #6c63ff);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin: 0 auto 15px;
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: rgba(78, 84, 200, 0.95); backdrop-filter: blur(10px);">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <img src="assets/images/logo-icon.png" alt="Logo" height="35" class="me-2">
            Unidustry SkillCheck
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="#how-it-works">How It Works</a></li>
                <li class="nav-item"><a class="nav-link btn btn-outline-light ms-2 px-3 py-1" href="login.php">Login</a></li>
                <li class="nav-item"><a class="nav-link btn btn-custom ms-2 px-3 py-1 text-white" href="register.php">Register</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero d-flex align-items-center" id="home">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <img src="assets/images/logo-icon.png" class="hero-logo">
                <h1>Unidustry SkillCheck</h1>
                <p>A smart platform designed to assess, analyze, and improve students' technical and soft skills for industry readiness.</p>
                <div class="mt-4">
                    <a href="login.php" class="btn btn-custom btn-lg me-3 text-white">
                        <i class="fas fa-sign-in-alt me-2"></i> Login
                    </a>
                    <a href="register.php" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-user-plus me-2"></i> Get Started
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="assets/images/tech.png" class="img-fluid hero-img">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5" id="features">
    <div class="container text-center">
        <h2 class="mb-4" style="color: #4e54c8;">Key Features</h2>
        <p class="text-muted mb-5">Everything you need to assess and improve your skills</p>
        <div class="row">
            <div class="col-md-4">
                <div class="feature-box">
                    <img src="assets/images/tech.png" height="70">
                    <h5 class="mt-3">Technical Assessment</h5>
                    <p>Evaluate coding, database, web development, and problem-solving skills.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <img src="assets/images/soft.png" height="70">
                    <h5 class="mt-3">Soft Skills Evaluation</h5>
                    <p>Assess communication, teamwork, leadership, and adaptability.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-box">
                    <img src="assets/images/result.png" height="70">
                    <h5 class="mt-3">Smart Feedback</h5>
                    <p>Get personalized recommendations to improve weak areas instantly.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="bg-light py-5" id="how-it-works">
    <div class="container text-center">
        <h2 class="mb-4" style="color: #4e54c8;">How It Works</h2>
        <p class="text-muted mb-5">Three simple steps to assess your skills</p>
        <div class="row">
            <div class="col-md-4">
                <div class="how-it-works-step">
                    <div class="step-number">1</div>
                    <h5>Register</h5>
                    <p class="text-muted">Create a free account with your student details.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="how-it-works-step">
                    <div class="step-number">2</div>
                    <h5>Take Assessment</h5>
                    <p class="text-muted">Complete technical and soft skill assessments.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="how-it-works-step">
                    <div class="step-number">3</div>
                    <h5>Get Results</h5>
                    <p class="text-muted">View your performance and get recommendations.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="cta text-center">
    <div class="container">
        <h2>Start Your Skill Journey Today 🚀</h2>
        <p class="mb-4">Join students improving their career readiness.</p>
        <a href="register.php" class="btn btn-light btn-lg">
            <i class="fas fa-user-plus me-2"></i> Create Free Account
        </a>
    </div>
</section>

<!-- Footer -->
<div class="footer">
    <div class="container">
        <p>© <?php echo date('Y'); ?> Unidustry SkillCheck | Computing Skill Assessment System</p>
        <p class="small">Department of Computer Science, University of Ilorin</p>
    </div>
</div>

<!-- Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if(target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
</script>

</body>
</html>