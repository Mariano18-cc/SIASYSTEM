<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kumdan Jungang Christian School</title>
    <!-- FontAwesome and Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            
        }

        /* Updated Navigation Bar */
        .navbar {
            background-color: #003366; /* Dark blue background */
            padding: 15px 0;
        }
        .navbar-brand img {
            width: 100px; /* Slightly larger logo */
            height: 100px;
            transition: transform 0.3s;
        }
        .navbar-brand img:hover {
            transform: scale(1.05);
        }
        .navbar-nav .nav-link {
            color: white !important;
            font-family: 'Segoe UI', sans-serif;
            font-size: 1.1rem;
            margin: 0 15px;
            position: relative;
            padding: 5px 0;
        }
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: #ff6b00; /* Orange underline */
            left: 0;
            bottom: 0;
            transition: width 0.3s;
        }
        .navbar-nav .nav-link:hover::after {
            width: 100%;
        }

        /* Updated Banner Section */
        .banner {
            height: 100vh;
            background: linear-gradient(rgba(0, 51, 102, 0.7), rgba(0, 51, 102, 0.7)),
                        url('../picture/Childred.jpg') center center / cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .banner::before {
            display: none; /* Remove old overlay */
        }
        .banner-content {
            max-width: 800px;
            padding: 20px;
        }
        .banner-content h1 {
            font-size: 4rem;
            font-weight: 800;
            color: white;
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .banner-content p {
            font-size: 1.5rem;
            color: #ff6b00;
            font-weight: 500;
            font-style: normal;
        }

        /* Updated Footer */
        footer {
            background-color: #003366;
            color: white;
            padding: 2rem 0;
        }
        .footer-logo img {
            width: 120px;
            height: 120px;
            margin-bottom: 1rem;
            transition: transform 0.3s;
        }
        .footer-logo img:hover {
            transform: scale(1.1);
        }
        .social-icons a {
            color: white;
            margin: 0 15px;
            font-size: 1.8rem;
            transition: color 0.3s;
        }
        .social-icons a:hover {
            color: #ff6b00;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#"><img src="../picture/logo (4).jpg" alt="School Logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="../job_hiring/ApplicationForm.php">Apply</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Banner Section -->
    <section class="banner">
        <div class="banner-content">
            <h1>KUMDAN JUNGANG CHRISTIAN SCHOOL</h1>
            <p>"Transforming Lives Through Education: Where Faith Guides Learning and Character Development."</p>
        </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <div class="footer-logo">
            <img src="../picture/logo (4).jpg" alt="School Logo">
        </div>
        <div class="social-icons">
            <a href="https://www.facebook.com/KJCSI/photos_by" target="_blank"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin"></i></a>
        </div>
        <p>Designed by <a href="#" style="color: white; text-decoration: none;">Your Company Name</a></p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYXd023vcM41ckv3NIn8qt68zeefbta5PQvFqNRTjF7EVLzu3Czh58N9v" crossorigin="anonymous"></script>
</body>
</html>
