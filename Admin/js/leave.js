// Modal functionality
const modal = document.getElementById('statementModal');
const closeBtn = document.getElementsByClassName('close-modal')[0];

function showStatement(requestId, statement, employeeId, leaveType) {
    document.getElementById('statementText').innerText = statement;
    document.getElementById('employeeId').innerText = employeeId;
    document.getElementById('leaveType').innerText = leaveType;
    modal.style.display = "block";
    document.body.style.overflow = 'hidden';
}

closeBtn.onclick = function() {
    modal.style.display = "none";
    document.body.style.overflow = 'auto';
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
        document.body.style.overflow = 'auto';
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === "Escape" && modal.style.display === "block") {
        modal.style.display = "none";
        document.body.style.overflow = 'auto';
    }
});
