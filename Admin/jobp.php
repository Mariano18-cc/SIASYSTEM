<?php
// Include database connection
include "../db_connection.php";

// Fetch all applicants from the database
$results = [];
$stmt = $conn->prepare("SELECT applicant_id, fname, mname, lname, email, bday, applying_position, status FROM applicant");
$stmt->execute();
$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Check if a form to update status has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['applicant_id']) && isset($_POST['new_status'])) {
    $applicant_id = $_POST['applicant_id'];
    $new_status = $_POST['new_status'];

    if ($new_status === 'rejected') {
        // Delete the applicant from the database if rejected
        $delete_stmt = $conn->prepare("DELETE FROM applicant WHERE applicant_id = ?");
        $delete_stmt->bind_param("i", $applicant_id);
        $delete_stmt->execute();
        $delete_stmt->close();
        
        // Show a rejection message
        echo "<script>alert('Applicant has been rejected and removed from the system.');</script>";
        
        // Refresh the page to reflect the removal
        echo "<script>window.location.href = 'jobp.php';</script>";
        exit();
    } else {
        // Update the status if not rejected
        $update_stmt = $conn->prepare("UPDATE applicant SET status = ? WHERE applicant_id = ?");
        $update_stmt->bind_param("si", $new_status, $applicant_id);
        $update_stmt->execute();
        $update_stmt->close();

        // Redirect to refresh the page and show the updated status
        header("Location: jobp.php");
        exit();
    }
}

// This part will handle the AJAX request for applicant details
if (isset($_GET['id'])) {
    $applicant_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT applicant_id, fname, mname, lname, email, bday, applying_position, status FROM applicant WHERE applicant_id = ?");
    $stmt->bind_param("i", $applicant_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

   // Construct the full name based on fname, mname, and lname
$fullName = strtoupper($result['fname']) . " " . strtoupper($result['mname']) . " " . strtoupper($result['lname']);

// Replace spaces with underscores to ensure valid filename format (optional)
$formattedName = str_replace(' ', '_', $fullName);

// Construct the file path for the resume based on the formatted full name
$resumePath = "C:/xampp/htdocs/SIASYSTEM/applicant_resume/" . $formattedName . "_ Resume (1).pdf";

// Check if the resume exists on the server file system
if (file_exists($resumePath)) {
    // Set the relative URL path for the resume (the path accessible via the browser)
    $result['resume'] = "/SIASYSTEM/applicant_resume/" . $formattedName . "_ Resume (1).pdf";
} else {
    $result['resume'] = null;  // Handle the case where the resume is not found
}

echo json_encode($result);
exit();

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRMS Job Process</title>
    <link rel="stylesheet" href="css/jobp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo">
        <img src="../picture/logo.png" alt="Human Resource">
    </div>
    <h2 style="color: white; text-align: center;">HUMAN RESOURCE</h2>
    <ul>
        <li><a href="Dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="jobp.php" class="active"><i class="fas fa-briefcase"></i> Job Process</a></li>
        <li><a href="employee.php"><i class="fas fa-users"></i> Employee</a></li>
        <li><a href="payroll.php"><i class="fas fa-wallet"></i> Payroll</a></li>
        <li><a href="printr.php"><i class="fas fa-receipt"></i> Print Receipt</a></li>
    </ul>
    <div class="bottom-content">
        <a href="../login.php"><i class="fas fa-sign-out-alt"></i>Log Out</a>
    </div>
</div>

  <!-- Main content -->
  <main class="main-content">
    <div class="header">
      <div class="user-info">
        <img src="../picture/ex.pic.jpg" alt="Human Resource">
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
                  <!-- View Button -->
                  <button class="view-button" onclick="openModal(<?php echo $applicant['applicant_id']; ?>)">View</button>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- Modal for Applicant Details -->
  <div id="applicantModal" class="modal">
    <div class="modal-content">
      <h3>Applicant Details</h3>
      <table>
        <tr>
          <td>Applicant ID:</td>
          <td id="modal_applicant_id"></td>
        </tr>
        <tr>
          <td>Full Name:</td>
          <td id="modal_full_name"></td>
        </tr>
        <tr>
          <td>Email:</td>
          <td id="modal_email"></td>
        </tr>
        <tr>
          <td>Birthday:</td>
          <td id="modal_bday"></td>
        </tr>
        <tr>
          <td>Position:</td>
          <td id="modal_position"></td>
        </tr>
        <tr>
          <td>Resume:</td>
          <td><a id="modal_resume_link" href="" target="_blank">Open Resume</a></td>
        </tr>
        <tr>
          <td>Status:</td>
          <td id="modal_status"></td>
        </tr>
      </table>
      <button onclick="closeModal()">Close</button>
    </div>
  </div>

<script src="js/jobp.js"></script>
</body>
</html>
