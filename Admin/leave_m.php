<?php
include '../db_connection.php';

// Fetch leave requests for the table
$stmt = $pdo->query("SELECT lr.*, lt.type_name, lr.reason 
                     FROM leave_requests lr 
                     JOIN leave_types lt ON lr.type_id = lt.type_id 
                     ORDER BY lr.applied_date DESC");
$requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle approve/reject actions if needed
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['approve_request'])) {
        $stmt = $pdo->prepare("UPDATE leave_requests SET status = 'Approved' WHERE request_id = ?");
        $stmt->execute([$_POST['request_id']]);
    } elseif (isset($_POST['reject_request'])) {
        $stmt = $pdo->prepare("UPDATE leave_requests SET status = 'Rejected' WHERE request_id = ?");
        $stmt->execute([$_POST['request_id']]);
    }
    header('Location: leave_m.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRMS Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css'>
    <link rel='stylesheet' href='https://pro.fontawesome.com/releases/v6.0.0-beta3/css/all.css'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/css2?family=Outfit:wght@100;200;300;400;500;600;700;800;900&amp;display=swap'>
    <link rel="stylesheet" href="css/leave_a.css">
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo">
        <img src="../picture/logo.png" alt="Human Resource">
    </div>
    <h2 style="color: white; text-align: center;">HUMAN RESOURCE</h2>
    <ul>
        <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="jobp.php" class="active"><i class="fas fa-briefcase"></i> Job Process</a></li>
        <li><a href="employee.php"><i class="fas fa-users"></i> Employee</a></li>
        <li><a href="payroll.php"><i class="fas fa-wallet"></i> Payroll</a></li>
        <li><a href="leave_m.php"><i class="fas fa-envelope-open-text"></i>  Leave Management</a></li>
    </ul>
    <div class="bottom-content">
        <a href="../index.php"><i class="fas fa-sign-out-alt"></i>Log Out</a>
    </div>
</div>

<div class="main-content">
    <!-- Header -->
    <div class="header">
        <div class="user-info">
            <img src="../picture/ex.pic.jpg" alt="User Avatar" class="user-avatar">
            <span>cakelyn</span>
        </div>
        
    </div>

    <div class="content">
        <div class="leave-requests">
            <h2>Leave Requests Management</h2>
            <table>
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Employee ID</th>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Days</th>
                        <th>Statement</th>
                        <th>Status</th>
                        <th>Applied Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $request): ?>
                    <tr>
                        <td><?php echo $request['request_id']; ?></td>
                        <td><?php echo $request['employee_id']; ?></td>
                        <td><?php echo $request['type_name']; ?></td>
                        <td><?php echo $request['start_date']; ?></td>
                        <td><?php echo $request['end_date']; ?></td>
                        <td><?php echo $request['total_days']; ?></td>
                        <td><?php echo $request['reason']; ?></td>
                        <td><span class="status <?php echo strtolower($request['status']); ?>"><?php echo $request['status']; ?></span></td>
                        <td><?php echo $request['applied_date']; ?></td>
                        <td>
                            <?php if ($request['status'] == 'Pending'): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="request_id" value="<?php echo $request['request_id']; ?>">
                                <button type="submit" name="approve_request" class="action-btn approve" title="Approve">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button type="submit" name="reject_request" class="action-btn reject" title="Reject">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
 
</body>
</html>