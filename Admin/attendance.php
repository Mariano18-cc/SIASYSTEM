<?php
session_start();
include "../db_connection.php";

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

// Retrieve user info from session
$user = $_SESSION['user'];

// Query the hradmin table to get the user's info
$stmt = $conn->prepare("SELECT user, email FROM hradmin WHERE user = ? OR email = ?");
$stmt->bind_param("ss", $user, $user);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();

// Check if user exists
if ($userData) {
    $loggedInUser = $userData['user'];
} else {
    echo "User not found!";
    exit();
}

// Fetch attendance records from the database
$attendanceQuery = $conn->prepare("
    SELECT al.employee_id, e.fname, e.lname, e.position, al.date, al.time_in, al.time_out, 
           CASE 
               WHEN lr.request_id IS NOT NULL THEN 'ON LEAVE' 
               WHEN al.time_out IS NULL THEN 'LATE' 
               ELSE 'ON TIME' 
           END AS status
    FROM attendance_log al
    JOIN employee e ON al.employee_id = e.employee_id
    LEFT JOIN leave_requests lr ON al.employee_id = lr.employee_id AND al.date BETWEEN lr.start_date AND lr.end_date
");
$attendanceQuery->execute();
$attendanceRecords = $attendanceQuery->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management - HRMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/attendance.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="../picture/logo.png" alt="Human Resource">
        </div>
        <h2 style="color: white; text-align: center;">HUMAN RESOURCE</h2>
        <ul>
            <li><a href="Dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="jobp.php"><i class="fas fa-briefcase"></i> Job Process</a></li>
            <li><a href="employee.php"><i class="fas fa-users"></i> Employee</a></li>
            <li><a href="payroll.php"><i class="fas fa-wallet"></i> Payroll</a></li>
            <li><a href="attendance.php" class="active"><i class="fas fa-clock"></i> Attendance</a></li>
            <li><a href="leave_m.php"><i class="fas fa-envelope-open-text"></i> Leave Management</a></li>
        </ul>
        <div class="bottom-content">
            <a href="../index.php"><i class="fas fa-sign-out-alt"></i>Log Out</a>
        </div>
    </div>

    <main class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="user-info">
                <img src="../picture/ex.pic.jpg" alt="User Avatar">
                <span><?php echo htmlspecialchars($loggedInUser); ?></span>
            </div>
        </header>

        <!-- Main Content -->
        <div class="content">
            <!-- Stats Panel -->
            <div class="panel-container">
                <div class="panel">
                    <h2>Attendance Overview</h2>
                    <div class="attendance-cards">
                        <div class="attendance-stat-card">
                            <i class="fas fa-user-check"></i>
                            <p>PRESENT</p>
                            <h3 id="presentCount">0</h3>
                        </div>
                        <div class="attendance-stat-card">
                            <i class="fas fa-user-times"></i>
                            <p>ABSENT</p>
                            <h3 id="absentCount">0</h3>
                        </div>
                        <div class="attendance-stat-card">
                            <i class="fas fa-clock"></i>
                            <p>LATE</p>
                            <h3 id="lateCount">0</h3>
                        </div>
                        <div class="attendance-stat-card">
                            <i class="fas fa-business-time"></i>
                            <p>ON LEAVE</p>
                            <h3 id="leaveCount">0</h3>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Existing Table -->
                <div class="attendance-table-container">
                    <table class="attendance-table">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Date</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceRecords">
                            <?php while ($row = $attendanceRecords->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['employee_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['fname']) . ' ' . htmlspecialchars($row['lname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['position']); ?></td>
                                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['time_in']); ?></td>
                                    <td><?php echo htmlspecialchars($row['time_out']); ?></td>
                                    <td class="<?php echo htmlspecialchars(strtolower(str_replace(' ', '-', $row['status']))); ?>">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </td>
                                    <td>
                                        <button class="view-btn" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="edit-btn" title="Edit Record">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Add Pagination -->
                <div class="pagination">
                    <button class="page-btn" id="prevPage"><i class="fas fa-chevron-left"></i></button>
                    <span id="pageInfo">Page 1 of 1</span>
                    <button class="page-btn" id="nextPage"><i class="fas fa-chevron-right"></i></button>
                    <select id="rowsPerPage" class="rows-select">
                        <option value="10">10 rows</option>
                        <option value="25">25 rows</option>
                        <option value="50">50 rows</option>
                    </select>
                </div>
            </div>
        </div>
    </main>

    <script src="js/attendance.js"></script>
</body>
</html>
