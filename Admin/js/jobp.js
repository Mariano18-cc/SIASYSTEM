// Open the modal and fetch applicant details
function openModal(applicantId) {
    fetch(`jobp.php?id=${applicantId}`)
        .then(response => response.json())
        .then(data => {
            const modal = document.getElementById('applicantModal');
            document.getElementById('modal_applicant_id').textContent = data.applicant_id || 'N/A';
            document.getElementById('modal_full_name').textContent = `${data.fname} ${data.mname} ${data.lname}` || 'N/A';
            document.getElementById('modal_email').textContent = data.email || 'N/A';
            document.getElementById('modal_phone').textContent = data.phone || 'N/A';
            document.getElementById('modal_bday').textContent = data.bday || 'N/A';
            document.getElementById('modal_position').textContent = data.applying_position || 'N/A';
            document.getElementById('modal_subject').textContent = data.subject || 'N/A';
            document.getElementById('modal_employment_type').textContent = data.employment_type || 'N/A';
            document.getElementById('modal_status').textContent = data.status || 'N/A';

            const resumeLink = document.getElementById('modal_resume_link');
            if (data.resume) {
                resumeLink.href = data.resume;
                resumeLink.textContent = 'Open Resume';
                resumeLink.removeAttribute('disabled');
            } else {
                resumeLink.href = '#';
                resumeLink.textContent = 'Resume not available';
                resumeLink.setAttribute('disabled', 'disabled');
            }

            modal.style.display = 'block';
        })
        .catch(error => {
            console.error('Error fetching applicant details:', error);
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
};
