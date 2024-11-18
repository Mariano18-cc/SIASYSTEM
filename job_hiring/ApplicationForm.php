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

    <div class="form-container">
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
            
            <label>Phone Number:</label>
            <input type="email" name="phone" required>
        </div>

        <div class="form-group">
            <label>Email Address:</label>
            <input type="text" name="email" required>
            
            <label>Date of Birth:</label>
            <input type="date" name="date_of_birth" required>
        </div>

        <div class="form-group">
            <label>Position Applied:</label>
            <select name="position_applied" required>
                <option value="Elementary Teacher">Teacher</option>
                <option value="Assistant Teacher">Guard</option>
                <option value="Assistant Teacher">Excellent</option>
            </select>
        </div>

        <div class="form-group">
            <label>Documents:</label>
            <div class="document-buttons">
                
                <div class="document-button">
                    Attach Resume <i class="fas fa-download"></i>
                </div>
                
            </div>
        </div>

        <div class="form-check">
            <input type="checkbox" name="terms" required>
            <label>I agree with the terms and conditions</label>
        </div>

        <button type="submit" class="submit-button">SUBMIT</button>
    </div>

</body>
</html>
