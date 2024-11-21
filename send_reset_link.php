<?php
require 'vendor/autoload.php'; // If you used Composer
// Or include PHPMailer's files manually if you didn't use Composer.

// Use PHPMailer's classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    // Check if the email exists in the database
    require_once 'config.php'; // Include your database connection

    $stmt = $conn->prepare("SELECT user_id, username FROM Users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Generate a unique token (using a random string or hash)
        $token = bin2hex(random_bytes(50));
        $expiry = time() + 3600; // Token expires in 1 hour

        // Store the token in the database
        $stmt = $conn->prepare("INSERT INTO password_resets (user_id, token, expiry) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user['user_id'], $token, $expiry);
        $stmt->execute();

        // Create a PHPMailer instance and set up SMTP
        $mail = new PHPMailer(true); 
        try {
            //Server settings
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com';                         // Set the SMTP server to Gmail
            $mail->SMTPAuth   = true;                                     // Enable SMTP authentication
            $mail->Username   = 'abdulmausooq@gmail.com';                  // SMTP username
            $mail->Password   = 'okwh rhsd ruez evhx';                   // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;          // Enable TLS encryption
            $mail->Port       = 587;                                     // TCP port to connect to

            //Recipients
            $mail->setFrom('abdulmausooq@gmail.com', 'GD EDU TECH');
            $mail->addAddress($email);  // Add recipient's email address

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Hello, <br><br> To reset your password, please click the link below:<br>
                              <a href='http://localhost/gd-edu-tech/reset_password.php?token=$token'>
                              Reset Password</a>";
           
            // Send the email
            $mail->send();
            echo "A reset link has been sent to your email.";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "No user found with that email.";
    }
    $stmt->close();
    $conn->close();
}
?>
