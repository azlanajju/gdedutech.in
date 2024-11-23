<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}

// Get admin details from session
$admin_name = $_SESSION['username'] ?? 'Admin';

require_once '../config.php';

// Check if FAQ ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = "No FAQ specified for editing.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php');
    exit();
}

$faq_id = intval($_GET['id']);

// Fetch FAQ details
$faq_query = "SELECT * FROM FAQs WHERE faq_id = $faq_id";
$faq_result = mysqli_query($conn, $faq_query);

if (!$faq_result || mysqli_num_rows($faq_result) == 0) {
    $_SESSION['message'] = "FAQ not found.";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php');
    exit();
}

$faq = mysqli_fetch_assoc($faq_result);

// Fetch courses for dropdown
$courses_query = mysqli_query($conn, "SELECT course_id, title FROM Courses ORDER BY title");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $question = mysqli_real_escape_string($conn, trim($_POST['question']));
    $answer = mysqli_real_escape_string($conn, trim($_POST['answer']));
    $type = mysqli_real_escape_string($conn, trim($_POST['type']));
    $course_id = ($type == 'course') ? intval($_POST['course_id']) : null;

    // Validation
    $errors = [];
    if (empty($question)) $errors[] = "Question is required.";
    if (empty($answer)) $errors[] = "Answer is required.";
    if ($type == 'course' && empty($course_id)) $errors[] = "Please select a valid course.";

    // If no errors, update FAQ
    if (empty($errors)) {
        $update_query = "UPDATE FAQs SET 
                        question = '$question',
                        answer = '$answer',
                        type = '$type',
                        course_id = " . ($course_id ? $course_id : "NULL") . "
                        WHERE faq_id = $faq_id";
        
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['message'] = "FAQ updated successfully.";
            $_SESSION['message_type'] = "success";
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Database error: " . mysqli_error($conn);
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
    <style>
        .btn-group .btn {
            border-radius: 6px !important;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        
        .btn-group .btn:hover {
            transform: translateY(-1px);
        }
        
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 10px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Edit FAQ</h3>
                    </div>
                    <div class="card-body">
                        <!-- Error Handling -->
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <?php foreach ($errors as $error): ?>
                                    <p class="mb-0"><?php echo htmlspecialchars($error); ?></p>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="type" class="form-label">FAQ Type</label>
                                <select class="form-select" id="type" name="type" required onchange="toggleCourseSelect()">
                                    <option value="public" <?php echo $faq['type'] == 'public' ? 'selected' : ''; ?>>Public</option>
                                    <option value="course" <?php echo $faq['type'] == 'course' ? 'selected' : ''; ?>>Course Specific</option>
                                </select>
                            </div>

                            <div class="mb-3" id="courseSelectDiv" style="display: <?php echo $faq['type'] == 'course' ? 'block' : 'none'; ?>;">
                                <label for="course_id" class="form-label">Select Course</label>
                                <select class="form-select" id="course_id" name="course_id">
                                    <option value="">Select a course</option>
                                    <?php while ($course = mysqli_fetch_assoc($courses_query)): ?>
                                        <option value="<?php echo $course['course_id']; ?>"
                                                <?php echo ($faq['course_id'] == $course['course_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($course['title']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="question" class="form-label">Question</label>
                                <textarea class="form-control" id="question" name="question" 
                                          rows="3" required><?php echo htmlspecialchars($faq['question']); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="answer" class="form-label">Answer</label>
                                <textarea class="form-control" id="answer" name="answer" 
                                          rows="5" required><?php echo htmlspecialchars($faq['answer']); ?></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="index.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update FAQ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleCourseSelect() {
            const typeSelect = document.getElementById('type');
            const courseSelectDiv = document.getElementById('courseSelectDiv');
            const courseSelect = document.getElementById('course_id');
            
            if (typeSelect.value === 'course') {
                courseSelectDiv.style.display = 'block';
                courseSelect.required = true;
            } else {
                courseSelectDiv.style.display = 'none';
                courseSelect.required = false;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
