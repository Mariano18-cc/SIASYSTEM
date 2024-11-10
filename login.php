<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Management System Login</title>
    <link rel="stylesheet" href="stylesheet/login.css" type="text/css">
</head>
<body>
        <div class="panel">
            <div class="panel-header">
                <h2>HUMAN RESOURCES MANAGEMENT SYSTEM</h2>
            </div> 
        </div>

    <div class="login-container">
        <div class="login-box">
            <img src="picture/logo.png" alt="Logo" class="logo">
         
            <form id="loginForm" >
                <input type="email" id="loginEmail" placeholder="Email"  required>
                <div class="password-container">
                    <input type="password" id="loginPassword" placeholder="Password" required>
                 <i class="fa-solid fa-eye"></i>;</i>
                </div>
                <p id="errorMessage" style="color:red; display:none;">Invalid email or password</p>
                <button type="submit">Log In</button>

            </form>
        </div>
    </div>

    <script src="javascript/login.js"></script>
</body>
</html>
