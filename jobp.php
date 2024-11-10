<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRMS Job Process</title>
    <link rel="stylesheet" href="stylesheet/jobp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo">
      <img src="picture/logo.png" alt="Human Resource">
    </div>
    <h2>HUMAN RESOURCE</h2>
    <ul style="list-style-type: none; padding-left: 0;">
      <li><a href="Dashboard.html"><i class="fas fa-home"></i> Dashboard</a></li>
      <li><a href="jobp.html" class="active"><i class="fas fa-briefcase"></i> Job Process</a></li>
      <li><a href="employee.html"><i class="fas fa-users"></i> Employee</a></li>
      <li><a href="payroll.html"><i class="fas fa-wallet"></i> Payroll</a></li>
      <li><a href="printr.html"><i class="fas fa-receipt"></i> Print Receipt</a></li>
      <li><a href="index.html"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
  </ul>
  </div>

  <!-- Main content -->
  <div class="main">
    <!-- Header -->
    <div class="header">
      <div class="user-info">
        <img src="picture/ex.pic" alt="Human Resource">
        <span>Cakelyn</span><br>
        <p class="department".> Human Resource Admin</p>
</div>
    </div>

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
            <tr>
              <td>001</td>
              <td>Jane Doe</td>
              <td>In Review</td>
              <td>
                <div class="action-buttons">
                  <button class="view-button">View</button>
                  <button class="edit-button">Edit</button>
                </div>
              </td>
            </tr>
            <tr>
              <td>002</td>
              <td>John Smith</td>
              <td>Interview Scheduled</td>
              <td>
                <div class="action-buttons">
                  <button class="view-button">View</button>
                  <button class="edit-button">Edit</button>
                </div>
              </td>
            </tr>
            <tr>
              <td>003</td>
              <td>Alice Johnson</td>
              <td>Rejected</td>
              <td>
                <div class="action-buttons">
                  <button class="view-button">View</button>
                  <button class="edit-button">Edit</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="javascript/jobp.js"></script>
</body>
</html>



