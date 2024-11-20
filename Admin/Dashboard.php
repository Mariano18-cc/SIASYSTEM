<?php
session_start();
include "../db_connection.php"; // Ensure this file contains the database connection

// Initialize variables
$jobApplicationsCount = 0;
$inProgressCount = 0;
$teacherCount = 0;
$excellentCount = 0;
$guardCount = 0;

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to login if no user is logged in
    exit();
}

// Retrieve user info from session (assuming user data like email or username is stored in the session)
$user = $_SESSION['user'];

// Query the hradmin table to get the user's full name or any other required info
$stmt = $conn->prepare("SELECT user, email FROM hradmin WHERE user = ? OR email = ?");
$stmt->bind_param("ss", $user, $user);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();

// Check if user exists
if ($userData) {
    $loggedInUser = $userData['user']; // Get the username or email
    // You can also get the user's email or other data if needed
} else {
    echo "User not found!";
    exit();
}

// Query for job application statistics
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM applicant WHERE status = 'new'");
$stmt->execute();
$result = $stmt->get_result();
$jobApplicationsCount = $result->fetch_assoc()['total'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM applicant WHERE status = 'in progress'");
$stmt->execute();
$result = $stmt->get_result();
$inProgressCount = $result->fetch_assoc()['total'];

// Query for employee statistics
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM employee WHERE position = 'Teacher'");
$stmt->execute();
$result = $stmt->get_result();
$teacherCount = $result->fetch_assoc()['total'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM employee WHERE position = 'Excellent'");
$stmt->execute();
$result = $stmt->get_result();
$excellentCount = $result->fetch_assoc()['total'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM employee WHERE position = 'Guard'");
$stmt->execute();
$result = $stmt->get_result();
$guardCount = $result->fetch_assoc()['total'];

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRMS Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css'>
    <link rel='stylesheet' href='https://pro.fontawesome.com/releases/v6.0.0-beta3/css/all.css'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&amp;display=swap'>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo">
        <img src="../picture/logo.png" alt="Human Resource">
    </div>
    <h2 style="color: white; text-align: center;">HUMAN RESOURCE</h2>
    <ul>
        <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="jobp.php" class="active"><i class="fas fa-briefcase"></i> Job Process</a></li>
        <li><a href="employee.php"><i class="fas fa-users"></i> Employee</a></li>
        <li><a href="payroll.php"><i class="fas fa-wallet"></i> Payroll</a></li>
        <li><a href="printr.php"><i class="fas fa-receipt"></i> Print Receipt</a></li>
    </ul>
    <div class="bottom-content">
        <a href="../index.php"><i class="fas fa-sign-out-alt"></i>Log Out</a>
    </div>
</div>

        <main class="main-content">
      <header class="header">
        <div class="user-info">
          <img src="../picture/ex.pic.jpg" alt="User Avatar">
          <span><?php echo htmlspecialchars($loggedInUser); ?></span>
        </div>
      </header>

        <!-- Job Process Panel -->
        <div class="content">
            <div class="panel-container">
                <!-- Job Applications Panel -->
                <a href="jobp.php" style="text-decoration: none;">
                    <div class="panel">
                        <h2>Job Applications</h2>
                        <div class="job-process">
                            <div class="job-card">
                                <i class="fas fa-user-tie"></i>
                                <p>CANDIDATES</p>
                                <h3><?php echo $jobApplicationsCount; ?></h3>
                            </div>
                            <div class="job-card">
                                <i class="fas fa-tasks"></i>
                                <p>IN-PROGRESS</p>
                                <h3><?php echo $inProgressCount; ?></h3>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- Employee Info Panel -->
                <a href="employee.php" style="text-decoration: none;">
                    <div class="panel">
                        <h2>EMPLOYEE</h2>
                        <div class="job-process">
                            <div class="stat-card">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <p>TEACHER</p>
                                <h3><?php echo $teacherCount; ?></h3>
                            </div>
                            <div class="stat-card">
                                <i class="fas fa-award"></i>
                                <p>EXCELLENT</p>
                                <h3><?php echo $excellentCount; ?></h3>
                            </div>
                            <div class="stat-card">
                                <i class="fas fa-shield-alt"></i>
                                <p>GUARD</p>
                                <h3><?php echo $guardCount; ?></h3>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Calendar -->
            <div class="calendar-container">
                <div class="calendar-header">
                    <button id="prev-month">&#10094;</button>
                    <h2 id="month-year"></h2>
                    <button id="next-month">&#10095;</button>
                </div>
                <table class="calendar">
                    <thead>
                        <tr>
                            <th>Sun</th>
                            <th>Mon</th>
                            <th>Tue</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                            <th>Sat</th>
                        </tr>
                    </thead>
                    <tbody id="calendar-body">
                        <!-- Calendar days will be generated here by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
        

    <script src="js/dashboard.js"></script>
 
</body>
</html>
