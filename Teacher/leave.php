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
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="../picture/logo.png" alt="Human Resource">
        </div>
        <h2 style="color: white; text-align: center;">HUMAN RESOURCE</h2>
        <ul style="list-style-type: none; padding-left: 0;">
            <li><a href="teacher_p.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="inbox.php"><i class="fas fa-inbox"></i> Inbox</a></li>
            <li><a href="stlist.php"><i class="fas fa-users"></i> Student List</a></li>
            <li><a href="gradelist.php"><i class="fas fa-pencil-alt"></i> Grading List</a></li>
            <li><a href="leave.php"><i class="fas fa-envelope-open-text"></i> Leave Request</a></li>
            <li><a href="payroll_t.php"><i class="fas fa-wallet"></i> Payroll</a></li>
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
                                <th>Title</th>
                                <th>Request Date</th>
                                <th>Statement</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#527367455</td>
                                <td>Emergency Leave</td>
                                <td>07/02/24</td>
                                <td></td>
                                <td><span class="status approved">Approved</span></td>
                                <td>
                                    <i class="fas fa-trash"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this before </body> -->
    <div id="requestModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Leave Request Form</h3>
                <span class="close">&times;</span>
            </div>
            <form class="request-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Select Leave Type:</label>
                    <select name="title" required>
                        <option value="Sick Leave">Sick Leave</option>
                        <option value="Maternity/Paternity Leave">Maternity/Paternity Leave</option>
                        <option value="Funeral Leave">Funeral Leave</option>
                        <option value="Emergency Leave">Emergency Leave</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Request Date:</label>
                    <input type="date" name="request_date" required>
                </div>
                
                <div class="form-group">
                    <label>Statement:</label>
                    <textarea name="statement" required placeholder="Enter your reason for leave..."></textarea>
                </div>
                
                <div class="form-group">
                    <label>Attach File (Optional):</label>
                    <div class="file-input-container">
                        <input type="file" name="attachment" accept=".png,.pdf">
                    </div>
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
