<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once './Configurations/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - GD Edu Tech</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="./css/style.css">
    <!-- Custom JavaScript -->
    <script src="./js/main.js" defer></script>
        
    <link rel="icon" type="image/png" href="./Images/Logos/GD_Only_logo.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./Images/Logos/GD_Only_logo.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./Images/Logos/GD_Only_logo.png">
    <link rel="shortcut icon" href="./Images/Logos/GD_Only_logo.png">
    <link rel="apple-touch-icon" href="./Images/Logos/GD_Only_logo.png">
    <meta name="msapplication-TileImage" content="./Images/Logos/GD_Only_logo.png">
    <style>
        /* Add custom styles for the events image */
        .premium-feature-card .card-icon i {
            color: #0d7298 !important; 
        }
        .events-hero-image {
            max-width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: contain;
            animation: float 6s ease-in-out infinite;
            transition: transform 0.3s ease;
            box-shadow: none;
            border: none;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-15px);
            }
            100% {
                transform: translateY(0px);
            }
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <!-- Page Header -->
    <section class="page-header position-relative overflow-hidden">
        <div class="container position-relative py-7">
            <div class="row align-items-center">
                <div class="col-md-7" data-aos="fade-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-3">
                            <li class="breadcrumb-item"><a href="index.php" class="text-white-50">Home</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Events</li>
                        </ol>
                    </nav>
                    <h1 class="text-white display-5 fw-bold mb-3">Upcoming Events</h1>
                    <p class="text-white-50 lead mb-0">Join our educational events, workshops, and webinars to enhance your learning journey.</p>
                </div>
                <div class="col-md-5" data-aos="fade-left">
                    <!-- <img src="./Images/Others/event2.png" alt="Events" class="events-hero-image"> -->
                </div>
            </div>
        </div>
        <div class="page-header-shape">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </section>

    <!-- Event Categories -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-6">
                    <h2 class="section-heading" data-aos="fade-up">Browse Events by Category</h2>
                    <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">Find events that match your interests and learning goals</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up">
                    <div class="premium-feature-card h-100 text-center">
                        <div class="card-icon">
                            <i class="bi bi-laptop-fill fa-3x"></i>
                        </div>
                        <h3>Tech Workshops</h3>
                        <div class="card-shape"></div>
                        <p class="text-muted">Hands-on technical training sessions</p>
                        <a href="#" class="feature-link">View Events <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="premium-feature-card h-100 text-center">
                        <div class="card-icon">
                            <i class="bi bi-camera-video-fill fa-3x"></i>
                        </div>
                        <h3>Webinars</h3>
                        <div class="card-shape"></div>
                        <p class="text-muted">Online interactive learning sessions</p>
                        <a href="#" class="feature-link">View Events <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="premium-feature-card h-100 text-center">
                        <div class="card-icon">
                            <i class="bi bi-people-fill fa-3x"></i>
                        </div>
                        <h3>Networking</h3>
                        <div class="card-shape"></div>
                        <p class="text-muted">Connect with industry professionals</p>
                        <a href="#" class="feature-link">View Events <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="premium-feature-card h-100 text-center">
                        <div class="card-icon">
                            <i class="bi bi-trophy-fill fa-3x"></i>
                        </div>
                        <h3>Competitions</h3>
                        <div class="card-shape"></div>
                        <p class="text-muted">Showcase your skills and win prizes</p>
                        <a href="#" class="feature-link">View Events <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Events -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-6">
                    <h2 class="section-heading" data-aos="fade-up">Upcoming Events</h2>
                    <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">Don't miss out on these exciting learning opportunities</p>
                </div>
                <div class="col-lg-6 text-lg-end" data-aos="fade-up" data-aos-delay="200">
                    <div class="btn-group" role="group" aria-label="Event filter">
                        <button type="button" class="btn btn-primary active">All</button>
                        <button type="button" class="btn btn-outline-primary">Online</button>
                        <button type="button" class="btn btn-outline-primary">In-Person</button>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-12 text-center" data-aos="fade-up">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        No events available at the moment. Please check back later.
                    </div>
                </div>
            </div>
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="#" class="btn btn-outline-primary btn-lg px-5">View All Events</a>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section" data-aos="fade-up">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-5 fw-bold text-white mb-4">Ready to Join Our Events?</h2>
                    <p class="lead text-white-50 mb-5">Sign up now to get notified about upcoming events and exclusive offers.</p>
                    <form class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="input-group input-group-lg">
                                <input type="email" class="form-control" placeholder="Enter your email">
                                <button class="btn btn-light" type="submit">Subscribe <i class="bi bi-arrow-right ms-2"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Include footer -->
    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <!-- Back to Top Button -->
    <script src="js/back-to-top.js"></script>
</body>

</html> 