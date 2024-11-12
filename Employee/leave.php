<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Teacher Dashboard</title>
  <link rel="stylesheet" href="stylesheet/t_leave.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo">
      <img src="picture/logow.jpg" alt="Human Resource">
    </div>
    <h2>HUMAN RESOURCE</h2>
    <ul style="list-style-type: none; padding-left: 0;">
        <li><a href="t_dashboard.html"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="t_leave.html"><i class="fas fa-envelope-open-text"></i> Leave Request</a></li>
        <li><a href="t_attendance.html"><i class="fas fa-calendar-check"></i> Attendance</a></li>
        <li><a href="t_payroll.html"><i class="fas fa-money-check-alt"></i> Payroll</a></li>
        <li><a href="index.html"><i class="fas fa-sign-out-alt"></i> Log Out</a></li>
    </ul>
  </div>

  <div class="main">
    <!-- Header -->
    <div class="header">
        <div class="user-info">
            <img src="picture/ex.pic" alt="User Avatar" class="user-avatar">
            <span>cakelyn</span>
        </div>
    </div>

    <div class="main-content">
        <!-- Header with button and trash can -->
        <div class="header">
          <button class="request-button" id="requestButton"><i class="fas fa-plus"></i> Request Leave</button>
          <button class="trash-button" title="Delete selected">
            <i class="fas fa-trash trash-icon"></i>
          </button> <!-- Ensure this tag is properly closed -->
        </div>
    
        <!-- Leave Request Table -->
        <div class="table-container">
            <table>
              <thead>
                <tr>
                  <th><input type="checkbox" id="checkAll"></th> <!-- Checkbox to select all -->
                  <th>Request ID</th>
                  <th>Title</th>
                  <th>Request Date</th>
                  <th>Statement</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="requestTableBody">
                <tr>
                  <td><input type="checkbox" class="item"></td>
                  <td>001</td>
                  <td>Sick Leave</td>
                  <td>2024-10-01</td>
                  <td>Fever and cold</td>
                  <td>Pending</td>
                </tr>
                <tr>
                  <td><input type="checkbox" class="item"></td>
                  <td>002</td>
                  <td>Vacation</td>
                  <td>2024-10-05</td>
                  <td>Family trip</td>
                  <td>Approved</td>
                </tr>
                <tr>
                  <td><input type="checkbox" class="item"></td>
                  <td>003</td>
                  <td>Emergency</td>
                  <td>2024-10-15</td>
                  <td>Family emergency</td>
                  <td>Rejected</td>
                </tr>
              </tbody>
            </table>
        </div>

        <!-- Deleted Items Section -->
        <div class="deleted-items" id="deletedItems" style="display: none;">
            <h2>Deleted Items</h2>
            <ul id="deletedList"></ul>
        </div>

        <!-- Popup Form for Leave Request -->
   
<div class="popup" id="requestPopup">
  <div class="popup-content">
      <span class="close-btn" id="closePopup">&times;</span>
      <h2>Request Leave</h2>
      <form id="leaveRequestForm">
          <label for="leaveType">Leave Type:</label>
          <select id="leaveType" required>
              <option value="" disabled selected>Select leave type</option>
              <option value="Sick Leave">Sick Leave</option>
              <option value="Maternity/Paternity Leave">Maternity/Paternity Leave</option>
              <option value="Funeral Leave">Funeral Leave</option>
              <option value="Emergency Leave">Emergency Leave</option>
          </select>

          <label for="requestDate">Request Date:</label>
          <input type="date" id="requestDate" required>

          <label for="endDate">End Date:</label>
          <input type="date" id="endDate" required>

          <label for="statement">Statement (Optional):</label>
          <textarea id="statement" placeholder="Optional statement"></textarea>

          <label for="attachFile">Attach File:</label>
          <input type="file" id="attachFile">

          <button type="submit">Submit Request</button>
      </form>
  </div>
</div>
<!-- Success Message Popup -->
<div class="popup" id="successPopup">
  <div class="popup-content">
      <span class="close-btn" id="closeSuccessPopup">&times;</span>
      <h2>Request Submitted Successfully</h2>
      <p>Your leave request has been submitted. Thank you!</p>
      <button id="okButton">OK</button>
  </div>
</div>


    <script src="javascript/t_leave.js"></script>
</body>
</html>
