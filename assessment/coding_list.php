<?php
include("../includes/user_auth.php");

$challenges = mysqli_query($conn, "SELECT * FROM coding_challenges ORDER BY 
    FIELD(difficulty_level, 'easy', 'medium', 'hard'), id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coding Challenges | SkillCheck</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body style="background: linear-gradient(135deg, #4e54c8, #8f94fb); min-height: 100vh;">

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="text-white">💻 Coding Challenges</h1>
        <p class="text-white-50">Test your programming skills with real coding problems</p>
    </div>
    
    <div class="row">
        <?php while($challenge = mysqli_fetch_assoc($challenges)): 
            $badge_class = $challenge['difficulty_level'] == 'easy' ? 'success' : 
                          ($challenge['difficulty_level'] == 'medium' ? 'warning' : 'danger');
        ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <h5 class="card-title"><?php echo htmlspecialchars($challenge['title']); ?></h5>
                        <span class="badge bg-<?php echo $badge_class; ?>">
                            <?php echo ucfirst($challenge['difficulty_level']); ?>
                        </span>
                    </div>
                    <p class="card-text text-muted small">
                        <?php echo substr(htmlspecialchars($challenge['description']), 0, 100); ?>...
                    </p>
                    <div class="mt-3">
                        <span class="badge bg-primary"><?php echo $challenge['points']; ?> points</span>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="coding.php?id=<?php echo $challenge['id']; ?>" class="btn btn-primary w-100">
                        <i class="fas fa-code me-2"></i> Start Challenge
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    
    <div class="text-center mt-4">
        <a href="../dashboard.php" class="btn btn-light">
            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
        </a>
    </div>
</div>

</body>
</html>