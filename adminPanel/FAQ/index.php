<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../admin_login.php');
    exit();
}

// Get admin details from session
$admin_name = $_SESSION['username'] ?? 'Admin';

require_once '../config.php';

// Fetch all FAQs with course titles
$query = "SELECT f.*, c.title as course_title 
          FROM FAQs f 
          LEFT JOIN Courses c ON f.course_id = c.course_id 
          ORDER BY f.created_at DESC";
$result = mysqli_query($conn, $query);

// Handle delete if requested
if(isset($_GET['delete']) && !empty($_GET['delete'])) {
    $faq_id = intval($_GET['delete']);
    if(mysqli_query($conn, "DELETE FROM FAQs WHERE faq_id = $faq_id")) {
        $_SESSION['message'] = "FAQ deleted successfully";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error deleting FAQ";
        $_SESSION['message_type'] = "danger";
    }
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ Management - GD Edu Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .btn-group .btn {
            border-radius: 6px !important;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .btn-group .btn:hover {
            transform: translateY(-1px);
        }
        
        .btn-lg {
            padding: 0.8rem 1.5rem;
            font-weight: 500;
            border-radius: 8px !important;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        /* Custom hover effects */
        .btn-outline-primary:hover {
            background-color: #0d6efd15;
            color: #0d6efd;
            border-color: #0d6efd;
        }
        
        .btn-outline-danger:hover {
            background-color: #dc354515;
            color: #dc3545;
            border-color: #dc3545;
        }
        
        /* Badge styles */
        .badge {
            padding: 0.5em 1em;
            font-weight: 500;
            letter-spacing: 0.3px;
        }
        
        .badge.bg-success {
            background-color: #198754 !important;
        }
        
        .badge.bg-info {
            background-color: #0dcaf0 !important;
        }
        
        /* Add shadow to the card */
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 10px;
        }
        
        /* Table styles */
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
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
                        <span class="fs-5 fw-bolder" style="display: flex;align-items:center;color:black;"><img height="35px" src="./images/edutechLogo.png" alt="">&nbsp; GD Edu Tech</span>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
                        <li class="w-100">
                            <a href="../" class="nav-link ">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="../Categories/" class="nav-link">
                                <i class="bi bi-grid me-2"></i> Categories
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="../Courses/" class="nav-link">
                                <i class="bi bi-book me-2 "></i> Courses
                            </a>
                        </li>
                        
                        </li>
                        <li class="w-100">
                            <a href="../Quiz" class="nav-link ">
                                <i class="bi bi-lightbulb me-2"></i> Quiz
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="./" class="nav-link active">
                                <i class="bi bi-question-circle me-2"></i> FAQ
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="../Users/" class="nav-link">
                                <i class="bi bi-people me-2"></i> Users
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
                            <h2>FAQ Management</h2>
                            <p class="text-muted">Create, manage, and organize FAQs</p>
                        </div>
                        <div class="col-auto">
                            <a href="add_faq.php" class="btn btn-primary btn-lg shadow-sm">
                                <i class="bi bi-plus-circle-fill me-2"></i>Add New FAQ
                            </a>
                        </div>
                    </div>

                    <!-- Messages -->
                    <?php if(isset($_SESSION['message'])): ?>
                        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                            <?php 
                                echo $_SESSION['message']; 
                                unset($_SESSION['message']);
                                unset($_SESSION['message_type']);
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- FAQ List -->
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Question</th>
                                            <th>Answer</th>
                                            <th>Type</th>
                                            <th>Course</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(mysqli_num_rows($result) > 0): ?>
                                            <?php while($faq = mysqli_fetch_assoc($result)): ?>
                                                <tr>
                                                    <td title="<?php echo htmlspecialchars($faq['question'])  ?>"><?php echo htmlspecialchars(substr($faq['question'], 0, 10)) . '...'; ?></td>
                                                    <td><?php echo htmlspecialchars(substr($faq['answer'], 0, 10)) . '...'; ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php echo $faq['type'] == 'public' ? 'success' : 'info'; ?>">
                                                            <?php echo ucfirst($faq['type']); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php echo ($faq['type'] == 'course') ? htmlspecialchars($faq['course_title'] ?? 'N/A') : '-'; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($faq['created_at']); ?></td>
                                                    <td class="text-end">
                                                        <div class="btn-group" role="group">
                                                            <a href="edit_faq.php?id=<?php echo $faq['faq_id']; ?>" 
                                                               class="btn btn-outline-primary btn-sm me-2" 
                                                               data-bs-toggle="tooltip" 
                                                               title="Edit FAQ">
                                                                <i class="bi bi-pencil-fill me-1"></i>Edit
                                                            </a>
                                                            <button onclick="confirmDelete(<?php echo $faq['faq_id']; ?>)" 
                                                                    class="btn btn-outline-danger btn-sm"
                                                                    data-bs-toggle="tooltip" 
                                                                    title="Delete FAQ">
                                                                <i class="bi bi-trash-fill me-1"></i>Delete
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6">No FAQs found</td>
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
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // Enhanced delete confirmation
        function confirmDelete(faqId) {
            if(confirm('Are you sure you want to delete this FAQ? This action cannot be undone.')) {
                window.location.href = `index.php?delete=${faqId}`;
            }
        }
    </script>
</body>
</html>