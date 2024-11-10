<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Human Resource System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="stylesheet/payroll.css">
</head>
<body>
    <div class="sidebar">
        <div class="logo">
            <h2>HUMAN RESOURCE</h2>
        </div>
        <ul style="list-style-type: none; padding-left: 0;">
            <li><a href="Dashboard.html"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="jobp.html" class="active"><i class="fas fa-briefcase"></i> Job Process</a></li>
            <li><a href="employee.html"><i class="fas fa-users"></i> Employee</a></li>
            <li><a href="payroll.html"><i class="fas fa-wallet"></i> Payroll</a></li>
            <li><a href="printr.html"><i class="fas fa-receipt"></i> Print Receipt</a></li>
            <li><a href="index.html"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
        </ul>

    </div>
    
    <div class="content">
        <div class="header">
            <h2>Payroll</h2>
            <div class="profile">
                <span>OSAMU DAZAI</span><br>
                <span>Human Resource Admin</span>
            </div>
        </div>

        <div class="tabs">
            <button class="tablink active" onclick="openTab(event, 'Payslip')">Payslip</button>
            <button class="tablink" onclick="openTab(event, 'Attendance')">Attendance</button>
        </div>

        <div id="Payslip" class="tabcontent" style="display: block;">
            <div class="payslip-header">
                <button class="print-button">PRINT</button>
                <button class="service-button">CONTACT OF SERVICE</button>
                <button class="plantilla-button">PLANTILLA</button>
                <span class="date">OCTOBER 1 - 15, 2024</span>
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
                        <td><button class="view-button">View</button></td>
                    </tr>
                    <tr>
                        <td><div class="circle orange">JP</div></td>
                        <td>Jay Prince, Pogi</td>
                        <td>Teacher</td>
                        <td>P200</td>
                        <td>P1,200.00</td>
                        <td>P36,000.00</td>
                        <td><button class="view-button">View</button></td>
                    </tr>
                    <tr>
                        <td><div class="circle green">JS</div></td>
                        <td>Jay Prince, Sobrang Pogi</td>
                        <td>Teacher</td>
                        <td>P200</td>
                        <td>P1,200.00</td>
                        <td>P36,000.00</td>
                        <td><button class="view-button">View</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="Attendance" class="tabcontent">
            <h2>Attendance Section</h2>
            <!-- You can add the attendance section here -->
        </div>
    </div>

    <script src="javascript/payroll.js"></script>
</body>
</html>
