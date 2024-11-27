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
            <a href="../index.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>
  </div>

  <div class="main-content">
    <!-- Header -->
    <div class="header">
        <div class="user-info">
            <img src="../picture/ex.pic.jpg" alt="User Avatar" class="user-avatar">
            <span class="employee-name"><?php echo htmlspecialchars($employee['fname'] . ' ' . $employee['lname']); ?></span>
            <button class="profile-btn" onclick="openProfileModal()">
                <i class="fas fa-user-cog"></i>
            </button>
        </div>   
    </div>

    <!-- Content -->
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

<!-- Schedule section -->
<!-- Add this where you want the schedule to appear -->
<div class="schedule-container">
  <table class="schedule-table">
    <thead>
      <tr>
        <th>Day</th>
        <th>Time</th>
      </tr>
    </thead>
    <tbody id="scheduleBody">
      <!-- JavaScript will populate this -->
    </tbody>
  </table>
</div>

</div>


  <div class="memo-section">
    <h2>Memo/Announcement</h2>
    <p>All employees are requested to complete their end-of-month reports by Friday. Please check your email for further instructions.</p>
  </div>

  <div id="profileModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div class="profile-header">
            <div class="avatar-container">
                <img src="../picture/ex.pic.jpg" alt="Profile Picture" id="avatarPreview" class="profile-avatar">
                <div class="avatar-overlay">
                    <label for="avatarInput" class="avatar-upload-btn">
                        <i class="fas fa-camera"></i>
                    </label>
                    <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display: none;">
                </div>
            </div>
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
