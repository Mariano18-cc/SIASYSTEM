// Get the modal
var modal = document.getElementById("requestModal");
var btn = document.querySelector(".request-btn");
var span = document.querySelector(".close");
var cancelBtn = document.querySelector(".cancel-btn");

// Open modal
btn.onclick = function() {
    modal.style.display = "block";
}

// Close modal
span.onclick = function() {
    modal.style.display = "none";
}

cancelBtn.onclick = function() {
    modal.style.display = "none";
}

// Close if clicked outside
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Get the form
const form = document.querySelector('.request-form');

form.onsubmit = function(e) {
    e.preventDefault();
    
    // Get form fields
    const requestDate = form.querySelector('input[name="request_date"]').value;
    const statement = form.querySelector('textarea[name="statement"]').value;
    
    // Validate fields
    if (!requestDate) {
        alert('Please select a request date');
        return;
    }
    
    if (!statement.trim()) {
        alert('Please enter a statement');
        return;
    }
    
    // If validation passes, proceed with submission
    // Here you would typically handle the form submission to your backend
    
    // Close the request modal
    modal.style.display = "none";
    
    // Show success message
    const successModal = document.createElement('div');
    successModal.className = 'success-modal';
    successModal.innerHTML = `
        <div class="success-content">
            <h3>Request Submitted Successfully</h3>
            <button class="ok-btn">OK</button>
        </div>
    `;
    
    document.body.appendChild(successModal);
    
    // Handle OK button click
    const okBtn = successModal.querySelector('.ok-btn');
    okBtn.onclick = function() {
        successModal.remove();
        // Optional: Reset form after successful submission
        form.reset();
    }
}
