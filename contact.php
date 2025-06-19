<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once './Configurations/config.php';

$message = '';
$message_class = '';

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message_text = trim($_POST['message']);
    
    if (empty($name) || empty($email) || empty($subject) || empty($message_text)) {
        $message = 'Please fill out all required fields.';
        $message_class = 'alert-danger';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Please enter a valid email address.';
        $message_class = 'alert-danger';
    } else {
        // Save message to database or send email
        // This is a placeholder - you would implement your actual contact logic here
        $message = 'Thank you! Your message has been sent successfully.';
        $message_class = 'alert-success';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - GD Edu Tech</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="./css/style.css">
    <!-- Custom JavaScript -->
    <script src="./js/main.js" defer></script>
    <style>
        /* Add custom styles for the contact image */
        .contact-hero-image {
            max-width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: contain;
            animation: float 6s ease-in-out infinite;
            transition: transform 0.3s ease;
            box-shadow: none;
            border: none;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-15px);
            }
            100% {
                transform: translateY(0px);
            }
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <!-- Page Header -->
    <section class="page-header position-relative overflow-hidden">
        <div class="container position-relative py-7">
            <div class="row align-items-center">
                <div class="col-md-7" data-aos="fade-right">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-3">
                            <li class="breadcrumb-item"><a href="index.php" class="text-white-50">Home</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Contact</li>
                        </ol>
                    </nav>
                    <h1 class="text-white display-5 fw-bold mb-3">Contact Us</h1>
                    <p class="text-white-50 lead mb-0">Get in touch with us for any questions or inquiries.</p>
                </div>
                <div class="col-md-5" data-aos="fade-left">
                    <img src="./Images/Others/contact.png" alt="Contact Us" class="contact-hero-image">
                </div>
            </div>
        </div>
        <div class="page-header-shape">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="py-5">
        <div class="container">
            <?php if (!empty($message)): ?>
                <div class="alert <?php echo $message_class; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <div class="row g-5">
                <div class="col-lg-5" data-aos="fade-right">
                    <h2 class="section-heading">Get In Touch</h2>
                    <p class="lead">We're here to help and answer any questions you might have.</p>
                    <p>Please fill out the form, and we'll get back to you as soon as possible.</p>
                    
                    <div class="mt-5">
                        <div class="d-flex mb-4">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-4" style="width: 60px; height: 60px">
                                <i class="bi bi-geo-alt fs-3 text-primary"></i>
                            </div>
                            <div>
                                <h5>Our Location</h5>
                                <p class="text-muted mb-0">123 Education St, Learning City</p>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-4">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-4" style="width: 60px; height: 60px">
                                <i class="bi bi-envelope fs-3 text-primary"></i>
                            </div>
                            <div>
                                <h5>Email Us</h5>
                                <p class="text-muted mb-0">info@gdedutech.com</p>
                                <p class="text-muted mb-0">support@gdedutech.com</p>
                            </div>
                        </div>
                        
                        <div class="d-flex mb-4">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-4" style="width: 60px; height: 60px">
                                <i class="bi bi-phone fs-3 text-primary"></i>
                            </div>
                            <div>
                                <h5>Call Us</h5>
                                <p class="text-muted mb-0">+1 234 567 890</p>
                                <p class="text-muted mb-0">+1 987 654 321</p>
                            </div>
                        </div>
                        
                        <div class="social-links mt-5">
                            <h5>Connect With Us</h5>
                            <div class="d-flex mt-3">
                                <a href="#" class="me-3 p-3 rounded-circle bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="#" class="me-3 p-3 rounded-circle bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-twitter"></i>
                                </a>
                                <a href="#" class="me-3 p-3 rounded-circle bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-linkedin"></i>
                                </a>
                                <a href="#" class="p-3 rounded-circle bg-primary bg-opacity-10 text-primary">
                                    <i class="bi bi-instagram"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-7" data-aos="fade-left">
                    <div class="premium-card p-4 p-md-5">
                        <h3 class="mb-4">Send Us A Message</h3>
                        <form action="contact.php" method="POST">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required>
                                        <label for="name">Your Name*</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Your Email" required>
                                        <label for="email">Your Email*</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject" required>
                                        <label for="subject">Subject*</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating mb-3">
                                        <textarea class="form-control" id="message" name="message" placeholder="Your Message" style="height: 150px" required></textarea>
                                        <label for="message">Your Message*</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" name="contact_submit" class="btn btn-primary btn-lg px-5">
                                        Send Message <i class="bi bi-send ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12" data-aos="fade-up">
                    <div class="premium-card p-0 overflow-hidden">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d387193.30591910525!2d-74.25986630089809!3d40.69714941280076!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew%20York%2C%20NY%2C%20USA!5e0!3m2!1sen!2sin!4v1649226028489!5m2!1sen!2sin" 
                            width="100%" 
                            height="450" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-5 bg-light">
        <div class="container py-5">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="section-heading text-center" data-aos="fade-up">Frequently Asked Questions</h2>
                    <p class="lead text-muted" data-aos="fade-up" data-aos-delay="200">Find answers to common questions about GD Edu Tech</p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto" data-aos="fade-up" data-aos-delay="400">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    How do I enroll in a course?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    To enroll in a course, simply create an account on our platform, browse our course catalog, select the course you're interested in, and click the "Enroll" button. Some courses are free, while others may require payment.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    What payment methods do you accept?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We accept various payment methods including credit/debit cards (Visa, MasterCard, American Express), PayPal, and bank transfers. All transactions are processed securely.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Can I get a refund if I'm not satisfied with a course?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes, we offer a 30-day money-back guarantee for most courses. If you're not satisfied with a course, you can request a refund within 30 days of enrollment, provided you haven't completed more than 25% of the course content.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0 mb-3 shadow-sm">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    Do I get a certificate after completing a course?
                                </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes, upon successful completion of a course, you will receive a certificate of completion that you can share on your resume or LinkedIn profile. Our certificates are recognized by many employers and institutions.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item border-0 shadow-sm">
                            <h2 class="accordion-header" id="headingFive">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    How long do I have access to a course after enrollment?
                                </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Once you enroll in a course, you have lifetime access to the course materials. You can revisit the content as many times as you need, even after you complete the course.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Include footer -->
    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <!-- Back to Top Button -->
    <script src="js/back-to-top.js"></script>
</body>

</html> 