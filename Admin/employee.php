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
    <link rel="stylesheet" href="../stylesheet/employee.css">
    <style>
        /* Modal Style */
     
        .modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: #fff;
    margin: 10% auto;
    padding: 40px;
    border: 1px solid #ddd;
    width: 50%;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
}

.modal-header {
    text-align: center;
    margin-bottom: 20px;
}

.modal-header h3 {
    font-size: 24px;
    font-weight: bold;
    color: #333;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
}

.add-employee-form label {
    font-size: 16px;
    display: block;
    margin-bottom: 8px;
    color: #555;
}

.add-employee-form input,
.add-employee-form select {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    color: #333;
}

.add-employee-form button {
    padding: 12px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.add-employee-form button:hover {
    background-color: #0056b3;
}

.add-employee-form button:focus {
    outline: none;
}

.modal-footer {
    text-align: right;
    padding-top: 20px;
}

    </style>
</head>
<body>

    <!-- Sidebar -->
    <nav class="sidebar"> 
        <header>
            <div class="logo">
                <img src="../picture/logo.png">
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
            <div class="bottom-content"><li><a href="login.php"><i class="fas fa-sign-out-alt"></i><span>Log Out</span></a></li></div>
        </ul>
    </nav>

    <!-- Main content -->
    <main class="main-content">
        <div class="header">
            <div class="user-info">
                <img src="../picture/ex.pic" alt="User Avatar" class="user-avatar">
                <span>Cakelyn</span><br>
                <p class="department">Human Resource Admin</p>
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
        <div class="modal-header">
            <h3>Add New Employee</h3>
        </div>
        <form method="POST" action="employee.php" class="add-employee-form">
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
                                <form method="POST" action="employee.php">
                                    <input type="hidden" name="employee_id" value="<?php echo $employee['employee_id']; ?>">
                                    <select name="new_status" required>
                                        <option value="Active" <?php echo ($employee['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="Inactive" <?php echo ($employee['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                        <option value="Delete">Delete</option>
                                    </select>
                                    <button type="submit" class="update-button">&nbsp; Update&nbsp; </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script>
        // Modal handling for add employee
        const modal = document.getElementById("add-employee-modal");
        const addButton = document.getElementById("add-button");
        const closeModal = document.getElementById("close-modal");

        addButton.onclick = function() {
            modal.style.display = "block";
        }

        closeModal.onclick = function() {
            modal.style.display = "none";
        }

        // Close modal if user clicks outside of it
        window.onclick = function(event) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }

        // Handle search filtering
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
                                        <option value="Delete">Delete</option>
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
