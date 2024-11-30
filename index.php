<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once './Configurations/config.php';

// Fetch popular courses
$popular_courses_query = "
    SELECT c.*, 
           u.first_name, 
           u.last_name,
           cat.name as category_name,
           (SELECT COUNT(*) FROM Enrollments WHERE course_id = c.course_id) as student_count
    FROM Courses c
    JOIN Users u ON c.created_by = u.user_id
    LEFT JOIN Categories cat ON c.category_id = cat.category_id
    WHERE c.status = 'published' AND c.isPopular = 'yes'
    LIMIT 6";
$popular_courses = $conn->query($popular_courses_query)->fetch_all(MYSQLI_ASSOC);

// Fetch categories
$categories_query = "
    SELECT c.*, 
           (SELECT COUNT(*) FROM Courses WHERE category_id = c.category_id) as course_count
    FROM Categories c
    LIMIT 8";
$categories = $conn->query($categories_query)->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GD Edu Tech - Transform Your Future</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--primary-color);">
        <div class="container">
            <a class="navbar-brand" href="#"><img style="height: 60px;" src="./Images/logos/GD_Full_logo.png" alt=""></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#courses">Courses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#categories">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
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
                        Transform Your Future with 
                        <span class="gradient-text">GD Edu Tech</span>
                    </h1>
                    <p class="lead mb-5 text-white opacity-90">
                        Access world-class education from anywhere. Learn at your own pace with expert-led courses.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="./studentPanel/signup.php" class="btn btn-light btn-lg px-5 rounded-pill">
                            <i class="bi bi-rocket-takeoff me-2"></i>Start Learning Today
                        </a>
                        <a href="#courses" class="btn btn-outline-light btn-lg px-5 rounded-pill">
                            <i class="bi bi-play-circle me-2"></i>Explore Courses
                        </a>
                    </div>
                    <div class="mt-5 d-flex gap-4 stats-highlight">
                        <div class="text-white">
                            <h3 class="fw-bold mb-0">10K+</h3>
                            <p class="mb-0 opacity-75">Active Students</p>
                        </div>
                        <div class="text-white">
                            <h3 class="fw-bold mb-0">500+</h3>
                            <p class="mb-0 opacity-75">Expert Courses</p>
                        </div>
                        <div class="text-white">
                            <h3 class="fw-bold mb-0">4.8</h3>
                            <p class="mb-0 opacity-75">User Rating</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img style="filter: hue-rotate(100deg);" src="./Images/Others/illustration_home.png" alt="Education Illustration" class="img-fluid hero-image">
                </div>
            </div>
        </div>
    </section>

    <!-- After hero section, before features section -->
    <section class="py-5">
        <div class="container">
            <div id="featureCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#featureCarousel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#featureCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#featureCarousel" data-bs-slide-to="2"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="feature-card card h-100 p-4 mx-2">
                                    <div class="card-body text-center">
                                        <i class="bi bi-collection-play fs-1 text-primary mb-3"></i>
                                        <h4>Interactive Learning</h4>
                                        <p>Engage with interactive content, quizzes, and hands-on projects</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-card card h-100 p-4 mx-2">
                                    <div class="card-body text-center">
                                        <i class="bi bi-clock-history fs-1 text-primary mb-3"></i>
                                        <h4>Learn at Your Pace</h4>
                                        <p>Access course content 24/7 and learn at your own schedule</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="feature-card card h-100 p-4 mx-2">
                                    <div class="card-body text-center">
                                        <i class="bi bi-people fs-1 text-primary mb-3"></i>
                                        <h4>Community Support</h4>
                                        <p>Join a community of learners and get peer support</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-card card h-100 p-4 mx-2">
                                    <div class="card-body text-center">
                                        <i class="bi bi-chat-dots fs-1 text-primary mb-3"></i>
                                        <h4>Expert Feedback</h4>
                                        <p>Get personalized feedback from industry experts</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="feature-card card h-100 p-4 mx-2">
                                    <div class="card-body text-center">
                                        <i class="bi bi-phone fs-1 text-primary mb-3"></i>
                                        <h4>Mobile Learning</h4>
                                        <p>Learn on any device with our mobile-friendly platform</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-card card h-100 p-4 mx-2">
                                    <div class="card-body text-center">
                                        <i class="bi bi-patch-check fs-1 text-primary mb-3"></i>
                                        <h4>Verified Certificates</h4>
                                        <p>Earn industry-recognized certificates upon completion</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#featureCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#featureCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Why Choose GD Edu Tech?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card card h-100 p-4">
                        <div class="card-body text-center">
                            <i class="bi bi-laptop fs-1 text-primary mb-3"></i>
                            <h4>Learn Anywhere</h4>
                            <p>Access courses from any device, anytime, anywhere.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card card h-100 p-4">
                        <div class="card-body text-center">
                            <i class="bi bi-person-check fs-1 text-primary mb-3"></i>
                            <h4>Expert Instructors</h4>
                            <p>Learn from industry experts and professionals.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card card h-100 p-4">
                        <div class="card-body text-center">
                            <i class="bi bi-award fs-1 text-primary mb-3"></i>
                            <h4>Certificates</h4>
                            <p>Earn recognized certificates upon completion.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Courses -->
    <section class="py-5 bg-light" id="courses">
        <div class="container">
            <h2 class="text-center mb-5">Popular Courses</h2>
            <div class="row g-4">
                <?php foreach ($popular_courses as $course): ?>
                    <div class="col-md-4">
                        <div class="course-card card h-100">
                            <img src="./uploads/course_uploads/thumbnails/<?php echo htmlspecialchars($course['thumbnail']); ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($course['title']); ?>"
                                 style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <span class="badge bg-primary mb-2">
                                    <?php echo htmlspecialchars($course['category_name']); ?>
                                </span>
                                <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                                <p class="card-text text-muted">
                                    <?php echo substr(htmlspecialchars($course['description']), 0, 100) . '...'; ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">
                                        <i class="bi bi-people"></i> <?php echo $course['student_count']; ?> students
                                    </span>
                                    <a href="course.php?id=<?php echo $course['course_id']; ?>" 
                                       class="btn btn-primary">Learn More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-5" id="categories">
        <div class="container">
            <h2 class="text-center mb-5">Browse Categories</h2>
            <div class="row g-4">
                <?php foreach ($categories as $category): ?>
                    <div class="col-md-3">
                        <div class="category-card card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h5>
                                <p class="card-text text-muted">
                                    <?php echo $category['course_count']; ?> courses
                                </p>
                                <a href="category.php?id=<?php echo $category['category_id']; ?>" 
                                   class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <i class="bi bi-person-fill fs-1 text-primary mb-3"></i>
                        <h3>10,000+</h3>
                        <p class="mb-0">Active Students</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <i class="bi bi-book-fill fs-1 text-primary mb-3"></i>
                        <h3>500+</h3>
                        <p class="mb-0">Total Courses</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <i class="bi bi-star-fill fs-1 text-primary mb-3"></i>
                        <h3>4.8</h3>
                        <p class="mb-0">Average Rating</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <i class="bi bi-award-fill fs-1 text-primary mb-3"></i>
                        <h3>15,000+</h3>
                        <p class="mb-0">Certificates Awarded</p>
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
                        <i class="bi bi-envelope me-2"></i> info@gdedutech.com<br>
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