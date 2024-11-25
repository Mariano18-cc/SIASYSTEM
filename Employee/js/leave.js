document.addEventListener('DOMContentLoaded', function() {
    // Get all alert messages and overlays
    const alerts = document.querySelectorAll('.alert');
    const overlay = document.querySelector('.alert-overlay');
    
    if (alerts.length > 0 && overlay) {
        setTimeout(() => {
            // Fade out alerts
            alerts.forEach(alert => {
                alert.style.opacity = '0';
            });
            // Fade out overlay
            overlay.style.opacity = '0';
            
            // Remove elements after fade out
            setTimeout(() => {
                alerts.forEach(alert => alert.remove());
                overlay.remove();
            }, 300);
        }, 3000);
    }
});

// Get the modal
var modal = document.getElementById("requestModal");
var btn = document.querySelector(".request-btn");
var span = document.querySelector(".close");
var cancelBtn = document.querySelector(".cancel-btn");
var form = document.querySelector('.request-form');

// Open modal
btn.onclick = function() {
    modal.style.display = "block";
    
    // Set minimum date to today for start date
    const today = new Date().toISOString().split('T')[0];
    const startDateInput = document.querySelector('input[name="start_date"]');
    const endDateInput = document.querySelector('input[name="end_date"]');
    
    startDateInput.min = today;
    endDateInput.min = today;
    
    // Update end date minimum when start date changes
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
    });
}

// Close modal functions
function closeModal() {
    modal.style.display = "none";
    form.reset();
}

span.onclick = closeModal;
cancelBtn.onclick = closeModal;

// Close if clicked outside
window.onclick = function(event) {
    if (event.target == modal) {
        closeModal();
    }
}

// Form submission
form.onsubmit = function(e) {
    e.preventDefault();
    
    // Get form fields
    const startDate = form.querySelector('input[name="start_date"]').value;
    const endDate = form.querySelector('input[name="end_date"]').value;
    const statement = form.querySelector('textarea[name="statement"]').value;
    
    // Validate fields
    if (!startDate || !endDate) {
        alert('Please select both start and end dates');
        return false;
    }
    
    if (new Date(endDate) < new Date(startDate)) {
        alert('End date cannot be before start date');
        return false;
    }
    
    if (!statement.trim()) {
        alert('Please enter a statement');
        return false;
    }
    
    // If validation passes, submit the form
    this.submit();
    closeModal();
}
