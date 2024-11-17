<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance Tracker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('picture/logow.jpg'); /* Set logo as the background image */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh; /* Ensure the background covers the entire screen */
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        h1 {
            text-align: center;
            color: #fff;
        }
        .container {
            max-width: 800px;
            width: 100%;
            padding: 20px;
            background-color: #082C66; /* Set panel background color to #082C66 */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }


        .date-container {
            margin-bottom: 20px;
            color: #fff;
        }


        #current-date {
            font-size: 18px;
            font-weight: bold;
        }


        label, input[type="text"] {
            display: block;
            width: 100%;
            margin: 10px 0;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }


        input[type="button"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
            margin: 5px 0;
        }
        input[type="button"]:hover {
            background-color: #218838;
        }


        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }


        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }


        th {
            background-color: #007bff;
            color: white;
        }


        td.late {
            color: red;
        }


        td.early {
            color: green;
        }


        td.overtime {
            color: orange;
        }


    </style>
</head>
<body>


<div class="container">
    <h1>Employee Attendance Tracker</h1>
   
    <!-- Today's Date and Time -->
    <div class="date-container">
        <p>Today's Date and Time is:</p>
        <div id="current-date"></div>
    </div>
    <input type="text" id="employeeName" placeholder="Enter name">
   
    <input type="button" value="Mark Time-In" onclick="markTimeIn()">
    <input type="button" value="Mark Time-Out" onclick="markTimeOut()">


    <table id="attendanceTable">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Date</th>
                <th>Status</th>
                <th>Time-In</th>
                <th>Time-Out</th>
                <th>Time Worked</th>
            </tr>
        </thead>
        <tbody id="attendanceBody">
            <!-- Attendance records will appear here -->
        </tbody>
    </table>
</div>


<script>
    let timeInRecords = {}; // To store the time-in for each employee


    function markTimeIn() {
        const employeeName = document.getElementById('employeeName').value;
        if (!employeeName) {
            alert("Please enter the employee's name.");
            return;
        }


        const timeIn = new Date();
        if (!timeInRecords[employeeName]) {
            timeInRecords[employeeName] = [];
        }


        timeInRecords[employeeName].push({ timeIn });


        const timeInFormatted = timeIn.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });


        // Add record to the table
        const tableBody = document.getElementById('attendanceBody');
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${employeeName}</td>
            <td>${timeIn.toLocaleDateString()}</td>
            <td>Present</td>
            <td>${timeInFormatted}</td>
            <td>-</td>
            <td>-</td>
        `;
        tableBody.appendChild(row);
    }


    function markTimeOut() {
        const employeeName = document.getElementById('employeeName').value;
        if (!employeeName || !timeInRecords[employeeName] || timeInRecords[employeeName].length === 0) {
            alert("Please mark time-in first.");
            return;
        }


        const timeOut = new Date();
        const timeIn = timeInRecords[employeeName].pop().timeIn; // Get the most recent time-in
        const timeOutFormatted = timeOut.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });


        // Calculate time worked
        const timeWorked = Math.round((timeOut - timeIn) / 60000); // Time worked in minutes
        const standardWorkHours = 480; // Assuming 8 hours workday (480 minutes)
        let status = "On Time";
       
        if (timeWorked < standardWorkHours) {
            status = "Absent";
        } else if (timeWorked > standardWorkHours) {
            status = "Overtime";
        }


        const tableBody = document.getElementById('attendanceBody');
        const rows = tableBody.getElementsByTagName('tr');
        for (let row of rows) {
            const cells = row.cells;
            if (cells[0].innerText === employeeName && cells[4].innerText === '-') {
                cells[4].innerText = timeOutFormatted;
                cells[5].innerText = timeWorked + " mins";
                cells[2].innerText = status;


                if (status === "Absent") {
                    cells[2].classList.add("late");
                } else if (status === "Overtime") {
                    cells[2].classList.add("overtime");
                } else {
                    cells[2].classList.add("early");
                }


                break;
            }
        }
    }

    // Function to update the date and time every second
    function updateDateTime() {
        const now = new Date();
        const formattedDateTime = now.toLocaleString([], {
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
            hour: '2-digit', minute: '2-digit', second: '2-digit'
        });
        document.getElementById('current-date').textContent = formattedDateTime;
    }

    // Call updateDateTime every second
    setInterval(updateDateTime, 1000);

    // Initial call to display the date and time immediately
    updateDateTime();
</script>
</body>
</html>