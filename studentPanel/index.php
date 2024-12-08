<?php
session_start();

require_once  '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Database connection
require_once '../Configurations/config.php';
require_once '../Configurations/functions.php';

$jwtSecretKey = "your_secret_key_here";

// Check if user is logged in and is a student via session
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    // If no session, check for JWT token
    if (!isset($_COOKIE['auth_token'])) {
        header("Location: login.php");
        exit();
    }

    try {
        $jwt = $_COOKIE['auth_token'];
        $decoded = JWT::decode($jwt, new Key($jwtSecretKey, 'HS256'));

        // Recreate session from JWT token
        $_SESSION['user_id'] = $decoded->user_id;
        $_SESSION['username'] = $decoded->username;
        $_SESSION['role'] = $decoded->role;
    } catch (Exception $e) {
        // Clear any invalid cookie
        setcookie('auth_token', '', time() - 3600, '/');
        session_destroy();
        header("Location: login.php");
        exit();
    }
}

// Get student details from session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Additional security check
if ($role !== 'student') {
    header("Location: login.php");
    exit();
}

// Optional: Periodic session regeneration for security
if (!isset($_SESSION['last_regeneration']) || (time() - $_SESSION['last_regeneration']) > 300) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}


// Fetch user statistics
$stats_query = "
    SELECT 
        (SELECT COUNT(*) FROM Enrollments WHERE student_id = ?) AS enrolled_courses,
        (SELECT SUM(progress) FROM Enrollments WHERE student_id = ?) AS total_learning_hours,
        (SELECT COUNT(*) FROM Enrollments WHERE student_id = ? AND completion_status = 'pending') AS pending_assignments,
        (SELECT COUNT(*) FROM Certificates WHERE student_id = ?) AS certificates
";
$stats_stmt = $conn->prepare($stats_query);
$stats_stmt->bind_param("iiii", $user_id, $user_id, $user_id, $user_id);
$stats_stmt->execute();
$stats_result = $stats_stmt->get_result()->fetch_assoc();

// Fetch ongoing courses
$ongoing_courses_query = "
    SELECT 
        c.course_id,
        c.title, 
        c.thumbnail,
        (SELECT COUNT(*) FROM Videos v 
         JOIN Lessons l ON v.lesson_id = l.lesson_id 
         WHERE l.course_id = c.course_id) as total_videos,
        (SELECT COUNT(DISTINCT up.video_id) 
         FROM UserProgress up 
         JOIN Lessons l ON up.lesson_id = l.lesson_id 
         WHERE l.course_id = c.course_id 
         AND up.user_id = ? 
         AND up.completed = 1) as completed_videos
    FROM Courses c
    JOIN Enrollments e ON c.course_id = e.course_id
    WHERE e.student_id = ? 
    LIMIT 2
";
$ongoing_courses_stmt = $conn->prepare($ongoing_courses_query);
$ongoing_courses_stmt->bind_param("ii", $user_id, $user_id);
$ongoing_courses_stmt->execute();
$ongoing_courses_result = $ongoing_courses_stmt->get_result();

// Fetch recommended courses
$recommended_courses_query = "
    SELECT 
        c.course_id,
        c.title, 
        c.description,
        c.thumbnail,
        c.course_type,
        (SELECT name FROM Categories cat WHERE cat.category_id = c.category_id) AS category
    FROM Courses c
    WHERE c.status = 'published' 
    AND c.course_id NOT IN (
        SELECT course_id FROM Enrollments WHERE student_id = ?
    )
 
";
$recommended_courses_stmt = $conn->prepare($recommended_courses_query);
$recommended_courses_stmt->bind_param("i", $user_id);
$recommended_courses_stmt->execute();
$recommended_courses_result = $recommended_courses_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GD Edu Tech - Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/student_dashboard.css">
    <link rel="stylesheet" href="../css/customBoorstrap.css">
    <style>
        .course-card .card-text {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 4.5em;
            /* Approximately 3 lines of text */
        }

        .course-card {
            height: 100%;
            transition: transform 0.2s;
        }

        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .course-card .card-img-top {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- side bar  -->
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 min-vh-100">
                    <a href="#" class="d-flex align-items-center pb-3 mb-md-1 mt-md-3 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 fw-bolder" style="display: flex;align-items:center;"><img height="35px" src="../Images/Logos/edutechLogo.png" alt="">&nbsp; GD Edu Tech</span>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
                        <li class="w-100">
                            <a href="./" class="nav-link active">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="./MyCourses/" class="nav-link text-white">
                                <i class="bi bi-book me-2"></i> My Courses
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="./Categories/" class="nav-link text-white">
                                <i class="bi bi-grid me-2"></i> Categories
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="./Schedule" class="nav-link text-white">
                                <i class="bi bi-calendar-event me-2"></i> Schedule
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="./Messages" class="nav-link text-white">
                                <i class="bi bi-chat-dots me-2"></i> Messages
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="./Profile/" class="nav-link text-white">
                                <i class="bi bi-person me-2"></i> Profile
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="./shop.php" class="nav-link text-white">
                                <i class="bi bi-shop me-2"></i> Shop
                            </a>
                        </li>
                        <li class="w-100 mt-auto">
                            <a href="../logout.php" class="nav-link text-danger">
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
                                    <h2><?php echo $stats_result['enrolled_courses']; ?></h2>
                                    <p class="mb-0"><i class="bi bi-arrow-up"></i> New courses this month</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card">
                                <div class="card-body">
                                    <h5 class="card-title">Hours Learned</h5>
                                    <h2><?php echo number_format($stats_result['total_learning_hours'] / 100, 1); ?></h2>
                                    <p class="mb-0"><i class="bi bi-arrow-up"></i> Hours this week</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card">
                                <div class="card-body">
                                    <h5 class="card-title">Assignments</h5>
                                    <h2><?php echo $stats_result['pending_assignments']; ?></h2>
                                    <p class="mb-0">Pending</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card">
                                <div class="card-body">
                                    <h5 class="card-title">Certificates</h5>
                                    <h2><?php echo $stats_result['certificates']; ?></h2>
                                    <p class="mb-0">Earned</p>
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
                                        <?php while ($course = $ongoing_courses_result->fetch_assoc()): ?>
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center mb-3">
                                                    <img src="../uploads/course_uploads/thumbnails/<?php echo htmlspecialchars($course['thumbnail']); ?>"
                                                        class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;"
                                                        alt="Course">
                                                    <div>
                                                        <h6 class="mb-1"><?php echo htmlspecialchars($course['title']); ?></h6>
                                                        <p class="text-muted mb-0">
                                                            <?php echo $course['completed_videos']; ?>/<?php echo $course['total_videos']; ?> videos completed
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="progress mb-3">
                                                    <div class="progress-bar bg-success"
                                                        style="width: <?php
                                                                        $progress = $course['total_videos'] > 0 ?
                                                                            ($course['completed_videos'] / $course['total_videos']) * 100 : 0;
                                                                        echo number_format($progress, 0);
                                                                        ?>%">
                                                        <?php echo number_format($progress, 0); ?>%
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <a href="./MyCourses/course_content.php?id=<?php echo $course['course_id']; ?>"
                                                        class="btn btn-primary btn-sm">Continue Learning</a>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
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
                        <?php while ($course = $recommended_courses_result->fetch_assoc()): ?>
                            <div class="col-md-4">
                                <div class="card course-card">
                                    <img src="../uploads/course_uploads/thumbnails/<?php echo htmlspecialchars($course['thumbnail'] ?: './Courses/thumbnails'); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($course['title']); ?>">
                                    <div class="card-body">
                                        <span class="badge bg-primary mb-2"><?php echo htmlspecialchars($course['category'] ?: 'General'); ?></span>
                                        <h5 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h5>
                                        <p class="card-text text-muted"><?php echo htmlspecialchars($course['description']); ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span><i class="bi bi-clock"></i> Course Type: <?php echo htmlspecialchars($course['course_type'] ?: 'Undefined'); ?></span>
                                            <a href="./MyCourses/course.php?id=<?php echo $course['course_id'] . "&" . rand(10000000, 99999999) . chr(rand(65, 90)); ?>" class="btn btn-primary">Enroll Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>