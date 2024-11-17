<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRMS Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../stylesheet/payroll.css">
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
        <a href="../login.php"><i class="fas fa-sign-out-alt"></i>Log Out</a>
    </div>
</div>

        <main class="main-content">
      <header class="header">
        <div class="search-container">
            <button class="search-button">
            </button>
            <input type="text" class="search-input" placeholder="Search...">
        </div>
        <div class="user-info">
          <img src="../picture/ex.pic.jpg" alt="User Avatar">

         
        </div>
      </header>

        <div class="tabs">
            <button class="tablink active" onclick="openTab(event, 'Payslip')">Payslip</button>
            <button class="tablink" onclick="openTab(event, 'Attendance')">Attendance</button>
        </div>

        <div id="Payslip" class="tabcontent" style="display: block;">
            <div class="payslip-header">
                <button class="print-button">PRINT</button>
                <button class="service-button">CONTACT OF SERVICE</button>
                <button class="plantilla-button">PLANTILLA</button>
    </div>
     <!-- Date Container -->
     <div class="date-container">
        <p>Today's Date is:</p>
        <div id="current-date"></div>
            </div>
            <table class="payslip-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Minimum Rate</th>
                        <th>Daily Rate</th>
                        <th>Salary</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><div class="circle red">JP</div></td>
                        <td>Mangmang, Jay Prince</td>
                        <td>Teacher</td>
                        <td>P200</td>
                        <td>P1,200.00</td>
                        <td>P36,000.00</td>
                        <td><button class="openModalBtn view-button">View</button></td>
                    </tr>
                    <tr>
                        <td><div class="circle orange">JP</div></td>
                        <td>Jay Prince, Pogi</td>
                        <td>Teacher</td>
                        <td>P200</td>
                        <td>P1,200.00</td>
                        <td>P36,000.00</td>
                        <td><button class="openModalBtn view-button">View</button></td>
                    </tr>
                    <tr>
                        <td><div class="circle green">JS</div></td>
                        <td>Jay Prince, Sobrang Pogi</td>
                        <td>Teacher</td>
                        <td>P200</td>
                        <td>P1,200.00</td>
                        <td>P36,000.00</td>
                        <td><button class="openModalBtn view-button">View</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="datetime"></div>
    
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Time of Arrival</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="attendanceRecords">
            <tr onclick="showAttendanceHistory('John Doe')">
                <td class="employee-name">John Doe</td>
                <td>08:55:00 AM</td>
                <td class="ontime">On Time</td>
            </tr>
            <tr onclick="showAttendanceHistory('Jane Smith')">
                <td class="employee-name">Jane Smith</td>
                <td>09:05:00 AM</td>
                <td class="late">Late</td>
            </tr>
            <tr onclick="showAttendanceHistory('Alex Brown')">
                <td class="employee-name">Alex Brown</td>
                <td>08:45:00 AM</td>
                <td class="ontime">On Time</td>
            </tr>
        </tbody>
     <!-- Modal to display attendance history -->
     <div id="attendanceHistoryModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeHistoryModal()">&times;</span>
            <h3>Attendance History for <span id="employeeName"></span></h3>
            <table id="historyTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time of Arrival</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Attendance history will appear here -->
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
        <div id="modal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="modal-header">
                    <div class="header-content">
                        <h3>MANGMANG, JAY PRINCE T.</h3>
                        <p>mangmangjayprince@gmail.com</p>
                    </div>
                    <div class="header-buttons">
                        <button class="send-btn">Send</button>
                        <button class="print-btn">Print</button>
                    </div>
                    <div class="balance-date">
                        <h2 class="balance">₱ 36,000.00</h2>
                        <p class="date">OCTOBER 15, 2024</p>
                    </div>
                </div>
                <div class="modal-body">
                    <table class="details-table">
                        <tr>
                            <td class="label">Earnings</td>
                            <td class="value">₱ 10,000.00</td>
                        </tr>
                        <tr>
                            <td class="label">Deduction</td>
                            <td class="value">₱ 2,000.00</td>
                        </tr>
                        <tr>
                            <td class="label">Tax</td>
                            <td class="value">₱ 1,500.00</td>
                        </tr>
                        <tr>
                            <td class="label">Others</td>
                            <td class="value">₱ 500.00</td>
                        </tr>
                    </table>
                    <div class="total">
                        <p>Total:₱ 7,000.00</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="Attendance" class="tabcontent">
            <h2>Attendance Section</h2>
            <!-- You can add the attendance section here -->
        </div>

    <script src="../javascript/payroll.js"></script>
</body>
</html>
