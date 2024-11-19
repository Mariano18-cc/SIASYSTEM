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

// JavaScript for modal functionality
var modal = document.getElementById("add-employee-modal");
var btn = document.getElementById("add-button");
var closeModal = document.getElementById("close-modal");

// When the user clicks the Add button, open the modal
btn.onclick = function() {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
closeModal.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

