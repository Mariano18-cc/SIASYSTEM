<?php
// Start the session and include database connection
session_start();
include "../db_connection.php";

// Load PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

// Get user info from session
$user = $_SESSION['user'];

// Query the hradmin table to get the user's info
$stmt = $conn->prepare("SELECT user, email FROM hradmin WHERE user = ? OR email = ?");
$stmt->bind_param("ss", $user, $user);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();

// Set logged-in user
$loggedInUser = $userData ? $userData['user'] : 'Admin User';

// Initialize $results as an empty array
$results = [];

try {
    // Fetch all applicants from the database
    $stmt = $conn->prepare("SELECT applicant_id, fname, mname, lname, email, phone, bday, applying_position, subject, employment_type, status FROM applicant");
    $stmt->execute();
    $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} catch (Exception $e) {
    // Handle potential database errors
    echo "<script>console.error('Error fetching applicants: " . $e->getMessage() . "');</script>";
}

// Handle form submission for updating applicant status
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['applicant_id']) && isset($_POST['new_status'])) {
    $applicant_id = $_POST['applicant_id'];
    $new_status = $_POST['new_status'];

    // Company Name (used in email signature)
    $companyName = 'Kumdan Jungang Christian School Inc';

    if ($new_status === 'rejected') {
        // Fetch the applicant's details for the email before deleting
        $stmt = $conn->prepare("SELECT fname, lname, email, applying_position FROM applicant WHERE applicant_id = ?");
        $stmt->bind_param("i", $applicant_id);
        $stmt->execute();
        $applicant = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $applicantName = $applicant['fname'] . " " . $applicant['lname'];
        $applicantEmail = $applicant['email'];
        $applicantPosition = $applicant['applying_position'];

        // Send rejection email
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'zerefdamn11@gmail.com'; // Replace with your email
            $mail->Password = 'njqx phoh cfje wauo'; // Replace with your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('zerefdamn11@gmail.com', $companyName);
            $mail->addAddress($applicantEmail, $applicantName);

            $mail->isHTML(true);
            $mail->Subject = 'Job Application Status';
            $mail->Body = "<p>Dear $applicantName,</p>
                           <p>We regret to inform you that you were not selected for the position of <strong>$applicantPosition</strong>.</p>
                           <p>We sincerely appreciate the time and effort you put into the application process and wish you the best in your future endeavors.</p>
                           <p>Best regards,<br>$companyName HR team</p>";
            $mail->AltBody = "Dear $applicantName,\n\nWe regret to inform you that you were not selected for the position of $applicantPosition.\n\nWe appreciate your effort and wish you the best in your future endeavors.\n\nBest regards,\n$companyName HR team";

            // Send the email
            $mail->send();
            echo "<script>alert('Rejection email sent to $applicantName.');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Email could not be sent. Error: {$mail->ErrorInfo}');</script>";
        }

        // Now delete the applicant from the database
        $delete_stmt = $conn->prepare("DELETE FROM applicant WHERE applicant_id = ?");
        $delete_stmt->bind_param("i", $applicant_id);
        $delete_stmt->execute();
        $delete_stmt->close();

        echo "<script>window.location.href = 'jobp.php';</script>";
        exit();
    } elseif ($new_status === 'hired') {
        // Update status to hired
        $update_stmt = $conn->prepare("UPDATE applicant SET status = ? WHERE applicant_id = ?");
        $update_stmt->bind_param("si", $new_status, $applicant_id);
        $update_stmt->execute();
        $update_stmt->close();

        // Fetch the applicant's details for the email
        $stmt = $conn->prepare("SELECT fname, lname, email, applying_position FROM applicant WHERE applicant_id = ?");
        $stmt->bind_param("i", $applicant_id);
        $stmt->execute();
        $applicant = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $applicantName = $applicant['fname'] . " " . $applicant['lname'];
        $applicantEmail = $applicant['email'];
        $applicantPosition = $applicant['applying_position'];

        // Send hired email
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'zerefdamn11@gmail.com'; // Replace with your email
            $mail->Password = 'njqx phoh cfje wauo'; // Replace with your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('zerefdamn11@gmail.com', $companyName);
            $mail->addAddress($applicantEmail, $applicantName);

            $mail->isHTML(true);
            $mail->Subject = 'Congratulations on Your Hiring';
            $mail->Body = "<p>Dear $applicantName,</p>
                           <p>We are pleased to inform you that you have been hired for the position of <strong>$applicantPosition</strong>.</p>
                           <p>Congratulations on joining $companyName! We look forward to working with you.</p>
                           <p>Best regards,<br>$companyName HR team</p>";
            $mail->AltBody = "Dear $applicantName,\n\nWe are pleased to inform you that you have been hired for the position of $applicantPosition.\n\nCongratulations on joining $companyName! We look forward to working with you.\n\nBest regards,\n$companyName HR team";

            // Send the email
            $mail->send();
            echo "<script>alert('Applicant has been hired and email sent to $applicantName.');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Applicant hired, but email could not be sent. Error: {$mail->ErrorInfo}');</script>";
        }

        echo "<script>window.location.href = 'jobp.php';</script>";
        exit();
    } else {
        // Update the status for other statuses
        $update_stmt = $conn->prepare("UPDATE applicant SET status = ? WHERE applicant_id = ?");
        $update_stmt->bind_param("si", $new_status, $applicant_id);
        $update_stmt->execute();
        $update_stmt->close();

        // Fetch applicant's details for email (if status is "for interview" or "final interview")
        $stmt = $conn->prepare("SELECT fname, lname, email, applying_position FROM applicant WHERE applicant_id = ?");
        $stmt->bind_param("i", $applicant_id);
        $stmt->execute();
        $applicant = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $applicantName = $applicant['fname'] . " " . $applicant['lname'];
        $applicantEmail = $applicant['email'];
        $applicantPosition = $applicant['applying_position'];

        if ($new_status === 'for initial interview' || $new_status === 'final interview') {
            $interviewDate = date("l, F j, Y", strtotime("+3 days"));  // Adjust date formatting

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'zerefdamn11@gmail.com'; // Replace with your email
                $mail->Password = 'njqx phoh cfje wauo'; // Replace with your email password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('zerefdamn11@gmail.com', $companyName);
                $mail->addAddress($applicantEmail, $applicantName);

                $mail->isHTML(true);
                $mail->Subject = 'Interview Invitation';
                $mail->Body = "<p>Dear $applicantName,</p>
                <p>We are pleased to inform you that you have been scheduled for a <strong>$new_status</strong> for the <strong>$applicantPosition</strong> position at <strong>$companyName</strong>.</p>
                <p><strong>Interview Details:</strong></p>
                <p>Date: $interviewDate</p>
                <p>Time: 9:00 AM onwards</p>
                <p>Location: 181 Sitio 3 Bayanihan St. BATASAN HILLS</p>
                <p><strong>Important Reminders:</strong></p>
                <ul>
                    <li>Please bring the required documents and a ballpen.</li>
                    <li>Dress appropriately for the interview.</li>
                </ul>
                <p>We look forward to meeting you and learning more about your qualifications!</p>
                <p>Best regards,<br>$companyName HR Team</p>";
                
            $mail->AltBody = "Dear $applicantName,\n\nWe are pleased to inform you that you have been scheduled for a $new_status for the $applicantPosition position at $companyName on $interviewDate.\n\nPlease bring the required documents and a ballpen, and dress appropriately for the interview.\n\nWe look forward to meeting you and learning more about your qualifications!\n\nBest regards,\n$companyName HR Team";

                // Send the email
                $mail->send();
                echo "<script>alert('Interview email sent to $applicantName.');</script>";
            } catch (Exception $e) {
                echo "<script>alert('Interview email could not be sent. Error: {$mail->ErrorInfo}');</script>";
            }
        } else {
            echo "<script>alert('Status updated.');</script>";
        }

        echo "<script>window.location.href = 'jobp.php';</script>";
        exit();
    }
}

// Handle AJAX request for applicant details (modal data fetching)
if (isset($_GET['id'])) {
    $applicant_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT applicant_id, fname, mname, lname, email, phone, bday, applying_position, subject, employment_type, status FROM applicant WHERE applicant_id = ?");
    $stmt->bind_param("i", $applicant_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $fullName = strtoupper($result['fname']) . " " . strtoupper($result['mname']) . " " . strtoupper($result['lname']);
    $formattedName = str_replace(' ', '_', $fullName);
    $resumePath = "C:/xampp/htdocs/SIASYSTEM/applicant_resume/" . $formattedName . "_Resume.pdf";

    if (file_exists($resumePath)) {
        $result['resume'] = "/SIASYSTEM/applicant_resume/" . $formattedName . "_Resume.pdf";
    } else {
        $result['resume'] = null;
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
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="jobp.php"><i class="fas fa-briefcase"></i> Job Process</a></li>
            <li><a href="employee.php"><i class="fas fa-users"></i> Employee</a></li>
            <li><a href="payroll.php"><i class="fas fa-wallet"></i> Payroll</a></li>
            <li><a href="attendance.php"><i class="fas fa-clock"></i> Attendance</a></li>
            <li><a href="leave_m.php"><i class="fas fa-envelope-open-text"></i> Leave Management</a></li>
        </ul>
    <div class="bottom-content">
    <a href="../dashboard.php"><i class="fas fa-sign-out-alt"></i>Log Out</a>
    </div>
</div>

  <!-- Main content -->
  <main class="main-content">
      <header class="header">
        <div class="user-info">
          <img src="../picture/ex.pic.jpg" alt="User Avatar">
          <span><?php echo htmlspecialchars($loggedInUser); ?></span>
        </div>
      </header>

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
    <?php if (!empty($results)): ?>
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
                        <button class="view-button" onclick="openModal(<?php echo $applicant['applicant_id']; ?>)">View</button>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4">No applicants found.</td>
        </tr>
    <?php endif; ?>
</tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- Modal for Applicant Details -->
<div id="applicantModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
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
                <td>Phone Number:</td>
                <td id="modal_phone"></td>
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
                <td>Subject:</td>
                <td id="modal_subject"></td>
            </tr>
            <tr>
                <td>Employment Type:</td>
                <td id="modal_employment_type"></td>
            </tr>
            <tr>
                <td>Resume:</td>
                <td><a id="modal_resume_link" href="#" target="_blank">Open Resume</a></td>
            </tr>
            <tr>
                <td>Status:</td>
                <td id="modal_status"></td>
            </tr>
        </table>
    </div>
</div>


<script src="js/jobp.js"></script>
</body>
</html>
