<?php
include '../db_connection.php';

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_request'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM leave_requests WHERE request_id = ? AND status = 'Pending'");
        $result = $stmt->execute([$_POST['request_id']]);
        
        if ($result) {
            header('Location: leave.php?msg=deleted');
            exit;
        }
    } catch (PDOException $e) {
        $error = "Error deleting request: " . $e->getMessage();
    }
}

// Handle leave request submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['delete_request'])) {
    $employee_id = $_POST['employee_id'];
    $type_id = $_POST['type_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['statement'];
    
    try {
        // Calculate total days (excluding weekends)
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = $start->diff($end);
        $total_days = $interval->days + 1; // Include both start and end dates
        
        // Validate dates
        if ($end < $start) {
            throw new Exception("End date cannot be before start date");
        }
        
        $stmt = $pdo->prepare("INSERT INTO leave_requests (employee_id, type_id, start_date, end_date, total_days, reason, status) 
                               VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
        $stmt->execute([$employee_id, $type_id, $start_date, $end_date, $total_days, $reason]);
        
        header('Location: leave.php?msg=submitted');
        exit;
    } catch(Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// At the top of your file, add this to handle success messages
if (isset($_GET['msg']) && $_GET['msg'] == 'submitted') {
    $message = "Leave request submitted successfully!";
}

// Fetch leave types for the dropdown
$stmt = $pdo->query("SELECT * FROM leave_types");
$leave_types = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch leave requests for the table - simplified query
$stmt = $pdo->query("SELECT lr.*, lt.type_name 
                     FROM leave_requests lr 
                     JOIN leave_types lt ON lr.type_id = lt.type_id 
                     ORDER BY lr.applied_date DESC");
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Requests</title>
    <link rel="stylesheet" href="css/leave.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php if (isset($message) || isset($error)): ?>
        <div class="alert-overlay"></div>
        <?php if (isset($message)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="../picture/logo.png" alt="Human Resource">
        </div>
        <h2 style="color: white; text-align: center;">HUMAN RESOURCE</h2>
        <ul style="list-style-type: none; padding-left: 0;">
            <li><a href="employee_p.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="inbox.php"><i class="fas fa-inbox"></i> Inbox</a></li>
            <li><a href="leave.php"><i class="fas fa-envelope-open-text"></i> Leave Request</a></li>
            <li><a href="payroll.php"><i class="fas fa-wallet"></i> Payroll</a></li>
        </ul>
        <div class="bottom-content">
            <a href="../index.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>
    </div>

    <div class="main-content">
        <!-- Header (same as other pages) -->
        <div class="header">
            <div class="user-info">
                <img src="../picture/ex.pic.jpg" alt="User Avatar" class="user-avatar">
                <span>cakelyn</span>
            </div>
        </div>

        <!-- Leave Request Content -->
        <div class="content">
            <div class="leave-container">
                <div class="leave-header">
                    <h2>Leave Requests</h2>
                    <button class="request-btn">Request</button>
                </div>
                
                <div class="leave-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Leave Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Days</th>
                                <th>Status</th>
                                <th>Applied Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($requests as $request): ?>
                                <tr>
                                    <td><?php echo $request['request_id']; ?></td>
                                    <td><?php echo $request['type_name']; ?></td>
                                    <td><?php echo $request['start_date']; ?></td>
                                    <td><?php echo $request['end_date']; ?></td>
                                    <td><?php echo $request['total_days']; ?></td>
                                    <td>
                                        <span class="status <?php echo strtolower($request['status']); ?>">
                                            <?php echo $request['status']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $request['applied_date']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Form -->
    <div id="requestModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Leave Request Form</h3>
                <span class="close">&times;</span>
            </div>
            <form class="request-form" method="POST">
                <input type="hidden" name="employee_id" value="EMP4176">
                
                <div class="form-group">
                    <label>Select Leave Type:</label>
                    <select name="type_id" required>
                        <?php foreach ($leave_types as $type): ?>
                            <option value="<?php echo $type['type_id']; ?>">
                                <?php echo $type['type_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Start Date:</label>
                    <input type="date" name="start_date" required>
                </div>

                <div class="form-group">
                    <label>End Date:</label>
                    <input type="date" name="end_date" required>
                </div>
                
                <div class="form-group">
                    <label>Statement:</label>
                    <textarea name="statement" required placeholder="Enter your reason for leave..."></textarea>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="submit-btn">Submit</button>
                    <button type="button" class="cancel-btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script src="js/leave.js"></script>
</body>
</html>
