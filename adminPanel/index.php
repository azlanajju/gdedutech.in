<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}

// Get admin details from session
$admin_name = $_SESSION['username'] ?? 'Admin';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - GD Edu Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 min-vh-100">
                    <a href="#" class="d-flex align-items-center pb-3 mb-md-1 mt-md-3 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 fw-bolder" style="display: flex;align-items:center;color:black;"><img height="35px" src="./images/edutechLogo.png" alt="">&nbsp; GD Edu Tech</span>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
                        <li class="w-100">
                            <a href="./" class="nav-link active">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="./Categories/" class="nav-link">
                                <i class="bi bi-grid me-2"></i> Categories
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="./Courses/" class="nav-link">
                                <i class="bi bi-book me-2 "></i> Courses
                            </a>
                        </li>

                        </li>
                        <li class="w-100">
                            <a href="./Quiz/" class="nav-link">
                                <i class="bi bi-lightbulb me-2"></i> Quiz
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="./FAQ/" class="nav-link">
                                <i class="bi bi-question-circle me-2"></i> FAQ
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="./Users/" class="nav-link">
                                <i class="bi bi-people me-2"></i> Users
                            </a>
                        </li>
                        <li class="w-100 mt-auto">
                            <a href="./logout.php" class="nav-link text-danger">
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
                            <h2>Welcome, <?php echo htmlspecialchars($admin_name); ?>!</h2>
                            <p class="text-muted ">Here's what's happening with your platform today.</p>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <?php
                            // Include the database configuration file
                            require_once 'config.php';

                            // Query to fetch the total number of users and new users for the current month
                            $totalUsersQuery = "
    SELECT 
        COUNT(*) AS total_users,
        SUM(CASE WHEN MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) THEN 1 ELSE 0 END) AS new_users_this_month
    FROM Users
";

                            $totalUsersResult = mysqli_query($conn, $totalUsersQuery);

                            // Check for query errors
                            if (!$totalUsersResult) {
                                die("Database query failed: " . mysqli_error($conn));
                            }

                            $data = mysqli_fetch_assoc($totalUsersResult);
                            $totalUsers = $data['total_users'];
                            $newUsersThisMonth = $data['new_users_this_month'];

                            // Calculate percentage increase dynamically
                            $previousMonthUsersQuery = "
    SELECT COUNT(*) AS previous_month_users
    FROM Users
    WHERE MONTH(created_at) = MONTH(CURDATE()) - 1 AND YEAR(created_at) = YEAR(CURDATE())
";

                            $previousMonthResult = mysqli_query($conn, $previousMonthUsersQuery);
                            if (!$previousMonthResult) {
                                die("Database query failed: " . mysqli_error($conn));
                            }

                            $previousMonthUsers = mysqli_fetch_assoc($previousMonthResult)['previous_month_users'];
                            $percentageIncrease = $previousMonthUsers > 0
                                ? round(($newUsersThisMonth / $previousMonthUsers) * 100)
                                : 0;

                            ?>

                            <div class="card stats-card">
                                <div class="card-body">
                                    <h6 class="card-title">Total Users</h6>
                                    <h2><?php echo number_format($totalUsers); ?></h2>
                                    <p class="mb-0">
                                        <i class="bi bi-arrow-up"></i> <?php echo $percentageIncrease; ?>% this month
                                    </p>
                                </div>
                            </div>

                            <?php
                            // Free result sets and close the connection
                            mysqli_free_result($totalUsersResult);
                            mysqli_free_result($previousMonthResult);
                            // mysqli_close($conn);
                            ?>


                        </div>
                        <div class="col-md-3">
                            <?php
                            // Include the database configuration file
                            require_once 'config.php';

                            // Query to fetch total active courses and new courses added in the last 7 days
                            $activeCoursesQuery = "
    SELECT 
        COUNT(*) AS active_courses,
        SUM(CASE WHEN DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) AS new_courses
    FROM Courses
    WHERE status = 'published'
";

                            $activeCoursesResult = mysqli_query($conn, $activeCoursesQuery);

                            // Check for query errors
                            if (!$activeCoursesResult) {
                                die("Database query failed: " . mysqli_error($conn));
                            }

                            $data = mysqli_fetch_assoc($activeCoursesResult);
                            $activeCourses = $data['active_courses'];
                            $newCoursesThisWeek = $data['new_courses'];
                            ?>

                            <div class="card stats-card">
                                <div class="card-body">
                                    <h6 class="card-title">Active Courses</h6>
                                    <h2><?php echo number_format($activeCourses); ?></h2>
                                    <p class="mb-0">
                                        <i class="bi bi-arrow-up"></i> <?php echo $newCoursesThisWeek; ?> new this week
                                    </p>
                                </div>
                            </div>

                            <?php
                            // Free result set and close the connection
                            mysqli_free_result($activeCoursesResult);
                            // mysqli_close($conn);
                            ?>

                        </div>
                        <div class="col-md-3">
                            <div class="card stats-card">
                                <div class="card-body">
                                    <h6 class="card-title">Total Revenue</h6>
                                    <h2>₹
                                        12,845</h2>
                                    <p class="mb-0"><i class="bi bi-arrow-up"></i> 8% this month</p>
                                </div>
                            </div>
                        </div>
                        <?php
                        // Include the config file for database connection
                        include 'config.php';

                        // Initialize variables for course completion
                        $courseCompletion = 0;

                        try {
                            // 1. Query to get the count of active courses
                            $activeResult = $conn->query("SELECT COUNT(*) AS active_courses FROM Enrollments WHERE access_status = 'active'");
                            $activeCourses = 0;

                            if ($activeResult && $activeRow = $activeResult->fetch_assoc()) {
                                $activeCourses = (int) $activeRow['active_courses'];
                            }

                            // 2. Query to get the count of completed courses
                            $completedResult = $conn->query("SELECT COUNT(*) AS completed_courses FROM Enrollments WHERE completion_status = 'completed'");
                            $completedCourses = 0;

                            if ($completedResult && $completedRow = $completedResult->fetch_assoc()) {
                                $completedCourses = (int) $completedRow['completed_courses'];
                            }

                            // 3. Calculate the completion percentage
                            if ($activeCourses > 0) {
                                $courseCompletion = ($completedCourses / $activeCourses) * 100;
                                $courseCompletion = round($courseCompletion, 2); // Round to 2 decimal places
                            }
                        } catch (Exception $e) {
                            echo "Error fetching data: " . $e->getMessage();
                        }
                        ?>

                        <!-- HTML for the stats card -->
                        <div class="col-md-3">
                            <div class="card stats-card">
                                <div class="card-body">
                                    <h6 class="card-title">Course Completion</h6>
                                    <h2><?php echo $courseCompletion; ?>%</h2>
                                    <p class="mb-0">
                                        <i class="bi bi-arrow-right"></i> <?php echo abs($completedCourses); ?> / <?php echo abs($activeCourses); ?>
                                    </p>
                                </div>
                            </div>
                        </div>


                    </div>

                    <!-- Recent Activities & Quick Actions -->
                    <div class="row mb-4">
                        <!-- Recent Activities -->
                        <div class="col-md-8">
                            <div class="card table-card">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0 color-primary">Recent Activities</h5>
                                    <a href="" class="btn btn-sm btn-outline-primary">View All</a>

                                </div>
                                <?php
                                // config.php (Database Connection)
                                require_once 'config.php';

                                // Retrieve latest 8 activities
                                $query = "SELECT 
            user_name, 
            activity_type, 
            activity_description, 
            activity_status, 
            activity_timestamp 
          FROM recent_activities 
          ORDER BY activity_timestamp DESC 
          LIMIT 8";

                                $result = $conn->query($query);
                                ?>

                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Activity</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($result->num_rows === 0): ?>
                                                <tr>
                                                    <td colspan="4" class="text-center">No recent activities found.</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php while ($activity = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?php echo $activity['user_name']; ?></td>
                                                        <td><?php echo $activity['activity_description']; ?></td>
                                                        <td><?php echo $activity['activity_timestamp']; ?></td>
                                                        <td><?php echo $activity['activity_status']; ?></td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <?php
                                // Close the connection
                                // $conn->close();
                                ?>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="./Courses/add_course.php" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-2"></i>Add New Course
                                        </a>
                                        <button class="btn btn-outline-primary">
                                            <i class="bi bi-person-plus me-2"></i>Create User
                                        </button>
                                        <button class="btn btn-outline-primary">
                                            <i class="bi bi-file-text me-2"></i>Generate Report
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- System Status -->
                            <div class="card mt-4">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mb-0">System Status</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Storage</span>
                                            <span>75%</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: 75%"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span>Bandwidth</span>
                                            <span>50%</span>
                                        </div>
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 50%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Users & Course Status -->
                    <div class="row">
                        <!-- Recent Users -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Recent Users</h5>
                                    <a href="./Users/" class="btn btn-sm btn-outline-primary">View All</a>
                                </div>
                                <?php
                                // Include the database configuration file
                                require_once 'config.php';

                                // Fetch the 10 most recent users
                                $query = "SELECT username, role FROM Users ORDER BY date_joined DESC LIMIT 10";
                                $result = mysqli_query($conn, $query);

                                // Check for query errors
                                if (!$result) {
                                    die("Database query failed: " . mysqli_error($conn));
                                }
                                ?>

                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Role</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Loop through the users and display them
                                            while ($row = mysqli_fetch_assoc($result)) { ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                                    <td><?php echo ucfirst(htmlspecialchars($row['role'])); ?></td>
                                                    <td>
                                                        <i class="bi bi-pencil action-icon"></i>
                                                        <i class="bi bi-trash action-icon text-danger"></i>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <?php
                                // Free result set and close the connection
                                mysqli_free_result($result);
                                // mysqli_close($conn);
                                ?>

                            </div>
                        </div>

                        <!-- Popular Courses -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Popular Courses</h5>
                                    <a href="./Courses/" class="btn btn-sm btn-outline-primary">View All</a>
                                </div>
                                <?php
                                // Include the database configuration file
                                require_once 'config.php';

                                // Fetch only popular courses
                                $query = "SELECT title FROM Courses WHERE isPopular = 'yes'";
                                $result = mysqli_query($conn, $query);

                                // Check for query errors
                                if (!$result) {
                                    die("Database query failed: " . mysqli_error($conn));
                                }
                                ?>

                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Course</th>
                                                <th>Students</th>
                                                <th>Rating</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Loop through the courses and display them
                                            while ($row = mysqli_fetch_assoc($result)) { ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                                    <td> Students</td>
                                                    <td>
                                                        <i class="bi bi-star-fill text-warning"></i>  Rating
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>

                                <?php
                                // Free result set and close the connection
                                mysqli_free_result($result);
                                mysqli_close($conn);
                                ?>

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