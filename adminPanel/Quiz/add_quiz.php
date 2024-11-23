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
require_once '../config.php';

// Fetch courses for dropdown
$courses_query = mysqli_query($conn, "SELECT course_id, title FROM Courses ORDER BY title");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $course_id = intval($_POST['course_id']);
    $title = mysqli_real_escape_string($conn, trim($_POST['title']));
    $instructions = mysqli_real_escape_string($conn, trim($_POST['instructions']));
    $total_marks = intval($_POST['total_marks']);

    // Validation
    $errors = [];
    if (empty($title)) $errors[] = "Quiz title is required.";
    if ($course_id <= 0) $errors[] = "Please select a valid course.";
    if ($total_marks <= 0) $errors[] = "Total marks must be a positive number.";

    // If no errors, insert quiz
    if (empty($errors)) {
        $insert_query = "INSERT INTO Quizzes (course_id, title, instructions, total_marks) 
                         VALUES ($course_id, '$title', '$instructions', $total_marks)";
        
        if (mysqli_query($conn, $insert_query)) {
            $new_quiz_id = mysqli_insert_id($conn);
            $_SESSION['message'] = "Quiz created successfully. Now add questions.";
            $_SESSION['message_type'] = "success";
            header("Location: add_question.php?quiz_id=$new_quiz_id");
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
    <title>Create Quiz - GD Edu Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Create New Quiz</h3>
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
                                <label for="course_id" class="form-label">Select Course</label>
                                <select name="course_id" id="course_id" class="form-select" required>
                                    <option value="">Choose a Course</option>
                                    <?php while ($course = mysqli_fetch_assoc($courses_query)): ?>
                                        <option value="<?php echo $course['course_id']; ?>">
                                            <?php echo htmlspecialchars($course['title']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="title" class="form-label">Quiz Title</label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       placeholder="Enter quiz title" required
                                       value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
                            </div>

                            <div class="mb-3">
                                <label for="instructions" class="form-label">Quiz Instructions</label>
                                <textarea class="form-control" id="instructions" name="instructions" 
                                          rows="4" placeholder="Provide quiz instructions"><?php 
                                              echo isset($_POST['instructions']) ? htmlspecialchars($_POST['instructions']) : ''; 
                                          ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="total_marks" class="form-label">Total Marks</label>
                                <input type="number" class="form-control" id="total_marks" name="total_marks" 
                                       placeholder="Enter total marks" required min="1"
                                       value="<?php echo isset($_POST['total_marks']) ? intval($_POST['total_marks']) : '10'; ?>">
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="quiz.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create Quiz</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>