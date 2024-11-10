<?php
// Include the database connection file
include "db_connection.php";

// Initialize an array for employees
$results = [];

// Fetch all employees from the database
$stmt = $conn->prepare("SELECT ID, employee_id, fname, lname, position, hired_date, status FROM employee");
$stmt->execute();
$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Handle the employee status update request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['employee_id']) && isset($_POST['new_status'])) {
    $employee_id = $_POST['employee_id'];
    $new_status = $_POST['new_status'];

    // Prepare the update query
    $update_stmt = $conn->prepare("UPDATE employee SET status = ? WHERE employee_id = ?");
    $update_stmt->bind_param("ss", $new_status, $employee_id); // Correct bind type for employee_id (string)
    $update_stmt->execute();
    $update_stmt->close();

    // Redirect to the page to show updated data
    header("Location: employee.php");
    exit();
}

// Handle the add employee form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_employee'])) {
    // Collect the form data
    $fname = $_POST['fname'];
    $mname = $_POST['mname']; // Middle name is optional
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    $status = $_POST['status'];

    // Generate a unique employee ID (just an example; adjust it based on your needs)
    $employee_id = "EMP" . rand(1000, 9999);

    // Get today's date for hired_date
    $hired_date = date("Y-m-d");

    // Insert the new employee into the database
    $insert_stmt = $conn->prepare("INSERT INTO employee (employee_id, fname, mname, lname, email, hired_date, position, salary, status) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    // Bind parameters with correct types:
    // - 's' for strings (employee_id, fname, mname, lname, email, position, status)
    // - 'd' for double (salary)
    // - 's' for date (hired_date)
    $insert_stmt->bind_param("sssssssd", $employee_id, $fname, $mname, $lname, $email, $hired_date, $position, $salary, $status);
    $insert_stmt->execute();
    $insert_stmt->close();

    // Redirect to refresh the page and show the new employee
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
    <style>
        /* Modal Style */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
        }

        .modal-content {
            background-color: #f9f9f9;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 60%;
            border-radius: 5px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .add-employee-form label {
            display: block;
            margin-bottom: 5px;
        }

        .add-employee-form input,
        .add-employee-form select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .add-employee-form button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .add-employee-form button:hover {
            background-color: #45a049;
        }
    </style>
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
            <button class="add-button" id="add-button">Add</button>
        </div>

        <!-- Modal for Add Employee Form -->
        <div id="add-employee-modal" class="modal">
            <div class="modal-content">
                <span class="close" id="close-modal">&times;</span>
                <h3>Add New Employee</h3>
                <form method="POST" action="employee.php">
                    <label for="fname">First Name:</label>
                    <input type="text" name="fname" required><br>

                    <label for="mname">Middle Name:</label> 
                    <input type="text" name="mname"><br> 

                    <label for="lname">Last Name:</label>
                    <input type="text" name="lname" required><br>

                    <label for="email">Email:</label>
                    <input type="email" name="email" required><br>

                    <label for="position">Position:</label>
                    <input type="text" name="position" required><br>

                    <label for="salary">Salary:</label>
                    <input type="number" name="salary" step="0.01" required><br>

                    <label for="status">Status:</label>
                    <select name="status" required>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select><br>

                    <button type="submit" name="add_employee">Add Employee</button>
                </form>
            </div>
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

<script>
// JavaScript for modal functionality
var modal = document.getElementById("add-employee-modal");
var btn = document.getElementById("add-button");
var closeModal = document.getElementById("close-modal");

// When the user clicks the Add button, open the modal
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
closeModal.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
</script>
<script src="javascript/dashboard.js"></script>
</body>
</html>
