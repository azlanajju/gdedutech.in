<?php
session_start();
require_once './Configurations/config.php';

// Check if job_id is provided
if (!isset($_GET['id'])) {
    header('Location: career.php');
    exit();
}

// Fetch job details
$job_id = intval($_GET['id']);
$sql = "SELECT * FROM Careers WHERE job_id = ? AND status = 'Active'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $job_id);
$stmt->execute();
$result = $stmt->get_result();
$job = $result->fetch_assoc();

// If job not found or not active, redirect
if (!$job) {
    header('Location: career.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply - <?php echo htmlspecialchars($job['job_title']); ?> - GD Edu Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/style.css">
        
    <link rel="icon" type="image/png" href="./Images/Logos/GD_Only_logo.png">
    <link rel="shortcut icon" href="./Images/Logos/GD_Only_logo.png">
</head>
<body>
    <!-- Navigation (same as career.php) -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--primary-color);">
        <div class="container">
            <a class="navbar-brand" href="index.php"><img style="height: 60px;" src="./Images/Logos/GD_Full_logo.png" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#courses">Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#categories">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="career.php">Career</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="./" class="btn btn-outline-light me-2">Dashboard</a>
                    <?php else: ?>
                        <a href="./studentPanel/login.php" class="btn btn-outline-light me-2">Login</a>
                        <a href="./studentPanel/signup.php" class="btn btn-primary">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Job Application Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Job Details Column -->
                <div class="col-lg-4 mb-4">
                    <div class="card feature-card">
                        <div class="card-body">
                            <h3 class="card-title"><?php echo htmlspecialchars($job['job_title']); ?></h3>
                            <span class="badge bg-primary mb-3"><?php echo htmlspecialchars($job['job_type']); ?></span>
                            <p class="card-text"><?php echo htmlspecialchars($job['job_description']); ?></p>
                            <h5>Requirements:</h5>
                            <ul class="list-unstyled mb-4">
                                <?php
                                $requirements = explode("\n", $job['requirements']);
                                foreach ($requirements as $requirement) {
                                    if (trim($requirement) != '') {
                                        echo '<li><i class="bi bi-check-circle-fill text-primary me-2"></i>' . htmlspecialchars(trim($requirement)) . '</li>';
                                    }
                                }
                                ?>
                            </ul>
                            <?php if ($job['salary_range']): ?>
                                <p><strong>Salary Range:</strong> <?php echo htmlspecialchars($job['salary_range']); ?></p>
                            <?php endif; ?>
                            <p><strong>Location:</strong> <?php echo htmlspecialchars($job['location']); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Application Form Column -->
                <div class="col-lg-8">
                    <div class="card feature-card">
                        <div class="card-body">
                            <h3 class="mb-4">Application Form</h3>
                            <form action="process_application.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="first_name" class="form-label">First Name *</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="last_name" class="form-label">Last Name *</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required>
                                </div>

                                <div class="mb-3">
                                    <label for="resume" class="form-label">Upload Resume (PDF only) *</label>
                                    <input type="file" class="form-control" id="resume" name="resume" accept=".pdf" required>
                                </div>

                                <div class="mb-3">
                                    <label for="cover_letter" class="form-label">Cover Letter</label>
                                    <textarea class="form-control" id="cover_letter" name="cover_letter" rows="5"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="portfolio" class="form-label">Portfolio URL</label>
                                    <input type="url" class="form-control" id="portfolio" name="portfolio">
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                        <label class="form-check-label" for="terms">
                                            I agree to the terms and conditions and consent to the processing of my personal data
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit Application</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer (same as career.php) -->
    <footer class="bg-dark text-light py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h5>About GD Edu Tech</h5>
                    <p>Transforming education through technology. Learn, grow, and succeed with our platform.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light">About Us</a></li>
                        <li><a href="#" class="text-light">Contact</a></li>
                        <li><a href="#" class="text-light">Privacy Policy</a></li>
                        <li><a href="#" class="text-light">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Us</h5>
                    <p>
                        <i class="bi bi-envelope me-2"></i> careers@gdedutech.com<br>
                        <i class="bi bi-phone me-2"></i> +1 234 567 890
                    </p>
                    <div class="social-links">
                        <a href="#" class="text-light me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-light me-3"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-light me-3"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 