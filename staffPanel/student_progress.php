<?php
session_start();

// Check if user is logged in and is staff
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header('Location: ../staffPanel/staff_login.php');
    exit();
}

$staff_id = $_SESSION['user_id'];
$staff_name = $_SESSION['username'] ?? 'Staff Member';

require_once '../adminPanel/config.php';

// Fetch student progress for assigned courses
$query = "SELECT 
            u.username, u.first_name, u.last_name,
            c.title as course_title,
            e.progress,
            e.enrollment_id,
            e.completion_status
          FROM Enrollments e
          JOIN Users u ON e.student_id = u.user_id
          JOIN Courses c ON e.course_id = c.course_id
          JOIN StaffAssignments sa ON c.course_id = sa.course_id
          WHERE sa.staff_id = ?
          ORDER BY c.title, u.username";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$progress_data = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Progress - Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../adminPanel/css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 min-vh-100">
                    <a href="#" class="d-flex align-items-center pb-3 mb-md-1 mt-md-3 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 fw-bolder" style="display: flex;align-items:center;color:black;">
                            <img height="35px" src="../adminPanel/images/edutechLogo.png" alt="">&nbsp; GD Edu Tech
                        </span>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
                        <li class="w-100">
                            <a href="./" class="nav-link">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="./assigned_courses.php" class="nav-link">
                                <i class="bi bi-book me-2"></i> Assigned Courses
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="./manage_lessons.php" class="nav-link">
                                <i class="bi bi-journal-text me-2"></i> Manage Lessons
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="./quizzes.php" class="nav-link">
                                <i class="bi bi-question-circle me-2"></i> Quizzes
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="./student_progress.php" class="nav-link active">
                                <i class="bi bi-graph-up me-2"></i> Student Progress
                            </a>
                        </li>
                        <li class="w-100 mt-auto">
                            <a href="../staffPanel/logout.php" class="nav-link text-danger">
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
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2>Student Progress</h2>
                                    <p class="text-muted">Track and monitor student progress in your courses</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-outline-primary" onclick="window.print()">
                                        <i class="bi bi-printer me-2"></i>Print Report
                                    </button>
                                    <button class="btn btn-outline-success" onclick="exportToExcel()">
                                        <i class="bi bi-file-earmark-excel me-2"></i>Export to Excel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Table -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Course</th>
                                            <th>Progress</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($progress_data->num_rows > 0): ?>
                                            <?php while ($progress = $progress_data->fetch_assoc()): ?>
                                            <tr>
                                                <td>
                                                    <?php echo htmlspecialchars($progress['first_name'] . ' ' . $progress['last_name']); ?>
                                                    <div class="small text-muted"><?php echo htmlspecialchars($progress['username']); ?></div>
                                                </td>
                                                <td><?php echo htmlspecialchars($progress['course_title']); ?></td>
                                                <td style="width: 200px;">
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar <?php echo $progress['progress'] == 100 ? 'bg-success' : 'bg-primary'; ?>" 
                                                             role="progressbar" 
                                                             style="width: <?php echo $progress['progress']; ?>%"
                                                             aria-valuenow="<?php echo $progress['progress']; ?>" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                            <?php echo $progress['progress']; ?>%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo $progress['completion_status'] === 'completed' ? 'success' : 'warning'; ?>">
                                                        <?php echo ucfirst($progress['completion_status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="view_student_progress.php?enrollment_id=<?php echo $progress['enrollment_id']; ?>" 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye me-1"></i>View Details
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="bi bi-info-circle me-2"></i>
                                                        No student progress data available.
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function exportToExcel() {
            // Add Excel export functionality here
            alert('Excel export functionality will be implemented here');
        }
    </script>
</body>
</html> 