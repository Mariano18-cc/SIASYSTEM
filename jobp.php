<?php
// Include database connection
include "db_connection.php";

// Fetch all applicants from the database
$results = [];
$stmt = $conn->prepare("SELECT applicant_id, fname, mname, lname, status FROM applicant");
$stmt->execute();
$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Check if a form to update status has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['applicant_id']) && isset($_POST['new_status'])) {
    $applicant_id = $_POST['applicant_id'];
    $new_status = $_POST['new_status'];

    // Prepare the update query
    $update_stmt = $conn->prepare("UPDATE applicant SET status = ? WHERE applicant_id = ?");
    $update_stmt->bind_param("si", $new_status, $applicant_id); // 's' for string, 'i' for integer
    $update_stmt->execute();
    $update_stmt->close();

    // Redirect to refresh the page and show the updated status
    header("Location: jobp.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRMS Job Process</title>
    <link rel="stylesheet" href="stylesheet/jobp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo">
      <img src="picture/logo.png" alt="Human Resource">
    </div>
    <h2>HUMAN RESOURCE</h2>
    <ul style="list-style-type: none; padding-left: 0;">
      <li><a href="Dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
      <li><a href="jobp.php" class="active"><i class="fas fa-briefcase"></i> Job Process</a></li>
      <li><a href="employee.php"><i class="fas fa-users"></i> Employee</a></li>
      <li><a href="payroll.php"><i class="fas fa-wallet"></i> Payroll</a></li>
      <li><a href="printr.php"><i class="fas fa-receipt"></i> Print Receipt</a></li>
      <li><a href="index.php"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
    </ul>
  </div>

  <!-- Main content -->
  <div class="main">
    <!-- Header -->
    <div class="header">
      <div class="user-info">
        <img src="picture/ex.pic" alt="Human Resource">
       <!-- <span><?php echo htmlspecialchars($_SESSION['username']); ?></span><br> -->
        <p class="department">Human Resource Admin</p>
      </div>
    </div>

    <!-- Content -->
    <div class="content">
      <h2>Job Applications</h2>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Applicant ID</th>
              <th>Name</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($results as $applicant): ?>
            <tr>
              <td><?php echo htmlspecialchars($applicant['applicant_id']); ?></td>
              <td><?php echo htmlspecialchars($applicant['fname'] . ' ' . $applicant['mname'] . ' ' . $applicant['lname']); ?></td>
              <td><?php echo htmlspecialchars($applicant['status']); ?></td>
              <td>
                <div class="action-buttons">
                  <form method="POST" action="jobp.php">
                    <input type="hidden" name="applicant_id" value="<?php echo htmlspecialchars($applicant['applicant_id']); ?>">
                    <select name="new_status" required>
                      <option value="new" <?php echo $applicant['status'] == 'new' ? 'selected' : ''; ?>>New</option>
                      <option value="in progress" <?php echo $applicant['status'] == 'in progress' ? 'selected' : ''; ?>>In Progress</option>
                      <option value="for initial interview" <?php echo $applicant['status'] == 'for initial interview' ? 'selected' : ''; ?>>For Initial Interview</option>
                      <option value="validation" <?php echo $applicant['status'] == 'validation' ? 'selected' : ''; ?>>Validation</option>
                      <option value="final interview" <?php echo $applicant['status'] == 'final interview' ? 'selected' : ''; ?>>Final Interview</option>
                      <option value="hired" <?php echo $applicant['status'] == 'hired' ? 'selected' : ''; ?>>Hired</option>
                      <option value="rejected" <?php echo $applicant['status'] == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                    </select>
                    <button type="submit" class="edit-button">Update Status</button>
                  </form>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="javascript/jobp.js"></script>
</body>
</html>
