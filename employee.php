<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="stylesheet/employee.css">
</head>
<body>

    <!-- Sidebar -->
    <nav class="sidebar"> 
        <header>
            <div class="logo">
                <img src="picture/logo.png">
              </div>
              <h1>HUMAN RESOURCE</h1>
            </div>
        </header>
         
        <ul >
            <hr>
            <li><a href="Dashboard.php"><i class="fas fa-home"></i><span>Dashboard</span></a></li>
            <li><a href="jobp.php"><i class="fas fa-briefcase"></i><span>Job Process</span></a></li>
            <li><a href="employee.php"><i class="fas fa-users"></i><span>Employee</span></a></li>
            <li><a href="payroll.php"><i class="fas fa-wallet"></i><span>Payroll</span></a></li>
            <li><a href="printr.php"><i class="fas fa-receipt"></i><span>Print Receipt</span></a></li>

            <div class="bottom-content"><li><a href="login.html"><i class="fas fa-sign-out-alt"></i><span>Log Out</span></a></li></div>
        </ul>
    </nav>

    <!-- Main content -->
    <div class="main">
        <!-- Header -->
        <div class="header">
            <div class="user-info">
                <img src="picture/ex.pic" alt="User Avatar" class="user-avatar">
                <span>Cakelyn</span><br>
                <p class="department">Human Resource Admin</p>
            </div>
        </div>

        <div class="search-container">
          <input type="text" class="search-input" placeholder="Search Employee...">
          <button class="add-button">Add</button>
      </div>

            <!-- Second Grid: Employee Info Table -->
            <div class="grid-item employee-table">
                <table class="employee-info-table">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Employee ID</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Overtime</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Rows will be added here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="javascript/dashboard.js"></script>
</body>
</html>
