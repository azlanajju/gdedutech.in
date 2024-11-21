<?php
require_once __DIR__ . '/vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$jwtSecretKey = "your_secret_key_here";

if (!isset($_COOKIE['auth_token'])) {
    header("Location: login.php");
    exit();
}

try {
    $jwt = $_COOKIE['auth_token'];
    $decoded = JWT::decode($jwt, new Key($jwtSecretKey, 'HS256'));

    // Access user details from the token
    $user_id = $decoded->user_id;
    $username = $decoded->username;
    $role = $decoded->role;


} catch (Exception $e) {
    echo "Unauthorized: " . $e->getMessage();
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GD Edu Tech - Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        .sidebar {
            background: #2C3E50;
            min-height: 100vh;
            color: white;
        }
        .nav-link {
            color: #ffffff;
            padding: 0.8rem 1rem;
            margin: 0.2rem 0;
            border-radius: 0.5rem;
        }
        .nav-link:hover {
            background: #34495E;
            color: #ffffff;
        }
        .nav-link.active {
            background: #3498DB;
            color: white;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .stats-card {
            background: linear-gradient(45deg, #3498db, #2980b9);
            color: white;
        }
        .course-card img {
            height: 160px;
            object-fit: cover;
        }
        .progress {
            height: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 min-vh-100">
                    <a href="#" class="d-flex align-items-center pb-3 mb-md-1 mt-md-3 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 fw-bolder">GD Edu Tech</span>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
                        <li class="w-100">
                            <a href="#" class="nav-link active">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="./course_details.php" class="nav-link">
                                <i class="bi bi-book me-2"></i> My Courses
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="#" class="nav-link">
                                <i class="bi bi-calendar-event me-2"></i> Schedule
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="#" class="nav-link">
                                <i class="bi bi-chat-dots me-2"></i> Messages
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="#" class="nav-link">
                                <i class="bi bi-person me-2"></i> Profile
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="#" class="nav-link">
                                <i class="bi bi-gear me-2"></i> Settings
                            </a>
                        </li>
                        <li class="w-100 mt-auto">
                            <a href="logout.php" class="nav-link text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col py-3">
                <!-- Header -->
                <div class="container-fluid">
                    <div class="row mb-4">
                        <div class="col">
                            <h2>Welcome back, <?php echo htmlspecialchars($username); ?>! 👋</h2>
                            <p class="text-muted">Here's what's happening with your learning journey.</p>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="card stats-card">
                                <div class="card-body">
                                    <h5 class="card-title">Enrolled Courses</h5>
                                    <h2>6</h2>
                                    <p class="mb-0"><i class="bi bi-arrow-up"></i> 2 new this month</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card">
                                <div class="card-body">
                                    <h5 class="card-title">Hours Learned</h5>
                                    <h2>24.5</h2>
                                    <p class="mb-0"><i class="bi bi-arrow-up"></i> 3.5 hrs this week</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card">
                                <div class="card-body">
                                    <h5 class="card-title">Assignments</h5>
                                    <h2>12</h2>
                                    <p class="mb-0">8 completed, 4 pending</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card">
                                <div class="card-body">
                                    <h5 class="card-title">Certificates</h5>
                                    <h2>3</h2>
                                    <p class="mb-0">2 in progress</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Progress -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Continue Learning</h5>
                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-3">
                                                <img src="/api/placeholder/80/80" class="rounded me-3" alt="Course">
                                                <div>
                                                    <h6 class="mb-1">Web Development Fundamentals</h6>
                                                    <p class="text-muted mb-0">75% Complete</p>
                                                </div>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" style="width: 75%"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-3">
                                                <img src="" class="rounded me-3" alt="Course">
                                                <div>
                                                    <h6 class="mb-1">Python Programming</h6>
                                                    <p class="text-muted mb-0">45% Complete</p>
                                                </div>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-primary" style="width: 45%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recommended Courses -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-4">Recommended Courses</h5>
                        </div>
                        <div class="col-md-4">
                            <div class="card course-card">
                                <img src="./assets/images/javascript-course-thumbnail-1024x1024.jpg" class="card-img-top" alt="Course">
                                <div class="card-body">
                                    <span class="badge bg-primary mb-2">Programming</span>
                                    <h5 class="card-title">JavaScript Mastery</h5>
                                    <p class="card-text text-muted">Master modern JavaScript from basics to advanced concepts.</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><i class="bi bi-clock"></i> 12 hours</span>
                                        <a href="#" class="btn btn-primary">Enroll Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card course-card">
                                <img src="./assets/images/Uiux.webp" class="card-img-top" alt="Course">
                                <div class="card-body">
                                    <span class="badge bg-success mb-2">Design</span>
                                    <h5 class="card-title">UI/UX Design Basics</h5>
                                    <p class="card-text text-muted">Learn the fundamentals of user interface design.</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><i class="bi bi-clock"></i> 8 hours</span>
                                        <a href="#" class="btn btn-primary">Enroll Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card course-card">
                                <img src="./assets/images/ds.webp" class="card-img-top" alt="Course">
                                <div class="card-body">
                                    <span class="badge bg-warning mb-2">Data Science</span>
                                    <h5 class="card-title">Data Analysis with Python</h5>
                                    <p class="card-text text-muted">Learn data analysis using Python and its libraries.</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span><i class="bi bi-clock"></i> 15 hours</span>
                                        <a href="#" class="btn btn-primary">Enroll Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

