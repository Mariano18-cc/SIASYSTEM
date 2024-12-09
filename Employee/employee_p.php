<?php
// Add this at the top of the file
session_start();
include "../db_connection.php";

// Check if user is logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: ../index.php");
    exit();
}

// Get employee details
$stmt = $conn->prepare("SELECT fname, lname FROM employee WHERE employee_id = ?");
$stmt->bind_param("s", $_SESSION['employee_id']);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
$stmt->close();

// Get attendance remarks for the logged-in employee
$stmtAttendance = $conn->prepare("SELECT remarks FROM attendance_log WHERE employee_id = ? ORDER BY date DESC LIMIT 1");
$stmtAttendance->bind_param("s", $_SESSION['employee_id']);
$stmtAttendance->execute();
$resultAttendance = $stmtAttendance->get_result();
$attendanceRemarks = $resultAttendance->fetch_assoc();
$stmtAttendance->close();

// Get attendance details for the logged-in employee
$stmtAttendanceDetails = $conn->prepare("SELECT date, time_in, time_out FROM attendance_log WHERE employee_id = ? ORDER BY date DESC");
$stmtAttendanceDetails->bind_param("s", $_SESSION['employee_id']);
$stmtAttendanceDetails->execute();
$resultAttendanceDetails = $stmtAttendanceDetails->get_result();
$attendanceDetails = $resultAttendanceDetails->fetch_all(MYSQLI_ASSOC);
$stmtAttendanceDetails->close();

// Update attendance status display
$attendanceStatusText = isset($attendanceRemarks['remarks']) ? htmlspecialchars($attendanceRemarks['remarks']) : 'No remarks available';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Dashboard</title>
  <link rel="stylesheet" href="css/employee_p.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo">
      <img src="../picture/logo.png" alt="Human Resource">
    </div>
    <h2 style="color: white; text-align: center;">HUMAN RESOURCE</h2>
    <ul style="list-style-type: none; padding-left: 0;">
      <li><a href="employee_p.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="leave.php"><i class="fas fa-envelope-open-text"></i> Leave Request</a></li>
        <li><a href="payroll.php"><i class="fas fa-wallet"></i> Payroll</a></li>
    </ul>
    <div class="bottom-content">
    <a href="../dashboard.php"><i class="fas fa-sign-out-alt"></i>Log Out</a>
        </div>
  </div>

  <div class="main-content">
    <!-- Header -->
    <div class="header">
        <div class="user-info">
            <span class="employee-name"><?php echo htmlspecialchars($employee['fname'] . ' ' . $employee['lname']); ?></span>
            <button class="profile-btn" onclick="openProfileModal()">
                <i class="fas fa-user-cog"></i>
            </button>
        </div>   
    </div>

    <!-- Add this attendance status container below the header -->
    <div class="attendance-container">
        <h2>Attendance Status</h2> <!-- Header for attendance status -->
        <div class="stat-card">
            <i class="fas fa-user-tie"></i> <!-- Icon for attendance status -->
            <p>Current Status</p>
            <h3 id="attendanceStatus"><?php echo $attendanceStatusText; ?></h3> <!-- Updated status -->
            <button id="view-details-btn" onclick="openAttendanceDetailsModal()">View Details</button> <!-- Updated View Details button -->
        </div>
    </div>

    <!-- Add this modal for attendance details -->
    <div id="attendanceDetailsModal" class="modal" style="display: none;">
        <div class="attendance-details-modal">
            <span class="close" onclick="closeAttendanceDetailsModal()">&times;</span>
            
            <h2>Attendance Details</h2>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                    </tr>
                </thead>
                <tbody id="attendanceDetailsBody">
                    <?php foreach ($attendanceDetails as $detail): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($detail['date']); ?></td>
                            <td><?php echo htmlspecialchars($detail['time_in']); ?></td>
                            <td><?php echo htmlspecialchars($detail['time_out']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- End of attendance details modal -->

    <div class="content">

      <!-- Calendar section -->
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


  <div class="memo-section">
    <h2>Memo/Announcement</h2>
    <p>All employees are requested to complete their end-of-month reports by Friday. Please check your email for further instructions.</p>
  </div>

  <div id="profileModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="profile-header">
            <h2><?php echo htmlspecialchars($employee['fname'] . ' ' . $employee['lname']); ?></h2>
        </div>
        
        <form id="profileForm">
            <div class="profile-details">
                <div class="detail-group">
                    <label>Employee ID:</label>
                    <input type="text" value="<?php echo htmlspecialchars($_SESSION['employee_id']); ?>" disabled>
                </div>
                <div class="detail-group">
                    <label>Email:</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($employee['email'] ?? ''); ?>" disabled>
                </div>
                <div class="detail-group">
                    <label>Contact:</label>
                    <input type="tel" name="contact" value="<?php echo htmlspecialchars($employee['contact'] ?? ''); ?>" disabled>
                </div>
                <div class="detail-group">
                    <label>Address:</label>
                    <textarea name="address" disabled><?php echo htmlspecialchars($employee['address'] ?? ''); ?></textarea>
                </div>
            </div>
            
            <div class="profile-actions">
                <button type="button" class="edit-profile-btn" onclick="toggleEdit()">Edit Profile</button>
                <button type="submit" class="save-profile-btn" style="display: none;">Save Changes</button>
                <button type="button" class="cancel-edit-btn" style="display: none;" onclick="cancelEdit()">Cancel</button>
            </div>
        </form>
    </div>
</div>

  <script src="js/employee_p.js"></script>
</body>
</html>
