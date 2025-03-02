<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: admin_login.php');
    exit();
}

// Database connection
require_once '../../Configurations/config.php';

// Check if job ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = "Invalid job ID provided";
    $_SESSION['message_type'] = "danger";
    header('Location: index.php');
    exit();
}

$job_id = mysqli_real_escape_string($conn, $_GET['id']);

// Delete the career listing
$query = "DELETE FROM Careers WHERE job_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $job_id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['message'] = "Career listing deleted successfully";
    $_SESSION['message_type'] = "success";
} else {
    $_SESSION['message'] = "Error deleting career listing: " . mysqli_error($conn);
    $_SESSION['message_type'] = "danger";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

// Redirect back to the career management page
header('Location: index.php');
exit();
