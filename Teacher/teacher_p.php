<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Teacher Dashboard</title>
  <link rel="stylesheet" href="../stylesheet/teacher_p.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            <li><a href="jobp.php" class="active"><i class="fas fa-briefcase"></i> Job Process</a></li>
            <li><a href="employee.php"><i class="fas fa-users"></i> Employee</a></li>
            <li><a href="payroll.php"><i class="fas fa-wallet"></i> Payroll</a></li>
            <li><a href="printr.php"><i class="fas fa-receipt"></i> Print Receipt</a></li>
        </ul>
        <div class="bottom-content">
            <a href="../login.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>
    </div>
  <div class="main">
    <!-- Header -->
    <div class="header">
        <div class="user-info">
            <img src="picture/ex.pic" alt="User Avatar" class="user-avatar">
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


      <!-- Schedule table section -->
      <div class="schedule-section">
        <h2>Schedule</h2>
        <table>
          <thead>
            <tr>
              <th>Day</th>
              <th>Time</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Mon</td>
              <td></td>
            </tr>
            <tr>
              <td>Tue</td>
              <td></td>
            </tr>
            <tr>
              <td>Wed</td>
              <td></td>
            </tr>
            <tr>
              <td>Thu</td>
              <td></td>
            </tr>
            <tr>
              <td>Fri</td>
              <td></td>
            </tr>
            <tr>
                <td>Sat</td>
                <td></td>
              </tr>
          </tbody>
        </table>
    </div>
  </div>

  <div class="memo-section">
    <h2>Memo/Announcement</h2>
    <p>All employees are requested to complete their end-of-month reports by Friday. Please check your email for further instructions.</p>
  </div>
</div>

  <script src="../javascript/tt_dashboard.js"></script>
</body>
</html>
