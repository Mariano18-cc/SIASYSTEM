<?php
session_start();
include "../db_connection.php";

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

// Get user info from session
$user = $_SESSION['user'];
$stmt = $conn->prepare("SELECT user, email FROM hradmin WHERE user = ? OR email = ?");
$stmt->bind_param("ss", $user, $user);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();
$stmt->close();

$loggedInUser = isset($userData['user']) ? $userData['user'] : 'Unknown User';

// Handle AJAX request for employee details
if (isset($_GET['action']) && $_GET['action'] === 'get_employee_details') {
    header('Content-Type: application/json');
    
    if (isset($_GET['id'])) {
        try {
            $id = intval($_GET['id']);
            $query = "SELECT id, employee_id, CONCAT(fname, ' ', mname, ' ', lname) as full_name, 
                      email, position, salary as monthly_salary, hired_date, phone_number 
                      FROM employee WHERE id = ?";
            
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }
            
            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                // Calculate deductions
                $salary = floatval($row['monthly_salary']);
                
                // Calculate deductions (you can adjust these values based on your requirements)
                $incomeTax = $salary * 0.12; // 12% income tax
                $philHealth = $salary * 0.035; // 3.5% PhilHealth
                $pagibig = 100; // Fixed Pag-IBIG contribution
                $sss = $salary * 0.045; // 4.5% SSS
                
                // Calculate total deductions and net salary
                $totalDeductions = $incomeTax + $philHealth + $pagibig + $sss;
                $netSalary = $salary - $totalDeductions;
                
                // Add calculations to the response
                $row['deductions'] = [
                    'Income Tax' => number_format($incomeTax, 2),
                    'PhilHealth' => number_format($philHealth, 2),
                    'Pag-IBIG' => number_format($pagibig, 2),
                    'SSS' => number_format($sss, 2)
                ];
                $row['total_deductions'] = number_format($totalDeductions, 2);
                $row['net_salary'] = number_format($netSalary, 2);
                
                echo json_encode($row);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Employee not found']);
            }
            $stmt->close();
        } catch (Exception $e) {
            http_response_code(500);
            error_log("Error in get_employee_details: " . $e->getMessage());
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
        exit();
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'No employee ID provided']);
        exit();
    }
}

// Regular page load - only proceed if not an AJAX request
$query = "SELECT id, employee_id, CONCAT(fname, ' ', mname, ' ', lname) as full_name, 
         position, salary, status 
         FROM employee 
         WHERE status = 'Active'";

// Improve security by using prepared statement for search
if (isset($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    $query .= " AND (employee_id LIKE ? OR CONCAT(fname, ' ', mname, ' ', lname) LIKE ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $search, $search);
    $result = $stmt->execute();
} else {
    $result = $conn->query($query);
}

// Add error handling for database connection
if (!$result) {
    error_log("Database query failed: " . $conn->error);
    die("An error occurred while fetching data. Please try again later.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRMS Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/payroll.css">
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo">
        <img src="../picture/logo.png" alt="Human Resource">
    </div>
    <h2 style="color: white; text-align: center;">HUMAN RESOURCE</h2>
    <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="jobp.php"><i class="fas fa-briefcase"></i> Job Process</a></li>
            <li><a href="employee.php"><i class="fas fa-users"></i> Employee</a></li>
            <li><a href="payroll.php" class="active"><i class="fas fa-wallet"></i> Payroll</a></li>
            <li><a href="attendance.php"><i class="fas fa-clock"></i> Attendance</a></li>
            <li><a href="leave_m.php"><i class="fas fa-envelope-open-text"></i> Leave Management</a></li>
        </ul>
    <div class="bottom-content">
        <a href="../index.php"><i class="fas fa-sign-out-alt"></i>Log Out</a>
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
          <span><?php echo htmlspecialchars($loggedInUser); ?></span>
        </div>
      </header>

        <div id="Payslip" style="display: block;">
            <div class="payroll-summary">
                <div class="summary-card">
                    <h3>Total Payroll</h3>
                    <?php
                    $total_query = "SELECT SUM(salary) as total_payroll FROM employee WHERE status = 'Active'";
                    $total_result = $conn->query($total_query);
                    $total_row = $total_result->fetch_assoc();
                    ?>
                    <p class="amount">₱<?php echo number_format($total_row['total_payroll'], 2); ?></p>
                </div>
                <div class="summary-card">
                    <h3>Total Employees</h3>
                    <?php
                    $count_query = "SELECT COUNT(*) as total_employees FROM employee WHERE status = 'Active'";
                    $count_result = $conn->query($count_query);
                    $count_row = $count_result->fetch_assoc();
                    ?>
                    <p class="amount"><?php echo $count_row['total_employees']; ?></p>
                </div>
            </div>

            <table class="payslip-table">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Salary</th>
                        <th>Pay Period</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['employee_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['position']); ?></td>
                        <td>₱<?php echo number_format($row['salary'], 2); ?></td>
                        <td><?php echo date('M 1-15, Y'); // First half of month ?></td>
                        <td>
                            <button 
                                type="button" 
                                class="view-button" 
                                data-employee-id="<?php echo htmlspecialchars($row['id']); ?>"
                            >
                                View
                            </button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- View Modal -->
        <div id="payrollModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close">&times;</span>
                    <div class="header-content">
                        <h3 id="employeeName">Employee Name</h3>
                        <p id="employeeEmail">employee@email.com</p>
                    </div>
                    <div class="header-buttons">
                        <button class="send-btn">
                            <i class="fas fa-paper-plane"></i> Send Payslip
                        </button>
                        <button class="print-btn">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                    <div class="balance-date">
                        <h2 id="balanceAmount">₱ 0.00</h2>
                        <p id="currentDate">JANUARY 1, 2024</p>
                    </div>
                </div>
                <div class="modal-body">
                    <table id="detailsTable" class="details-table">
                        <!-- Content will be dynamically populated -->
                    </table>
                    <div id="totalSection">
                        <!-- Content will be dynamically populated -->
                    </div>
                </div>
            </div>
        </div>
        
    <script src="js/payroll.js"></script>
</body>
</html>
