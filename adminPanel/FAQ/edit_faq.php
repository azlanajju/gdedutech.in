<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../admin_login.php');
    exit();
}

require_once '../config.php';

// Get FAQ details
if (isset($_GET['id'])) {
    $faq_id = intval($_GET['id']);
    $query = "SELECT * FROM FAQs WHERE faq_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $faq_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $faq = mysqli_fetch_assoc($result);

    if (!$faq) {
        $_SESSION['message'] = "FAQ not found.";
        $_SESSION['message_type'] = "danger";
        header("Location: ./");
        exit();
    }
} else {
    header("Location: ./");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);
    
    if (empty($question) || empty($answer)) {
        $_SESSION['message'] = "Both question and answer are required.";
        $_SESSION['message_type'] = "danger";
    } else {
        $update_query = "UPDATE FAQs SET question = ?, answer = ? WHERE faq_id = ?";
        $stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($stmt, 'ssi', $question, $answer, $faq_id);
        
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['message'] = "FAQ updated successfully.";
            $_SESSION['message_type'] = "success";
            header("Location: ./");
            exit();
        } else {
            $_SESSION['message'] = "Error updating FAQ.";
            $_SESSION['message_type'] = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit FAQ - GD Edu Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .form-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }
        .btn-action {
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-action:hover {
            transform: translateY(-2px);
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
                        <span class="fs-5 fw-bolder" style="display: flex;align-items:center;color:black;">
                            <img height="35px" src="../images/edutechLogo.png" alt="">&nbsp; GD Edu Tech
                        </span>
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
                            <a href="../Courses/" class="nav-link">
                                <i class="bi bi-book me-2"></i> Courses
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="../Quiz/" class="nav-link">
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
                <div class="container">
                    <div class="row mb-4">
                        <div class="col">
                            <h2>Edit FAQ</h2>
                            <p class="text-muted">Update the frequently asked question details.</p>
                        </div>
                    </div>

                    <!-- Alert Messages -->
                    <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                            <i class="bi bi-<?php echo $_SESSION['message_type'] == 'success' ? 'check-circle' : 'exclamation-circle'; ?>-fill me-2"></i>
                            <?php 
                            echo $_SESSION['message'];
                            unset($_SESSION['message']);
                            unset($_SESSION['message_type']);
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="form-card">
                                <div class="card-body p-4">
                                    <form method="POST">
                                        <div class="mb-4">
                                            <label for="question" class="form-label">
                                                <i class="bi bi-question-circle me-2"></i>Question
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="question" 
                                                   name="question" 
                                                   value="<?php echo htmlspecialchars($faq['question']); ?>"
                                                   required>
                                        </div>
                                        <div class="mb-4">
                                            <label for="answer" class="form-label">
                                                <i class="bi bi-chat-left-text me-2"></i>Answer
                                            </label>
                                            <textarea class="form-control" 
                                                      id="answer" 
                                                      name="answer" 
                                                      rows="5" 
                                                      required><?php echo htmlspecialchars($faq['answer']); ?></textarea>
                                        </div>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="./" class="btn btn-secondary btn-action">
                                                <i class="bi bi-x-circle me-2"></i>Cancel
                                            </a>
                                            <button type="submit" class="btn btn-primary btn-action">
                                                <i class="bi bi-check-circle me-2"></i>Update FAQ
                                            </button>
                                        </div>
                                    </form>
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