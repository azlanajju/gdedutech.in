<?php
require_once 'config.php'; // Include database configuration
require_once __DIR__ . '/vendor/autoload.php'; // Load Composer dependencies (including JWT)

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

session_start(); // Start a session for user authentication

// Secret key for JWT
$jwtSecretKey = "your_secret_key_here";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Check if the username exists
    $stmt = $conn->prepare("SELECT user_id, username, password_hash, role, status FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check user status
        if ($user['status'] !== 'active') {
            echo "<div class='alert alert-warning text-center'>Your account is {$user['status']}. Please contact the administrator.</div>";
        } elseif (password_verify($password, $user['password_hash'])) {
            // Password matches, create a JWT token
            $payload = [
                'iss' => 'http://localhost', // Issuer
                'aud' => 'http://localhost', // Audience
                'iat' => time(),            // Issued at
                'exp' => time() + 3600,     // Expiration time (1 hour)
                'user_id' => $user['user_id'],
                'username' => $user['username'],
                'role' => $user['role']
            ];

            $jwt = JWT::encode($payload, $jwtSecretKey, 'HS256');

            // Store JWT in a cookie
            setcookie("auth_token", $jwt, time() + 3600, "/", "", false, true);

            // Redirect based on user role
            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } elseif ($user['role'] === 'staff') {
                header("Location: staff_dashboard.php");
            } else {
                header("Location: student_dashboard.php");
            }
            exit();
        } else {
            echo "<div class='alert alert-danger text-center'>Invalid username or password.</div>";
        }
    } else {
        echo "<div class='alert alert-danger text-center'>Invalid username or password.</div>";
    }

    $stmt->close();
}

$conn->close();
?>


<!-- HTML Login Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .login-title {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-label {
            font-weight: bold;
        }
        .alert {
            margin-top: 15px;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2 class="login-title">Login</h2>
    <form action="login.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
        <div class="text-center mt-3">
            <small>Don't have an account? <a href="signup.php">Sign up</a></small>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
