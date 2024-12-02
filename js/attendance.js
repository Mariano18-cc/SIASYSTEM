function updateDateTime() {
    const now = new Date();
    const formattedDateTime = now.toLocaleString([], {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
        hour: '2-digit', minute: '2-digit', second: '2-digit'
    });
    document.getElementById('current-date').textContent = formattedDateTime;
}

setInterval(updateDateTime, 1000);
updateDateTime();

function confirmTimeOut() {
    return confirm('Are you sure you want to mark your time-out? This action cannot be undone.');
}

const modal = document.getElementById('timeInModal');
const closeBtn = document.getElementsByClassName('close')[0];

function markTime(employeeId) {
    const currentTime = new Date().toLocaleTimeString();
    document.getElementById('timeInValue').textContent = currentTime;
    modal.style.display = 'block';

    // Find and fade out the employee details card
    const detailsCard = document.querySelector('.employee-details-card');
    if (detailsCard) {
        detailsCard.classList.add('fade-out');
        
        // Remove the card after animation completes
        setTimeout(() => {
            detailsCard.style.display = 'none';
            // Clear the input field
            document.getElementById('employeeInput').value = '';
        }, 500);
    }

    // Auto close modal after 3 seconds
    setTimeout(() => {
        closeModal();
    }, 3000);

    // Make the AJAX call to record the time
    fetch('attendance.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `employeeInput=${employeeId}&mark_in=true`
    });
}

function closeModal() {
    modal.style.display = 'none';
}

closeBtn.onclick = closeModal;

window.onclick = function(event) {
    if (event.target == modal) {
        closeModal();
    }
} 