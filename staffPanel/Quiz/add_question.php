<?php
session_start();
require_once '../../Configurations/config.php';

// Check if user is logged in and is staff
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Staff') {
    header('Location: ../staff_login.php');
    exit();
}

// Get staff details from session
$staff_name = $_SESSION['username'] ?? 'Staff';

// Fetch quiz details
$quiz_id = isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0;
if ($quiz_id === 0) {
    header("Location: quiz.php");
    exit();
}

// Check if the quiz exists
$quiz_query = mysqli_query($conn, "SELECT * FROM Quizzes WHERE quiz_id = $quiz_id");
if (mysqli_num_rows($quiz_query) == 0) {
    header("Location: quiz.php");
    exit();
}

// Handle form submission for adding a question
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = [];
    $content = trim($_POST['content']);
    $option_a = trim($_POST['option_a']);
    $option_b = trim($_POST['option_b']);
    $option_c = trim($_POST['option_c']);
    $option_d = trim($_POST['option_d']);
    $correct_option = $_POST['correct_option'];

    // Validate inputs
    if (empty($content)) $errors[] = "Question content is required.";
    if (empty($option_a) || empty($option_b) || empty($option_c) || empty($option_d)) {
        $errors[] = "All options are required.";
    }
    if (!in_array($correct_option, ['A', 'B', 'C', 'D'])) {
        $errors[] = "Valid correct option must be selected (A, B, C, or D).";
    }

    // If no errors, insert the question into the database
    if (empty($errors)) {
        $insert_query = "INSERT INTO Questions (quiz_id, content, option_a, option_b, option_c, option_d, correct_option)
                         VALUES ($quiz_id, '$content', '$option_a', '$option_b', '$option_c', '$option_d', '$correct_option')";
                         
        if (mysqli_query($conn, $insert_query)) {
            $_SESSION['message'] = "Question added successfully.";
            $_SESSION['message_type'] = "success";
            header("Location: add_question.php?quiz_id=$quiz_id");
            exit();
        } else {
            $_SESSION['message'] = "Error adding question: " . mysqli_error($conn);
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
    <title>Add Question - GD Edu Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar (same as previous example) -->

            <!-- Main Content -->
            <div class="col py-3">
                <h2>Add Question to Quiz</h2>
                <p class="text-muted">Add questions for the selected quiz.</p>

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

                <!-- Add Question Form -->
                <form action="add_question.php?quiz_id=<?php echo $quiz_id; ?>" method="POST">
                    <div class="mb-3">
                        <label for="content" class="form-label">Question Content</label>
                        <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="option_a" class="form-label">Option A</label>
                        <input type="text" class="form-control" id="option_a" name="option_a" required>
                    </div>
                    <div class="mb-3">
                        <label for="option_b" class="form-label">Option B</label>
                        <input type="text" class="form-control" id="option_b" name="option_b" required>
                    </div>
                    <div class="mb-3">
                        <label for="option_c" class="form-label">Option C</label>
                        <input type="text" class="form-control" id="option_c" name="option_c" required>
                    </div>
                    <div class="mb-3">
                        <label for="option_d" class="form-label">Option D</label>
                        <input type="text" class="form-control" id="option_d" name="option_d" required>
                    </div>
                    <div class="mb-3">
                        <label for="correct_option" class="form-label">Correct Option</label>
                        <select class="form-select" id="correct_option" name="correct_option" required>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Question</button>
                </form>

                <!-- Back to Quiz List -->
                <a href="quiz.php" class="btn btn-secondary mt-3">Back to Quiz List</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>