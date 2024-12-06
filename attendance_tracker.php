<?php
require_once('db_connection.php');
$error = "";
session_start();

// Initialize variables
$employeeDetails = [
    'name' => '',
    'employee_id' => '',
    'email' => ''
];
$currentDateTime = date("Y-m-d H:i:s"); // Server-side current date and time

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['check_id'])) {
    $employee_id = $_POST['employee_id'];

    // Prepare and execute the query to get employee details
    $stmt = $conn->prepare("SELECT fname, lname, email FROM employee WHERE employee_id = ?");
    $stmt->bind_param("s", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($employee = $result->fetch_assoc()) {
        // Mask the email
        $maskedEmail = substr($employee['email'], 0, 3) . str_repeat('*', strlen($employee['email']) - 3);
        $employeeDetails = [
            'name' => $employee['fname'] . ' ' . $employee['lname'],
            'employee_id' => $employee_id,
            'email' => $maskedEmail
        ];
    } else {
        $employeeDetails = ['error' => 'Employee not found'];
    }

    $stmt->close();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['time_in'])) {
    $employee_id = $_POST['employee_id'];
    $currentDateTime = date("Y-m-d H:i:s");
    $currentDate = date("Y-m-d");
    $currentTime = date("H:i:s");
    $workday = date("l"); // This will give us the current day of the week
    $defaultTimeOut = '00:00:00'; // Use this as a placeholder for time_out
    $remarks = 'Time-in recorded';

    // Insert the time-in record into the attendance table
    $stmt = $conn->prepare("INSERT INTO attendance (employee_id, workday, date, time_in, time_out, remarks) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param("ssssss", $employee_id, $workday, $currentDate, $currentTime, $defaultTimeOut, $remarks);

    if ($stmt->execute()) {
        $formattedDateTime = date("F j, Y g:i A", strtotime($currentDateTime));
        echo json_encode(['status' => 'success', 'time' => $formattedDateTime]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to execute statement: ' . $stmt->error]);
    }

    $stmt->close();
    exit();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

file_put_contents('debug.log', print_r($_POST, true), FILE_APPEND);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Tracker</title>
    <link rel="stylesheet" href="stylesheet/attendance_t.css"> <!-- Add your CSS file -->
</head>
<body>
<div class="header-container">
        <h1>Attendance Tracker</h1>
        <p><strong>Date and Time:</strong> <span id="current-datetime"><?php echo $currentDateTime; ?></span></p>
    </div>

    <div class="container">
        <div class="search-bar">
            <h2>Check Employee ID</h2>
            <form method="POST" action="attendance_tracker.php">
                <input type="text" name="employee_id" placeholder="Enter Employee ID" required>
                <button type="submit" name="check_id">Check ID</button>
            </form>
        </div>

        <div class="employee-details">
    <h3>Employee Details</h3>
    <?php if (isset($employeeDetails['error'])): ?>
        <p><?php echo $employeeDetails['error']; ?></p>
    <?php else: ?>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($employeeDetails['name']); ?></p>
        <p><strong>Employee ID:</strong> <?php echo htmlspecialchars($employeeDetails['employee_id']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($employeeDetails['email']); ?></p>
        <p><strong>Status:</strong> <span id="statusOutput">Not timed in</span></p>
        <form id="timeInForm">
            <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employeeDetails['employee_id']); ?>">
            <button type="button" id="timeInButton" class="time-in-button">Time In</button>
        </form>
    <?php endif; ?>
</div>
    </div>

    <!-- Optional JavaScript for Live Time Update -->
<script>
    function updateDateTime() {
        const currentDateTimeElem = document.getElementById('current-datetime');
        const now = new Date();
        const formatted = now.toISOString().slice(0, 19).replace('T', ' '); // Format: YYYY-MM-DD HH:mm:ss
        currentDateTimeElem.textContent = formatted;
    }
    setInterval(updateDateTime, 1000); // Update every second

document.addEventListener('DOMContentLoaded', function() {
    const timeInButton = document.getElementById('timeInButton');
    if (timeInButton) {
        timeInButton.addEventListener('click', function(e) {
            e.preventDefault();
            const employeeId = document.querySelector('input[name="employee_id"]').value;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'attendance_tracker.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            document.getElementById('statusOutput').innerText = `Timed in: ${response.time}`;
                            timeInButton.disabled = true;
                        } else {
                            alert('Failed to record time-in: ' + response.message);
                        }
                    } catch (error) {
                        console.error('Error parsing response:', xhr.responseText);
                        alert('An error occurred while processing the response');
                    }
                } else {
                    alert('Failed to record time-in. Server returned status: ' + xhr.status);
                }
            };
            xhr.onerror = function() {
                alert('An error occurred while sending the request');
            };
            xhr.send(`time_in=true&employee_id=${employeeId}`);
        });
    } else {
        console.error('Time In button not found');
    }
});
</script>
</body>
</html>