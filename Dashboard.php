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
    <link rel="stylesheet" href="stylesheet/dashboard.css">
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
            <div class="search-container">
                <button class="search-button">
                    <i class="fas fa-search"></i> <!-- Font Awesome Search Icon -->
                </button>
                <input type="text" class="search-input" placeholder="Search...">
            </div>
            <div class="user-info">
                <img src="picture/ex.pic" alt="User Avatar" class="user-avatar">
                <span>cakelyn</span>
            </div>        
        </div>

        <!-- Job Process Panel -->
        <div class="content">

            <div class="panel">
            <h2>Job Applications</h2>
            <div class="job-process">
                <div class="job-card">
                    <i class="fas fa-user-tie"></i>
                    <p>CANDIDATES</p>
                    <h3>10</h3>
                </div>
                <div class="job-card">
                    <i class="fas fa-tasks"></i>
                    <p>IN-PROGRESS</p>
                    <h3>10</h3>
                </div>
            </div>
            </div>

            <!-- Employee Info Panel -->
            <div class="panel">
                <h2>EMPLOYEE</h2>
                <div class="job-process">
                    <div class="stat-card">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <p>TEACHER</p>
                        <h3>10</h3>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-award"></i>
                        <p>EXCELLENT</p>
                        <h3>10</h3>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-shield-alt"></i>
                        <p>GUARD</p>
                        <h3>10</h3>
                    </div>
                </div>
            </div>

            <!-- Calendar -->
            <!-- <div class="panel"> -->
            <div class="calendar-container">
                <div class="calendar-header">
                    <button id="prev-month">&#10094;</button>
                    <h2 id="month-year"></h2>
                    <button id="next-month">&#10095;</button>
                </div>
                <table class="calendar">
                    <thead>
                        <tr>
                            <th>Sun</th>
                            <th>Mon</th>
                            <th>Tue</th>
                            <th>Wed</th>
                            <th>Thu</th>
                            <th>Fri</th>
                            <th>Sat</th>
                        </tr>
                    </thead>
                    <tbody id="calendar-body">
                        <!-- Calendar days will be generated here by JavaScript -->
                    </tbody>
                </table>
            </div>
            <!-- </div> -->
        
        </div>
    </div>

    <script src="javascript/dashboard.js"></script>
 
</body>
</html>
