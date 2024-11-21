<!-- forgot_password.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="send_reset_link.php" method="POST">
        <input type="email" name="email" required placeholder="Enter your email">
        <button type="submit">Send Reset Link</button>
    </form>
</body>
</html>
