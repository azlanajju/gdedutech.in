<?php
// publish_course.php
session_start();
require_once '../config.php';

if (isset($_GET['id'])) {
    $course_id = intval($_GET['id']);
    
    // Update course status to published
    $query = "UPDATE Courses SET status = 'published' WHERE course_id = $course_id";
    
    if (mysqli_query($conn, $query)) {
        $_SESSION['message'] = "Course published successfully.";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error publishing course: " . mysqli_error($conn);
        $_SESSION['message_type'] = "danger";
    }
    
    header("Location: courses.php");
    exit();
}
?>