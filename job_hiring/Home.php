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

        /* Navigation Bar */
        .navbar {
            background-color: white;
        }
        .navbar-brand img {
            width: 80px;
            height: 80px;
        }
        .navbar-nav .nav-link {
            color: rgb(63, 138, 236);
            font-family: 'Times New Roman', Times, serif;
            font-size: 1.2rem;
            margin: 0 10px;
        }

        /* Banner Section */
        .banner {
            height: 90vh;
            background: url('../picture/Childred.jpg') center center / cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            position: relative;
        }
        .banner::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4); /* Adds a dark overlay for text readability */
            z-index: 1;
        }
        .banner-content {
            position: relative;
            z-index: 2;
        }
        .banner-content h1 {
            font-size: 3rem;
            font-weight: bold;
            text-transform: uppercase;
        }
        .banner-content p {
            font-size: 1.2rem;
            font-style: italic;
        }

        /* Footer Section */
        footer {
            background-color: #CCCCCC;
            color: white;
            text-align: center;
            padding: 1rem 0;
        }
        .footer-logo img {
            width: 80px;
            height: 80px;
            margin-bottom: 0.5rem;
        }
        .social-icons a {
            color: grey;
            margin: 0 10px;
            font-size: 1.5rem;
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
