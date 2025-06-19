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
    <title>Blog - GD Edu Tech</title>
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
    <style>
        /* Enhanced mobile responsiveness for blog.php */
        :root {
            --mobile-padding: 1rem;
            --card-radius: 16px;
            --transition-speed: 0.3s;
        }

        /* General Mobile Improvements */
        @media (max-width: 991.98px) {
            .container {
                padding-left: var(--mobile-padding);
                padding-right: var(--mobile-padding);
            }

            .page-header .display-5 {
                font-size: 2.2rem;
                line-height: 1.3;
            }

            .premium-card {
                padding: 1.5rem !important;
                border-radius: var(--card-radius);
                transition: transform var(--transition-speed) ease;
            }

            .premium-card:hover {
                transform: translateY(-5px);
            }

            .section-heading {
                font-size: 1.8rem;
                margin-bottom: 1rem;
            }

            .lead {
                font-size: 1.1rem;
                line-height: 1.6;
            }
        }

        @media (max-width: 767.98px) {
            /* Header Improvements */
            .page-header {
                padding: 100px 0 40px !important;
                min-height: auto !important;
            }

            .page-header .display-5 {
                font-size: 1.8rem;
            }

            .page-header .lead {
                font-size: 1rem;
            }

            /* Card Improvements */
            .premium-card {
                flex-direction: column !important;
                padding: 1.25rem !important;
                margin-bottom: 1.5rem;
            }

            .premium-card img {
                margin-bottom: 1.25rem !important;
                border-radius: calc(var(--card-radius) - 4px);
            }

            .premium-card .card-title {
                font-size: 1.4rem;
                margin-bottom: 0.75rem;
            }

            /* Button Improvements */
            .btn-group {
                display: flex;
                width: 100%;
                margin-top: 1rem;
            }

            .btn-group .btn {
                flex: 1;
                font-size: 0.95rem;
                padding: 0.75rem 1rem;
                white-space: nowrap;
            }

            /* Newsletter Improvements */
            .newsletter-section {
                padding: 3rem 0;
            }

            .newsletter-section .input-group {
                flex-direction: column;
                gap: 1rem;
            }

            .newsletter-section .form-control {
                height: 3.5rem;
                font-size: 1rem;
                border-radius: 50px;
                padding: 0 1.5rem;
            }

            .newsletter-section .btn {
                height: 3.5rem;
                border-radius: 50px;
                font-size: 1rem;
                padding: 0 2rem;
            }

            /* Category Cards Improvements */
            .premium-feature-card {
                padding: 1.5rem;
                margin-bottom: 1rem;
            }

            .premium-feature-card .card-icon {
                width: 70px;
                height: 70px;
                margin-bottom: 1.25rem;
            }

            .premium-feature-card h3 {
                font-size: 1.3rem;
                margin-bottom: 0.75rem;
            }


            /* Author Info Improvements */
            .author-info {
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .author-info img {
                width: 40px;
                height: 40px;
            }

            .author-info small {
                font-size: 0.85rem;
            }

            /* Blog Post Card Improvements */
            .blog-post-card {
                height: 100%;
                display: flex;
                flex-direction: column;
            }

            .blog-post-card .card-body {
                flex: 1;
                display: flex;
                flex-direction: column;
            }

            .blog-post-card .card-text {
                flex: 1;
                margin-bottom: 1rem;
            }

            /* Touch-friendly improvements */
            .btn, 
            .nav-link,
            .feature-link {
                min-height: 44px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            /* Image optimizations */
            img {
                max-width: 100%;
                height: auto;
                object-fit: cover;
            }

            /* Spacing improvements */
            .section-padding {
                padding: 3rem 0;
            }

            .mb-mobile {
                margin-bottom: 1.5rem;
            }
        }

        /* Animation improvements */
        @media (prefers-reduced-motion: no-preference) {
            .premium-card,
            .premium-feature-card,
            .btn {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .premium-card:hover,
            .premium-feature-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            }
        }

        /* Button and Form Improvements */
        .btn-view-more {
            padding: 0.75rem 2rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--primary);
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(13, 114, 152, 0.2);
        }

        .btn-view-more:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 114, 152, 0.3);
            color: white;
        }

        .newsletter-section .form-control {
            height: 3.5rem;
            border-radius: 50px;
            padding: 0 1.5rem;
            font-size: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            backdrop-filter: blur(10px);
        }

        .newsletter-section .form-control::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        .newsletter-section .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: none;
            color: white;
        }

        .newsletter-section .btn-subscribe {
            height: 3.5rem;
            border-radius: 50px;
            padding: 0 2rem;
            font-size: 1rem;
            font-weight: 600;
            background: white;
            color: var(--primary);
            border: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .newsletter-section .btn-subscribe:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            background: white;
            color: var(--primary);
        }

        /* Newsletter Section Mobile Fixes */
        @media (max-width: 767.98px) {
            .newsletter-section .input-group {
                flex-direction: column;
                gap: 1rem;
                width: 100%;
            }

            .newsletter-section .form-control {
                width: 100%;
                margin-bottom: 0.5rem;
                height: 3.5rem;
                border-radius: 50px;
                padding: 0 1.5rem;
                font-size: 1rem;
                border: 2px solid rgba(255, 255, 255, 0.2);
                background: rgba(255, 255, 255, 0.1);
                color: white;
            }

            .newsletter-section .btn-subscribe {
                width: 100%;
                height: 3.5rem;
                border-radius: 50px;
                padding: 0 2rem;
                font-size: 1rem;
                font-weight: 600;
                background: white;
                color: var(--primary);
                border: none;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
            }

            .newsletter-section .form-control::placeholder {
                color: rgba(255, 255, 255, 0.7);
            }

            .newsletter-section .form-control:focus {
                background: rgba(255, 255, 255, 0.15);
                border-color: rgba(255, 255, 255, 0.3);
                box-shadow: none;
                color: white;
            }
        }

        /* Testimonial CTA Section */
        .testimonial-cta {
            width: 100%;
            height: 20%;
            background-color: var(--primary);
        }

        .testimonial-cta h3 {
            font-size: 4rem;
        }

        @media (max-width: 767.98px) {
            .testimonial-cta h3 {
                font-size: 2.5rem;
            }
        }

        /* Add custom styles for the blog image */
        .blog-hero-image {
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

        .blog-hero-image:hover::after {
            opacity: 1;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <!-- Page Header -->
    <section class="page-header position-relative overflow-hidden">
        <div class="container position-relative py-7">
            <div class="row align-items-center">
                <div class="col-md-7 col-12 mb-4 mb-md-0" data-aos="fade-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-3">
                            <li class="breadcrumb-item"><a href="index.php" class="text-white-50">Home</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Blog</li>
                        </ol>
                    </nav>
                    <h1 class="text-white display-5 fw-bold mb-3">Educational Insights</h1>
                    <p class="text-white-50 lead mb-0">Discover the latest trends, tips, and insights in education and technology.</p>
                </div>
                <div class="col-md-5 col-12 text-center" data-aos="fade-left">
                    <img src="./Images/Others/image1.png" alt="Blog" class="blog-hero-image">
                </div>
            </div>
        </div>
        <div class="page-header-shape">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </section>

    <!-- Blog Categories -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-6 col-12">
                    <h2 class="section-heading" data-aos="fade-up">Browse by Category</h2>
                    <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">Find articles that match your interests</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up">
                    <a href="#" class="text-decoration-none">
                        <div class="premium-feature-card h-100 text-center">
                            <div class="card-icon">
                                <i class="bi bi-laptop-fill fa-3x"></i>
                            </div>
                            <h3>Technology</h3>
                            <div class="card-shape"></div>
                            <p class="text-muted">Latest tech trends in education</p>
                            <span class="feature-link">View Articles <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up" data-aos-delay="100">
                    <a href="#" class="text-decoration-none">
                        <div class="premium-feature-card h-100 text-center">
                            <div class="card-icon">
                                <i class="bi bi-book-fill fa-3x"></i>
                            </div>
                            <h3>Learning Tips</h3>
                            <div class="card-shape"></div>
                            <p class="text-muted">Study strategies and advice</p>
                            <span class="feature-link">View Articles <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up" data-aos-delay="200">
                    <a href="#" class="text-decoration-none">
                        <div class="premium-feature-card h-100 text-center">
                            <div class="card-icon">
                                <i class="bi bi-graph-up-arrow fa-3x"></i>
                            </div>
                            <h3>Career Growth</h3>
                            <div class="card-shape"></div>
                            <p class="text-muted">Professional development guides</p>
                            <span class="feature-link">View Articles <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6 col-12" data-aos="fade-up" data-aos-delay="300">
                    <a href="#" class="text-decoration-none">
                        <div class="premium-feature-card h-100 text-center">
                            <div class="card-icon">
                                <i class="bi bi-people-fill fa-3x"></i>
                            </div>
                            <h3>Student Life</h3>
                            <div class="card-shape"></div>
                            <p class="text-muted">Stories and experiences</p>
                            <span class="feature-link">View Articles <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Posts -->
    <section class="py-5 section-padding">
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-6 col-12">
                    <h2 class="section-heading" data-aos="fade-up">Latest Articles</h2>
                    <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">Stay updated with our newest content</p>
                </div>
                <div class="col-lg-6 col-12 text-lg-end mt-3 mt-lg-0" data-aos="fade-up" data-aos-delay="200">
                    <div class="btn-group w-100 w-lg-auto" role="group" aria-label="Blog filter">
                        <button type="button" class="btn btn-primary active">All</button>
                        <button type="button" class="btn btn-outline-primary">Popular</button>
                        <button type="button" class="btn btn-outline-primary">Trending</button>
                    </div>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-12 text-center" data-aos="fade-up">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        No blog posts available at the moment. Please check back later.
                    </div>
                </div>
            </div>
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="#" class="btn btn-view-more">
                    View All Articles <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="cta-section newsletter-section" data-aos="fade-up">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 col-12 mx-auto text-center">
                    <h2 class="display-5 fw-bold text-white mb-4">Subscribe to Our Newsletter</h2>
                    <p class="lead text-white-50 mb-5">Get the latest articles, tips, and insights delivered directly to your inbox.</p>
                    <form class="row justify-content-center">
                        <div class="col-md-8 col-12">
                            <div class="input-group input-group-lg flex-column flex-md-row">
                                <input type="email" class="form-control" placeholder="Enter your email">
                                <button class="btn btn-subscribe" type="submit">
                                    Subscribe <i class="bi bi-arrow-right"></i>
                                </button>
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
    <!-- Initialize AOS and other scripts -->
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });
    </script>
</body>

</html> 