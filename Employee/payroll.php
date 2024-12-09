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
    <title>Payroll</title>
    <link rel="stylesheet" href="css/payroll.css">
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
        <!-- Header with just user info -->
        <div class="header">
            <div class="user-info">
                <span class="employee-name"><?php echo htmlspecialchars($employee['fname'] . ' ' . $employee['lname']); ?></span>
            </div>
        </div>

        <div class="content">
            <!-- Add Payroll title here -->
            <h2 class="page-title">Payroll</h2>
            
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
    

    <script src="js/payroll.js"></script>
</body>
</html>
