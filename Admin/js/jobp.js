// Open the modal and fetch applicant details
function openModal(applicantId) {
    fetch(`jobp.php?id=${applicantId}`)
        .then(response => response.json())
        .then(data => {
            const modal = document.getElementById('applicantModal');
            const modalContent = `
                <div class="modal-content">
                    <span class="close" onclick="closeModal()">&times;</span>
                    <div class="modal-header">
                        <h3>Applicant Details</h3>
                    </div>
                    <div class="modal-body">
                        <div class="info-row">
                            <span class="info-label">Applicant ID:</span>
                            <span class="info-value" id="modal_applicant_id">${data.applicant_id}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Full Name:</span>
                            <span class="info-value" id="modal_full_name">${data.fname} ${data.mname} ${data.lname}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value" id="modal_email">${data.email}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Birthday:</span>
                            <span class="info-value" id="modal_bday">${data.bday}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Position:</span>
                            <span class="info-value" id="modal_position">${data.applying_position}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status:</span>
                            <span class="info-value" id="modal_status">${data.status}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Resume:</span>
                            <a id="modal_resume_link" href="${data.resume || '#'}" ${!data.resume ? 'disabled' : ''}>
                                ${data.resume ? 'Open Resume' : 'Resume not available'}
                            </a>
                        </div>
                    </div>
                </div>
            `;
            
            modal.innerHTML = modalContent;
            modal.style.display = 'flex';
        });
}

// Close the modal
function closeModal() {
    document.getElementById('applicantModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('applicantModal');
    if (event.target === modal) {
        closeModal();
    }
}
