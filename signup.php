<?php
require_once 'config.php'; // Include the configuration file

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $profile_image = $_FILES['profile_image'];
    $registration_success = false;

    // Check for duplicate username or email
    $checkStmt = $conn->prepare("SELECT user_id FROM Users WHERE username = ? OR email = ?");
    $checkStmt->bind_param("ss", $username, $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "Username or Email already exists. Please try another.";
        $checkStmt->close();
    } else {
        $checkStmt->close();

        // Hash the password
        $password_hash = password_hash($password, PASSWORD_BCRYPT);

        // Handle profile image upload
        $profile_image_path = null;
        if ($profile_image && $profile_image['error'] == 0) {
            $profile_image_name = time() . '_' . basename($profile_image['name']);
            $profile_image_path = UPLOADS_DIR . $profile_image_name;

            // Move the uploaded file
            if (!move_uploaded_file($profile_image['tmp_name'], $profile_image_path)) {
                $_SESSION['error_message'] = "Error uploading profile image.";
                header('Location: signup.php');
                exit();
            }
        }

        // Insert user into the database
        $stmt = $conn->prepare("
            INSERT INTO Users (username, password_hash, email, first_name, last_name, profile_image, role, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $role = "student"; // Default role
        $status = "active"; // Default status
        $stmt->bind_param("ssssssss", $username, $password_hash, $email, $first_name, $last_name, $profile_image_name, $role, $status);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Registration successful! Please login.";
            $registration_success = true;
        } else {
            $_SESSION['error_message'] = "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $conn->close();

    // Redirect after processing
    if ($registration_success) {
        header('Location: login.php');
        exit();
    } else {
        header('Location: signup.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Sign-Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <?php
                if (isset($_SESSION['error_message'])) {
                    echo "<div class='alert alert-danger text-center'>" . htmlspecialchars($_SESSION['error_message']) . "</div>";
                    unset($_SESSION['error_message']);
                }
                ?>
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h2>Sign Up</h2>
                    </div>
                    <div class="card-body">
                        <form action="signup.php" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="profile_image" class="form-label">Profile Image</label>
                                <input type="file" class="form-control" id="profile_image" name="profile_image">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                            <div class="text-center mt-3">
                                <small>Have an account? <a href="login.php">Sign in</a></small>
                             </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>