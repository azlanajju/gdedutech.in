<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once './Configurations/config.php';

$eventId = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;
if ($eventId <= 0) {
    http_response_code(404);
}

function resolveEventImageUrl($value) {
    if (!$value) { return ''; }
    if (preg_match('/^https?:\/\//i', $value)) { return $value; }
    if (substr($value, 0, 1) === '/') { return $value; }
    if (strpos($value, 'uploads/') === 0 || strpos($value, './uploads/') === 0) {
        return './' . ltrim($value, './');
    }
    return './uploads/events/' . ltrim($value, '/');
}

$event = null;
if ($eventId > 0) {
    $q = $conn->prepare("SELECT * FROM Events WHERE event_id = ? AND status IN ('upcoming','ongoing','completed')");
    $q->bind_param('i', $eventId);
    $q->execute();
    $res = $q->get_result();
    $event = $res ? $res->fetch_assoc() : null;
}

// Load social links for this event
$eventSocialLinks = [];
if ($event) {
    $sl = $conn->prepare("SELECT platform, url FROM social_links WHERE target_type = 'event' AND target_id = ?");
    $sl->bind_param('i', $eventId);
    $sl->execute();
    $slr = $sl->get_result();
    if ($slr) {
        while ($row = $slr->fetch_assoc()) { $eventSocialLinks[] = $row; }
    }
}

// Load additional event images (gallery)
$eventImages = [];
if ($event) {
    $imgq = $conn->prepare("SELECT image_url FROM events_images WHERE event_id = ? ORDER BY id ASC");
    $imgq->bind_param('i', $eventId);
    $imgq->execute();
    $imgr = $imgq->get_result();
    if ($imgr) {
        while ($row = $imgr->fetch_assoc()) { $eventImages[] = $row['image_url']; }
    }
}

function getSocialIconClass($platform) {
    $p = strtolower(trim((string)$platform));
    $map = [
        'facebook' => 'fa-brands fa-facebook',
        'instagram' => 'fa-brands fa-instagram',
        'twitter' => 'fa-brands fa-x-twitter',
        'x' => 'fa-brands fa-x-twitter',
        'linkedin' => 'fa-brands fa-linkedin',
        'youtube' => 'fa-brands fa-youtube',
        'github' => 'fa-brands fa-github',
        'website' => 'fa-solid fa-globe',
        'site' => 'fa-solid fa-globe',
        'web' => 'fa-solid fa-globe'
    ];
    return $map[$p] ?? 'fa-solid fa-link';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $event ? htmlspecialchars($event['title']) . ' - ' : ''; ?>Events - GD Edu Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" type="image/png" href="./Images/Logos/GD_Only_logo.png">
    <style>
        .event-cover { width: 100%; max-height: 420px; object-fit: cover; border-radius: 12px; }
        .event-meta small { color: #6c757d; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <section class="page-header position-relative overflow-hidden">
        <div class="container position-relative py-7">
            <div class="row align-items-center">
                <div class="col-md-8 col-12" data-aos="fade-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-3">
                            <li class="breadcrumb-item"><a href="index.php" class="text-white-50">Home</a></li>
                            <li class="breadcrumb-item"><a href="events.php" class="text-white-50">Events</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page"><?php echo $event ? htmlspecialchars($event['title']) : 'Not Found'; ?></li>
                        </ol>
                    </nav>
                    <h1 class="text-white display-5 fw-bold mb-3"><?php echo $event ? htmlspecialchars($event['title']) : 'Event not found'; ?></h1>
                    <?php if ($event): ?>
                        <p class="text-white-50 mb-0">
                            <?php if (!empty($event['event_date'])): ?>
                                <i class="bi bi-calendar3 me-1"></i><?php echo date('M d, Y', strtotime($event['event_date'])); ?>
                            <?php endif; ?>
                            <?php if (!empty($event['event_time'])): ?>
                                <span class="ms-3"><i class="bi bi-clock me-1"></i><?php echo htmlspecialchars(substr($event['event_time'],0,5)); ?></span>
                            <?php endif; ?>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="page-header-shape">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <?php if (!$event): ?>
                <div class="alert alert-warning">The requested event was not found.</div>
            <?php else: ?>
                <div class="row">
                    <div class="col-12 mb-4" data-aos="fade-up">
                        <?php if (!empty($event['main_cover_image'])): ?>
                            <img src="<?php echo htmlspecialchars(resolveEventImageUrl($event['main_cover_image'])); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>" class="event-cover">
                        <?php endif; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12" data-aos="fade-up">
                        <article>
                        <div class="row g-3 mb-4">
                                <?php if (!empty($event['location'])): ?>
                                    <div class="col-md-6"><div class="border rounded p-3 h-100"><i class="bi bi-geo-alt me-2"></i><strong>Location:</strong> <?php echo htmlspecialchars($event['location']); ?></div></div>
                                <?php endif; ?>
                                <?php if (!empty($event['event_link'])): ?>
                                    <div class="col-md-6"><div class="border rounded p-3 h-100"><i class="bi bi-link-45deg me-2"></i><strong>Event Link:</strong> <?php echo htmlspecialchars($event['event_link']); ?></div></div>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($event['description'])): ?>
                                <div class="mb-4">
                                    <?php echo nl2br($event['description']); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($eventImages)): ?>
                                <div class="mb-4">
                                    <h3 class="h5 mb-3">Gallery</h3>
                                    <div class="row g-3">
                                        <?php foreach ($eventImages as $img): ?>
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <img src="<?php echo htmlspecialchars(resolveEventImageUrl($img)); ?>" alt="event image" class="img-fluid rounded" style="object-fit:cover; width:100%; height:160px;">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($eventSocialLinks)): ?>
                                <div class="border-top pt-3 mt-4 d-flex align-items-center gap-3">
                                    <?php foreach ($eventSocialLinks as $lnk): 
                                        $icon = getSocialIconClass($lnk['platform']);
                                        $url = $lnk['url'];
                                        if (!$url) { continue; }
                                    ?>
                                        <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" rel="noopener" class="text-muted">
                                            <i class="<?php echo htmlspecialchars($icon); ?>"></i>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <div class="text-center mt-4">
                                <a href="events.php" class="btn btn-outline-primary">Back to Events</a>
                            </div>
                        </article>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init({ duration: 1000, easing: 'ease-in-out', once: true, mirror: false });
    </script>
</body>

</html>



