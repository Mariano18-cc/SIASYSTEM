<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KJCSI</title>
    <style>
        :root {
            --primary-blue: #002B5B;
            --accent-orange: #FF8C00;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #fff;
            font-family: Arial, sans-serif;
        }

        /* Navigation Bar */
        .top-nav {
            background-color: #002B5B;
            padding: 15px 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            text-decoration: none;
        }

        .logo img {
            height: 40px;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }

        .login-btn {
            background-color: #FF8C00;
            padding: 8px 25px;
            border-radius: 20px;
            color: white;
            text-decoration: none;
        }

        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 50px;
        }

        .content-left {
            flex: 1;
        }

        .school-title {
            color: #002B5B;
            font-size: 48px;
            line-height: 1.2;
            margin-bottom: 10px;
        }

        .former-name {
            color: #333;
            font-size: 18px;
            margin-bottom: 30px;
        }

        .application-text {
            margin: 20px 0;
        }

     
        .apply-btn {
            background-color: #002B5B;
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
            min-width: 200px;
        }

        .content-right {
            flex: 1;
            display: flex;
            justify-content: center;
        }

        .school-logo {
            width: 100%;
            max-width: 400px;
        }

        .copyright {
            color: #666;
            font-size: 14px;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .main-content {
                flex-direction: column;
                text-align: center;
            }
            
            .school-title {
                font-size: 36px;
            }

            .email-form {
                margin: 20px auto;
            }
        }

        /* Add these styles to your existing stylesheet */
        .about-us {
            background-color: var(--primary-blue);
            padding: 60px 20px;
            text-align: left;
        }

        .about-us-content {
            max-width: 1200px;
            margin: 0 auto;
            text-align: left;
        }

        .about-us h2 {
            font-size: 24px;
            margin: 30px 0 15px 0;
            color: var(--accent-orange);
        }

        .about-us p {
            margin: 15px 0;
            line-height: 1.6;
            font-size: 16px;
            color: white;
        }

        .about-us ul {
            list-style-type: none;
            padding-left: 0;
            margin: 15px 0;
        }

        .about-us ul li {
            margin-bottom: 10px;
            line-height: 1.6;
            color: white;
        }

        .programs {
            background-color: #002B5B; /* Dark blue background */
            color: white;
            padding: 60px 20px;
            text-align: left; /* Align text to the left */
        }

        .programs-container {
            display: flex; /* Use flexbox for layout */
            justify-content: space-between; /* Space between columns */
        }

        .program-column {
            flex: 1; /* Allow columns to grow equally */
            margin: 0 20px; /* Space between columns */
        }

        /* Adjust heading styles */
        .programs h1 {
            text-align: center; /* Center the main title */
            font-size: 36px; /* Adjust font size */
            margin-bottom: 40px; /* Space below the title */
        }

        .section-title {
            background-color: #0066cc;  /* Blue background */
            color: white;
            padding: 10px 20px;
            font-size: 28px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 30px;
        }

        .main-title {
            color: white;
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 30px;
            font-family: Arial, sans-serif;
        }

        .about-us {
            text-align: center; /* Center the text */
        }

        .image-gallery {
            display: flex; /* Use flexbox for layout */
            justify-content: space-between; /* Space between images */
            margin-top: 20px; /* Space above the gallery */
        }

        .about-image {
            width: 100%; /* Set to 100% for responsive design */
            max-width: 300px; /* Set a max width for images */
            height: auto; /* Maintain aspect ratio */
            border-radius: 8px; /* Optional: rounded corners */
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="top-nav">
        <div class="nav-container">
            <a href="#" class="logo">
                <img src="../picture/logo (4).jpg" alt="KJCSI">
                <span>KJCSI</span>
            </a>
            <div class="nav-links">
                <a href="#">Home</a>
                <a href="#">About us</a>
                <a href="#">Program</a>
                <a href="#">Contact us</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content-left">
            <h1 class="school-title">Kumdan Jungang Christian School Inc.</h1>
            <p class="former-name">(Formerly: Batasan Chunan Christian School)</p>
            <div class="application-text">
                <p>Applications are now open for SY 2024-2025</p>
                <p>Preschool, Elementary & Secondary</p>
            </div>
            <div class="email-form">
                <button class="apply-btn" onclick="location.href='ApplicationForm.php'">APPLY NOW!</button>
            </div>
            <div class="copyright">
                © 2024 by Kumdan Jungang Christian School Inc.
            </div>
        </div>
        <div class="content-right">
            <img src="../picture/AdKUMDAN JUNGANG CHRISTIAN SCHOOL INC..png" alt="School Logo" class="school-logo">
        </div>
    </div>

    <!-- About Us Section -->
    <div class="about-us">
        <div class="about-us-content">
            <h1 class="main-title">About Us</h1>
            <h2>Philosophy</h2>
            <p>The Kumdan Jungang Christian School, Inc. is dedicated to pursuit of knowledge, truth and excellence toward the holistic formation of the human person as a strong foundation for discipline and development. KJCSI is to serve children in the development of their highest potential thus, helping them expand their learning experience through a balance training, intellectually, physically, socially and nurturing faith community rooted in spiritually, which develops transformative Christian life.</p>
            
            <h2>Vision</h2>
            <p>The Kumdan Jungang Christian School, Inc. is committed to provide quality education, moral development and spiritual growth based on the biblical principles to prepare the learner as a role model for useful citizenship through a holistic education, towards academic excellence as a foundation in building a long life worth.</p>
            
            <h2 style="font-size: 28px; margin-bottom: 20px; color: var(--accent-orange);">Mission</h2>
            <p style="margin-bottom: 30px;">The Kumdan Jungang Christian School, Inc. aims to:</p>
            <ul style="list-style-type: decimal; padding-left: 20px;">
                <li>To train up the child in the way he should go; so that when he grows up, he will not depart from it.</li>
                <li>Provide quality and relevant education accessible to all; and</li>
                <li>To bring them up in the training and instructions of the Lord and imbued with the core values of personal integrity.</li>
            </ul>
            <div class="image-gallery">
                <img src="../picture/1.png" alt="Image 1" class="about-image">
                <img src="../picture/about.jpg" alt="About Us" class="about-image">
                <img src="../picture/us.jpg" alt="Us" class="about-image">
            </div>
        </div>
    </div>

    <!-- Programs Section -->
    <div class="programs">
        <h1>Programs</h1>
        <div class="programs-container">
            <div class="program-column">
                <h2>Pre-School & Elementary</h2>
                <ul>
                    <p><li>Physical and emotional well-being</p></li>

                    <p><li>Communication Skills. Education should develop in each learner. The reading, writing, listening, speaking and computing skills necessary for communication as well as ability to think critically and clearly.</p></li>

                    <p><li>The equipping of an individual to be a productive member of his family with upright living for God's glory and service to his fellowmen.</p></li>

                    <p><li>To form desirable habits, values and practice that will enable him to be a model Filipino in his chosen profession.</p></li>
                </ul>
            </div>
            
            <div class="program-column">
                <h2>Secondary</h2>
                <ul>
                    <p><li>To continue to promote the objectives of Elementary Education.</li></p>

                    <p><li>To become mature Christians who are called to live the Gospel that will lead them to a fullness of life in the image and likeness of God.</li><p>

                    <p><li>To obtain knowledge and form desirable attitudes for understanding the nature and goals of man, of one self, his own people interrelationships of all peoples and environment; thus promoting a capable sensitivity to oneself, of family, and of the global communities for peace and unity.</li></p>

                    <p><li>To show appreciation and respect for the Filipino and Christian cultures, as well as other cultures, and thus, bring out a healthy spirit of Patriotism.</li></p>

                    <p><li>To discover and enhance the different aptitudes and interest of the students so as to equip him with skills for productive endeavor and/or prepare him for SHS and tertiary schooling.</li></p>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
