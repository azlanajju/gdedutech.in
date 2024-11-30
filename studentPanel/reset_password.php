<?php
require_once 'config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify token
    $stmt = $conn->prepare("SELECT user_id, expiry FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $reset = $result->fetch_assoc();

        // Check if the token has expired
        if ($reset['expiry'] > time()) {
            // Token is valid, allow user to reset the password
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $new_password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
                $user_id = $reset['user_id'];

                // Update the password in the database
                $stmt = $conn->prepare("UPDATE Users SET password_hash = ? WHERE user_id = ?");
                $stmt->bind_param("si", $new_password, $user_id);
                $stmt->execute();

                // Delete the token after it is used
                $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
                $stmt->bind_param("s", $token);
                $stmt->execute();
                    // Redirect to the login page after password reset
                    header('Location: login.php');
                    exit(); // Make sure to stop the script after the redirect
            }
        } else {
            echo "This token has expired.";
        }
    } else {
        echo "Invalid or expired token.";
    }
}

$conn->close();
?>

<!-- reset_password_form.php -->
<form action="reset_password.php?token=<?php echo $token; ?>" method="POST">
    <input type="password" name="password" required placeholder="Enter new password">
    <button type="submit">Reset Password</button>
</form>
