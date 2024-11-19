<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Teacher Dashboard</title>
  <link rel="stylesheet" href="css/inbox.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="logo">
      <img src="../picture/logo.png" alt="Human Resource">
    </div>
    <h2 style="color: white; text-align: center;">HUMAN RESOURCE</h2>
    <ul style="list-style-type: none; padding-left: 0;">
        <li><a href="teacher_p.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="inbox.php"><i class="fas fa-inbox"></i> Inbox</a></li>
        <li><a href="stlist.php"><i class="fas fa-users"></i> Student List</a></li>
        <li><a href="gradelist.php"><i class="fas fa-pencil-alt"></i> Grading List</a></li>
        <li><a href="leave.php"><i class="fas fa-envelope-open-text"></i> Leave Request</a></li>
        <li><a href="payroll_t.php"><i class="fas fa-wallet"></i> Payroll</a></li>
    </ul>
    <div class="bottom-content">
            <a href="../index.php"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>
  </div>

  <div class="main-content">
    <!-- Header -->
    <div class="header">
        <div class="user-info">
            <img src="../picture/ex.pic.jpg" alt="User Avatar" class="user-avatar">
            <span>cakelyn</span>
        </div>
        
    </div>
    <!-- Content -->
    <div class="content">
      <div class="inbox-container">
        <div class="inbox-header">
          <h2>Inbox</h2>
        </div>
        
        <!-- Message type tabs -->
        <div class="message-tabs">
          <select class="message-filter">
            <option value="all">All Messages</option>
            <option value="email">Email</option>
            <option value="announcement">Announcement</option>
            <option value="alert">Alert</option>
          </select>
        </div>

        <div class="inbox-filters">
          <div class="search-bar">
            <input type="text" placeholder="Search messages...">
            <i class="fas fa-search"></i>
          </div>
        </div>

        <div class="messages-list">
          <!-- Unread message -->
          <div class="message-item unread">
            <div class="sender-info">
              <img src="../picture/default-avatar.png" alt="Sender Avatar">
              <span class="sender-name">John Doe</span>
            </div>
            <div class="message-content">
              <div class="message-subject">Meeting Schedule Update</div>
              <div class="message-preview">The department meeting has been rescheduled to...</div>
            </div>
            <div class="message-time">10:30 AM</div>
            <div class="message-actions">
              <i class="fas fa-trash"></i>
            </div>
          </div>

          <!-- Read message (without unread class) -->
          <div class="message-item">
            <div class="sender-info">
              <img src="../picture/default-avatar.png" alt="Sender Avatar">
              <span class="sender-name">Jane Smith</span>
            </div>
            <div class="message-content">
              <div class="message-subject">Curriculum Updates</div>
              <div class="message-preview">Please review the attached curriculum changes...</div>
            </div>
            <div class="message-time">Yesterday</div>
            <div class="message-actions">
              <i class="fas fa-trash"></i>
            </div>
          </div>
        </div>

        <div class="inbox-pagination">
          <button><i class="fas fa-chevron-left"></i></button>
          <span>1 of 10</span>
          <button><i class="fas fa-chevron-right"></i></button>
        </div>
      </div>
    </div>
  </div>
</body>
</html>