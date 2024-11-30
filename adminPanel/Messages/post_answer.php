<?php
session_start();
require_once '../../Configurations/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') {
    $question_id = intval($_POST['question_id']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];

    if (empty($content)) {
        $_SESSION['error'] = "Answer content is required.";
    } else {
        // Start transaction
        mysqli_begin_transaction($conn);

        try {
            // Insert answer
            $answer_query = "INSERT INTO Answers (question_id, content, user_id) VALUES (?, ?, ?)";
            $answer_stmt = mysqli_prepare($conn, $answer_query);
            mysqli_stmt_bind_param($answer_stmt, 'isi', $question_id, $content, $user_id);
            mysqli_stmt_execute($answer_stmt);

            // Update question status
            $status_query = "UPDATE Questions SET status = 'answered' WHERE question_id = ?";
            $status_stmt = mysqli_prepare($conn, $status_query);
            mysqli_stmt_bind_param($status_stmt, 'i', $question_id);
            mysqli_stmt_execute($status_stmt);

            mysqli_commit($conn);
            $_SESSION['success'] = "Answer posted successfully.";
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $_SESSION['error'] = "Error posting answer. Please try again.";
        }
    }
}

header('Location: index.php');
exit();
