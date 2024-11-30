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
            $answer_query = "INSERT INTO Answers (question_id, content, user_id, created_at) VALUES (?, ?, ?, NOW())";
            $answer_stmt = mysqli_prepare($conn, $answer_query);
            mysqli_stmt_bind_param($answer_stmt, 'isi', $question_id, $content, $user_id);
            
            if (!mysqli_stmt_execute($answer_stmt)) {
                throw new Exception("Failed to insert answer");
            }
            
            // Update question status
            $status_query = "UPDATE Questions SET status = 'answered', updated_at = NOW() WHERE question_id = ?";
            $status_stmt = mysqli_prepare($conn, $status_query);
            mysqli_stmt_bind_param($status_stmt, 'i', $question_id);
            
            if (!mysqli_stmt_execute($status_stmt)) {
                throw new Exception("Failed to update question status");
            }
            
            mysqli_commit($conn);
            $_SESSION['success'] = "Answer posted successfully.";
        } catch (Exception $e) {
            mysqli_rollback($conn);
            $_SESSION['error'] = "Error posting answer: " . $e->getMessage();
        }
    }
    
    // Close statements
    if (isset($answer_stmt)) mysqli_stmt_close($answer_stmt);
    if (isset($status_stmt)) mysqli_stmt_close($status_stmt);
}

// Redirect back to the messages page
header('Location: index.php');
exit();