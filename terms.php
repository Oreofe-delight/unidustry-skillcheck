<?php
session_start();
include("includes/db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms and Conditions | Unidustry SkillCheck</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="assets/css/style.css" rel="stylesheet">
    
    <style>
        .terms-container {
            max-width: 1000px;
            margin: 100px auto 50px;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .terms-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 20px;
        }
        .terms-header h1 {
            color: #4e54c8;
            font-weight: 700;
        }
        .terms-section {
            margin-bottom: 25px;
        }
        .terms-section h3 {
            color: #4e54c8;
            font-size: 1.3rem;
            margin-bottom: 15px;
        }
        .terms-section p, .terms-section li {
            color: #4a5568;
            line-height: 1.6;
        }
        .btn-accept {
            background: linear-gradient(90deg, #4e54c8, #6c63ff);
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }
        .btn-accept:hover {
            opacity: 0.9;
            color: white;
        }
        footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
        }
    </style>
</head>
<body style="background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);">

<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: #4e54c8;">
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
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <div class="terms-container">
        <div class="terms-header">
            <img src="assets/images/logo-full.png" alt="Logo" style="height: 60px;">
            <h1>Terms and Conditions</h1>
            <p>Last Updated: June 2025</p>
        </div>
        
        <div class="terms-section">
            <h3><i class="fas fa-file-contract me-2"></i> 1. Acceptance of Terms</h3>
            <p>By registering and using the Unidustry SkillCheck platform, you agree to be bound by these Terms and Conditions. If you do not agree, please do not use the system.</p>
        </div>
        
        <div class="terms-section">
            <h3><i class="fas fa-user-check me-2"></i> 2. User Eligibility</h3>
            <p>This system is designed for software engineering and computer science students, as well as fresh graduates of accredited institutions. You must be either a currently enrolled student or a recent graduate (within 2 years of graduation) to use this platform. Proof of enrollment or graduation may be requested for verification purposes.</p>
        </div>
        
        <div class="terms-section">
            <h3><i class="fas fa-gavel me-2"></i> 3. Academic Integrity</h3>
            <p>All assessments must be completed independently. Any form of cheating, plagiarism, or unauthorized collaboration is strictly prohibited. Violations may result in account suspension.</p>
        </div>
        
        <div class="terms-section">
            <h3><i class="fas fa-database me-2"></i> 4. Data Privacy</h3>
            <p>Your personal information is collected and stored in accordance with our Privacy Policy. We do not share your data with third parties without your consent.</p>
        </div>
        
        <div class="terms-section">
            <h3><i class="fas fa-clock me-2"></i> 5. Assessment Rules</h3>
            <ul>
                <li>Each assessment has a time limit. Answers submitted after time expires may not be counted.</li>
                <li>You may retake assessments, but results from all attempts will be recorded.</li>
                <li>A passing score is 70% or higher.</li>
            </ul>
        </div>
        
        <div class="terms-section">
            <h3><i class="fas fa-copyright me-2"></i> 6. Intellectual Property</h3>
            <p>All content, questions, and materials on this platform are the intellectual property of Unidustry SkillCheck. Reproduction or distribution without permission is prohibited.</p>
        </div>
        
        <div class="terms-section">
            <h3><i class="fas fa-shield-alt me-2"></i> 7. Account Security</h3>
            <p>You are responsible for maintaining the confidentiality of your login credentials. Notify administrators immediately of any unauthorized access.</p>
        </div>
        
        <div class="terms-section">
            <h3><i class="fas fa-ban me-2"></i> 8. Termination</h3>
            <p>We reserve the right to suspend or terminate accounts that violate these terms or engage in inappropriate behavior.</p>
        </div>
        
        <div class="terms-section">
            <h3><i class="fas fa-envelope me-2"></i> 9. Contact Information</h3>
            <p>For questions about these terms, contact: <strong>obaneghagrace@gmail.com | 09036743200</strong></p>
        </div>
        
        <div class="text-center">
            <a href="register.php" class="btn-accept">
                <i class="fas fa-check-circle me-2"></i> I Accept the Terms and Conditions
            </a>
        </div>
        
        <footer>
            <p>&copy; 2025 Unidustry SkillCheck | Computing Skill Assessment System</p>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>