// Open the modal and populate it with applicant details
function openModal(applicantId, applicantName, applicantStatus) {
    document.getElementById("applicantId").innerText = applicantId;
    document.getElementById("applicantName").innerText = applicantName;
    document.getElementById("applicantStatus").innerText = applicantStatus;
    
    document.getElementById("viewModal").style.display = "block";
}

// Close the modal
function closeModal() {
    document.getElementById("viewModal").style.display = "none";
}

function openModal(applicantId) {
    // Fetch applicant details from the server
    fetch(`jobp.php?id=${applicantId}`)
        .then(response => response.json())
        .then(data => {
            // Fill the modal with the applicant's data
            document.getElementById('modal_applicant_id').innerText = data.applicant_id;
            document.getElementById('modal_full_name').innerText = `${data.fname} ${data.mname} ${data.lname}`;
            document.getElementById('modal_email').innerText = data.email;
            document.getElementById('modal_bday').innerText = data.bday;
            document.getElementById('modal_position').innerText = data.applying_position;
            document.getElementById('modal_status').innerText = data.status;

            // If resume exists, set the link; otherwise, provide a fallback message
            if (data.resume) {
                document.getElementById('modal_resume_link').href = data.resume;
            } else {
                document.getElementById('modal_resume_link').innerText = "Resume not available";
                document.getElementById('modal_resume_link').href = "#";
            }

            // Show the modal
            document.getElementById('applicantModal').style.display = 'flex';
        });
}

function closeModal() {
    document.getElementById('applicantModal').style.display = 'none';
}
