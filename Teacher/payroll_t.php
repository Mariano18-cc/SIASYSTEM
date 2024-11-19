<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll</title>
    <link rel="stylesheet" href="css/payroll_t.css">
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
            <li><a href="stlist.php"><i class="fas fa-users"></i> Student List</a></li>
            <li><a href="gradelist.php"><i class="fas fa-pencil-alt"></i> Grading List</a></li>
            <li><a href="leave.php"><i class="fas fa-envelope-open-text"></i> Leave Request</a></li>
            <li><a href="payroll_t.php"><i class="fas fa-wallet"></i> Payroll</a></li>
        </ul>
        <div class="bottom-content">
            <a href="../index.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>
    </div>

    <div class="main-content">
        <div class="header">
            <h2>Payroll</h2>
            <div class="user-info">
                <img src="../picture/ex.pic.jpg" alt="User Avatar" class="user-avatar">
                <span>cakelyn</span>
            </div>
        </div>

        <div class="content">
            <div class="left-section">
                <div class="payroll-container">
                    <div class="payroll-header">
                        <div class="pay-period">
                            <i class="far fa-calendar"></i>
                            <span>PAY PERIOD: October 1st-31st</span>
                        </div>
                    </div>
                    
                    <div class="payroll-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Pay Period</th>
                                    <th>Total Hours</th>
                                    <th>Overtime</th>
                                    <th>Basic Pay</th>
                                    <th>Net Pay</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>October 2023</td>
                                    <td>160 HRS</td>
                                    <td>24 HRS</td>
                                    <td>₱25,000.00</td>
                                    <td>₱28,500.00</td>
                                    <td><i class="fas fa-eye view-btn"></i></td>
                                </tr>
                                <tr>
                                    <td>September 2023</td>
                                    <td>160 HRS</td>
                                    <td>16 HRS</td>
                                    <td>₱25,000.00</td>
                                    <td>₱27,000.00</td>
                                    <td><i class="fas fa-eye view-btn"></i></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Payslip Details -->
        <div id="payslipModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Payslip Details</h3>
                    <span class="close">&times;</span>
                </div>
                <div class="payslip-details">
                    <!-- Content will be dynamically inserted by JavaScript -->
                </div>
                <div class="modal-footer">
                    <button class="print-btn"><i class="fas fa-print"></i> Print</button>
                    <button class="download-btn"><i class="fas fa-download"></i> Download</button>
                </div>
            </div>
        </div>
    </div>
    

    <script src="js/payroll_t.js"></script>
</body>
</html>
