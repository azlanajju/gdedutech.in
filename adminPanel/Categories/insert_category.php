<?php
session_start();

// Database connection
require_once '../config.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    // Input validation
    $errors = [];

    // Validate category name
    if (empty($name)) {
        $errors[] = "Category name is required.";
    } elseif (strlen($name) > 100) {
        $errors[] = "Category name cannot exceed 100 characters.";
    }

    // Validate description (optional, but limit length if provided)
    if (strlen($description) > 500) {
        $errors[] = "Description cannot exceed 500 characters.";
    }

    // If no errors, proceed with database insertion
    if (empty($errors)) {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO Categories (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);

        try {
            if ($stmt->execute()) {
                $_SESSION['message'] = "Category added successfully.";
                $_SESSION['message_type'] = "success";
                header("Location: categories.php");
                exit();
            } else {
                // Check for duplicate entry
                if ($stmt->errno == 1062) {
                    $errors[] = "A category with this name already exists.";
                } else {
                    $errors[] = "Error adding category: " . $stmt->error;
                }
            }
        } catch (Exception $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Category - GD Edu Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 min-vh-100">
                    <a href="#" class="d-flex align-items-center pb-3 mb-md-1 mt-md-3 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 fw-bolder">GD Edu Tech</span>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
                        <li class="w-100">
                            <a href="../index.php" class="nav-link">
                                <i class="bi bi-speedometer2 me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="w-100">
                            <a href="categories.php" class="nav-link active">
                                <i class="bi bi-grid me-2"></i> Categories
                            </a>
                        </li>
                        <!-- Other menu items remain the same as in categories.php -->
                        <li class="w-100 mt-auto">
                            <a href="logout.php" class="nav-link text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col py-3">
                <div class="container-fluid">
                    <!-- Header -->
                    <div class="row mb-4">
                        <div class="col">
                            <h2>Add New Category</h2>
                            <p class="text-muted">Create a new course category</p>
                        </div>
                        <div class="col-auto">
                            <a href="categories.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Categories
                            </a>
                        </div>
                    </div>

                    <!-- Error Messages -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php foreach ($errors as $error): ?>
                                <p class="mb-0"><?php echo htmlspecialchars($error); ?></p>
                            <?php endforeach; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Category Add Form -->
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="name" 
                                               name="name" 
                                               required 
                                               maxlength="100"
                                               value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>"
                                        >
                                        <small class="form-text text-muted">Maximum 100 characters</small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea 
                                            class="form-control" 
                                            id="description" 
                                            name="description" 
                                            rows="4" 
                                            maxlength="500"
                                        ><?php echo isset($description) ? htmlspecialchars($description) : ''; ?></textarea>
                                        <small class="form-text text-muted">Optional. Maximum 500 characters</small>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-2"></i>Add Category
                                        </button>
                                        <a href="categories.php" class="btn btn-secondary ms-2">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>