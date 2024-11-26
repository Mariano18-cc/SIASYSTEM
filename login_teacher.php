<?php
// Include the database connection
require_once('db_connection.php');

// Start session for login tracking
session_start();

// Initialize error message variable
$error = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = trim($_POST['email']); // Can be email or username
    $password = trim($_POST['password']);

    // Validate input and password
    if (!empty($input) && !empty($password)) {
        try {
            // First check if the user exists and is active, regardless of position
            if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
                $sql = "SELECT * FROM `employee` WHERE `email` = :input AND `status` = 'Active'";
            } else {
                $sql = "SELECT * FROM `employee` WHERE `username` = :input AND `status` = 'Active'";
            }

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':input', $input, PDO::PARAM_STR);
            $stmt->execute();

            // Fetch the user
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // First check if the user is a teacher
                if ($user['position'] !== 'Teacher') {
                    $error = "Access denied. This portal is for teachers only.";
                }
                // Then check password if user is a teacher
                else if (trim($password) === trim($user['password'])) {
                    // Store user data in session
                    $_SESSION['user_id'] = $user['ID'];
                    $_SESSION['employee_id'] = $user['employee_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['fname'] = $user['fname'];
                    $_SESSION['mname'] = $user['mname'];
                    $_SESSION['lname'] = $user['lname'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['status'] = $user['status'];
                    $_SESSION['position'] = $user['position'];

                    // Redirect to the teacher dashboard
                    header("Location: Teacher/teacher_p.php");
                    exit();
                } else {
                    $error = "Incorrect password. Please try again.";
                }
            } else {
                $error = "Account not found or inactive. Please check your credentials.";
            }
        } catch (PDOException $e) {
            $error = "An error occurred. Please try again later.";
            error_log("Database Error: " . $e->getMessage());
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teachers Portal - Login</title>
    <link rel="stylesheet" href="stylesheet/login.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="panel">
        <div class="panel-header">
            <h2 style="text-align: center;">TEACHERS PORTAL</h2>
            <a href="index.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back</a>
        </div> 
    </div>

    <div class="login-container">
        <div class="login-box">
            <img src="picture/logo.png" alt="Logo" class="logo">
            <?php
            // Display error message if login failed
            if (!empty($error)) {
                echo '<div class="error-message show">' . htmlspecialchars($error) . '</div>';
            }
            ?>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
