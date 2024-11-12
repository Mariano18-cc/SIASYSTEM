<?php
// Include database connection
include "db_connection.php";

require 'vendor/autoload.php'; // Include the library for PDF generation
use Dompdf\Dompdf;
use Dompdf\Options;

// Fetch all employee data from the database
$stmt = $conn->prepare("SELECT employee_id, fname, mname, lname, position, salary FROM employee WHERE status = 'Active'");
$stmt->execute();
$employees = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Handle PDF generation request
if (isset($_GET['generate_pdf'])) {
    $html = "<h1>Employee Salary Report</h1><table border='1' cellpadding='10' cellspacing='0'>";
    $html .= "<tr><th>Employee ID</th><th>Name</th><th>Position</th><th>Salary</th></tr>";

    foreach ($employees as $employee) {
        $fullName = $employee['fname'] . ' ' . ($employee['mname'] ? $employee['mname'] . ' ' : '') . $employee['lname'];
        $html .= "<tr>
                    <td>{$employee['employee_id']}</td>
                    <td>{$fullName}</td>
                    <td>{$employee['position']}</td>
                    <td>{$employee['salary']}</td>
                  </tr>";
    }

    $html .= "</table>";

    // Initialize Dompdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);
    $dompdf = new Dompdf($options);

    // Load HTML content to Dompdf
    $dompdf->loadHtml($html);

    // Set paper size (A4, landscape)
    $dompdf->setPaper('A4', 'landscape');

    // Render PDF (first pass)
    $dompdf->render();

    // Output the generated PDF to the browser
    $dompdf->stream("Employee_Salary_Report.pdf", ["Attachment" => 1]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRMS Print Employee Salary</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="stylesheet/print.css">
</head>
<body>
<div class="dashboard">
    <aside class="sidebar">
      <div class="logo">
        <img src="picture/logo.png" alt="user-info">
      </div>
      <h2>Human Resources</h2>
      <ul style="list-style-type: none; padding-left: 0;">
        <li><a href="Dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="jobp.php" class="active"><i class="fas fa-briefcase"></i> Job Process</a></li>
        <li><a href="employee.php"><i class="fas fa-users"></i> Employee</a></li>
        <li><a href="payroll.php"><i class="fas fa-wallet"></i> Payroll</a></li>
        <li><a href="printr.php"><i class="fas fa-receipt"></i> Print Receipt</a></li>
        <div class="bottom-content"><li><a href="login.php"><i class="fas fa-sign-out-alt"></i><span>Log Out</span></a></li></div>
    </ul>
    </aside>
<!-- Sidebar -->
<div class="sidebar">
    <div class="logo">
      <img src="picture/logo.png" alt="Human Resource">
    </div>
    <h2 style="color: white; text-align: center;">HUMAN RESOURCE</h2>
    <ul style="list-style-type: none; padding-left: 0;">
      <li><a href="Dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
      <li><a href="jobp.php" class="active"><i class="fas fa-briefcase"></i> Job Process</a></li>
      <li><a href="employee.php"><i class="fas fa-users"></i> Employee</a></li>
      <li><a href="payroll.php"><i class="fas fa-wallet"></i> Payroll</a></li>
      <li><a href="printr.php"><i class="fas fa-receipt"></i> Print Receipt</a></li>
      <div class="bottom-content"><li><a href="login.php"><i class="fas fa-sign-out-alt"></i><span>Log Out</span></a></li></div>
    </ul>
  </div>

  <main class="main-content">
  <div class="header">
    <div class="user-info">
      <img src="picture/ex.pic.jpg" alt="Human Resource">
    </div>
  </div>
</main>

        <!-- Payslip Section -->
        <div id="Payslip">
            <h2>Employee Salary Report</h2>
            <table>
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Salary</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($employee['employee_id']); ?></td>
                            <td>
                                <?php
                                    echo htmlspecialchars($employee['fname'] . ' ' . ($employee['mname'] ? $employee['mname'] . ' ' : '') . $employee['lname']);
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($employee['position']); ?></td>
                            <td><?php echo htmlspecialchars($employee['salary']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <form method="GET" action="printr.php">
                <button type="submit" name="generate_pdf" class="pdf-button">Print PDF</button>
            </form>
            </table>
            <!-- Print PDF Button -->
            
        </div>
    </div>
    </div>
</body>
</html>

