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
    <link rel="stylesheet" href="stylesheet/payroll.css">
</head>
<body>
<div class="dashboard">
    <aside class="sidebar">
      <div class="logo">
        <img src="picture/logo.png" alt="user-info">
      </div>
      <h2>Human Resources</h2>
      <ul style="list-style-type: none; padding-left: 0;">
        <li><a href="Dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="jobp.php" class="active"><i class="fas fa-briefcase"></i> Job Process</a></li>
        <li><a href="employee.php"><i class="fas fa-users"></i> Employee</a></li>
        <li><a href="payroll.php"><i class="fas fa-wallet"></i> Payroll</a></li>
        <li><a href="printr.php"><i class="fas fa-receipt"></i> Print Receipt</a></li>
        <div class="bottom-content"><li><a href="login.php"><i class="fas fa-sign-out-alt"></i><span>Log Out</span></a></li></div>
    </ul>
    </aside>

        <main class="main-content">
      <header class="header">
        <div class="search-container">
            <button class="search-button">
            </button>
            <input type="text" class="search-input" placeholder="Search...">
        </div>
        <div class="user-info">
          <img src="picture/ex.pic.jpg" alt="User Avatar">

         
        </div>
      </header>

        <div class="tabs">
            <button class="tablink active" onclick="openTab(event, 'Payslip')">Payslip</button>
            <button class="tablink" onclick="openTab(event, 'Attendance')">Attendance</button>
        </div>

        <div id="Payslip" class="tabcontent" style="display: block;">
            <div class="payslip-header">
                <button class="print-button">PRINT</button>
                <button class="service-button">CONTACT OF SERVICE</button>
                <button class="plantilla-button">PLANTILLA</button>
    </div>
     <!-- Date Container -->
     <div class="date-container">
        <p>Today's Date is:</p>
        <div id="current-date"></div>
            </div>
            <table class="payslip-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Minimum Rate</th>
                        <th>Daily Rate</th>
                        <th>Salary</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><div class="circle red">JP</div></td>
                        <td>Mangmang, Jay Prince</td>
                        <td>Teacher</td>
                        <td>P200</td>
                        <td>P1,200.00</td>
                        <td>P36,000.00</td>
                        <td><button class="view-button">View</button></td>
                    </tr>
                    <tr>
                        <td><div class="circle orange">JP</div></td>
                        <td>Jay Prince, Pogi</td>
                        <td>Teacher</td>
                        <td>P200</td>
                        <td>P1,200.00</td>
                        <td>P36,000.00</td>
                        <td><button class="view-button">View</button></td>
                    </tr>
                    <tr>
                        <td><div class="circle green">JS</div></td>
                        <td>Jay Prince, Sobrang Pogi</td>
                        <td>Teacher</td>
                        <td>P200</td>
                        <td>P1,200.00</td>
                        <td>P36,000.00</td>
                        <td><button class="view-button">View</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="Attendance" class="tabcontent">
            <h2>Attendance Section</h2>
            <!-- You can add the attendance section here -->
        </div>
    </div>

    <script>
         // Function to display the current date
         function displayCurrentDate() {
            const today = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            };
            const formattedDate = today.toLocaleDateString('en-US', options);

            // Display the formatted date inside the #current-date element
            document.getElementById('current-date').innerHTML = formattedDate;
        }

        // Call the function to display the date when the page loads
        window.onload = displayCurrentDate;
// for tablinks
    function openTab(evt, tabName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablink" and remove the class "active"
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}
//
    </script>
</body>
</html>
