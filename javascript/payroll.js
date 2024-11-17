 // Function to switch between tabs
 function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

// Function to update the date to show "Month Day, Year" (e.g., October 12, 2024)
function updateDate() {
    const dateElement = document.getElementById("current-date");
    const currentDate = new Date();
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const formattedDate = currentDate.toLocaleDateString('en-US', options);
    dateElement.innerHTML = formattedDate;
    
    // Also update the attendance date
    const attendanceDateElement = document.getElementById("attendance-date");
    attendanceDateElement.innerHTML = formattedDate;
}

// Update the date immediately on page load
updateDate();
 // for attendance

 // Update date and time every second
function updateDateTime() {
    const now = new Date();
    const date = now.toLocaleDateString();
    const time = now.toLocaleTimeString();
    document.getElementById("datetime").innerHTML = `Date: ${date} | Time: ${time}`;
}

setInterval(updateDateTime, 1000); // Update every second

// Simulated employee attendance data
const attendanceHistory = {
    "John Doe": [
        { date: "11/12/2024", arrivalTime: "08:55:00 AM", status: "On Time" },
        { date: "11/11/2024", arrivalTime: "09:10:00 AM", status: "Late" },
    ],
    "Jane Smith": [
        { date: "11/12/2024", arrivalTime: "09:05:00 AM", status: "Late" },
        { date: "11/11/2024", arrivalTime: "08:45:00 AM", status: "On Time" },
    ],
    "Alex Brown": [
        { date: "11/12/2024", arrivalTime: "08:45:00 AM", status: "On Time" },
        { date: "11/11/2024", arrivalTime: "08:55:00 AM", status: "On Time" },
    ],
};

// Show attendance history in a modal when employee name is clicked
function showAttendanceHistory(employeeName) {
    document.getElementById("employeeName").textContent = employeeName;

    // Get the history of the employee
    const history = attendanceHistory[employeeName];
    const historyTableBody = document.getElementById("historyTable").getElementsByTagName("tbody")[0];
    
    // Clear previous data
    historyTableBody.innerHTML = "";

    // Add new history data
    history.forEach(record => {
        const row = historyTableBody.insertRow();
        row.insertCell(0).textContent = record.date;
        row.insertCell(1).textContent = record.arrivalTime;
        row.insertCell(2).textContent = record.status;
    });

    // Show the modal
    document.getElementById("attendanceHistoryModal").style.display = "block";
}

// Close the modal
function closeHistoryModal() {
    document.getElementById("attendanceHistoryModal").style.display = "none";
}
