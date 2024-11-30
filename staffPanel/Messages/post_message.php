<?php
session_start();
require_once '../../Configurations/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id']) && $_SESSION['role'] === 'staff') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];
   
    if (empty($title) || empty($content)) {
        $_SESSION['error'] = "Both title and content are required.";
    } else {
        // Add error logging
        $query = "INSERT INTO Messages (title, content, created_by) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        
        if ($stmt === false) {
            // Log preparation error
            $_SESSION['error'] = "Statement preparation failed: " . mysqli_error($conn);
        } else {
            mysqli_stmt_bind_param($stmt, 'ssi', $title, $content, $user_id);
           
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['success'] = "Message posted successfully.";
            } else {
                // Log execution error
                $_SESSION['error'] = "Error posting message: " . mysqli_stmt_error($stmt);
            }
            
            // Close the statement
            mysqli_stmt_close($stmt);
        }
    }
}
header('Location: index.php');
exit();