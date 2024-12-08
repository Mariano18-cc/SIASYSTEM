<?php
// Include database connection
include "db_connection.php"; // Adjust the path if necessary

// Initialize variables
$employee_name = "";
$employee_email = "";
$employee_id = "";
$employee_found = false;
$message = "";

// Check if the form is submitted for checking employee ID
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['employee_id']) && empty($_POST['action'])) {
    $employee_id = $_POST['employee_id'];

    // Fetch employee details
    $stmt = $conn->prepare("SELECT fname, lname, email FROM employee WHERE employee_id = ?");
    $stmt->bind_param("s", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($employee = $result->fetch_assoc()) {
        $employee_name = $employee['fname'] . ' ' . $employee['lname'];
        $employee_email = $employee['email'];
        $employee_found = true;
    } else {
        $message = "Employee not found.";
    }
    $stmt->close();
}

// Handle time-in and time-out actions
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $employee_id = $_POST['employee_id'];
    $action = $_POST['action'];
    $current_time = date("H:i:s");
    $current_date = date("Y-m-d");
    $workday = date("l");

    if ($action == "time_in") {
        // Check if time-in already exists
        $stmt = $conn->prepare("SELECT * FROM attendance_log WHERE employee_id = ? AND workday = ? AND date = ? AND time_in IS NOT NULL");
        $stmt->bind_param("sss", $employee_id, $workday, $current_date);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "You have already timed in for today.";
        } else {
            // Insert time-in record
            $stmt = $conn->prepare("INSERT INTO attendance_log (employee_id, workday, date, time_in, remarks) VALUES (?, ?, ?, ?, 'On time')");
            $stmt->bind_param("ssss", $employee_id, $workday, $current_date, $current_time);
            if ($stmt->execute()) {
                $message = "Successfully timed in at $current_time.";
            } else {
                $message = "Error timing in.";
            }
        }
        $stmt->close();
    } elseif ($action == "time_out") {
        // Check if time-out already exists
        $stmt = $conn->prepare("SELECT * FROM attendance_log WHERE employee_id = ? AND workday = ? AND date = ? AND time_out IS NOT NULL");
        $stmt->bind_param("sss", $employee_id, $workday, $current_date);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "You have already timed out for today.";
        } else {
            // Update time-out record
            $stmt = $conn->prepare("UPDATE attendance_log SET time_out = ? WHERE employee_id = ? AND workday = ? AND date = ?");
            $stmt->bind_param("ssss", $current_time, $employee_id, $workday, $current_date);
            if ($stmt->execute()) {
                $message = "Successfully timed out at $current_time.";
            } else {
                $message = "Error timing out.";
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Tracker</title>
    <link rel="stylesheet" href="stylesheet/attendance_t.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</head>
<body>
    <header>
        <h1>Attendance Tracker</h1>
        <div id="date-time" class="date-time"></div>
    </header>
    <hr>

    <div class="container check-employee-container">
        <h2 class="section-title">Check Employee ID</h2>
        <div class="form-group">
            <form method="POST" action="attendance_tracker.php">
                <input type="text" id="employee_id" name="employee_id" required placeholder="Enter Employee ID" class="input-field">
                <button type="submit" class="submit-button" style="background-color: #28a745; color: white;">Check ID</button>
            </form>
        </div>
    </div>

    <div class="container employee-details-container" id="employee-details-container" style="display: <?php echo $employee_found ? 'block' : 'none'; ?>;">
        <h2 class="section-title">Employee Details</h2>
        <div class="details">
            <p><strong>Name:</strong> <span id="employee-name"><?php echo $employee_name; ?></span></p>
            <p><strong>Employee ID:</strong> <span id="employee-id"><?php echo $employee_id; ?></span></p>
            <p><strong>Email:</strong> <span id="employee-email">
                <?php 
                    $email_parts = explode('@', $employee_email);
                    $local_part = $email_parts[0];
                    $domain_part = '@' . $email_parts[1];

                    // Masking logic
                    $masked_email = substr($local_part, 0, 1) . '****' . substr($local_part, -1) . $domain_part;
                    echo $masked_email; 
                ?>
            </span></p>

            <!-- Time-In and Time-Out Buttons -->
            <div class="time-buttons-container">
                <form method="POST" action="attendance_tracker.php">
                    <input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>">
                    <button type="submit" name="action" value="time_in" class="time-button">Time In</button>
                    <button type="submit" name="action" value="time_out" class="time-button">Time Out</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    <?php if (!empty($message)): ?>
    <div class="message">
        <p><?php echo $message; ?></p>
    </div>
    <?php endif; ?>

    <script src="javascript/attendance_t.js"></script>
    <script>
        // Clear the container after an action
        const message = "<?php echo $message; ?>";
        if (message.includes("Successfully")) {
            setTimeout(() => {
                document.getElementById('employee-details-container').style.display = 'none';
            }, 2000); // Delay to show message
        }
    </script>
</body>
</html>
