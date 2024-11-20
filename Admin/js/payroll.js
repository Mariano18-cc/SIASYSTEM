document.addEventListener("DOMContentLoaded", function() {
    // Show Payslip tab by default
    document.getElementById("Payslip").style.display = "block";
    document.querySelector(".tablink").classList.add("active");
    
    // Hide Attendance tab by default
    document.getElementById("Attendance").style.display = "none";
    
    document.querySelectorAll(".openModalBtn").forEach(function(button) {
        button.onclick = function() {
            document.getElementById("modal").style.display = "flex";
        };
    });

    document.querySelector(".close").onclick = function() {
        document.getElementById("modal").style.display = "none";
    };

    window.onclick = function(event) {
        if (event.target == document.getElementById("modal")) {
            document.getElementById("modal").style.display = "none";
        }
    };

    // Search functionality
    function searchEmployees() {
        const searchInput = document.querySelector('.search-input');
        const filter = searchInput.value.toLowerCase();
        const rows = document.querySelectorAll('.payslip-table tbody tr');

        rows.forEach(row => {
            const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            const position = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            const shouldShow = name.includes(filter) || position.includes(filter);
            row.style.display = shouldShow ? '' : 'none';
        });
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Add search event listener
        document.querySelector('.search-input').addEventListener('keyup', searchEmployees);

        // ... your existing DOMContentLoaded code ...
    });
});

function openTab(evt, tabName) {
    // Hide all tab content
    var tabcontent = document.getElementsByClassName("tabcontent");
    for (var i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Remove active class from all tab buttons
    var tablinks = document.getElementsByClassName("tablink");
    for (var i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
    }

    // Show the selected tab content and mark the button as active
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.classList.add("active");
}

// Function to update the date to show "Month Day, Year" (e.g., October 12, 2024)
function updateDate() {
    const currentDate = new Date();
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const formattedDate = currentDate.toLocaleDateString('en-US', options);
    
    // Update all date containers
    const dateContainers = document.querySelectorAll('.date-container');
    dateContainers.forEach(container => {
        const dateSpan = container.querySelector('.current-date-display') || document.createElement('span');
        dateSpan.className = 'current-date-display';
        dateSpan.textContent = formattedDate;
        if (!container.querySelector('.current-date-display')) {
            container.appendChild(dateSpan);
        }
    });
    
    // Keep the attendance date update
    const attendanceDateElement = document.getElementById("attendance-date");
    if (attendanceDateElement) {
        attendanceDateElement.innerHTML = formattedDate;
    }
}

// Call updateDate when the page loads
document.addEventListener('DOMContentLoaded', updateDate);

// Update date and time every second
function updateDateTime() {
    const now = new Date();
    const date = now.toLocaleDateString();
    const time = now.toLocaleTimeString();
    document.getElementById("datetime").innerHTML = `Date: ${date} | Time: ${time}`;
}

setInterval(updateDateTime, 1000); // Update every second
