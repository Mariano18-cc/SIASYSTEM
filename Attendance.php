<?php
require_once('db_connection.php');
$error = "";
session_start();

if (isset($_POST['check_employee'])) {
    $employeeInput = trim($_POST['employeeInput']);
    
    // Fetch employee details
    try {
        $sql = "SELECT e.*, ae.time_in as scheduled_time_in, ae.time_out as scheduled_time_out 
                FROM employee e 
                LEFT JOIN attendance_emp ae ON e.employee_id = ae.employee_id 
                WHERE e.employee_id = :employeeInput";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':employeeInput', $employeeInput, PDO::PARAM_STR);
        $stmt->execute();
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$employee) {
            $error = "Employee not found.";
        }
    } catch (PDOException $e) {
        $error = "An error occurred: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $employeeInput = trim($_POST['employeeInput']);
    $employee_id = "";
    $time_in = date("Y-m-d H:i:s");
    $time_out = '00:00:00'; // Default time-out value, as NULL is not allowed
    $workday = date("l");

    // Fetch employee's time_in and time_out from attendance_emp
    try {
        // Check if input is employee_id or employee_name
        if (strlen($employeeInput) > 4 && preg_match('/^[A-Za-z]+\d+$/', $employeeInput)) {
            // It's employee_id
            $sql = "SELECT `employee_id`, `employee_name`, `time_in`, `time_out`, `workday` FROM `attendance_emp` WHERE `employee_id` = :employeeInput";
        } else {
            // It's employee name (first last)
            $sql = "SELECT `employee_id`, `employee_name`, `time_in`, `time_out`, `workday` FROM `attendance_emp` WHERE CONCAT(LOWER(`employee_name`)) = LOWER(:employeeInput)";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':employeeInput', $employeeInput, PDO::PARAM_STR);
        $stmt->execute();
        $employee = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($employee) {
            $employee_id = $employee['employee_id'];
            $employee_name = $employee['employee_name'];
            $time_in = $employee['time_in'];
            $time_out = $employee['time_out'];

            // Check if employee has already logged in today
            $check_attendance = "SELECT * FROM `attendance` WHERE `employee_id` = :employee_id AND `date` = CURDATE()";
            $stmt_check = $pdo->prepare($check_attendance);
            $stmt_check->bindParam(':employee_id', $employee_id, PDO::PARAM_STR);
            $stmt_check->execute();
            $attendance_today = $stmt_check->fetch(PDO::FETCH_ASSOC);

            if ($attendance_today) {
                // If already logged in today, don't allow time-in again
                $error = "You have already marked your time-in today.";
            } else {
                // Insert attendance data into the attendance table
                $remarks = "";
                $workday = $employee['workday'];

                // Check if employee is late or absent
                $current_time = date("H:i:s");
                if ($time_in > $current_time) {
                    $remarks = "Late";
                } elseif ($time_in <= $current_time) {
                    $remarks = "Present";
                } else {
                    $remarks = "Absent";
                }

                $sql_insert = "INSERT INTO `attendance`(`employee_id`, `workday`, `date`, `time_in`, `time_out`, `remarks`) 
                               VALUES (:employee_id, :workday, :date, :time_in, :time_out, :remarks)";
                $stmt_insert = $pdo->prepare($sql_insert);
                $stmt_insert->bindParam(':employee_id', $employee_id, PDO::PARAM_STR);
                $stmt_insert->bindParam(':workday', $workday, PDO::PARAM_STR);
                $stmt_insert->bindParam(':date', date("Y-m-d"), PDO::PARAM_STR);
                $stmt_insert->bindParam(':time_in', $time_in, PDO::PARAM_STR);
                $stmt_insert->bindParam(':time_out', $time_out, PDO::PARAM_STR);
                $stmt_insert->bindParam(':remarks', $remarks, PDO::PARAM_STR);

                $stmt_insert->execute();
                if ($stmt_insert->rowCount() > 0) {
                    header("Location: attendance.php?success=true");
                    exit();
                } else {
                    $error = "Failed to insert attendance record.";
                }
            }
        } else {
            $error = "Employee not found.";
        }
    } catch (PDOException $e) {
        $error = "An error occurred: " . $e->getMessage();
    }
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

                <!-- Employee Details Card (shows after checking) -->
                <?php if (isset($employee) && $employee): ?>
                <div class="employee-details-card">
                    <div class="employee-header">
                        <h3><?php echo $employee['fname'] . ' ' . $employee['lname']; ?></h3>
                        <span class="employee-id"><?php echo $employee['employee_id']; ?></span>
                    </div>
                    <div class="schedule-info">
                        <div class="schedule-item">
                            <span class="label">Scheduled Time In:</span>
                            <span class="time"><?php echo $employee['scheduled_time_in']; ?></span>
                        </div>
                        <div class="schedule-item">
                            <span class="label">Scheduled Time Out:</span>
                            <span class="time"><?php echo $employee['scheduled_time_out']; ?></span>
                        </div>
                    </div>
                    <button type="button" id="markTimeBtn" class="mark-time-btn" 
                            onclick="markTime('<?php echo $employee['employee_id']; ?>')">
                        Mark Time In
                    </button>
                </div>
                <?php endif; ?>
            </div>

            <!-- Right side - Today's attendance -->
            <div class="attendance-records">
                <h2>Today's Attendance</h2>
                <div class="records-container">
                    <?php
                    $sql_select = "SELECT * FROM `attendance` WHERE `date` = CURDATE()";
                    $stmt_select = $pdo->prepare($sql_select);
                    $stmt_select->execute();
                    $attendance_records = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($attendance_records as $record):
                        $sql_name = "SELECT CONCAT(`fname`, ' ', `lname`) AS employee_name FROM `employee` WHERE `employee_id` = :employee_id";
                        $stmt_name = $pdo->prepare($sql_name);
                        $stmt_name->bindParam(':employee_id', $record['employee_id'], PDO::PARAM_STR);
                        $stmt_name->execute();
                        $employee_name = $stmt_name->fetch(PDO::FETCH_ASSOC)['employee_name'];
                    ?>
                        <div class="attendance-card">
                            <div class="employee-info">
                                <h3><?php echo $employee_name; ?></h3>
                                <span class="employee-id"><?php echo $record['employee_id']; ?></span>
                            </div>
                            <div class="time-info">
                                <div class="time-block">
                                    <span class="label">Time In:</span>
                                    <span class="time"><?php echo $record['time_in']; ?></span>
                                </div>
                                <div class="time-block">
                                    <span class="label">Time Out:</span>
                                    <?php if ($record['time_out'] == '00:00:00' || $record['time_out'] == $record['time_in']): ?>
                                        <span class="time">Not yet timed out</span>
                                    <?php else: ?>
                                        <span class="time"><?php echo $record['time_out']; ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="status-badge <?php echo strtolower($record['remarks']); ?>">
                                <?php echo $record['remarks']; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
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
