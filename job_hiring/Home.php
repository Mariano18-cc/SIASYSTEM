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

        .email-form {
            background: white;
            border-radius: 30px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.1);
            padding: 5px;
            display: flex;
            align-items: center;
            margin-top: 20px;
            max-width: 500px;
        }

        .email-icon {
            padding: 10px 15px;
            color: #666;
        }

        .email-input {
            flex: 1;
            border: none;
            outline: none;
            padding: 10px;
            font-size: 16px;
        }

        .apply-btn {
            background-color: #002B5B;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
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
            color: white;
            padding: 60px 20px;
            text-align: center;
            width: 100%;
            display: block;
            position: relative;
        }

        .about-us h2 {
            font-size: 24px;
            margin-bottom: 10px;
            display: block;
        }

        .about-us h1 {
            font-size: 36px;
            margin-bottom: 30px;
            display: block;
        }

        .about-us p {
            max-width: 800px;
            margin: 20px auto;
            line-height: 1.6;
            display: block;
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
                <a href="#">Events</a>
                <a href="#">Personnel</a>
                <a href="#">Contact us</a>
                <a href="#" class="login-btn">Log in</a>
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
                <span class="email-icon">✉️</span>
                <input type="email" placeholder="Enter your email address" class="email-input">
                <button class="apply-btn">APPLY NOW!</button>
            </div>
            <div class="copyright">
                © 2024 by Kumdan Jungang Christian School Inc.
            </div>
        </div>
        <div class="content-right">
            <img src="../picture/logo (4).jpg" alt="School Logo" class="school-logo">
        </div>
    </div>

    <!-- About Us Section -->
    <div class="about-us">
        <h2>ABOUT US</h2>
        <h1>KUMDAN JUNGANG CHRISTIAN SCHOOL</h1>
        
        <p>AT KUMDAN JUNGANG CHRISTIAN SCHOOL, WE ARE DEDICATED TO NURTURING THE MINDS AND HEARTS OF OUR STUDENTS THROUGH A CHRIST-CENTERED EDUCATION. OUR MISSION IS TO INSPIRE ACADEMIC EXCELLENCE WHILE FOSTERING SPIRITUAL GROWTH, CHARACTER DEVELOPMENT, AND COMMUNITY SERVICE.</p>
        
        <p>WE BELIEVE IN CREATING A SUPPORTIVE AND INCLUSIVE ENVIRONMENT WHERE EVERY CHILD CAN THRIVE. WITH A CURRICULUM ROOTED IN BIBLICAL VALUES AND A COMMITMENT TO ACADEMIC ACHIEVEMENT, WE EQUIP STUDENTS WITH THE KNOWLEDGE, SKILLS, AND FAITH TO SUCCEED IN ALL AREAS OF LIFE.</p>
        
        <p>JOIN US ON A JOURNEY OF LEARNING, FAITH, AND DISCOVERY AS WE GUIDE STUDENTS TO BECOME COMPASSIONATE LEADERS AND LIFELONG LEARNERS.</p>
    </div>
</body>
</html>
