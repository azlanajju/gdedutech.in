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

    // If no errors, insert FAQ
    if (empty($errors)) {
        $insert_query = "INSERT INTO FAQs (question, answer, type, course_id) 
                        VALUES ('$question', '$answer', '$type', " . 
                        ($course_id ? $course_id : "NULL") . ")";
        
        if (mysqli_query($conn, $insert_query)) {
            $_SESSION['message'] = "FAQ added successfully.";
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
    <title>Add FAQ - GD Edu Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Add New FAQ</h3>
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
                                    <option value="public">Public</option>
                                    <option value="course">Course Specific</option>
                                </select>
                            </div>

                            <div class="mb-3" id="courseSelectDiv" style="display: none;">
                                <label for="course_id" class="form-label">Select Course</label>
                                <select class="form-select" id="course_id" name="course_id">
                                    <option value="">Select a course</option>
                                    <?php while ($course = mysqli_fetch_assoc($courses_query)): ?>
                                        <option value="<?php echo $course['course_id']; ?>">
                                            <?php echo htmlspecialchars($course['title']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="question" class="form-label">Question</label>
                                <textarea class="form-control" id="question" name="question" 
                                          rows="3" required><?php 
                                    echo isset($_POST['question']) ? htmlspecialchars($_POST['question']) : ''; 
                                ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="answer" class="form-label">Answer</label>
                                <textarea class="form-control" id="answer" name="answer" 
                                          rows="5" required><?php 
                                    echo isset($_POST['answer']) ? htmlspecialchars($_POST['answer']) : ''; 
                                ?></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="manage_faqs.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Add FAQ</button>
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