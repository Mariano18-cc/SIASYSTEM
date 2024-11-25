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
      <li><a href="teacher_p.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="inbox.php"><i class="fas fa-inbox"></i> Inbox</a></li>
        <li><a href="leave.php"><i class="fas fa-envelope-open-text"></i> Leave Request</a></li>
        <li><a href="payroll_t.php"><i class="fas fa-wallet"></i> Payroll</a></li>
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
            <span>cakelyn</span>
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

  <script src="js/employee_p.js"></script>
</body>
</html>
