<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kumdan Jungang Christian School - Login Portal</title>
    <link rel="stylesheet" href="stylesheet/index.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="main-container">
        <div class="panel-container">
            <h1>Welcome to Kumdan Jungang Christian School Management System</h1>
            <h2>Please select your role to continue</h2>

    <div class="role-container">
        <!-- Admin Role -->
        <div class="role-box" onclick="window.location.href='login.php'">
            <i class="fas fa-user-shield"></i>
            <h2>Administrator</h2>
            <p>School administration, HR management, and system settings</p>
            <a href="login.php">Login as Administrator</a>
        </div>

        <!-- Employee Role -->
        <div class="role-box" onclick="window.location.href='login_emp.php'">
            <i class="fas fa-users"></i>
            <h2>Staff Portal</h2>
            <p>Access attendance, payroll, and staff resources</p>
            <a href="login_emp.php">Staff Login</a>
        </div>

        <!-- Portal Role -->
        <div class="role-box" onclick="window.location.href='login_portal.php'">
            <i class="fas fa-chalkboard-teacher"></i>
            <h2>Teachers Portal</h2>
            <p>Access grades, attendance, and teaching resources</p>
            <a href="login_portal.php">Teacher Login</a>
        </div>
    </div>
    </div>
</div>

</body>
</html>
