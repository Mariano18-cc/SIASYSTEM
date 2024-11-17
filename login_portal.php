<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers Portal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Reset and Box Sizing */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Helvetica;
            overflow: hidden;
        }

        /* Body Styling */
        body {
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Panel Header */
        .panel-header {
            font-family: Helvetica;
            position: absolute;
            top: 8px;
            left: 16px;
            font-size: 15px;
            color: #082C66;
            padding: 15px;
            width: 83.2rem;
            border-bottom: 2px solid #080808; 
        }

        /* Login Box */
        .login-box {
            background-color: #082C66;
            padding: 60px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 340px;
        }

        /* Logo Styling */
        .logo {
            width: 90px;
            border-radius: 6rem;
            margin-bottom: 3rem;
            align-items: center;
        }

        /* Login Form Styling */
        form {
            display: flex;
            flex-direction: column;
        }

        /* Input Fields Styling */
        input[type="text"],
        input[type="password"] {
            font-family: Helvetica;
            width: 100%;
            padding: 14px;
            margin-bottom: 10px;
            border: none;
            color: white;
            font-weight: bold;
            outline: none;
            border-bottom: 1px solid white;
            background-color: transparent;
            border-bottom: 1px solid #f0f0f0;
        }

        /* Placeholder Styling */
        input[type="text"]::placeholder,
        input[type="password"]::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }

        /* Eye Icon */
        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: white;
        }

        /* Button Styling */
        button {
            padding: 10px;
            margin-top: 2rem;
            background-color: #fff;
            color: #080808;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: Helvetica;
        }

        /* Button Hover Effect */
        button:hover {
            background-color: #b0c4de;
        }

        /* Login Container */
        .login-container {
            text-align: center;
            margin-top: 25px;
        }
    </style>
</head>
<body>
    <!-- Panel Header -->
    <div class="panel-header">
        <h2>Teachers Portal</h2>
    </div>

    <!-- Login Container -->
    <div class="login-container">
        <!-- Login Box -->
        <div class="login-box">
            <!-- Logo Image -->
            <img src="picture/logo.png" alt="Logo" class="logo">
            
            <!-- Login Form -->
            <form id="loginForm" method="POST" action="login.php">
                <input type="text" id="loginEmail" name="email" placeholder="Email or Username" required>
                
                <!-- Password Field with Eye Icon -->
                <div class="password-container" style="position: relative;">
                    <input type="password" id="loginPassword" name="password" placeholder="Password" required>
                    <i class="fa-solid fa-eye eye-icon" onclick="togglePasswordVisibility()"></i>
                </div>

                <!-- Error Message (hidden by default) -->
                <p id="errorMessage" style="color:red; display:none;">Invalid email or password</p>

                <!-- Log In Button -->
                <button type="submit">Log In</button>
            </form>
        </div>
    </div>

    <script>
        // Toggle Password Visibility
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('loginPassword');
            const passwordIcon = document.querySelector('.eye-icon');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                passwordIcon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordField.type = 'password';
                passwordIcon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>
