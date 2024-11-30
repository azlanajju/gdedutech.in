<?php
session_start();
require_once '../config.php';

// Check if quiz ID is provided
if (!isset($_GET['id'])) {
    $_SESSION['message'] = "No quiz ID provided.";
    $_SESSION['message_type'] = "danger";
    header("Location: quiz.php");
    exit();
}

$quiz_id = intval($_GET['id']);

// Fetch the quiz details
$query = "SELECT * FROM Quizzes WHERE quiz_id = $quiz_id";
$result = mysqli_query($conn, $query);
$quiz = mysqli_fetch_assoc($result);

if (!$quiz) {
    $_SESSION['message'] = "Quiz not found.";
    $_SESSION['message_type'] = "danger";
    header("Location: quiz.php");
    exit();
}

// Handle form submission for editing quiz
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $instructions = mysqli_real_escape_string($conn, $_POST['instructions']);
    $total_marks = intval($_POST['total_marks']);
    $course_id = intval($_POST['course_id']);

    // Update quiz details
    $update_query = "UPDATE Quizzes SET title = '$title', instructions = '$instructions', total_marks = $total_marks, course_id = $course_id, updated_at = NOW() WHERE quiz_id = $quiz_id";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['message'] = "Quiz updated successfully.";
        $_SESSION['message_type'] = "success";
        header("Location: quiz.php");
        exit();
    } else {
        $_SESSION['message'] = "Error updating quiz: " . mysqli_error($conn);
        $_SESSION['message_type'] = "danger";
    }
}

// Fetch available courses for the dropdown
$courses_query = "SELECT * FROM Courses";
$courses_result = mysqli_query($conn, $courses_query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz - GD Edu Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar">
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
                    <li class="w-100">
                        <a href="quiz.php" class="nav-link active">
                            <i class="bi bi-clipboard-check me-2"></i> Quizzes
                        </a>
                    </li>
                    <!-- Add other menu items here -->
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col py-3">
                <div class="container-fluid">
                    <!-- Header -->
                    <div class="row mb-4">
                        <div class="col">
                            <h2>Edit Quiz</h2>
                            <p class="text-muted">Edit quiz details here</p>
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

                    <!-- Edit Quiz Form -->
                    <form method="POST">
                        <div class="mb-3">
                            <label for="title" class="form-label">Quiz Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($quiz['title']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="instructions" class="form-label">Instructions</label>
                            <textarea class="form-control" id="instructions" name="instructions" rows="4"><?php echo htmlspecialchars($quiz['instructions']); ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="total_marks" class="form-label">Total Marks</label>
                            <input type="number" class="form-control" id="total_marks" name="total_marks" value="<?php echo htmlspecialchars($quiz['total_marks']); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="course_id" class="form-label">Course</label>
                            <select class="form-select" id="course_id" name="course_id" required>
                                <option value="" disabled>Select Course</option>
                                <?php while ($course = mysqli_fetch_assoc($courses_result)): ?>
                                    <option value="<?php echo $course['course_id']; ?>" <?php echo $quiz['course_id'] == $course['course_id'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($course['title']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
