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
    $bday = $_POST['date_of_birth'];
    $applying_position = $_POST['position_applied'];
    $status = "New";

    // File upload handling
    $resume_dir = $_SERVER['DOCUMENT_ROOT'] . "/SIASYSTEM/applicant_resume/"; // Absolute path to the directory
    $resume_file = $resume_dir . basename($_FILES["resume"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($resume_file, PATHINFO_EXTENSION));

    // Ensure the directory exists
    if (!is_dir($resume_dir)) {
        mkdir($resume_dir, 0777, true); // Create the directory if it doesn't exist
    }

    // Check if the file is a PDF
    if ($fileType != "pdf") {
        echo "Sorry, only PDF files are allowed.";
        $uploadOk = 0;
    }

    // Handle the file upload
    if ($uploadOk && move_uploaded_file($_FILES["resume"]["tmp_name"], $resume_file)) {
        // Insert data into the database
        $sql = "INSERT INTO applicant (fname, mname, lname, email, bday, applying_position, status)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        // Prepare statement
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssssss", $fname, $mname, $lname, $email, $bday, $applying_position, $status);

            if ($stmt->execute()) {
               
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "Sorry, your file was not uploaded.";
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../stylesheet/jobh_css">
</head>
<body>

    <form class="form-container" method="POST" action="" enctype="multipart/form-data">
        <h1>Application Form</h1>
        
        <div class="form-section">Personal Information</div>
        
        <div class="form-group">
            <label>First Name:</label>
            <input type="text" name="first_name" required>
            
            <label>Middle Name:</label>
            <input type="text" name="middle_name" required>
        </div>

        <div class="form-group">
            <label>Last Name:</label>
            <input type="text" name="last_name">
            
        </div>

        <div class="form-group">
            <label>Email Address:</label>
            <input type="email" name="email" required>
            
            <label>Date of Birth:</label>
            <input type="date" name="date_of_birth" required>
        </div>

        <div class="form-group">
            <label>Position Applied:</label>
            <select name="position_applied" required>
                <option value="Teacher">Teacher</option>
                <option value="Guard">Guard</option>
                <option value="Excellent">Excellent</option>
            </select>
        </div>

        <div class="form-group">
            <label>Attach Resume:</label>
            <input type="file" name="resume" accept=".pdf" required>
        </div>

        <div class="form-check">
            <input type="checkbox" name="terms" required>
            <label>I agree with the terms and conditions</label>
        </div>

        <button type="submit" class="submit-button">SUBMIT</button>
    </form>

</body>
</html>

