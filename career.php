<?php
session_start();
require_once './Configurations/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Opportunities - GD Edu Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <!-- Navigation -->
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
                        <a href="./studentPanel/" class="btn btn-outline-light me-2">Dashboard</a>
                    <?php else: ?>
                        <a href="./studentPanel/login.php" class="btn btn-outline-light me-2">Login</a>
                        <a href="./studentPanel/signup.php" class="btn btn-primary">Sign Up</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 text-lg-start">
                    <h1 class="display-4 fw-bold mb-4 text-white">
                        Launch Your Career with 
                        <span class="gradient-text">GD Edu Tech</span>
                    </h1>
                    <p class="lead mb-5 text-white opacity-90">
                        Discover exciting career opportunities and join our mission to transform education through technology.
                    </p>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="./assets/images/Online-resume-2--unscreen.gif" alt="Career Illustration" class="img-fluid hero-image">
                </div>
            </div>
        </div>
    </section>

    <!-- Current Openings Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Current Openings</h2>
            <div class="row g-4">
                <?php
                // Fetch active jobs from the database
                $sql = "SELECT * FROM Careers WHERE status = 'Active' ORDER BY created_at DESC";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($job = $result->fetch_assoc()) {
                        ?>
                        <div class="col-md-6">
                            <div class="card h-100 feature-card">
                                <div class="card-body">
                                    <h3 class="card-title"><?php echo htmlspecialchars($job['job_title']); ?></h3>
                                    <span class="badge bg-primary mb-3"><?php echo htmlspecialchars($job['job_type']); ?></span>
                                    <p class="card-text"><?php echo htmlspecialchars($job['job_description']); ?></p>
                                    <ul class="list-unstyled mb-4">
                                        <?php
                                        // Convert requirements string to array and display
                                        $requirements = explode("\n", $job['requirements']);
                                        foreach ($requirements as $requirement) {
                                            if (trim($requirement) != '') {
                                                echo '<li><i class="bi bi-check-circle-fill text-primary me-2"></i>' . htmlspecialchars(trim($requirement)) . '</li>';
                                            }
                                        }
                                        ?>
                                    </ul>
                                    <a href="apply.php?job_id=<?php echo $job['job_id']; ?>" class="btn btn-primary">Apply Now</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="col-12 text-center"><p>No job openings available at the moment.</p></div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Why Join Us Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Why Join GD Edu Tech?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 feature-card">
                        <div class="card-body text-center">
                            <i class="bi bi-graph-up-arrow fs-1 text-primary mb-3"></i>
                            <h4>Growth Opportunities</h4>
                            <p>Continuous learning and career advancement opportunities in a rapidly growing edtech company.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 feature-card">
                        <div class="card-body text-center">
                            <i class="bi bi-people fs-1 text-primary mb-3"></i>
                            <h4>Inclusive Culture</h4>
                            <p>Join a diverse team of passionate educators and technologists making a difference in education.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 feature-card">
                        <div class="card-body text-center">
                            <i class="bi bi-gift fs-1 text-primary mb-3"></i>
                            <h4>Great Benefits</h4>
                            <p>Competitive salary, health insurance, flexible work hours, and remote work options.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

   

    <!-- Footer -->
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
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.querySelector('.navbar').classList.add('scrolled');
            } else {
                document.querySelector('.navbar').classList.remove('scrolled');
            }
        });
    </script>
</body>
</html> 