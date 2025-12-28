<!-- Navbar Styles -->
<style>
/* Navigation Styles */
.navbar {
    padding: 20px 0;
    transition: all 0.4s ease;
    background: rgba(255, 255, 255, 0.97);
    box-shadow: var(--shadow);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    position: fixed;
    width: 100%;
    top: 0;
    z-index: 1000;
}

.navbar.scrolled {
    padding: 12px 0;
    box-shadow: var(--shadow-md);
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
}

.navbar-brand {
    margin-right: 3rem;
    display: flex;
    align-items: center;
}

.navbar-brand img {
    height: 40px;
    transition: all 0.3s ease;
}

.navbar-toggler {
    border: none;
    padding: 0;
    width: 30px;
    height: 30px;
    position: relative;
    background: transparent;
    cursor: pointer;
    display: none;
}

.navbar-toggler:focus {
    outline: none;
    box-shadow: none;
}

.navbar-toggler-icon {
    width: 100%;
    height: 2px;
    background: var(--text-dark);
    display: block;
    position: relative;
    transition: all 0.3s ease;
}

.navbar-toggler-icon::before,
.navbar-toggler-icon::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 2px;
    background: var(--text-dark);
    left: 0;
    transition: all 0.3s ease;
}

.navbar-toggler-icon::before {
    top: -8px;
}

.navbar-toggler-icon::after {
    bottom: -8px;
}

.navbar-toggler[aria-expanded="true"] .navbar-toggler-icon {
    background: transparent;
}

.navbar-toggler[aria-expanded="true"] .navbar-toggler-icon::before {
    transform: rotate(45deg);
    top: 0;
}

.navbar-toggler[aria-expanded="true"] .navbar-toggler-icon::after {
    transform: rotate(-45deg);
    bottom: 0;
}

.navbar-nav {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin: 0;
    padding: 0;
}

.nav-item {
    position: relative;
    padding: 0 0.5rem;
}

.nav-link {
    color: var(--text-dark) !important;
    font-weight: 600;
    position: relative;
    padding: 5px 0;
    font-size: 0.95rem;
    transition: color 0.3s ease;
}

.nav-link:hover,
.nav-link.active {
    color: var(--text-dark) !important;
}

.nav-link:after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: 0;
    left: 0;
    background: var(--text-gradient);
    transition: width 0.3s ease;
}

.nav-link:hover:after,
.nav-link.active:after {
    width: 100%;
}

.navbar-actions {
    margin-left: 2rem;
    display: flex;
    align-items: center;
    gap: 12px;
}

.btn-login {
    padding: 8px 16px;
    color: var(--text-dark);
    font-weight: 600;
    border-radius: 50px;
    transition: all 0.3s ease;
    background: transparent;
    border: 1px solid var(--primary);
}

.btn-login:hover {
    background: var(--primary);
    color: white;
}

.btn-signup {
    padding: 8px 16px;
    color: white;
    font-weight: 600;
    border-radius: 50px;
    transition: all 0.3s ease;
    background: var(--primary);
    border: 1px solid var(--primary);
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-signup:hover {
    background: var(--primary-dark);
    color: white;
    transform: translateY(-2px);
}

.btn-signup i {
    transition: transform 0.3s ease;
}

.btn-signup:hover i {
    transform: translateX(4px);
}

@media (max-width: 991.98px) {
    .navbar-brand {
        margin-right: 0;
    }
    
    .navbar-nav {
        margin-top: 1rem;
        gap: 0.5rem;
    }

    .navbar-actions {
        margin: 1rem 0;
        width: 100%;
        justify-content: center;
    }
    
    .btn-login,
    .btn-signup {
        width: 100%;
        text-align: center;
        justify-content: center;
    }
}

/* Mobile Navigation Styles */
@media (max-width: 991px) {
    .navbar-toggler {
        display: block;
    }

    .navbar-collapse {
        position: fixed;
        top: 70px;
        left: -100%;
        width: 100%;
        height: calc(100vh - 70px);
        background: rgba(255, 255, 255, 0.98);
        padding: 20px;
        transition: all 0.4s ease;
        display: flex;
        flex-direction: column;
    }

    .navbar-collapse.show {
        left: 0;
    }

    .navbar-nav {
        flex-direction: column;
        gap: 0;
        align-items: flex-start;
        width: 100%;
    }

    .nav-item {
        width: 100%;
    }

    .nav-link {
        padding: 12px 0;
        display: block;
        width: 100%;
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }

    .navbar-actions {
        flex-direction: column;
        width: 100%;
        margin: 20px 0 0 0;
        gap: 10px;
    }

    .btn-login,
    .btn-signup {
        width: 100%;
        text-align: center;
        padding: 10px 20px;
    }

    .navbar.scrolled .navbar-collapse {
        top: 60px;
    }
}

/* Additional Mobile Responsive Improvements */
@media (max-width: 768px) {
    .navbar-brand img {
        height: 35px;
    }

    .hero-section {
        padding-top: 100px;
    }

    .section-header {
        margin-bottom: 30px;
    }

    .heading {
        font-size: 2rem;
    }

    .sub-heading {
        font-size: 1rem;
    }

    .features-grid,
    .categories-grid {
        gap: 15px;
    }

    .premium-feature-card,
    .premium-category-card {
        padding: 20px;
    }

    .testimonial-card {
        margin: 10px;
    }
}
</style>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg fixed-top" data-aos="fade-down" data-aos-duration="800">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="./Images/Logos/GD_Full_logo.png" alt="GD Edu Tech Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item" style="--item-index: 1;">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item" style="--item-index: 2;">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'courses.php' ? 'active' : ''; ?>" href="courses.php">Courses</a>
                </li>
                <li class="nav-item" style="--item-index: 3;">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'blog.php' ? 'active' : ''; ?>" href="blog.php">Blog</a>
                </li>
                <li class="nav-item" style="--item-index: 4;">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'events.php' ? 'active' : ''; ?>" href="events.php">Events</a>
                </li>
                <li class="nav-item" style="--item-index: 5;">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>" href="about.php">About</a>
                </li>
                <li class="nav-item" style="--item-index: 6;">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'career.php' ? 'active' : ''; ?>" href="career.php">Career</a>
                </li>
                <li class="nav-item" style="--item-index: 7;">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>" href="contact.php">Contact</a>
                </li>
            </ul>
            <div class="navbar-actions">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="./studentPanel/" class="btn-login">Dashboard</a>
                <?php else: ?>
                    <a href="./studentPanel/login.php" class="btn-login">Login</a>
                    <a href="./studentPanel/signup.php" class="btn-signup">Sign Up <i class="bi bi-arrow-right"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav> 