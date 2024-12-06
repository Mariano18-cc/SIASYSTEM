<?php
require_once('db_connection.php');
$error = "";
session_start();

if (isset($_POST['check_employee'])) {
    $employeeInput = trim($_POST['employeeInput']);
    
    // Static employee data
    $employees = [
        'EMP001' => [
            'employee_id' => 'EMP001',
            'fname' => 'John',
            'lname' => 'Doe',
            'position' => 'Manager',
            'scheduled_time_in' => '09:00:00',
            'scheduled_time_out' => '18:00:00'
        ],
        'EMP002' => [
            'employee_id' => 'EMP002',
            'fname' => 'Jane',
            'lname' => 'Smith',
            'position' => 'Developer',
            'scheduled_time_in' => '08:00:00',
            'scheduled_time_out' => '17:00:00'
        ]
    ];
    
    $employee = isset($employees[$employeeInput]) ? $employees[$employeeInput] : null;
    
    if (!$employee) {
        $error = "Employee not found.";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['mark_in'])) {
    $employeeInput = trim($_POST['employeeInput']);
    
    // Static attendance storage (you might want to use sessions or local storage instead)
    if (!isset($_SESSION['attendance'])) {
        $_SESSION['attendance'] = [];
    }
    
    $current_time = date("H:i:s");
    $remarks = "Present"; // You can add logic for Late/Absent based on scheduled_time_in
    
    $_SESSION['attendance'][] = [
        'employee_id' => $employeeInput,
        'fname' => $employees[$employeeInput]['fname'],
        'lname' => $employees[$employeeInput]['lname'],
        'position' => $employees[$employeeInput]['position'],
        'time_in' => $current_time,
        'time_out' => '00:00:00',
        'remarks' => $remarks
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance Tracker</title>
    <link rel="stylesheet" href="styles/attendance.css">
</head>
<body>
    <div class="container">
        <div class="attendance-grid">
            <!-- Left side - Time-in form -->
            <div class="attendance-form-section">
                <h1>Employee Attendance Tracker</h1>
                <div class="date-container">
                    <p>Today's Date and Time is:</p>
                    <div id="current-date"></div>
                </div>

                <form method="POST" action="" id="attendanceForm">
                    <input type="text" id="employeeInput" name="employeeInput" 
                           placeholder="Enter employee ID" required>
                    <input type="submit" name="check_employee" id="checkButton" 
                           value="Check Employee" class="check-btn">
                </form>
            </div>

            <!-- Right side - Employee Details -->
            <div class="employee-details-section">
                <h3>Employee Details</h3>
                <div class="schedule-info">
                    <div class="schedule-item">
                        <span class="label">Scheduled Time In:</span>
                        <span class="time"></span>
                    </div>
                    <div class="schedule-item">
                        <span class="label">Scheduled Time Out:</span>
                        <span class="time"></span>
                    </div>
                </div>
                <button type="button" id="markTimeBtn" class="mark-time-btn" style="display: none;">
                    Mark Time In
                </button>
            </div>
        </div>
    </div>

    <!-- Time In Modal -->
    <div id="timeInModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Time In Confirmation</h2>
            <p>You have successfully timed in at <span id="timeInValue"></span></p>
            <button onclick="closeModal()" class="confirm-btn">OK</button>
        </div>
    </div>

    <script src="js/attendance.js"></script>
</body>
</html>
