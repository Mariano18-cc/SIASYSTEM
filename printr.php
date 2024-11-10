<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRMS Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="stylesheet/printr.css">
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="picture/logow.jpg" alt="Human Resource">
        </div>
        <h2>HUMAN RESOURCE</h2>
        <ul style="list-style-type: none; padding-left: 0;">
            <li><a href="Dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="jobp.php" class="active"><i class="fas fa-briefcase"></i> Job Process</a></li>
            <li><a href="employee.php"><i class="fas fa-users"></i> Employee</a></li>
            <li><a href="payroll.php"><i class="fas fa-wallet"></i> Payroll</a></li>
            <li><a href="printr.php"><i class="fas fa-receipt"></i> Print Receipt</a></li>
            <li><a href="index.php"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="main">
        <!-- Header -->
        <div class="header">
            <div class="user-info">
                <img src="picture/ex.pic" alt="User Avatar" class="user-avatar">
                <span>cakelyn</span>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tablink active" onclick="openTab(event, 'Payslip')">Payslip</button>
            <button class="tablink" onclick="openTab(event, 'ElectricBill')">Electric Bill</button>
            <button class="tablink" onclick="openTab(event, 'WaterBill')">Water Bill</button>
            <button class="tablink" onclick="openTab(event, 'Others')">Others</button>
        </div>

        <!-- Tab Content -->
        <div id="Payslip" class="tabcontent" style="display: block;">
            <h2>Payslip Section</h2>
            <!-- Content for Payslip goes here -->
        </div>
        <div id="ElectricBill" class="tabcontent" style="display: none;">
            <h2>Electric Bill Section</h2>
            <!-- Content for Electric Bill goes here -->
        </div>
        <div id="WaterBill" class="tabcontent" style="display: none;">
            <h2>Water Bill Section</h2>
            <!-- Content for Water Bill goes here -->
        </div>
        <div id="Others" class="tabcontent" style="display: none;">
            <h2>Others Section</h2>
            <!-- Content for Others goes here -->
        </div>
    </div>

    <script src="javascript/payroll.js"></script>
</body>
</html>
