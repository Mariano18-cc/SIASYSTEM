<?php
// Include database connection
include "../db_connection.php";

require '../vendor/autoload.php';
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
    <link rel="stylesheet" href="../stylesheet/printr.css">
    <style>
        /* Center the table */
        table {
            width: 80%; /* Adjust table width as needed */
            margin: 20px auto; /* Center the table horizontally */
            border-collapse: collapse; /* Optional: for cleaner borders */
        }

        /* Style for table headers and cells */
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        /* Style for Print PDF button */
        .pdf-button {
            position: absolute;
            right: 10px;
            bottom: 10px;
            padding: 10px 20px;
            background-color: #4CAF50; /* Green color */
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .pdf-button:hover {
            background-color: #45a049; /* Darker green on hover */
        }

        /* Adjust layout of the main content and ensure the button stays at the bottom right */
        #Payslip {
            position: relative; /* Make the container relative for absolute positioning of the button */
        }
    </style>
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

    <!-- Main content -->
    <div class="main">
        <!-- Header -->
        <div class="header">
            <div class="user-info">
                <img src="picture/ex.pic" alt="User Avatar" class="user-avatar">
                <span>cakelyn</span>
            </div>
        </div>

        <!-- Payslip Section -->
        <div id="Payslip">
            <h2>Employee Salary Report</h2>

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
                                    echo htmlspecialchars($employee['fname'] . ' ' . ($employee['mname'] ? $employee['mname'] . ' ' : '') . $employee['lname']); 
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($employee['position']); ?></td>
                            <td><?php echo htmlspecialchars($employee['salary']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Print PDF Button -->
            <form method="GET" action="printr.php">
                <button type="submit" name="generate_pdf" class="pdf-button">Print PDF</button>
            </form>
        </div>
    </div>


</body>
</html>

