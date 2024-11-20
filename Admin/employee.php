<?php
// Include the database connection file
include "../db_connection.php";

// Initialize an array for employees
$results = [];

// Check if there’s a search query and handle AJAX request
if (isset($_GET['ajax']) && isset($_GET['search']) && !empty($_GET['search'])) {
    $search = "%" . $_GET['search'] . "%";
    // Search by first name, last name, or position
    $stmt = $conn->prepare("SELECT ID, employee_id, fname, lname, position, hired_date, status FROM employee WHERE fname LIKE ? OR lname LIKE ? OR position LIKE ?");
    $stmt->bind_param("sss", $search, $search, $search);
} else {
    // Fetch all employees if no search query is provided
    $stmt = $conn->prepare("SELECT ID, employee_id, fname, lname, position, hired_date, status FROM employee");
}

$stmt->execute();
$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// If it’s an AJAX request, return the results as JSON
if (isset($_GET['ajax'])) {
    echo json_encode($results);
    exit();
}

// Handle the employee status update request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['employee_id']) && isset($_POST['new_status'])) {
    $employee_id = $_POST['employee_id'];
    $new_status = $_POST['new_status'];

    // Check if the action is to delete
    if ($new_status == "Delete") {
        // Prepare and execute the delete query
        $delete_stmt = $conn->prepare("DELETE FROM employee WHERE employee_id = ?");
        $delete_stmt->bind_param("s", $employee_id);
        $delete_stmt->execute();
        $delete_stmt->close();
    } else {
        // Update the status if it's not delete
        $update_stmt = $conn->prepare("UPDATE employee SET status = ? WHERE employee_id = ?");
        $update_stmt->bind_param("ss", $new_status, $employee_id);
        $update_stmt->execute();
        $update_stmt->close();
    }

    // Redirect to the page to show updated data
    header("Location: employee.php");
    exit();
}

// Handle the add employee form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_employee'])) {
    // Collect the form data
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    $status = $_POST['status'];

    // Generate a unique employee ID
    $employee_id = "EMP" . rand(1000, 9999);

    // Get today's date for hired_date
    $hired_date = date("Y-m-d");

    // Insert the new employee into the database
    $insert_stmt = $conn->prepare("INSERT INTO employee (employee_id, fname, mname, lname, email, password, hired_date, position, salary, status) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $insert_stmt->bind_param("sssssssssd", $employee_id, $fname, $mname, $lname, $email, $password, $hired_date, $position, $salary, $status);
    
    if ($insert_stmt->execute()) {
        // Redirect or display a success message
        $_SESSION['message'] = "Employee added successfully!";
    } else {
        // Handle the error
        $_SESSION['message'] = "Failed to add employee. Please try again.";
    }

    $insert_stmt->close();

    // Redirect to refresh the page and show the new employee
    header("Location: employee.php");
    exit();
}



// Handle the schedule form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['employee_id']) && isset($_POST['employee_name']) && isset($_POST['time_in']) && isset($_POST['time_out']) && isset($_POST['workday'])) {
    $employee_id = $_POST['employee_id'];
    $employee_name = $_POST['employee_name'];
    $time_in = $_POST['time_in'];
    $time_out = $_POST['time_out'];
    $workday = $_POST['workday'];

    // Insert the schedule data into the attendance_emp table
    $insert_stmt = $conn->prepare("INSERT INTO attendance_emp (employee_id, employee_name, time_in, time_out, workday) VALUES (?, ?, ?, ?, ?)");
    $insert_stmt->bind_param("sssss", $employee_id, $employee_name, $time_in, $time_out, $workday);
    if ($insert_stmt->execute())

    {
        // Redirect or display a success message
        $_SESSION['message'] = "Schedule saved successfully!";
    } else {
        // Handle the error
        $_SESSION['message'] = "Failed to save schedule. Please try again.";
    }
    $insert_stmt->close();

    // Redirect to refresh the page and show the new schedule
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
    <link rel="stylesheet" href="css/employee.css">
</head>
<body>

<?php if (isset($_SESSION['message'])): ?>
        <div class="alert">
            <?php echo $_SESSION['message']; ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

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

    <!-- Main content -->
    <main class="main-content">
        <div class="header">
            <div class="user-info">
                <img src="../picture/ex.pic.jpg" alt="User Avatar" class="user-avatar">
                <span>Cakelyn</span><br>
                <p class="department">Human Resource Admin</p>
            </div>
        </div>

        <!-- Add the centered title here -->
        <div class="page-title">
            <h1>Employee</h1>
        </div>

        <div class="search-container">
            <input type="text" id="search-input" class="search-input" placeholder="Search Employee...">
            <button class="add-button" id="add-button">
                <i class="fas fa-plus"></i> Add
            </button>
        </div>

        <!-- Employee Info Table -->
        <div class="grid-item employee-table">
            <table class="employee-info-table" id="employee-table">
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Hired Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="employee-tbody">
                    <?php foreach ($results as $employee): ?>
                        <tr>
                            <td><?php echo $employee['employee_id']; ?></td>
                            <td><?php echo $employee['fname'] . " " . $employee['lname']; ?></td>
                            <td><?php echo $employee['position']; ?></td>
                            <td><?php echo $employee['hired_date']; ?></td>
                            <td><?php echo $employee['status']; ?></td>
                            <td>
                                <form method="POST" action="employee.php" style="display:inline-block;">
                                    <input type="hidden" name="employee_id" value="<?php echo $employee['employee_id']; ?>">
                                    <select name="new_status" required>
                                        <option value="Active" <?php echo ($employee['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="Inactive" <?php echo ($employee['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                        <option value="Delete">Delete</option>
                                    </select>
                                    <button type="submit" class="update-button">Update</button>
                                </form>
                                <button type="button" class="schedule-button" 
                                    onclick="openScheduleModal('<?php echo $employee['employee_id']; ?>', '<?php echo $employee['fname'] . ' ' . $employee['lname']; ?>')">
                                    Schedule
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

   <!-- Modal for Scheduling Employee -->
<div id="schedule-modal" class="modal">
    <div class="modal-content">
        <span class="close" id="close-schedule-modal">&times;</span>
        <h3>Schedule Employee</h3>
        <form method="POST" action="employee.php" id="schedule-form">
            <label for="schedule-employee-id">Employee ID:</label>
            <input type="text" id="schedule-employee-id" name="employee_id" readonly><br>

            <label for="schedule-employee-name">Employee Name:</label>
            <input type="text" id="schedule-employee-name" name="employee_name" readonly><br>

            <label for="time-in">Time In:</label>
            <input type="time" id="time-in" name="time_in" required><br>

            <label for="time-out">Time Out:</label>
            <input type="time" id="time-out" name="time_out" required><br>

            <label for="workday">Workday:</label>
            <select name="workday" id="workday" required>
                <option value="Monday">Monday</option>
                <option value="Tuesday">Tuesday</option>
                <option value="Wednesday">Wednesday</option>
                <option value="Thursday">Thursday</option>
                <option value="Friday">Friday</option>
                <option value="Saturday">Saturday</option>
                <option value="Sunday">Sunday</option>
            </select><br>

            <button type="submit" class="submit-button">Save Schedule</button>
        </form>
    </div>
</div>

    <script src="js/employee.js"></script>
</body>
</html>

