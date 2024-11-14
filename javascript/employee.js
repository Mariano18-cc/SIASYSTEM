document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("add-employee-modal");
    const addButton = document.getElementById("add-button");
    const closeModal = document.getElementById("close-modal");

    addButton.onclick = function () {
        console.log("Add button clicked"); // Debugging line
        modal.style.display = "block";
};

    closeModal.onclick = function () {
        modal.style.display = "none";
};

    window.onclick = function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
};
});


    // Handle search filtering
    document.getElementById("search-input").addEventListener("keyup", function() {
        const query = this.value;

        // Make an AJAX request to fetch filtered employees
        fetch("employee.php?ajax=1&search=" + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById("employee-tbody");
                tbody.innerHTML = ""; // Clear existing rows

                // Populate table with new search results
                data.forEach(employee => {
                    const row = document.createElement("tr");

                    row.innerHTML = `
                        <td>${employee.employee_id}</td>
                        <td>${employee.fname} ${employee.lname}</td>
                        <td>${employee.position}</td>
                        <td>${employee.hired_date}</td>
                        <td>${employee.status}</td>
                        <td>
                            <form method="POST" action="employee.php">
                                <input type="hidden" name="employee_id" value="${employee.employee_id}">
                                <select name="new_status" required>
                                    <option value="Active" ${employee.status === 'Active' ? 'selected' : ''}>Active</option>
                                    <option value="Inactive" ${employee.status === 'Inactive' ? 'selected' : ''}>Inactive</option>
                                    <option value="Delete">Delete</option>
                                </select>
                                <button type="submit" class="update-button">Update Status</button>
                            </form>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });
    });