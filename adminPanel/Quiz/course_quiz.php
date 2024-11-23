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
<?php
require_once '../config.php';

if (!isset($_GET['course_id'])) {
    $_SESSION['message'] = "No course selected.";
    $_SESSION['message_type'] = "danger";
    header("Location: courses.php");
    exit();
}

$course_id = intval($_GET['course_id']);

// Fetch quizzes for the selected course
$query = "SELECT * FROM Quizzes WHERE course_id = $course_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error fetching quizzes: ' . mysqli_error($conn));
}

if (mysqli_num_rows($result) == 0) {
    echo "No quizzes found for this course.";
}

$questions = [];
while ($quiz = mysqli_fetch_assoc($result)) {
    $quiz_id = $quiz['quiz_id'];
    $questions_query = "SELECT * FROM Questions WHERE quiz_id = $quiz_id";
    $questions_result = mysqli_query($conn, $questions_query);

    if (!$questions_result) {
        die('Error fetching questions: ' . mysqli_error($conn));
    }

    if (mysqli_num_rows($questions_result) == 0) {
        echo "No questions found for quiz ID: $quiz_id";
    }

    while ($question = mysqli_fetch_assoc($questions_result)) {
        $questions[$quiz_id][] = $question;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz - GD Edu Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .correct-option {
            background-color: green;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container my-4">
        <h2>Quiz Questions</h2>
        <div class="accordion" id="quizAccordion">
            <?php foreach ($questions as $quiz_id => $quiz_questions): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading<?php echo $quiz_id; ?>">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $quiz_id; ?>" aria-expanded="true" aria-controls="collapse<?php echo $quiz_id; ?>">
                            Quiz <?php echo $quiz_id; ?>
                        </button>
                    </h2>
                    <div id="collapse<?php echo $quiz_id; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $quiz_id; ?>" data-bs-parent="#quizAccordion">
                        <div class="accordion-body">
                            <?php foreach ($quiz_questions as $question): ?>
                                <div class="mb-3">
                                    <strong>Question: </strong> <?php echo htmlspecialchars($question['content']); ?>
                                    <ul class="list-unstyled">
                                        <li><?php echo htmlspecialchars($question['option_a']); ?></li>
                                        <li><?php echo htmlspecialchars($question['option_b']); ?></li>
                                        <li><?php echo htmlspecialchars($question['option_c']); ?></li>
                                        <li><?php echo htmlspecialchars($question['option_d']); ?></li>
                                    </ul>
                                    <strong>Correct Answer: </strong> <?php echo $question['correct_option']; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
