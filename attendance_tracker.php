<?php
// Include database connection
include "db_connection.php"; // Adjust the path as necessary

// Initialize variables
$employee_name = "";
$employee_email = "";
$employee_id = "";
$employee_found = false; // New variable to track if employee is found

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['employee_id'])) {
    $employee_id = $_POST['employee_id'];

    // Prepare and execute the query to fetch employee details
    $stmt = $conn->prepare("SELECT fname, lname, email FROM employee WHERE employee_id = ?");
    $stmt->bind_param("s", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if employee exists
    if ($employee = $result->fetch_assoc()) {
        $employee_name = $employee['fname'] . ' ' . $employee['lname'];
        $employee_email = $employee['email'];
        $employee_found = true; // Set to true if employee is found
    } else {
        $employee_name = "Not found";
        $employee_email = "";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Tracker</title>
    <link rel="stylesheet" href="stylesheet/attendance_t.css">
</head>
<body>
    <header>
        <h1>Attendance Tracker</h1>
        <div id="date-time" class="date-time"></div>

    </header>        <hr>


    <div class="container check-employee-container">
        <h2 class="section-title">Check Employee ID</h2>
        <div class="form-group">
            <form method="POST" action="attendance_tracker.php">
                <input type="text" id="employee_id" name="employee_id" required placeholder="Enter Employee ID" class="input-field">
                <button type="submit" class="submit-button">Check ID</button>
            </form>
        </div>
    </div>

    <div class="container employee-details-container">
        <h2 class="section-title">Employee Details</h2>
        <div class="details">
            <p class="no-details-message" style="display: <?php echo $employee_found ? 'none' : 'block'; ?>;">
                No employee details available. Please enter an Employee ID and check.
            </p>
            <div id="employee-info" style="display: <?php echo $employee_found ? 'block' : 'none'; ?>;">
                <p><strong>Name:</strong> <span id="employee-name"><?php echo $employee_name; ?></span></p>
                <p><strong>Employee ID:</strong> <span id="employee-id"><?php echo $employee_id; ?></span></p>
                <p><strong>Email:</strong> <span id="employee-email">
                    <?php 
                        $email_parts = explode('@', $employee_email);
                        $local_part = $email_parts[0]; // Get the local part before the '@'
                        $domain_part = '@' . $email_parts[1]; // Get the domain part

                        // Masking logic
                        $masked_email = substr($local_part, 0, 1) . '****' . substr($local_part, -1) . $domain_part;
                        echo $masked_email; 
                    ?>
                </span></p>
                
                <!-- New Buttons for Time In and Time Out inside Employee Info -->
                <div class="time-buttons-container">
                    <button class="time-button" id="time-in-button">Time In</button>
                    <button class="time-button" id="time-out-button">Time Out</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="javascript/attendance_t.js"></script>  
</body>
</html>