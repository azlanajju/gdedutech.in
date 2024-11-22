<?php
require_once '../config.php';
session_start();

$course_id = intval($_GET['course_id']);

// Fetch course details
$course_query = "SELECT c.*, cat.name AS category_name 
                 FROM Courses c 
                 JOIN Categories cat ON c.category_id = cat.category_id 
                 WHERE c.course_id = ?";
$course_stmt = mysqli_prepare($conn, $course_query);
mysqli_stmt_bind_param($course_stmt, 'i', $course_id);
mysqli_stmt_execute($course_stmt);
$course_result = mysqli_stmt_get_result($course_stmt);
$course = mysqli_fetch_assoc($course_result);

if (!$course) {
    header("Location: courses.php");
    exit();
}

// Fetch lessons with their videos
$lessons_query = "SELECT l.*, 
                         (SELECT COUNT(*) FROM Videos v WHERE v.lesson_id = l.lesson_id) as video_count
                  FROM Lessons l 
                  WHERE l.course_id = ? 
                  ORDER BY l.lesson_order";
$lessons_stmt = mysqli_prepare($conn, $lessons_query);
mysqli_stmt_bind_param($lessons_stmt, 'i', $course_id);
mysqli_stmt_execute($lessons_stmt);
$lessons_result = mysqli_stmt_get_result($lessons_stmt);

// Fetch quizzes for the course
$quizzes_query = "SELECT * FROM Quizzes WHERE course_id = ?";
$quizzes_stmt = mysqli_prepare($conn, $quizzes_query);
mysqli_stmt_bind_param($quizzes_stmt, 'i', $course_id);
mysqli_stmt_execute($quizzes_stmt);
$quizzes_result = mysqli_stmt_get_result($quizzes_stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($course['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/plyr@3.7.8/dist/plyr.css" rel="stylesheet">
    <style>
        .correct-option {
            background-color: lightgreen;
        }
        .quiz-options {
            list-style-type: none;
            padding: 0;
        }
        .quiz-options li {
            padding: 8px;
        }
        .quiz-options li:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="./thumbnails/<?php echo htmlspecialchars($course['thumbnail']); ?>" class="card-img-top" alt="Course Thumbnail">
                <div class="card-body">
                    <h1 class="card-title"><?php echo htmlspecialchars($course['title']); ?></h1>
                    <p class="card-text"><?php echo htmlspecialchars($course['description']); ?></p>
                    <ul class="list-unstyled">
                        <li><strong>Category:</strong> <?php echo htmlspecialchars($course['category_name']); ?></li>
                        <li><strong>Language:</strong> <?php echo htmlspecialchars($course['language']); ?></li>
                        <li><strong>Level:</strong> <?php echo ucfirst(htmlspecialchars($course['level'])); ?></li>
                        <li><strong>Price:</strong> ₹<?php echo number_format($course['price'], 2); ?></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <h2>Course Curriculum</h2>
            <div class="accordion" id="courseCurriculum">
                <?php 
                $lesson_index = 0;
                while ($lesson = mysqli_fetch_assoc($lessons_result)): 
                    $lesson_index++;
                    
                    // Fetch videos for this lesson
                    $videos_query = "SELECT * FROM Videos WHERE lesson_id = ? ORDER BY video_order";
                    $videos_stmt = mysqli_prepare($conn, $videos_query);
                    mysqli_stmt_bind_param($videos_stmt, 'i', $lesson['lesson_id']);
                    mysqli_stmt_execute($videos_stmt);
                    $videos_result = mysqli_stmt_get_result($videos_stmt);
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button <?php echo $lesson_index > 1 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#lesson<?php echo $lesson['lesson_id']; ?>">
                            Lesson <?php echo $lesson_index; ?>: <?php echo htmlspecialchars($lesson['title']); ?>
                            <span class="badge bg-secondary ms-2"><?php echo $lesson['video_count']; ?> Videos</span>
                        </button>
                    </h2>
                    <div id="lesson<?php echo $lesson['lesson_id']; ?>" class="accordion-collapse collapse <?php echo $lesson_index == 1 ? 'show' : ''; ?>">
                        <div class="accordion-body">
                            <p><?php echo htmlspecialchars($lesson['description']); ?></p>
                            
                            <?php while ($video = mysqli_fetch_assoc($videos_result)): ?>
                            <div class="mb-3">
                                <h5><?php echo htmlspecialchars($video['title']); ?></h5>
                                <p><?php echo htmlspecialchars($video['description']); ?></p>
                                <video controls crossorigin playsinline>
                                    <source src="./course_videos/<?php echo htmlspecialchars($video['video_url']); ?>" type="video/mp4">
                                </video>
                            </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            
            <!-- Display the quizzes below the videos -->
            <h2 class="mt-5">Quizzes</h2>
            <?php if (mysqli_num_rows($quizzes_result) > 0): ?>
                <?php while ($quiz = mysqli_fetch_assoc($quizzes_result)): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($quiz['title']); ?></h5>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($quiz['instructions'])); ?></p>
                            
                            <?php 
                            // Fetch questions for the quiz
                            $questions_query = "SELECT * FROM Questions WHERE quiz_id = ?";
                            $questions_stmt = mysqli_prepare($conn, $questions_query);
                            mysqli_stmt_bind_param($questions_stmt, 'i', $quiz['quiz_id']);
                            mysqli_stmt_execute($questions_stmt);
                            $questions_result = mysqli_stmt_get_result($questions_stmt);
                            ?>
                            
                            <?php while ($question = mysqli_fetch_assoc($questions_result)): ?>
                                <div class="mb-3">
                                    <h6><?php echo nl2br(htmlspecialchars($question['content'])); ?></h6>
                                    <ul class="quiz-options">
                                        <li class="<?php echo $question['correct_option'] == 'A' ? 'correct-option' : ''; ?>">
                                            A: <?php echo htmlspecialchars($question['option_a']); ?>
                                        </li>
                                        <li class="<?php echo $question['correct_option'] == 'B' ? 'correct-option' : ''; ?>">
                                            B: <?php echo htmlspecialchars($question['option_b']); ?>
                                        </li>
                                        <li class="<?php echo $question['correct_option'] == 'C' ? 'correct-option' : ''; ?>">
                                            C: <?php echo htmlspecialchars($question['option_c']); ?>
                                        </li>
                                        <li class="<?php echo $question['correct_option'] == 'D' ? 'correct-option' : ''; ?>">
                                            D: <?php echo htmlspecialchars($question['option_d']); ?>
                                        </li>
                                    </ul>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No quizzes available for this course.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/plyr@3.7.8/dist/plyr.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Initialize Plyr video players
    const players = Plyr.setup('video');
});
</script>
</body>
</html>
