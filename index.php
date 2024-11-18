<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Choose Your Role</title>
    <link rel="stylesheet" href="stylesheet/styles.css" type="text/css">
    <style>
body {
      background-image: url('picture/logo.png'); /* Background image */
      background-size: contain; /* Fit the image in the background */
      background-repeat: no-repeat; 
      background-position: center center; /* Center the image */
      color: #2c3e50; /* Dark text for contrast */
      font-family: Arial, sans-serif;
      background-color: #ffffff; /* Dark blue background */
      text-align: center;
      padding: 50px;
    color: #093157; /* Light text color for contrast */
    overflow: hidden;
}
.main-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}  
h1,h2{
            font-size: 28px;
            margin-bottom: 30px;
            color: #FEF9FE; 
        }
.panel-container {
    background-color: #082C66;
    padding: 40px;
    border-radius: 15px;
    text-align: center;
    width: 80%;
    max-width: 900px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    color: white;
}
.role-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .role-box {
            background-color: #ED8B26; /* Dark teal color for the box */
            color: white;
            border-radius: 8px;
            padding: 40px;
            width: 200px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

.role-box:hover {
            transform: scale(1.05);
            background-color: #093157; /* Dark blue on hover */
        }

.role-box img {
            width: 100px;
            height: 100px;
            margin-bottom: 20px;
        }

.role-box h2 {
            font-size: 18px;
            margin-bottom: 15px;
        }

.role-box p {
            font-size: 14px;
            margin-bottom: 20px;
            color: whitesmoke; 
        }

.role-box a {
            display: inline-block;
            padding: 10px 20px;
            background-color: white;
            color: #1abc9c; 
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

 .role-box a:hover {
            background-color: #34495e; /* Dark blue on hover */
            color: white;
        }

    </style>
</head>
<body>

<div class="main-container">
        <div class="panel-container">
            <h1>Welcome to the Human Resources Management System</h1>
            <h2>Select your role to proceed</h2>

    <div class="role-container">
        <!-- Admin Role -->
        <div class="role-box" onclick="window.location.href='login.php'">
            <h2>Admin</h2>
            <p>Access the HR management features</p>
            <a href="login.php">Login as Admin</a>
        </div>

        <!-- Employee Role -->
        <div class="role-box" onclick="window.location.href='login_emp.php'">
            
            <h2>Employee</h2>
            <p>Access employee-specific resources</p>
            <a href="login_emp.php">Login as Employee</a>
        </div>

        <!-- Portal Role -->
        <div class="role-box" onclick="window.location.href='login_portal.php'">
            
            <h2>Teachers Portal</h2>
            <p>Access the Teachers portal</p>
            <a href="login_portal.php">Go to Portal</a>
        </div>
    </div>
    </div>
</div>

</body>
</html>
