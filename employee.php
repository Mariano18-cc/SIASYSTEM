<?php
// Include database connection file
include "db_connection.php";

// Fetch all employees from the database
$results = [];
$stmt = $conn->prepare("SELECT ID, employee_id, fname, lname, position, hired_date, status FROM employee");
$stmt->execute();
$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Check if a form to update status has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['employee_id']) && isset($_POST['new_status'])) {
    $employee_id = $_POST['employee_id'];
    $new_status = $_POST['new_status'];

    // Prepare the update query to update the employee status
    $update_stmt = $conn->prepare("UPDATE employee SET status = ? WHERE employee_id = ?");
    $update_stmt->bind_param("si", $new_status, $employee_id);
    $update_stmt->execute();
    $update_stmt->close();

    // Redirect to refresh the page and show the updated status
    header("Location: employee.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="stylesheet/employee.css">
</head>
<body>

    <!-- Sidebar -->
    <nav class="sidebar"> 
        <header>
            <div class="logo">
                <img src="picture/logo.png">
            </div>
            <h1>HUMAN RESOURCE</h1>
        </header>
         
        <ul>
            <hr>
            <li><a href="Dashboard.php"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="jobp.php"><i class="fas fa-briefcase"></i><span>Job Process</span></a></li>
            <li><a href="employee.php"><i class="fas fa-users"></i><span>Employee</span></a></li>
            <li><a href="payroll.php"><i class="fas fa-wallet"></i><span>Payroll</span></a></li>
            <li><a href="printr.php"><i class="fas fa-receipt"></i><span>Print Receipt</span></a></li>
            <div class="bottom-content"><li><a href="login.html"><i class="fas fa-sign-out-alt"></i><span>Log Out</span></a></li></div>
        </ul>
    </nav>

    <!-- Main content -->
    <div class="main">
        <!-- Header -->
        <div class="header">
            <div class="user-info">
                <img src="picture/ex.pic" alt="User Avatar" class="user-avatar">
                <span>Cakelyn</span><br>
                <p class="department">Human Resource Admin</p>
            </div>
        </div>

        <div class="search-container">
          <input type="text" class="search-input" placeholder="Search Employee...">
          <button class="add-button">Add</button>
      </div>

        <!-- Employee Info Table -->
        <div class="grid-item employee-table">
            <table class="employee-info-table">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>Position</th>
                        <th>Hired Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $employee): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($employee['employee_id']); ?></td>
                        <td><?php echo htmlspecialchars($employee['fname'] . ' ' . $employee['lname']); ?></td>
                        <td><?php echo htmlspecialchars($employee['position']); ?></td>
                        <td><?php echo htmlspecialchars($employee['hired_date']); ?></td>
                        <td><?php echo htmlspecialchars($employee['status']); ?></td>
                        <td>
                            <form method="POST" action="employee.php">
                                <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employee['employee_id']); ?>">
                                <select name="new_status" required>
                                    <option value="Active" <?php echo ($employee['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                    <option value="Inactive" <?php echo ($employee['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                                <button type="submit" class="update-button">Update Status</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="javascript/dashboard.js"></script>
</body>
</html>
