<?php
require_once('db_connection.php');
session_start();
date_default_timezone_set('Asia/Manila');

$employeeDetails = ['name' => '', 'employee_id' => '', 'email' => ''];
$currentDateTime = date("Y-m-d g:i A");

// Fetch employee details when "Check ID" is clicked
if (isset($_POST['check_id'])) {
    $employee_id = $_POST['employee_id'];
    
    // Prepare a query to fetch employee details (fname, lname, email)
    $stmt = $conn->prepare("SELECT fname, lname, email FROM employee WHERE employee_id = ?");
    
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
        exit();
    }

    $stmt->bind_param("s", $employee_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        // Combine first name and last name into full name
        $full_name = $employee['fname'] . ' ' . $employee['lname'];
        echo json_encode([
            'status' => 'success',
            'name' => $full_name,  // Sending combined full name
            'email' => $employee['email'],
            'employee_id' => $employee_id
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Employee not found.']);
    }
    $stmt->close();
    exit();
}

// Handle "Time Out" functionality
if (isset($_POST['time_out'])) {
    $employee_id = $_POST['employee_id'];
    $currentDate = date("Y-m-d");
    $currentTime = date("H:i:s");
    $currentDateTime = new DateTime();

    $stmt = $conn->prepare("UPDATE attendance SET time_out = ?, remarks = ? WHERE employee_id = ? AND date = ? AND time_out IS NULL");
    
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
        exit();
    }

    $remarks = 'Time-out recorded';
    $stmt->bind_param("ssss", $currentTime, $remarks, $employee_id, $currentDate);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success', 
            'action' => 'time_out', 
            'time' => $currentDateTime->format('F j, Y g:i A'), 
            'employee_id' => $employee_id
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to execute statement: ' . $stmt->error]);
    }
    $stmt->close();
    exit();
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
    
        <div class="employee-details" id="employee-details">
            <h3>Employee Details</h3>
            <div class="details-content" id="details-content">
                <!-- Content is dynamically updated using JavaScript -->
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const employeeForm = document.querySelector('form[action="attendance_tracker.php"]');
            const detailsContent = document.getElementById('details-content');

            // Listen for form submission to fetch employee details
            if (employeeForm) {
                employeeForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const employeeId = document.querySelector('input[name="employee_id"]').value;
                    
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'attendance_tracker.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === XMLHttpRequest.DONE) {
                            if (xhr.status === 200) {
                                try {
                                    const response = JSON.parse(xhr.responseText);
                                    if (response.status === 'error') {
                                        alert(response.message);
                                        detailsContent.style.display = 'none';
                                    } else {
                                        displayEmployeeDetails(response);
                                    }
                                } catch (e) {
                                    console.error('Error parsing JSON:', e);
                                    alert('An error occurred while processing the response.');
                                }
                            } else {
                                alert('An error occurred while fetching employee details.');
                            }
                        }
                    };
                    xhr.send(`check_id=1&employee_id=${employeeId}`);
                });
            }

            // Display employee details dynamically
            function displayEmployeeDetails(employee) {
                detailsContent.innerHTML = ` 
                    <p><strong>Name:</strong> ${employee.name}</p>
                    <p><strong>Employee ID:</strong> <span id="displayedEmployeeId">${employee.employee_id}</span></p>
                    <p><strong>Email:</strong> ${employee.email}</p>
                    <p><strong>Status:</strong></p>
                    <p><strong>Time in:</strong> <span id="timeInStatus"></span></p>
                    <p><strong>Time out:</strong> <span id="timeOutStatus"></span></p>
                    <button type="button" id="timeInButton" class="time-in-button">Time In</button>
                    <button type="button" id="timeOutButton" class="time-out-button">Time Out</button>
                `;
                addTimeInOutListeners(); // Add listeners after updating content
            }

            function addTimeInOutListeners() {
                const timeInButton = document.getElementById('timeInButton');
                const timeOutButton = document.getElementById('timeOutButton');
                const timeInStatus = document.getElementById('timeInStatus');
                const timeOutStatus = document.getElementById('timeOutStatus');

                if (timeInButton) {
                    timeInButton.addEventListener('click', function(e) {
                        handleTimeInOut(e, 'time_in', timeInStatus);
                    });
                }

                if (timeOutButton) {
                    timeOutButton.addEventListener('click', function(e) {
                        handleTimeInOut(e, 'time_out', timeOutStatus);
                    });
                }
            }

            function handleTimeInOut(e, action, statusElement) {
                e.preventDefault();
                const employeeId = document.getElementById('displayedEmployeeId').textContent;
                if (!employeeId) {
                    alert('Employee ID is missing. Please check in again.');
                    return;
                }

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'attendance_tracker.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.status === 'success') {
                                    statusElement.textContent = `${action === 'time_in' ? 'Time In' : 'Time Out'}: ${response.time}`;
                                } else {
                                    alert(response.message || 'An error occurred');
                                }
                            } catch (e) {
                                console.error('Error parsing JSON:', e);
                                alert('An error occurred while processing the request.');
                            }
                        } else {
                            alert('An error occurred while processing the request.');
                        }
                    }
                };
                xhr.send(`employee_id=${employeeId}&${action}=1`);
            }
        });
    </script>
</body>
</html>
