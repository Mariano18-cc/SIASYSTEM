<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include the database connection
    if (!@include('../db_connection.php')) {
        die("Database connection file not found.");
    }

    // Collect form data
    $fname = $_POST['first_name'];
    $mname = $_POST['middle_name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $bday = $_POST['date_of_birth'];
    $applying_position = $_POST['position_applied'];
    $subject = isset($_POST['subject']) ? $_POST['subject'] : null;
    $employment_type = $_POST['employment_type'];
    $status = "New";

    // File upload handling
    $upload_dir = realpath(__DIR__ . '/../applicant_resume/') . '/';

    // Function to handle file upload
    function handleFileUpload($file, $upload_dir, $allowed_types, $fname, $mname, $lname) {
        // Check if file upload encountered errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => "Error during file upload. Code: " . $file['error']];
        }

        // Ensure upload directory exists
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                return ['success' => false, 'message' => "Failed to create upload directory."];
            }
        }

        // Validate file type
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed_types)) {
            return ['success' => false, 'message' => "Invalid file type. Only PDF files are allowed."];
        }

        // Generate new filename
        $new_filename = "{$fname}_{$mname}_{$lname}_resume.pdf";
        $new_filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $new_filename); // Sanitize filename
        $destination = $upload_dir . $new_filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => true, 'filename' => $new_filename];
        }

        return ['success' => false, 'message' => "Failed to move uploaded file."];
    }

    // Handle resume upload
    $resume_path = '';
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] !== UPLOAD_ERR_NO_FILE) {
        $resume_result = handleFileUpload($_FILES['resume'], $upload_dir, ['pdf'], $fname, $mname, $lname);
        if ($resume_result['success']) {
            $resume_path = $resume_result['filename'];
        } else {
            echo "<script>alert('Error uploading resume: " . $resume_result['message'] . "');</script>";
        }
    } else {
        echo "<script>alert('No resume file uploaded or invalid file.');</script>";
    }

    // Insert data into the database
    $sql = "INSERT INTO applicant (fname, mname, lname, email, phone, bday, applying_position, subject, 
            employment_type, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param(
            "ssssssssss",
            $fname,
            $mname,
            $lname,
            $email,
            $phone,
            $bday,
            $applying_position,
            $subject,
            $employment_type,
            $status
        );

        if ($stmt->execute()) {
            echo "<script>alert('Application submitted successfully!');</script>";
        } else {
            echo "<script>alert('Error submitting application: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement: " . $conn->error . "');</script>";
    }

    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 600px;
            border: 1px solid #ccc;
        }

        h1 {
            margin: 0 0 20px 0;
            font-size: 24px;
        }

        .form-section {
            font-weight: bold;
            margin: 15px 0;
            font-size: 16px;
        }

        .form-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-field {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        select {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            height: 30px;
        }

        .file-upload {
            display: flex;
            gap: 10px;
            margin: 15px 0;
        }

        .file-button {
            padding: 8px 15px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        .form-check {
            margin: 15px 0;
        }

        .submit-button {
            padding: 8px 20px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            float: right;
            cursor: pointer;
        }

        .submit-button:hover {
            background-color: #f0f0f0;
        }

        .upload-field {
            position: relative;
        }

        .file-button {
            padding: 8px 15px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .file-button:hover {
            background-color: #f0f0f0;
        }

        .file-button i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <form class="form-container" method="POST" action="" enctype="multipart/form-data">
        <h1>Application Form</h1>
        
        <div class="form-section">Personal Information</div>
        
        <div class="form-group">
            <div class="form-field">
                <label>First Name:</label>
                <input type="text" name="first_name" required>
            </div>
            <div class="form-field">
                <label>Phone Number:</label>
                <input type="text" name="phone" required>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label>Middle Name:</label>
                <input type="text" name="middle_name" required>
            </div>
            <div class="form-field">
                <label>Email Address:</label>
                <input type="email" name="email" required>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label>Last Name:</label>
                <input type="text" name="last_name" required>
            </div>
            <div class="form-field">
                <label>Date of Birth:</label>
                <input type="date" name="date_of_birth" required>
            </div>
        </div>

        <div class="form-group">
            <div class="form-field">
                <label>Position Applied:</label>
                <select name="position_applied" required>
                    <option value="">Select Position</option>
                    <option value="Kinder Teacher">Kinder Teacher</option>
                    <option value="Elementary Teacher">Elementary Teacher</option>
                    <option value="Junior High Teacher">Junior High Teacher</option>
                    <option value="School Administrator">School Administrator</option>
                    <option value="Guidance Councilor">Guidance Councilor</option>
                    <option value="IT Support Specialist">IT Support Specialist</option>
                    <option value="Librarian">Librarian</option>
                    <option value="School Nurse">School Nurse</option>
                    <option value="Finance/Accounting">Finance/Accounting</option>
                    <option value="Registrar">Registrar</option>
                    <option value="Custodial/Maintenance">Custodial/Maintenance</option>
                    <option value="Guard">Guard</option>
                    <option value="Excellent">Excellent</option>
                </select>
            </div>
            <div class="form-field">
                <label>Specific Subject:(if Teacher)</label>
                <select name="subject">
                    <option value="">Select Subject</option>
                    <option value="English">English</option>
                    <option value="Math">Mathematics</option>
                    <option value="Science">Science</option>
                    <option value="Filipino">Filipino</option>
                    <option value="Physical Education">Physical Education</option>
                    <option value="MAPEH">MAPEH</option>
                    <option value="History">History</option>
                </select>
            </div>
        </div>

        <div class="form-field">
            <label>Employment Type:</label>
            <select name="employment_type" required>
                <option value="">Select Type</option>
                <option value="Full-time">Full-time</option>
                <option value="Part-time">Part-time</option>
            </select>
        </div>

        <div class="form-section">Document</div>
               
            <div class="upload-field">
                <input type="file" id="resume" name="resume" accept=".pdf" style="display: none;" onchange="updateFileName(this, 'resume-label')">
                <label for="resume" class="file-button">
                    <i class="fas fa-arrow-down"></i> 
                    <span id="resume-label">Attach Resume</span>
                </label>
            </div>
            
          
            </div>
        </div>

        <div class="form-check">
            <input type="checkbox" name="terms" required>
            <label>I agree with the terms and conditions</label>
        </div>

        <button type="submit" class="submit-button">SUBMIT</button>
    </form>

    <script>
    function updateFileName(input, labelId) {
        const label = document.getElementById(labelId);
        if (input.files.length > 0) {
            label.textContent = input.files[0].name;
        }
    }
    </script>
</body>
</html>

