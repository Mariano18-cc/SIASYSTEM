const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();

const monthYearElement = document.getElementById("month-year");
const calendarBody = document.getElementById("calendar-body");

function generateCalendar(month, year) {
    calendarBody.innerHTML = ""; // Clear existing calendar
    
    const firstDay = new Date(year, month).getDay(); // First day of the month
    const daysInMonth = 32 - new Date(year, month, 32).getDate(); // Get the number of days in the month
    
    // Update month and year in the header
    monthYearElement.textContent = `${monthNames[month]} ${year}`;
    
    // Create the rows and cells for the calendar
    let date = 1;
    for (let i = 0; i < 6; i++) {  // Create 6 rows (the maximum number of weeks in a month)
        const row = document.createElement("tr");
        
        for (let j = 0; j < 7; j++) {  // Create 7 cells (Sunday to Saturday)
            if (i === 0 && j < firstDay) {
                const cell = document.createElement("td");
                cell.textContent = "";
                row.appendChild(cell);
            } else if (date > daysInMonth) {
                break;
            } else {
                const cell = document.createElement("td");
                cell.textContent = date;
                
                // Highlight today's date
                if (date === new Date().getDate() && month === new Date().getMonth() && year === new Date().getFullYear()) {
                    cell.classList.add("current-day");
                }

                row.appendChild(cell);
                date++;
            }
        }
        
        calendarBody.appendChild(row); // Append each row to the calendar body
    }
}

// Event listeners for previous and next month buttons
document.getElementById("prev-month").addEventListener("click", function() {
    currentMonth--;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    generateCalendar(currentMonth, currentYear);
});

document.getElementById("next-month").addEventListener("click", function() {
    currentMonth++;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    generateCalendar(currentMonth, currentYear);
});

// Initial calendar generation
generateCalendar(currentMonth, currentYear);

// Sample schedule data - you can replace this with data from your backend
const scheduleData = [
    { day: 'Mon', time: '9:00 AM - 10:30 AM' },
    { day: 'Tue', time: '11:00 AM - 12:30 PM' },
    { day: 'Wed', time: '2:00 PM - 3:30 PM' },
    { day: 'Thu', time: '1:00 PM - 2:30 PM' },
    { day: 'Fri', time: '10:00 AM - 11:30 AM' }
];

function renderSchedule() {
    const scheduleBody = document.getElementById('scheduleBody');
    scheduleBody.innerHTML = ''; // Clear existing content

    scheduleData.forEach(slot => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="day">${slot.day}</td>
            <td class="time">${slot.time}</td>
        `;
        scheduleBody.appendChild(row);
    });
}

// Call this when the page loads
document.addEventListener('DOMContentLoaded', renderSchedule);

// Add this to your calendar cell creation code
document.querySelectorAll('.calendar td').forEach(td => {
    td.addEventListener('click', function() {
        // Remove selected class from all cells
        document.querySelectorAll('.calendar td').forEach(td => {
            td.classList.remove('selected-day');
        });
        // Add selected class to clicked cell
        this.classList.add('selected-day');
    });
});