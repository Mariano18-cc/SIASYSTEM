// javascript/attendance_t.js

// Function to update date and time
function updateDateTime() {
    const now = new Date();
    const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true };
    document.getElementById('date-time').innerText = now.toLocaleString('en-US', options);
}

// Update date and time every second
setInterval(updateDateTime, 1000);
updateDateTime(); // Initial call

