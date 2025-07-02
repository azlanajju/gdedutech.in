<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once './Configurations/config.php';

// Fetch popular courses
$popular_courses_query = "
    SELECT c.*, 
           cat.name as category_name,
           (SELECT COUNT(*) FROM Enrollments e WHERE e.course_id = c.course_id) as student_count
    FROM Courses c
    LEFT JOIN Categories cat ON c.category_id = cat.category_id
    WHERE c.isPopular = '1'
    AND c.status = 'published'
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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- AOS Animation Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <!-- Swiper JS CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css">
    <!-- Particles.js -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="./css/index.css">
</head>

<body>
    <!-- Navigation -->
     <?php include './navbar.php'; ?>


    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div id="particles-js"></div>
        <div class="shape shape-1" data-aos="fade-right" data-aos-duration="1000"></div>
        <div class="shape shape-2" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200"></div>
        <div class="shape shape-3" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400"></div>
        
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                    <div class="hero-content">
                        <div class="hero-badge" data-aos="fade-up" data-aos-delay="200">
                            <i class="bi bi-lightning-fill me-2"></i>Transform Your Future with GD Edu Tech
                        </div>
                        <h1 class="hero-title" data-aos="fade-up" data-aos-delay="400">Discover a <span data-text="World of Learning">World of Learning</span> for Your Future</h1>
                        <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="600">Join thousands of students in our world-class online programs and develop the skills needed for in-demand careers.</p>
                        
                        <div class="hero-buttons" data-aos="fade-up" data-aos-delay="800">
                            <a href="#courses" class="btn-primary-action">
                                Explore Courses <i class="bi bi-arrow-right"></i>
                            </a>
                            <a href="about.php" class="btn-secondary-action">
                                Learn More <i class="bi bi-info-circle"></i>
                            </a>
                        </div>
                        
                        <div class="hero-stats">
                            <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
                                <h3><span class="counter">50</span>+</h3>
                                <p>Expert Instructors</p>
                            </div>
                            <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
                                <h3><span class="counter">100</span>+</h3>
                                <p>Quality Courses</p>
                            </div>
                            <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
                                <h3><span class="counter">10</span>K+</h3>
                                <p>Active Students</p>
                            </div>
                        </div>
                    </div>
                    </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                    <div class="hero-image-wrapper">
                        <img src="./Images/Others/illustration_home1.png" alt="Education Platform" class="hero-image">
                        
                        <div class="hero-card hero-card-1">
                            <div class="hero-card-icon">
                                <i class="bi bi-laptop"></i>
                            </div>
                            <div class="hero-card-content">
                                <h4>Learn Anywhere</h4>
                                <p>24/7 Access to Courses</p>
                            </div>
                        </div>
                        
                        <div class="hero-card hero-card-2">
                            <div class="hero-card-icon">
                                <i class="bi bi-award-fill"></i>
                            </div>
                            <div class="hero-card-content">
                                <h4>Get Certified</h4>
                                <p>Industry Recognized</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <span class="sub-heading text-accent-gradient">Why Choose Us</span>
                <h2 class="heading">Features That <span class="text-gradient">Set Us Apart</span></h2>
                <p class="lead">Discover the features that make our learning platform unique and effective</p>
            </div>
            
            <div class="features-grid">
                <!-- Feature 1 -->
                <div class="feature-card-wrapper" data-aos="fade-up" data-aos-delay="100">
                    <div class="premium-feature-card">
                        <div class="feature-content">
                            <div class="card-icon">
                                <i class="bi bi-laptop-fill"></i>
                            </div>
                            <h3>Online Learning</h3>
                            <p>Access our courses anytime, anywhere with our flexible online learning platform. Learn at your own pace with 24/7 access to course materials.</p>
                            <a href="#" class="feature-link">Learn More <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card-wrapper" data-aos="fade-up" data-aos-delay="200">
                    <div class="premium-feature-card">
                        <div class="feature-content">
                            <div class="card-icon">
                                <i class="bi bi-person-video3"></i>
                            </div>
                            <h3>Expert Instructors</h3>
                            <p>Learn from industry professionals with years of practical experience. Get personalized guidance and support throughout your learning journey.</p>
                            <a href="#" class="feature-link">Learn More <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card-wrapper" data-aos="fade-up" data-aos-delay="300">
                    <div class="premium-feature-card">
                        <div class="feature-content">
                            <div class="card-icon">
                                <i class="bi bi-award-fill"></i>
                            </div>
                            <h3>Certifications</h3>
                            <p>Earn industry-recognized certifications upon course completion. Boost your resume with credentials that matter to employers.</p>
                            <a href="#" class="feature-link">Learn More <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Courses -->
    <section class="py-5 bg-light" id="courses">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">Popular Courses</h2>
            <div class="row g-4">
                <?php foreach ($popular_courses as $index => $course): ?>
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="<?php echo $index * 200; ?>">
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
                                    <a href="./studentPanel/MyCourses/course.php?id=<?php echo $course['course_id']; ?>"
                                        class="btn btn-primary">Learn More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="view-all-course" data-aos="fade-up" data-aos-delay="200" style="text-align: center;display: flex;justify-content: center;align-items: center;margin-top: 30px;">
            <a href="courses.php" class="btn btn-primary">View All Courses</a>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories-section" id="categories">
        <div class="container">
            <div class="section-header text-center" data-aos="fade-up">
                <span class="sub-heading text-accent-gradient">Learning Paths</span>
                <h2 class="heading">Explore Our <span class="text-gradient">Categories</span></h2>
                <p class="lead">Discover specialized learning paths tailored to your interests</p>
            </div>
            
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
                <?php foreach ($categories as $index => $category): ?>
                <div class="col" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="category-card h-100">
                        <div class="category-icon">
                            <div class="icon-bg"></div>
                            <i class="<?php echo !empty($category['icon']) ? $category['icon'] : 'bi bi-book'; ?>"></i>
                        </div>
                        <div class="category-content">
                            <h3><?php echo $category['name']; ?></h3>
                            <p><?php echo !empty($category['description']) ? substr($category['description'], 0, 80) . '...' : 'Explore courses in this category'; ?></p>
                            <!-- <div class="category-meta">
                                <span><i class="bi bi-collection"></i> <?php echo $category['course_count']; ?> Courses</span>
                            </div> -->
                            <a href="courses.php?category=<?php echo $category['category_id']; ?>" class="category-link">
                                View Courses <i class="bi bi-arrow-right"></i>
                            </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="testimonial-section" id="testimonials">
        <div class="container">

        <!-- testimonial section --- not required for now -->
            <!-- <div class="section-header text-center" data-aos="fade-up">
                <h6 class="text-primary text-uppercase fw-bold mb-3">
                    <i class="bi bi-star-fill me-2"></i>Student Reviews
                </h6>
                <h2 class="display-5 fw-bold text-white mb-3">What Our Students Say</h2>
                <div class="section-divider mx-auto"></div>
            </div>

            <div class="testimonial-slider-wrapper" data-aos="fade-up" data-aos-delay="200">
                <div class="swiper testimonialSwiper">
                    <div class="swiper-wrapper">

                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <div class="testimonial-content">
                                    <div class="rating mb-3">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                    </div>
                                    <div class="testimonial-text">
                                        <i class="bi bi-quote quote-icon"></i>
                                        <p>The quality of education and support at GD Edu Tech is exceptional. The instructors are knowledgeable and always willing to help. I've learned so much in such a short time!</p>
                                    </div>
                                </div>
                                <div class="testimonial-author">
                                    <div class="author-image">
                                        <img src="./Images/Others/testimonial-1.jpg" alt="Sarah Johnson">
                                    </div>
                                    <div class="author-info">
                                        <h5>Sarah Johnson</h5>
                                        <p>Web Development Student</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <div class="testimonial-content">
                                    <div class="rating mb-3">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                    </div>
                                    <div class="testimonial-text">
                                        <i class="bi bi-quote quote-icon"></i>
                                        <p>The practical approach to learning and industry-relevant curriculum helped me land my dream job. GD Edu Tech has truly transformed my career path!</p>
                                    </div>
                                </div>
                                <div class="testimonial-author">
                                    <div class="author-image">
                                        <img src="./Images/Others/testimonial-2.jpg" alt="Michael Chen">
                                    </div>
                                    <div class="author-info">
                                        <h5>Michael Chen</h5>
                                        <p>Data Science Graduate</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="swiper-slide">
                            <div class="testimonial-card">
                                <div class="testimonial-content">
                                    <div class="rating mb-3">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-half"></i>
                                    </div>
                                    <div class="testimonial-text">
                                        <i class="bi bi-quote quote-icon"></i>
                                        <p>The flexibility of online learning combined with expert instruction makes GD Edu Tech stand out. I've gained valuable skills that I use every day in my work.</p>
                                    </div>
                                </div>
                                <div class="testimonial-author">
                                    <div class="author-image">
                                        <img src="./Images/Others/testimonial-3.jpg" alt="Emily Rodriguez">
                                    </div>
                                    <div class="author-info">
                                        <h5>Emily Rodriguez</h5>
                                        <p>UI/UX Design Student</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="swiper-pagination"></div>
                    <div class="slider-arrows">
                        <button class="slider-prev">
                            <i class="bi bi-arrow-left"></i>
                        </button>
                        <button class="slider-next">
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div> -->

            <!-- Call to Action -->
            <div class="testimonial-cta" data-aos="fade-up" data-aos-delay="400" style="text-align: center;display: flex;justify-content: center;align-items: center;margin-top: 40px;">
                <div class="row align-items-center">
                    <div class="col-lg-8 mb-4 mb-lg-0">
                        <div class="cta-content">
                            <h3 class="text-white mb-2">Ready to Start Your Learning Journey?</h3>
                            <p class="text-white-50 mb-0">Join thousands of successful students who have transformed their careers with us.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <a href="./studentPanel/signup.php" class="btn btn-light btn-lg rounded-pill">
                            Get Started Now
                            <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>


    <!-- Gallery Section -->
    <section class="py-5 bg-light" id="gallery">
        <div class="container py-5">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-heading text-center" data-aos="fade-up">Our Learning Journey</h2>
                    <p class="lead text-muted" data-aos="fade-up" data-aos-delay="200">Take a glimpse at our vibrant learning community in action</p>
                </div>
            </div>
            <div class="row g-4" data-aos="fade-up" data-aos-delay="400">
                <div class="col-12">
                    <div class="swiper gallerySwiper">
                        <div class="swiper-wrapper">
                            <?php
                            $images = glob('./Images/gallery/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
                            foreach ($images as $image): ?>
                                <div class="swiper-slide">
                                    <div class="premium-card overflow-hidden">
                                        <img src="<?php echo $image; ?>" 
                                            class="img-fluid w-100" 
                                            alt="Gallery Image" 
                                            style="height: 400px; object-fit: cover;">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                </div>
                        <div class="swiper-pagination mt-4"></div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Waypoints -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <!-- Counter Up -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>

    <script>
        // Initialize AOS with custom settings
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
            mirror: false,
            offset: 120,
            delay: 100
        });

        // Enhanced Mobile Menu Behavior
        document.addEventListener('DOMContentLoaded', function() {
            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.querySelector('.navbar-collapse');
            const navLinks = document.querySelectorAll('.nav-link');
            
            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                const isClickInside = navbarCollapse.contains(event.target) || navbarToggler.contains(event.target);
                if (!isClickInside && navbarCollapse.classList.contains('show')) {
                    navbarToggler.click();
                }
            });

            // Close menu when clicking on a link
            navLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (navbarCollapse.classList.contains('show')) {
                        navbarToggler.click();
                    }
                });
            });

            // Enhanced scroll behavior
            let lastScrollTop = 0;
            const navbar = document.querySelector('.navbar');
            
            window.addEventListener('scroll', function() {
                let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                
                // Add/remove scrolled class
                if (scrollTop > 50) {
                    navbar.classList.add('scrolled');
                    navbar.classList.remove('navbar-dark');
                    navbar.classList.add('navbar-light');
                } else {
                    navbar.classList.remove('scrolled');
                    navbar.classList.remove('navbar-light');
                    navbar.classList.add('navbar-dark');
                }

                // Hide/show navbar on scroll
                if (scrollTop > lastScrollTop && scrollTop > 100) {
                    // Scrolling down & not at the top
                    navbar.style.transform = 'translateY(-100%)';
                } else {
                    // Scrolling up or at the top
                    navbar.style.transform = 'translateY(0)';
                }
                
                lastScrollTop = scrollTop;
            });
        });

        // Initialize Swiper for Testimonials
        var testimonialSwiper = new Swiper(".testimonialSwiper", {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoHeight: true,
            pagination: {
                el: ".testimonialSwiper .swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".slider-next",
                prevEl: ".slider-prev",
            },
            breakpoints: {
                576: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 2,
                    spaceBetween: 30,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 40,
                },
            },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
        });
        
        // Initialize Swiper for Gallery
        var gallerySwiper = new Swiper(".gallerySwiper", {
            slidesPerView: 1,
            spaceBetween: 10,
            loop: true,
            autoHeight: true,
            navigation: {
                nextEl: ".gallerySwiper .swiper-button-next",
                prevEl: ".gallerySwiper .swiper-button-prev",
            },
            pagination: {
                el: ".gallerySwiper .swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                480: {
                    slidesPerView: 1,
                    spaceBetween: 10,
                },
                576: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                992: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
            },
            autoplay: {
                delay: 4000,
                disableOnInteraction: false,
            },
        });
        
        // Counter Animation
        const counterUp = window.counterUp = (el, options = {}) => {
            const {
                duration = 1000,
                delay = 16,
            } = options;
            
            if (typeof el === 'string') {
                el = document.querySelector(el);
            }
            
            const start = el.innerText.replace(/,/g, '');
            const countTo = parseInt(el.getAttribute('data-count').replace(/,/g, ''));
            const inc = countTo / (duration / delay);
            let current = start;
            
            const counter = setInterval(() => {
                current = Math.ceil(current + inc);
                el.innerText = current.toLocaleString();
                
                if (parseInt(current) >= countTo) {
                    clearInterval(counter);
                    el.innerText = countTo.toLocaleString();
                }
            }, delay);
        };
        
        // Initialize counters when in viewport
        const counterElements = document.querySelectorAll('.counter');
        const observerOptions = {
            threshold: 0.2
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    counterUp(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);
        
        counterElements.forEach(el => {
            observer.observe(el);
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
                navbar.classList.remove('navbar-dark');
                navbar.classList.add('navbar-light');
            } else {
                navbar.classList.remove('scrolled');
                navbar.classList.remove('navbar-light');
                navbar.classList.add('navbar-dark');
            }
        });
        
        
        // Initialize particles.js
        particlesJS('particles-js', {
            "particles": {
                "number": {
                    "value": 80,
                    "density": {
                        "enable": true,
                        "value_area": 800
                    }
                },
                "color": {
                    "value": "#ffffff"
                },
                "shape": {
                    "type": "circle",
                    "stroke": {
                        "width": 0,
                        "color": "#000000"
                    },
                    "polygon": {
                        "nb_sides": 5
                    }
                },
                "opacity": {
                    "value": 0.3,
                    "random": true,
                    "anim": {
                        "enable": false,
                        "speed": 1,
                        "opacity_min": 0.1,
                        "sync": false
                    }
                },
                "size": {
                    "value": 3,
                    "random": true,
                    "anim": {
                        "enable": false,
                        "speed": 40,
                        "size_min": 0.1,
                        "sync": false
                    }
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#ffffff",
                    "opacity": 0.2,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 2,
                    "direction": "none",
                    "random": false,
                    "straight": false,
                    "out_mode": "out",
                    "bounce": false,
                    "attract": {
                        "enable": false,
                        "rotateX": 600,
                        "rotateY": 1200
                    }
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "grab"
                    },
                    "onclick": {
                        "enable": true,
                        "mode": "push"
                    },
                    "resize": true
                },
                "modes": {
                    "grab": {
                        "distance": 140,
                        "line_linked": {
                            "opacity": 0.5
                        }
                    },
                    "bubble": {
                        "distance": 400,
                        "size": 40,
                        "duration": 2,
                        "opacity": 8,
                        "speed": 3
                    },
                    "repulse": {
                        "distance": 200,
                        "duration": 0.4
                    },
                    "push": {
                        "particles_nb": 4
                    },
                    "remove": {
                        "particles_nb": 2
                    }
                }
            },
            "retina_detect": true
        });
    </script>
</body>

</html>