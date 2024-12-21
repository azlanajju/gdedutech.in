<?php
session_start();
require_once '../../Configurations/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Fetch all question papers from the database
$papers_query = "SELECT * FROM question_papers";
$papers_result = mysqli_query($conn, $papers_query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resources - GD Edu Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/student_dashboard.css">
    <style>
        /* Add any custom styles here */
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 min-vh-100">
                    <a href="#" class="d-flex align-items-center pb-3 mb-md-1 mt-md-3 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 fw-bolder" style="display: flex;align-items:center;">
                            <img height="35px" src="../../Images/Logos/edutechLogo.png" alt="">&nbsp; GD Edu Tech
                        </span>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
                        <li class="w-100">
                            <a href="../" class="nav-link text-white">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="../MyCourses/" class="nav-link text-white">
                                <i class="bi bi-book me-2"></i> My Courses
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="../Categories/" class="nav-link text-white">
                                <i class="bi bi-grid me-2"></i> Categories
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="../Schedule/" class="nav-link text-white">
                                <i class="bi bi-calendar-event me-2"></i> Schedule
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="../Messages/" class="nav-link text-white">
                                <i class="bi bi-chat-dots me-2"></i> Messages
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="./" class="nav-link text-white active">
                                <i class="bi bi-file-earmark-text me-2"></i> Resources
                            </a>
                        </li>
                        <li class="w-100 mt-auto">
                            <a href="../../logout.php" class="nav-link text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col py-3">
                <h3 class="mb-4">Available Question Papers</h3>
                <div class="row g-4">
                    <?php while ($paper = mysqli_fetch_assoc($papers_result)): ?>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($paper['title']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($paper['description']); ?></p>
                                    <?php
                                    // Check if the user has access to the paper
                                    $user_id = $_SESSION['user_id'];
                                    $access_query = "SELECT * FROM access_requests WHERE paper_id = {$paper['id']} AND user_id = $user_id AND status = 'granted'";
                                    $access_result = mysqli_query($conn, $access_query);
                                    $has_access = mysqli_num_rows($access_result) > 0;

                                    if ($paper['status'] === 'open' || $has_access): ?>
                                        <a href="<?php echo '../../uploads/question_papers/' . htmlspecialchars($paper['pdf']); ?>" class="btn btn-primary" target="_blank">View Paper</a>
                                    <?php else: ?>
                                        <a href="https://api.whatsapp.com/send?phone=8867575821&text=Request%20to%20access%20the%20paper%20entitled%20<?php echo urlencode($paper['title']); ?>"
                                            class="btn btn-info"
                                            target="_blank"
                                            onclick="event.preventDefault(); requestAccess(<?php echo $paper['id']; ?>); window.open(this.href, '_blank');">
                                            Access Paper
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function requestAccess(paperId) {
            // Create form data
            var formData = new FormData();
            formData.append('paper_id', paperId);

            // Send AJAX request
            fetch('request_access.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    console.log('Access request sent successfully');
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
</body>

</html>