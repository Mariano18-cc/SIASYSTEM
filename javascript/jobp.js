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
