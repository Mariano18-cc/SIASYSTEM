<?php
require_once('db_connection.php');
$error = "";
session_start();

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
    <style>
       body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('picture/logo.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .container {
            max-width: 800px;
            width: 100%;
            padding: 20px;
            background-color: #082C66;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .date-container {
            margin-bottom: 20px;
            color: #fff;
        }
        #current-date {
            font-size: 18px;
            font-weight: bold;
        }
        label, input[type="text"] {
            display: block;
            width: 100%;
            margin: 10px 0;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            margin: 5px 0;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Employee Attendance Tracker</h1>
        <div class="date-container">
            <p>Today's Date and Time is:</p>
            <div id="current-date"></div>
        </div>

        <form method="POST" action="">
            <input type="text" id="employeeInput" name="employeeInput" placeholder="Enter employee's name or employee ID" required>
            <input type="submit" name="mark_in" value="Mark Time-In">
        </form>

        <table>
            <thead>
                <tr>
                    <th>Employee Id</th>
                    <th>Employee Name</th>
                    <th>Date</th>
                    <th>Time-In</th>
                    <th>Time-Out</th>
                    <th>Remarks</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch attendance records from the attendance table
                $sql_select = "SELECT * FROM `attendance` WHERE `date` = CURDATE()";
                $stmt_select = $pdo->prepare($sql_select);
                $stmt_select->execute();
                $attendance_records = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

                foreach ($attendance_records as $record):
                    // Fetch employee name by employee_id
                    $sql_name = "SELECT CONCAT(`fname`, ' ', `lname`) AS employee_name FROM `employee` WHERE `employee_id` = :employee_id";
                    $stmt_name = $pdo->prepare($sql_name);
                    $stmt_name->bindParam(':employee_id', $record['employee_id'], PDO::PARAM_STR);
                    $stmt_name->execute();
                    $employee_name = $stmt_name->fetch(PDO::FETCH_ASSOC)['employee_name'];
                ?>
                    <tr>
                        <td><?php echo $record['employee_id']; ?></td>
                        <td><?php echo $employee_name; ?></td>
                        <td><?php echo $record['date']; ?></td>
                        <td><?php echo $record['time_in']; ?></td>
                        <td><?php echo $record['time_out']; ?></td>
                        <td><?php echo $record['remarks']; ?></td>
                        <td>
                            <?php if ($record['time_out'] == '00:00:00') { ?>
                                <form method="POST" action="">
                                    <input type="hidden" name="attendance_id" value="<?php echo $record['attendance_id']; ?>">
                                    <input type="submit" name="mark_out" value="Mark Time-Out">
                                </form>
                            <?php } else { ?>
                                <button disabled>Already Time Out</button>
                            <?php } ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function updateDateTime() {
            const now = new Date();
            const formattedDateTime = now.toLocaleString([], {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            });
            document.getElementById('current-date').textContent = formattedDateTime;
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>
</body>
</html>
