<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f9f9f9;
        }

        .form-container {
            width: 600px;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .form-container h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-section {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }

        .form-group {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .form-group label {
            flex: 1 0 50%;
            margin-bottom: 5px;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            flex: 1 0 50%;
            padding: 8px;
            margin-bottom: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group select {
            width: 100%;
        }

        .form-group .document-buttons {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: 10px;
        }

        .document-button {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            margin: 0 5px;
            font-size: 14px;
            border: 1px solid #333;
            border-radius: 4px;
            text-align: center;
            cursor: pointer;
        }

        .document-button i {
            margin-left: 8px;
        }

        .form-check {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }

        .form-check input {
            margin-right: 10px;
        }

        .submit-button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            background-color: #333;
            color: white;
            border-radius: 4px;
            cursor: pointer;
        }

        .submit-button:hover {
            background-color: #555;
        }
    </style>
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
                <option value="Elementary Teacher">Kinder Teacher</option>
                <option value="High School Teacher">Elementary Teacher</option>
                <option value="Assistant Teacher">Junior High Teacher</option>
                <option value="Assistant Teacher">School Administrator</option>
                <option value="Elementary Teacher">Guidance Councilor</option>
                <option value="High School Teacher">It Suppot specialist</option>
                <option value="Assistant Teacher">Librarian</option>
                <option value="Assistant Teacher">School Nurse</option>
                <option value="Elementary Teacher">Finance/Accountanting off.</option>
                <option value="High School Teacher">Registrar</option>
                <option value="Assistant Teacher">Custodial/Maintenance</option>
                <option value="Assistant Teacher">Guard</option>
                <option value="Assistant Teacher">Excellent/Janitor</option>
            </select>
        </div>

        <div class="form-group">
            <label>Specific Subject:</label>
            <select name="specific_subject" required>
                <option value="Math">Math</option>
                <option value="Science">Science</option>
                <option value="English">English</option>
                <option value="History">History</option>
                <option value="Math">MAPEH</option>
                <option value="Math">Physical Education</option>
                <option value="Math">Filipino</option>
            </select>
        </div>

        <div class="form-group">
            <label>Employment Type:</label>
            <select name="employment_type" required>
                <option value="Full-Time">Full-Time</option>
                <option value="Part-Time">Part-Time</option>
            </select>
        </div>

        <div class="form-group">
            <label>Documents:</label>
            <div class="document-buttons">
                <div class="document-button">
                    Attach CV <i class="fas fa-download"></i>
                </div>
                <div class="document-button">
                    Attach Resume <i class="fas fa-download"></i>
                </div>
                <div class="document-button">
                    E-Signature <i class="fas fa-download"></i>
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
