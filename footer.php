    <!-- footer start   -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 text-center row">
                    <img src="<?php echo $path?>Images/Logos/edutechLogo.png" alt="GD Edu Tech Logo" class="footer-logo col-md-4">
                    <p class="footer-about col-md-6 ">GD Edu Tech is dedicated to providing quality education and resources to students worldwide.</p>
                </div>
                <div class="col-md-4 text-center">
                    <h5>Contact Us</h5>
                    <p>Email: support@gedutech.com</p>
                    <p>Phone: +1 234 567 890</p>
                </div>
                <div class="col-md-4 text-center">
                    <img onclick="window.open('https://intelexsolutions.in')" src="<?php echo $path?>Images/Logos/developed_by.png" alt="Developed by Company" class="footer-dev-logo">
                </div>
            </div>
        </div>
    </footer>

    <!-- ... existing code ... -->

    <style>
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 20px 0;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        .footer-logo {
            width: 150px;
            /* Adjust the size as needed */
            margin-bottom: 10px;
        }

        .footer-about {
            font-size: 0.9rem;
            opacity: 0.8;
            text-align: justify;
        }

        .footer h5 {
            margin-top: 10px;
            font-weight: bold;
        }

        .footer p {
            margin: 5px 0;
        }

        .footer-dev-logo {
            height: 80px;
            /* Adjust the size as needed */
            margin-top: 10px;
            cursor: pointer;

        }

        @media (max-width: 576px) {
            .footer-logo {
                width: 100px; /* Adjust logo size for small devices */
            }

            .footer-dev-logo {
                height: 50px; /* Adjust developer logo size for small devices */
            }

            .footer-about {
                font-size: 0.85rem; /* Slightly smaller font size for better readability */
            }
        }
    </style>

    <!-- footer end  -->
