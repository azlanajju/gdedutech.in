<?php
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    require_once 'config.php';

    $stmt = $conn->prepare("SELECT user_id, username FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $token = bin2hex(random_bytes(50));
        $expiry = time() + 3600;

        $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user['user_id'], $token, $expiry);
        $stmt->execute();

        $mail = new PHPMailer(true); 
        try {
            // Your existing PHPMailer configuration...
            $mail->send();
            $messageType = 'success';
            $message = "Password reset link has been sent to your email address.";
        } catch (Exception $e) {
            $messageType = 'danger';
            $message = "Failed to send reset link. Please try again later.";
        }
    } else {
        $messageType = 'warning';
        $message = "No account found with that email address.";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Link Status - GD Edu Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .status-container {
            max-width: 450px;
            margin: 0 auto;
            padding: 40px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        }
        .status-title {
            font-size: 28px;
            font-weight: 700;
            color: #2C3E50;
            margin-bottom: 20px;
            text-align: center;
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo img {
            height: 60px;
        }
        .status-message {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 10px;
        }
        .btn-primary {
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
            background: #2C3E50;
            border: none;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 15px;
        }
        .btn-primary:hover {
            background: #34495E;
            transform: translateY(-2px);
        }
        .back-to-login {
            text-align: center;
        }
        .back-to-login a {
            color: #2C3E50;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .back-to-login a:hover {
            color: #34495E;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="status-container">
            <div class="logo">
                <img src="./assets/images/logo.png" alt="GD Edu Tech">
            </div>
            <h2 class="status-title">Password Reset</h2>
            
            <?php if ($message): ?>
                <div class="status-message alert alert-<?php echo $messageType; ?>">
                    <?php if ($messageType === 'success'): ?>
                        <i class="bi bi-check-circle-fill me-2"></i>
                    <?php elseif ($messageType === 'danger'): ?>
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <?php else: ?>
                        <i class="bi bi-info-circle-fill me-2"></i>
                    <?php endif; ?>
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if ($messageType === 'success'): ?>
                <p class="text-center text-muted mb-4">
                    Please check your email and follow the instructions to reset your password.
                </p>
            <?php endif; ?>
            
            <div class="back-to-login">
                <a href="login.php">
                    <i class="bi bi-arrow-left me-2"></i>Back to Login
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
