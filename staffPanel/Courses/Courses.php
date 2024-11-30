<?php
session_start();
require_once '../config.php';

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
    header("Location: courses.php");
    exit();
}

// Get staff ID from session
$staff_id = $_SESSION['user_id'];

// Pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Fetch total number of courses for this staff member
$total_courses_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM Courses WHERE uploadedBy_id = $staff_id");
$total_courses_row = mysqli_fetch_assoc($total_courses_query);
$total_courses = $total_courses_row['count'];
$total_pages = ceil($total_courses / $limit);

// Fetch courses with pagination for this staff member only
$query = "SELECT * FROM Courses 
          WHERE uploadedBy_id = $staff_id 
          ORDER BY date_created DESC 
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $query);

// Get staff details from session
$staff_name = $_SESSION['first_name'] ?? 'Staff';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management - GD Edu Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../staffPanel/css/style.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 min-vh-100">
                    <a href="#" class="d-flex align-items-center pb-3 mb-md-1 mt-md-3 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 fw-bolder" style="display: flex;align-items:center;color:black;">
                            <img height="35px" src="../../adminPanel/images/edutechLogo.png" alt="">&nbsp; GD Edu Tech
                        </span>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
                        <li class="w-100">
                            <a href="../" class="nav-link">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="./courses.php" class="nav-link active">
                                <i class="bi bi-book me-2"></i> Courses
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="../student_progress.php" class="nav-link">
                                <i class="bi bi-graph-up me-2"></i> Student Progress
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
                            <p class="text-muted">Manage and organize your courses</p>
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

                    <!-- Courses Grid -->
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <?php while ($course = mysqli_fetch_assoc($result)): ?>
                            <div class="col">
                                <div class="card course-card h-100 <?php echo $course['status'] == 'draft' ? 'border-warning' : ''; ?>">
                                    <a href="./viewCourseDetails.php?course_id=<?php echo $course['course_id'] ?>" class="text-decoration-none ">
                                        <!-- Thumbnail -->
                                        <div class="position-relative">
                                            <img src="<?php echo $course['thumbnail'] ? "./thumbnails/" . htmlspecialchars($course['thumbnail']) : '../images/default-course.png'; ?>"
                                                class="card-img-top course-thumbnail"
                                                alt="<?php echo htmlspecialchars($course['title']); ?>">

                                            <!-- Level Badge -->
                                            <span class="badge bg-primary badge-level">
                                                <?php echo htmlspecialchars($course['level']); ?>
                                            </span>
                                        </div>

                                        <!-- Card Body -->
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">
                                                <?php echo htmlspecialchars($course['title']); ?>
                                            </h5>

                                            <!-- Description -->
                                            <p class="course-description">
                                                <?php
                                                $description = $course['description'] ?? 'No description available.';
                                                echo htmlspecialchars(substr($description, 0, 150) . (strlen($description) > 150 ? '...' : ''));
                                                ?>
                                            </p>

                                            <!-- Course Details -->
                                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                                <span class="price-tag">
                                                    <?php echo $course['price'] > 0 ? '₹' . number_format($course['price'], 2) : 'Free'; ?>
                                                </span>
                                                <div class="btn-group" role="group">
                                                    <!-- Publish Button -->
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
                                                    <a href="?delete&id=<?php echo $course['course_id']; ?>" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Are you sure you want to delete this course? This will also remove the course thumbnail.');"
                                                        title="Delete Course"><i class="bi bi-trash"></i></a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Footer -->
                                        <div class="card-footer course-footer d-flex justify-content-between align-items-center">
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

                    <!-- Pagination -->
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="courses.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
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