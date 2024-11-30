<?php
session_start();
require_once '../../Configurations/config.php';

if (isset($_GET['id']) && isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') {
    $message_id = intval($_GET['id']);
    
    $query = "DELETE FROM Messages WHERE message_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $message_id);
    
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Message deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting message.";
    }
}

header('Location: index.php');
exit();
?> 