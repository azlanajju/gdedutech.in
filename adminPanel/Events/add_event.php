<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
	header('Location: ../admin_login.php');
	exit();
}

require_once '../../Configurations/config.php';

function getUploadsBasePath() {
	return 'C:\\xampp\\htdocs\\gdedutech.in\\uploads' . DIRECTORY_SEPARATOR;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$title = trim($_POST['title'] ?? '');
	$description = $_POST['description'] ?? '';
	$location = $_POST['location'] ?? '';
	$event_date = $_POST['event_date'] ?? null;
	$event_time = $_POST['event_time'] ?? null;
	$event_link = $_POST['event_link'] ?? '';
	$media_url = $_POST['media_url'] ?? '';
	$organizer_id = intval($_SESSION['user_id']);
	$status = $_POST['status'] ?? 'upcoming';
	$coverFileName = null;

	if (!empty($_FILES['cover_image']['name']) && isset($_FILES['cover_image']['error']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
		$dir = getUploadsBasePath() . 'events' . DIRECTORY_SEPARATOR;
		if (!is_dir($dir)) { @mkdir($dir, 0775, true); }
		$ext = preg_replace('/[^a-zA-Z0-9]/','', pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION));
		$coverFileName = time() . '_event_cover.' . $ext;
		$target = $dir . $coverFileName;
		if (!is_uploaded_file($_FILES['cover_image']['tmp_name']) || !@move_uploaded_file($_FILES['cover_image']['tmp_name'], $target)) {
			$error = 'Failed to upload cover image.';
		}
	}

	if (!$error) {
		$stmt = $conn->prepare("INSERT INTO Events (main_cover_image, title, description, location, event_date, event_time, event_link, media_url, organizer_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param('ssssssssss', $coverFileName, $title, $description, $location, $event_date, $event_time, $event_link, $media_url, $organizer_id, $status);
		if ($stmt->execute()) {
			$_SESSION['message'] = 'Event created successfully.';
			$_SESSION['message_type'] = 'success';
			header('Location: ./');
			exit();
		} else {
			$error = 'Error creating event: ' . $conn->error;
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Add Event - GD Edu Tech</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
	<link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container-fluid">
	<div class="row">
		<div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 sidebar">
			<div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 min-vh-100">
				<a href="#" class="d-flex align-items-center pb-3 mb-md-1 mt-md-3 me-md-auto text-white text-decoration-none">
					<span class="fs-5 fw-bolder" style="display:flex;align-items:center;color:black;"><img height="35px" src="../images/edutechLogo.png" alt="">&nbsp; GD Edu Tech</span>
				</a>
				<ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100" id="menu">
					<li class="w-100"><a href="../" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
					<li class="w-100"><a href="../Categories/" class="nav-link"><i class="bi bi-grid me-2"></i> Categories</a></li>
					<li class="w-100"><a href="../Courses/" class="nav-link"><i class="bi bi-book me-2"></i> Courses</a></li>
					<li class="w-100"><a href="../Blogs/" class="nav-link"><i class="bi bi-journal-text me-2"></i> Blogs</a></li>
					<li class="w-100"><a href="../Events/" class="nav-link active"><i class="bi bi-calendar2-event me-2"></i> Events</a></li>
					<li class="w-100 dropdown">
                            <a href="#" class="nav-link dropdown-toggle" id="quizDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-lightbulb me-2"></i> Quick Links
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="quizDropdown">
                                <li><a class="dropdown-item" href="../index.php">Career portal</a></li>
                                <li><a class="dropdown-item" href="./Shop/shop.php">Shop</a></li>
                            </ul>
            </li>
			<li class="w-100"><a href="../Schedule/" class="nav-link"><i class="bi bi-calendar-event me-2"></i> Schedule</a></li>
					<li class="w-100"><a href="../Messages/" class="nav-link"><i class="bi bi-chat-dots me-2"></i> Messages</a></li>
					<li class="w-100"><a href="../FAQ/" class="nav-link"><i class="bi bi-question-circle me-2"></i> FAQ</a></li>
					<li class="w-100"><a href="../Users/" class="nav-link"><i class="bi bi-people me-2"></i> Users</a></li>
					<li class="w-100"><a href="../manage_qr.php" class="nav-link"><i class="bi bi-qr-code me-2"></i> Payment QR</a></li>
					<li class="w-100"><a href="../pending_payments.php" class="nav-link"><i class="bi bi-credit-card me-2"></i> Pending Payments</a></li>
					<li class="w-100 mt-auto"><a href="../logout.php" class="nav-link text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
				</ul>
			</div>
		</div>

		<div class="col py-3">
			<div class="container-fluid">
				<div class="row mb-4">
					<div class="col">
						<h2>Add Event</h2>
						<p class="text-muted">Create a new event</p>
					</div>
					<div class="col-auto">
						<a href="./" class="btn btn-outline-primary"><i class="bi bi-arrow-left me-2"></i>Back</a>
					</div>
				</div>

				<?php if ($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

				<form method="post" enctype="multipart/form-data" class="card p-3">
					<div class="mb-3"><label class="form-label">Title</label><input name="title" class="form-control" required></div>
					<div class="mb-3"><label class="form-label">Cover Image</label><input type="file" name="cover_image" class="form-control" accept="image/*"></div>
					<div class="mb-3"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="6"></textarea></div>
					<div class="row g-3">
						<div class="col-md-6"><label class="form-label">Location</label><input name="location" class="form-control"></div>
						<div class="col-md-3"><label class="form-label">Date</label><input type="date" name="event_date" class="form-control"></div>
						<div class="col-md-3"><label class="form-label">Time</label><input type="time" name="event_time" class="form-control"></div>
					</div>
					<div class="row g-3 mt-1">
						<div class="col-md-6"><label class="form-label">Event Link</label><input name="event_link" class="form-control"></div>
						<div class="col-md-6"><label class="form-label">Media URL</label><input name="media_url" class="form-control"></div>
					</div>
					<div class="mb-3 mt-3">
						<label class="form-label">Status</label>
						<select name="status" class="form-select">
							<option value="upcoming">upcoming</option>
							<option value="ongoing">ongoing</option>
							<option value="completed">completed</option>
							<option value="cancelled">cancelled</option>
						</select>
					</div>
					<button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>Create</button>
				</form>
			</div>
		</div>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


