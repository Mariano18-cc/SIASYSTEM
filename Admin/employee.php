<?php
// Start the session and include database connection
session_start();
include "../db_connection.php";

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

// Get user info from session
$user = $_SESSION['user'];

// Query the hradmin table to get the user's info
$stmt = $conn->prepare("SELECT user, email FROM hradmin WHERE user = ? OR email = ?");
$stmt->bind_param("ss", $user, $user);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();

// Set logged in user
$loggedInUser = $userData ? $userData['user'] : 'Admin User';

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
        $_SESSION['schedule_success'] = "Schedule saved successfully!";
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
<style>
    .schedule-button {
  background-color:transparent;
  color:  #082C66;
  border: none;
  padding: 5px 10px;
  cursor: pointer;
  font-size: 14px;
  margin-left: 5px;
}
.schedule-button:hover {
  background-color: #082C66;
  color: white;
}
/* Modal background overlay */
.modal {
    display: none; 
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5); 
}

/* Modal content */
.modal-content {
    position: relative;
    background-color:#082C66;
    margin: auto;
    padding: 20px;
    border-radius: 5px;
    width: 80%;
    max-width: 500px;
    top: 20%;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

/* Close button styling */
.close {
    position: absolute;
    top: 10px;
    right: 20px;
    color:FEF9FE ;
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: #082C66;
    text-decoration: none;
}

/* Label and input styling */
.modal-content label {
    font-weight: bold;
    display: block;
    margin-top: 10px;
}

.modal-content input[type="text"],
.modal-content input[type="time"] {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

/* Submit button styling */
.modal-content .modal-footer button {
    background-color: #082C66;
    color: #fff; /* Set the text color to white */
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
}

.modal-content .modal-footer button:hover {
    background-color: #0A1E50;
}

.alert {
    background-color: #4CAF50; /* Green */
    color: white;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 5px;
    text-align: center;
}


</style>
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
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="jobp.php"><i class="fas fa-briefcase"></i> Job Process</a></li>
            <li><a href="employee.php"><i class="fas fa-users"></i> Employee</a></li>
            <li><a href="payroll.php"><i class="fas fa-wallet"></i> Payroll</a></li>
            <li><a href="attendance.php"><i class="fas fa-clock"></i> Attendance</a></li>
            <li><a href="leave_m.php"><i class="fas fa-envelope-open-text"></i> Leave Management</a></li>
        </ul>
        <div class="bottom-content">
            <a href="../index.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>
    </div>

    <!-- Main content -->
    <main class="main-content">
      <header class="header">
        <div class="user-info">
          <img src="../picture/ex.pic.jpg" alt="User Avatar">
          <span><?php echo htmlspecialchars($loggedInUser); ?></span>
        </div>
      </header>


        <div class="search-container">
            <input type="text" id="search-input" class="search-input" placeholder="Search Employee...">
            <button class="add-button" id="add-button">
                <i class="fas fa-plus"></i> Add
            </button>
        </div>

        <h1 style="font-size: 24px; font-weight: bold; color: #082C66; margin-bottom: 10px; text-align: left;">Employee</h1>

        <!-- Modal for Add Employee Form -->
        <div id="add-employee-modal" class="modal">
            <div class="modal-content">
                <span class="close" id="close-modal">&times;</span>
                <h3>Add New Employee</h3>
                <form method="POST" action="employee.php" class="add-employee-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fname">First Name</label>
                            <input type="text" name="fname" required>
                        </div>
                        <div class="form-group">
                            <label for="mname">Middle Name</label>
                            <input type="text" name="mname">
                        </div>
                        <div class="form-group">
                            <label for="lname">Last Name</label>
                            <input type="text" name="lname" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="text" name="password" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="position">Position</label>
                            <select name="position" id="position" required>
                                <option value="">Select a position</option>
                                <option value="Teacher">Teacher</option>
                                <option value="Guard">Guard</option>
                                <option value="Excellent">Excellent</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="salary">Salary</label>
                            <input type="number" name="salary" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="add_employee">Add Employee</button>
                    </div>
                </form>
            </div>
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
                                    <button type="submit" class="update-button">&nbsp; Update&nbsp; </button>
                                </form>
                                <button type="button" class="schedule-button" 
                                    onclick="openScheduleModal('<?php echo $employee['employee_id']; ?>', '<?php echo $employee['fname'] . ' ' . $employee['lname']; ?>')">
                                    &nbsp; Schedule&nbsp;
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

    <script>
document.addEventListener("DOMContentLoaded", function () {
    // Handle the "Add Employee" modal
    const addEmployeeModal = document.getElementById("add-employee-modal");
    const addButton = document.getElementById("add-button");
    const closeAddModal = document.getElementById("close-modal");

    addButton.onclick = function () {
        console.log("Add button clicked"); // Debugging line
        addEmployeeModal.style.display = "block";
    };

    closeAddModal.onclick = function () {
        addEmployeeModal.style.display = "none";
    };

    window.onclick = function (event) {
        if (event.target === addEmployeeModal) {
            addEmployeeModal.style.display = "none";
        }
    };

    // Handle the "Schedule Employee" modal
    const scheduleModal = document.getElementById("schedule-modal");
    const closeScheduleModal = document.getElementById("close-schedule-modal");

    // Function to open the "Schedule Employee" modal
    window.openScheduleModal = function (employeeId, employeeName) {
        document.getElementById("schedule-employee-id").value = employeeId;
        document.getElementById("schedule-employee-name").value = employeeName;
        scheduleModal.style.display = "block";
    };

    closeScheduleModal.onclick = function () {
        scheduleModal.style.display = "none";
    };

    window.onclick = function (event) {
        if (event.target === scheduleModal) {
            scheduleModal.style.display = "none";
        }
    };

    // Handle search functionality
    const searchInput = document.getElementById("search-input");
    const employeeTbody = document.getElementById("employee-tbody");

    searchInput.addEventListener("keyup", function () {
        const query = this.value.trim();

        // Make an AJAX request to fetch filtered employees
        fetch("employee.php?ajax=1&search=" + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                employeeTbody.innerHTML = ""; // Clear existing rows

                // Populate table with new search results
                data.forEach(employee => {
                    const row = document.createElement("tr");

                    row.innerHTML = `
                        <td>${employee.employee_id}</td>
                        <td>${employee.fname} ${employee.lname}</td>
                        <td>${employee.position}</td>
                        <td>${employee.hired_date}</td>
                        <td>${employee.status}</td>
                        <td>
                            <form method="POST" action="employee.php">
                                <input type="hidden" name="employee_id" value="${employee.employee_id}">
                                <select name="new_status" required>
                                    <option value="Active" ${employee.status === 'Active' ? 'selected' : ''}>Active</option>
                                    <option value="Inactive" ${employee.status === 'Inactive' ? 'selected' : ''}>Inactive</option>
                                    <option value="Delete">Delete</option>
                                </select>
                                <button type="submit" class="update-button">Update Status</button>
                            </form>
                            <button type="button" class="schedule-button" onclick="openScheduleModal('${employee.employee_id}', '${employee.fname} ${employee.lname}')">
                                Schedule
                            </button>
                        </td>
                    `;
                    employeeTbody.appendChild(row);
                });
            })
            .catch(error => console.error("Error fetching employee data:", error));
    });
});

    
</script>
</body>
</html>

