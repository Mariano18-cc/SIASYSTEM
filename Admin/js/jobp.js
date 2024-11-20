// Open the modal and fetch applicant details
function openModal(applicantId) {
    fetch(`jobp.php?id=${applicantId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modal_applicant_id').innerText = data.applicant_id;
            document.getElementById('modal_full_name').innerText = `${data.fname} ${data.mname} ${data.lname}`;
            document.getElementById('modal_email').innerText = data.email;
            document.getElementById('modal_bday').innerText = data.bday;
            document.getElementById('modal_position').innerText = data.applying_position;
            document.getElementById('modal_status').innerText = data.status;

            if (data.resume) {
                document.getElementById('modal_resume_link').href = data.resume;
                document.getElementById('modal_resume_link').innerText = "Open Resume";
            } else {
                document.getElementById('modal_resume_link').innerText = "Resume not available";
                document.getElementById('modal_resume_link').href = "#";
            }

            document.getElementById('applicantModal').style.display = 'flex';
        });
}

// Close the modal
function closeModal() {
    document.getElementById('applicantModal').style.display = 'none';
}
