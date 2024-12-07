<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../admin_login.php');
    exit();
}

// Get admin details from session
$admin_name = $_SESSION['username'] ?? 'Admin';
?>
<?php
require_once '../../Configurations/config.php';

// Handle course deletion
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $course_id = intval($_GET['id']);
    $query = "DELETE FROM Courses WHERE course_id = $course_id";
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Course deleted successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error deleting course: " . mysqli_error($conn);
        $_SESSION['message_type'] = "danger";
    }
    header("Location: ./");
    exit();
}

// Pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Fetch total number of courses
$total_courses_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM Courses");
$total_courses_row = mysqli_fetch_assoc($total_courses_query);
$total_courses = $total_courses_row['count'];
$total_pages = ceil($total_courses / $limit);

// Fetch courses with pagination
$query = "SELECT * FROM Courses ORDER BY date_created DESC LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

// Get admin details from session
$admin_name = $_SESSION['first_name'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management - GD Edu Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="./course.css">
    <style>
    
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 min-vh-100">
                    <a href="#" class="d-flex align-items-center pb-3 mb-md-1 mt-md-3 me-md-auto text-white text-decoration-none">
                    <span class="fs-5 fw-bolder" style="display: flex;align-items:center;color:black;"><img height="35px" src="../images/edutechLogo.png" alt="">&nbsp; GD Edu Tech</span>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
                        <li class="w-100">
                            <a href="../" class="nav-link">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="../Categories/" class="nav-link">
                                <i class="bi bi-grid me-2"></i> Categories
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="../Courses/" class="nav-link active">
                                <i class="bi bi-book me-2"></i> Courses
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="../Quiz/" class="nav-link">
                                <i class="bi bi-lightbulb me-2"></i> Quiz
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="../Schedule/index.php" class="nav-link">
                                <i class="bi bi-calendar-event me-2"></i> Schedule
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="../Messages/index.php" class="nav-link">
                                <i class="bi bi-chat-dots me-2"></i> Messages
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="../FAQ/" class="nav-link">
                                <i class="bi bi-question-circle me-2"></i> FAQ
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="../Users/" class="nav-link">
                                <i class="bi bi-people me-2"></i> Users
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="../manage_qr.php" class="nav-link">
                                <i class="bi bi-qr-code me-2"></i> Payment QR
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="../pending_payments.php" class="nav-link">
                                <i class="bi bi-credit-card me-2"></i> Pending Payments
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
                <div class="container-fluid">
                    <!-- Header -->
                    <div class="row mb-4">
                        <div class="col">
                            <h2>Course Management</h2>
                            <p class="text-muted">Manage and organize courses</p>
                        </div>
                        <div class="col-auto">
                            <a href="./add_course.php" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Add New Course
                            </a>
                        </div>
                    </div>

                    <!-- Alert Messages -->
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                            <?php
                            echo htmlspecialchars($_SESSION['message']);
                            unset($_SESSION['message']);
                            unset($_SESSION['message_type']);
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="courses-container">
        <div class="container">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php while ($course = mysqli_fetch_assoc($result)): ?>
                    <div class="col course-animation">
                        <div class="card course-card <?php echo $course['status'] == 'draft' ? 'border-warning' : ''; ?>">
                            <a href="./viewCourseDetails.php?course_id=<?php echo $course['course_id'] ?>" class="course-link">
                                <div class="thumbnail-container">
                                    <img 
                                        src="<?php echo $course['thumbnail'] ? "../../uploads/course_uploads/thumbnails/" . htmlspecialchars($course['thumbnail']) : '../images/default-course.png'; ?>"
                                        class="course-thumbnail"
                                        alt="<?php echo htmlspecialchars($course['title']); ?>"
                                        loading="lazy"
                                    >
                                    <span class="badge badge-level">
                                        <?php echo htmlspecialchars($course['level']); ?>
                                    </span>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo htmlspecialchars($course['title']); ?>
                                    </h5>

                                    <p class="course-description">
                                        <?php
                                        $description = $course['description'] ?? 'No description available.';
                                        echo htmlspecialchars(substr($description, 0, 150) . (strlen($description) > 150 ? '...' : ''));
                                        ?>
                                    </p>

                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <span class="price-tag <?php echo $course['price'] == 0 ? 'free' : ''; ?>">
                                            <?php echo $course['price'] > 0 ? '₹' . number_format($course['price'], 2) : 'Free'; ?>
                                        </span>
                                        
                                        <div class="btn-group">
                                            <?php if ($course['status'] == 'draft'): ?>
                                                <a href="publish_course.php?id=<?php echo $course['course_id']; ?>"
                                                   class="btn btn-sm btn-success"
                                                   onclick="return confirm('Are you sure you want to publish this course?');"
                                                   title="Publish Course">
                                                    <i class="bi bi-send me-1"></i>Publish
                                                </a>
                                            <?php endif; ?>

                                            <a href="./edit_course.php?id=<?php echo $course['course_id']; ?>"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Edit Course">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            
                                            <a href="?delete&id=<?php echo $course['course_id']; ?>"
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Are you sure you want to delete this course? This will also remove the course thumbnail.');"
                                               title="Delete Course">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar me-1"></i>
                                        <?php echo date('M d, Y', strtotime($course['date_created'])); ?>
                                    </small>
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle me-1"></i>
                                        <?php echo $course['status'] == 'draft' ? 'Draft' : 'Published'; ?>
                                    </small>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
                    <!-- Pagination -->
                    <nav aria-label="Page navigation" class="mt-3">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="index.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>