<?php
// Include database connection
include "../db_connection.php";

require '../vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Add date formatting function
function formatCurrency($amount) {
    return '₱ ' . number_format($amount, 2);
}

// Modify the SQL query to remove date_hired
$query = "SELECT employee_id, fname, mname, lname, position, salary 
          FROM employee 
          WHERE status = 'Active'";

// Remove the date filtering for now since the column doesn't exist
$stmt = $conn->prepare($query);
$stmt->execute();
$employees = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Handle PDF generation request
if (isset($_GET['generate_pdf'])) {
    $html = "<h1>Employee Salary Report</h1>";
    $html .= "<table border='1' cellpadding='10' cellspacing='0'>";
    $html .= "<tr><th>Employee ID</th><th>Name</th><th>Position</th><th>Salary</th></tr>";

    foreach ($employees as $employee) {
        $fullName = $employee['fname'] . ' ' . ($employee['mname'] ? $employee['mname'] . ' ' : '') . $employee['lname'];
        $html .= "<tr>
                    <td>{$employee['employee_id']}</td>
                    <td>{$fullName}</td>
                    <td>{$employee['position']}</td>
                    <td>" . formatCurrency($employee['salary']) . "</td>
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

    // Render PDF
    $dompdf->render();

    // Output the generated PDF
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
  <link rel="stylesheet" href="css/printr.css">

</head>
<body>
    <!-- Sidebar -->
    
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
            <a href="../index.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>
</div>

    <!-- Main content -->
    <div class="main">
        <!-- Header -->
        <div class="header">
            <div class="user-info">
                <img src="../picture/ex.pic.jpg" alt="User Avatar" class="user-avatar">
                <span>cakelyn</span>
            </div>
        </div>

        <!-- Payslip Section -->
        <div id="Payslip">
            <h2>Employee Salary Report</h2>

            <!-- Add date filter section -->
            <div class="filter-section">
                <form method="GET" action="printr.php" class="date-filter">
                    <div class="date-inputs">
                        <label>From: <input type="date" name="start_date" value="<?php echo $_GET['start_date'] ?? ''; ?>"></label>
                        <label>To: <input type="date" name="end_date" value="<?php echo $_GET['end_date'] ?? ''; ?>"></label>
                        <button type="submit" class="filter-button">Filter</button>
                    </div>
                </form>
            </div>

            <!-- Table displaying employee salary information -->
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
                                    echo htmlspecialchars($employee['fname'] . ' ' . 
                                         ($employee['mname'] ? $employee['mname'] . ' ' : '') . 
                                         $employee['lname']); 
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($employee['position']); ?></td>
                            <td><?php echo formatCurrency($employee['salary']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Print PDF Button -->
            <div class="bottom-content">
                <form method="GET" action="printr.php">
                    <!-- Pass the date range to PDF generation if set -->
                    <?php if (isset($_GET['start_date']) && isset($_GET['end_date'])): ?>
                        <input type="hidden" name="start_date" value="<?php echo htmlspecialchars($_GET['start_date']); ?>">
                        <input type="hidden" name="end_date" value="<?php echo htmlspecialchars($_GET['end_date']); ?>">
                    <?php endif; ?>
                    <button type="submit" name="generate_pdf" class="pdf-button">Print PDF</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>