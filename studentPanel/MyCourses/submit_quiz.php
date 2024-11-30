<?php
session_start();
require_once '../../Configurations/config.php';

// Check if user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['quiz_id']) || !isset($_POST['answers'])) {
    header("Location: my_course.php");
    exit();
}

$quiz_id = intval($_POST['quiz_id']);
$user_id = $_SESSION['user_id'];
$answers = $_POST['answers'];

// Fetch quiz details and course info
$quiz_query = "SELECT q.*, c.course_id, c.title as course_title 
               FROM Quizzes q 
               JOIN Courses c ON q.course_id = c.course_id 
               WHERE q.quiz_id = ?";
$quiz_stmt = $conn->prepare($quiz_query);
$quiz_stmt->bind_param('i', $quiz_id);
$quiz_stmt->execute();
$quiz = $quiz_stmt->get_result()->fetch_assoc();

if (!$quiz) {
    header("Location: my_course.php");
    exit();
}

// Calculate score
$total_questions = 0;
$correct_answers = 0;
$question_details = [];

foreach ($answers as $question_id => $selected_answer) {
    $question_query = "SELECT content, correct_option FROM Questions WHERE question_id = ?";
    $question_stmt = $conn->prepare($question_query);
    $question_stmt->bind_param('i', $question_id);
    $question_stmt->execute();
    $question = $question_stmt->get_result()->fetch_assoc();
    
    if ($question) {
        $total_questions++;
        $is_correct = ($selected_answer === $question['correct_option']);
        if ($is_correct) {
            $correct_answers++;
        }
        
        $question_details[] = [
            'question' => $question['content'],
            'selected' => $selected_answer,
            'correct' => $question['correct_option'],
            'is_correct' => $is_correct
        ];
    }
}

$score_percentage = ($correct_answers / $total_questions) * 100;
$pass_threshold = 70; // 70% to pass
$passed = $score_percentage >= $pass_threshold;

// Store quiz result in database
$conn->begin_transaction();


    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results - <?php echo htmlspecialchars($quiz['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 40px 0;
        }
        .result-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .result-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #edf2f7;
        }
        .score-card {
            text-align: center;
            padding: 20px;
            margin: 20px 0;
            border-radius: 15px;
            background: <?php echo $passed ? '#d4edda' : '#f8d7da'; ?>;
        }
        .question-review {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
        }
        .correct {
            color: #28a745;
        }
        .incorrect {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="result-container">
            <div class="result-header">
                <h2><?php echo htmlspecialchars($quiz['title']); ?></h2>
                <p class="text-muted"><?php echo htmlspecialchars($quiz['course_title']); ?></p>
            </div>

            <div class="score-card">
                <h3>Your Score</h3>
                <h1 class="display-4"><?php echo round($score_percentage); ?>%</h1>
                <p class="lead">
                    <?php echo $correct_answers; ?> out of <?php echo $total_questions; ?> questions correct
                </p>
                <?php if ($passed): ?>
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle-fill me-2"></i>Congratulations! You've passed the assessment!
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-x-circle-fill me-2"></i>You need <?php echo $pass_threshold; ?>% to pass. Please try again.
                    </div>
                <?php endif; ?>
            </div>

            <h4 class="mb-4">Question Review</h4>
            <?php foreach ($question_details as $index => $detail): ?>
                <div class="question-review">
                    <h5>
                        <span class="badge bg-primary me-2"><?php echo $index + 1; ?></span>
                        <?php echo htmlspecialchars($detail['question']); ?>
                    </h5>
                    <p>
                        Your Answer: 
                        <span class="<?php echo $detail['is_correct'] ? 'correct' : 'incorrect'; ?>">
                            <i class="bi bi-<?php echo $detail['is_correct'] ? 'check' : 'x'; ?>-circle-fill me-1"></i>
                            Option <?php echo htmlspecialchars($detail['selected']); ?>
                        </span>
                    </p>
                    <?php if (!$detail['is_correct']): ?>
                        <p class="correct">
                            <i class="bi bi-check-circle-fill me-1"></i>
                            Correct Answer: Option <?php echo htmlspecialchars($detail['correct']); ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <div class="mt-4 d-flex gap-3 justify-content-center">
                <?php if ($passed): ?>
                    <a href="view_certificate.php?course_id=<?php echo $quiz['course_id']; ?>" 
                       class="btn btn-success">
                        <i class="bi bi-award me-2"></i>View Certificate
                    </a>
                <?php else: ?>
                    <a href="take_quiz.php?quiz_id=<?php echo $quiz_id; ?>" 
                       class="btn btn-primary">
                        <i class="bi bi-arrow-repeat me-2"></i>Retry Quiz
                    </a>
                <?php endif; ?>
                <a href="course_content.php?id=<?php echo $quiz['course_id']; ?>" 
                   class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Course
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 