<?php
// Include the database connection file
include "db_connection.php"; 

// Start session for login tracking
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the login credentials from the POST request
    $input = $_POST['email']; // Can be email or username
    $password = $_POST['password'];

    // Check if the input is a valid email address
    if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
        // If input is email, query using email
        $stmt = $conn->prepare("SELECT `ID`, `user`, `pass`, `email` FROM `hradmin` WHERE `email` = ?");
    } else {
        // If input is not an email, treat it as a username
        $stmt = $conn->prepare("SELECT `ID`, `user`, `pass`, `email` FROM `hradmin` WHERE `user` = ?");
    }

    if ($stmt === false) {
        // Output error if SQL preparation fails
        echo "Error preparing the query: " . $conn->error;
        exit();
    }

    // Bind the input parameter and execute the query
    $stmt->bind_param("s", $input);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows == 1) {
        // Fetch user data
        $row = $result->fetch_assoc();

        // Verify the password (use password_verify if using hashed passwords)
        if ($password == $row['pass']) {
            // If password matches, set session and redirect to dashboard
            $_SESSION['user'] = $input;  // Store email or username
            $_SESSION['user_id'] = $row['ID'];
            header("Location: Admin/dashboard.php"); // Redirect to your dashboard or home page
            exit();
        } else {
            // Incorrect password
            echo "<script>alert('Invalid email or password');</script>";
        }
    } else {
        // User not found
        echo "<script>alert('Invalid email or password');</script>";
    }
}
?>

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
         
            <form id="loginForm" method="POST" action="login.php">
                <input type="text" id="loginEmail" name="email" placeholder="Email or Username" required>
                <div class="password-container">
                    <input type="password" id="loginPassword" name="password" placeholder="Password" required>
                    <i class="fa-solid fa-eye"></i>
                </div>
                <p id="errorMessage" style="color:red; display:none;">Invalid email or password</p>
                <button type="submit">Log In</button>
            </form>
        </div>
    </div>
</body>
</html>

