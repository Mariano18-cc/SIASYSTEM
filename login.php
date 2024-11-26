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
        
        // Verify the password - using trim() to remove any whitespace
        if (trim($password) === trim($row['pass'])) {
            // Password matches - set session and redirect
            $_SESSION['user'] = $row['user'];  // Store username instead of input
            $_SESSION['user_id'] = $row['ID'];
            header("Location: Admin/dashboard.php");
            exit();
        } else {
            // Incorrect password - don't show detailed error in production
            $error = "Invalid email or password";
        }
    } else {
        // User not found - don't show detailed error in production
        $error = "Invalid email or password";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="panel">
        <div class="panel-header">
            <h2 style="text-align: center;">HUMAN RESOURCES MANAGEMENT SYSTEM</h2>
            <a href="index.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div> 
    </div>

    <div class="login-container">
        <div class="login-box">
            <img src="picture/logo.png" alt="Logo" class="logo">
            <?php
            // Display error message if login failed
            if (isset($_POST['email']) && isset($_POST['password'])) {
                echo '<div class="error-message show">Invalid email or password</div>';
            }
            ?>
            <form method="POST" action="login.php">
                <input type="text" id="loginEmail" name="email" placeholder="Email or Username" required>
                <div class="password-container">
                    <input type="password" id="loginPassword" name="password" placeholder="Password" required>
                    <button type="button" id="showPassword" class="show-password-btn">Show</button>
                </div>
                <button type="submit">Log In</button>
            </form>
        </div>
    </div>

    <script src="javascript/login.js"></script>
</body>
</html>

