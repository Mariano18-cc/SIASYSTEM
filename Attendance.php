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

    // Check if employee has already clocked in for today
    if (isset($_POST['mark_in'])) {
        // Marking time-in
        if (!empty($employeeInput)) {
            try {
                // Check if the input is an employee_id or name
                if (strlen($employeeInput) > 4 && preg_match('/^[A-Za-z]+\d+$/', $employeeInput)) {
                    // Treat it as an employee_id (alphanumeric, e.g., EMP4176)
                    $sql = "SELECT `employee_id`, CONCAT(`fname`, ' ', `lname`) AS employee_name FROM `employee` WHERE `employee_id` = :employeeInput";
                } else {
                    // Treat it as an employee Name (first and last name)
                    $sql = "SELECT `employee_id`, CONCAT(`fname`, ' ', `lname`) AS employee_name FROM `employee` WHERE CONCAT(LOWER(`fname`), ' ', LOWER(`lname`)) = LOWER(:employeeInput)";
                }

                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':employeeInput', $employeeInput, PDO::PARAM_STR);
                $stmt->execute();
                $employee = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($employee) {
                    $employee_id = $employee['employee_id'];
                    $employee_name = $employee['employee_name'];

                    // Check if the employee has already clocked in today
                    $sql_check_in = "SELECT * FROM `attendance` WHERE `employee_id` = :employee_id AND `date` = CURDATE() AND `time_out` = '00:00:00'";
                    $stmt_check_in = $pdo->prepare($sql_check_in);
                    $stmt_check_in->bindParam(':employee_id', $employee_id, PDO::PARAM_STR);
                    $stmt_check_in->execute();
                    $existing_attendance = $stmt_check_in->fetch(PDO::FETCH_ASSOC);

                    if ($existing_attendance) {
                        $error = "You have already clocked in today.";
                    } else {
                        $sql_insert = "INSERT INTO `attendance` (`employee_id`, `workday`, `date`, `time_in`, `time_out`)
                                       VALUES (:employee_id, :workday, :date, :time_in, :time_out)";
                        $stmt_insert = $pdo->prepare($sql_insert);
                        $stmt_insert->bindParam(':employee_id', $employee_id, PDO::PARAM_STR);
                        $stmt_insert->bindParam(':workday', $workday, PDO::PARAM_STR);
                        $stmt_insert->bindParam(':date', date("Y-m-d"), PDO::PARAM_STR);
                        $stmt_insert->bindParam(':time_in', $time_in, PDO::PARAM_STR);
                        $stmt_insert->bindParam(':time_out', $time_out, PDO::PARAM_STR);

                        $stmt_insert->execute();

                        if ($stmt_insert->rowCount() > 0) {
                            header("Location: attendance.php?success=true");
                            exit();
                        } else {
                            $error = "Failed to insert time-in record.";
                        }
                    }
                } else {
                    $error = "Employee not found.";
                }
            } catch (PDOException $e) {
                $error = "An error occurred: " . $e->getMessage();
            }
        } else {
            $error = "Please enter the employee's name or employee ID.";
        }
    }

    if (isset($_POST['mark_out'])) {
        // Marking time-out
        $attendance_id = $_POST['attendance_id'];
        $time_out = date("Y-m-d H:i:s");

        try {
            // Ensure that employee has not already clocked out
            $sql_check_out = "SELECT * FROM `attendance` WHERE `attendance_id` = :attendance_id AND `time_out` = '00:00:00'";
            $stmt_check_out = $pdo->prepare($sql_check_out);
            $stmt_check_out->bindParam(':attendance_id', $attendance_id, PDO::PARAM_INT);
            $stmt_check_out->execute();
            $attendance = $stmt_check_out->fetch(PDO::FETCH_ASSOC);

            if ($attendance) {
                // Update the attendance record with the time-out
                $sql_update = "UPDATE `attendance` SET `time_out` = :time_out WHERE `attendance_id` = :attendance_id";
                $stmt_update = $pdo->prepare($sql_update);
                $stmt_update->bindParam(':time_out', $time_out, PDO::PARAM_STR);
                $stmt_update->bindParam(':attendance_id', $attendance_id, PDO::PARAM_INT);

                $stmt_update->execute();

                if ($stmt_update->rowCount() > 0) {
                    header("Location: attendance.php?success=true");
                    exit();
                } else {
                    $error = "Failed to update time-out record.";
                }
            } else {
                $error = "You have already clocked out or invalid record.";
            }
        } catch (PDOException $e) {
            $error = "An error occurred: " . $e->getMessage();
        }
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
            background-image: url('');
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
            <label for="employeeInput">Employee Name or ID:</label>
            <input type="text" id="employeeInput" name="employeeInput" placeholder="Enter employee's name or employee ID" required>
            <input type="submit" name="mark_in" value="Mark Time-In">
        </form>

        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Employee Id</th>
                    <th>Employee Name</th> <!-- Added column for Employee Name -->
                    <th>Date</th>
                    <th>Time-In</th>
                    <th>Time-Out</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch attendance records to allow time-out marking
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
                        <td><?php echo $employee_name; ?></td> <!-- Display employee name -->
                        <td><?php echo $record['date']; ?></td>
                        <td><?php echo $record['time_in']; ?></td>
                        <td><?php echo $record['time_out']; ?></td>
                        <td>
                            <?php if ($record['time_out'] === '00:00:00'): ?>
                                <form method="POST" action="">
                                    <input type="hidden" name="attendance_id" value="<?php echo $record['attendance_id']; ?>">
                                    <input type="submit" name="mark_out" value="Mark Time-Out">
                                </form>
                            <?php endif; ?>
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
