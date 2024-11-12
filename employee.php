<?php
// Include the database connection file
include "db_connection.php";

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

    // Prepare the update query
    $update_stmt = $conn->prepare("UPDATE employee SET status = ? WHERE employee_id = ?");
    $update_stmt->bind_param("ss", $new_status, $employee_id);
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
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    $status = $_POST['status'];

    // Generate a unique employee ID
    $employee_id = "EMP" . rand(1000, 9999);

    // Get today's date for hired_date
    $hired_date = date("Y-m-d");

    // Insert the new employee into the database
    $insert_stmt = $conn->prepare("INSERT INTO employee (employee_id, fname, mname, lname, email, hired_date, position, salary, status) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
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
    /* Modal Background */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Fixed position to stay on screen */
    z-index: 9999; /* Ensure it's on top of other content */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); 
    overflow: auto; /* Allows scrolling if content is long */
}

/* Modal Content Box */
.modal-content {
    background-color: #082C66; 
    color: white; 
    margin: 10% auto; /* Center modal */
    padding: 20px;
    border-radius: 8px;
    width: 600px;
    max-width: 90%; 
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    position: relative; 
    top: 50%;
    transform: translateY(-50%);
    overflow-y: auto; /* Allows vertical scrolling if content is too tall */
    max-height: 80%; 
}

/* Close Button */
.modal-content .close {
    color: white;
    font-size: 24px;
    font-weight: bold;
    position: absolute; 
    top: 10px;
    right: 10px;
    cursor: pointer;
}

.modal-content .close:hover {
    color: #ddd; /* Change color on hover */
}

/* Form Styling */
.modal-content h3 {
    margin-top: 0;
    text-align: center;
    font-size: 24px;
}

.modal-content label {
    display: block;
    margin: 15px 0 5px;
    font-weight: bold;
}

.modal-content input[type="text"],
.modal-content input[type="email"],
.modal-content input[type="number"],
.modal-content select {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: none;
    border-radius: 5px;
    box-sizing: border-box;
}

.modal-content button {
    width: 100%;
    padding: 12px;
    background-color: #0B3C7E; 
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.modal-content button:hover {
    background-color: #0A326A; 
}

</style>
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

    <!-- Main content -->
    <main class="main-content">
        <div class="header">
            <div class="user-info">
                <img src="picture/ex.pic.jpg" alt="User Avatar" class="user-avatar">
                <p>Cakelyn<br><small>Human Resources</small></p>
            </div>
        </div>

        <div class="search-container">
                        
            <input type="text" id="search-input" class="search-input" placeholder="Search Employee..." > 
            <button class="add-button" id="add-button">
            <i class="fas fa-plus"></i> Add
        </div>
        <h1 style="font-size: 24px; font-weight: bold; color: #082C66; margin-bottom: 10px; text-align: left;">Employee</h1>
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
                    <select name="position" id="position" required>
                        <option value="">Select a position</option>
                        <option value="Teacher">Teacher</option>
                        <option value="Guard">Guard</option>
                        <option value="Excellent">Excellent</option>
                    </select><br>

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
            <table class="employee-info-table" id="employee-table">
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
                <tbody id="employee-tbody">
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
    </main>

    <!-- JavaScript for Modal and Real-Time Search -->
    <script>
        // Modal functionality
        const addButton = document.getElementById("add-button");
        const modal = document.getElementById("add-employee-modal");
        const closeModal = document.getElementById("close-modal");

        addButton.onclick = function() {
            modal.style.display = "block";
        }

        closeModal.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // JavaScript for real-time search
        document.getElementById("search-input").addEventListener("keyup", function() {
            const query = this.value;

            // Make an AJAX request to fetch filtered employees
            fetch("employee.php?ajax=1&search=" + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById("employee-tbody");
                    tbody.innerHTML = ""; // Clear existing rows

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
                                    </select>
                                    <button type="submit" class="update-button">Update Status</button>
                                </form>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                });
        });
    </script>
</body>
</html>
