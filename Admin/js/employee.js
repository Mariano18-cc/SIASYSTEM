document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("add-employee-modal");
    const addButton = document.getElementById("add-button");
    const closeModal = document.getElementById("close-modal");
    const mainContent = document.querySelector('.main-content');

    addButton.onclick = function () {
        modal.style.display = "block";
        mainContent.classList.add('blur'); // Add blur to main content
    };

    closeModal.onclick = function () {
        modal.style.display = "none";
        mainContent.classList.remove('blur'); // Remove blur
    };

    window.onclick = function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
            mainContent.classList.remove('blur'); // Remove blur
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

    
        // Get modal and close button elements for schedule button
        const scheduleModal = document.getElementById("schedule-modal");
        const closeScheduleModal = document.getElementById("close-schedule-modal");

        // Function to open the modal and populate with employee details
        function openScheduleModal(employeeId, employeeName) {
            document.getElementById("schedule-employee-id").value = employeeId;
            document.getElementById("schedule-employee-name").value = employeeName;
            scheduleModal.style.display = "block";
        }

        // Close the modal when the close button is clicked
        closeScheduleModal.onclick = function() {
            scheduleModal.style.display = "none";
        }

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == scheduleModal) {
                scheduleModal.style.display = "none";
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            // Handle the "Add Employee" modal
            const addEmployeeModal = document.getElementById("add-employee-modal");
            const addButton = document.getElementById("add-button");
            const closeAddModal = document.getElementById("close-modal");
        
            addButton.onclick = function () {
                console.log("Add button clicked"); // Debugging line
                addEmployeeModal.style.display = "block";
            };
        
            closeAddModal.onclick = function () {
                addEmployeeModal.style.display = "none";
            };
        
            window.onclick = function (event) {
                if (event.target === addEmployeeModal) {
                    addEmployeeModal.style.display = "none";
                }
            };
        
            // Handle the "Schedule Employee" modal
            const scheduleModal = document.getElementById("schedule-modal");
            const closeScheduleModal = document.getElementById("close-schedule-modal");
        
            // Function to open the "Schedule Employee" modal
            window.openScheduleModal = function (employeeId, employeeName) {
                document.getElementById("schedule-employee-id").value = employeeId;
                document.getElementById("schedule-employee-name").value = employeeName;
                scheduleModal.style.display = "block";
            };
        
            closeScheduleModal.onclick = function () {
                scheduleModal.style.display = "none";
            };
        
            window.onclick = function (event) {
                if (event.target === scheduleModal) {
                    scheduleModal.style.display = "none";
                }
            };
        
            // Handle search functionality
            const searchInput = document.getElementById("search-input");
            const employeeTbody = document.getElementById("employee-tbody");
        
            searchInput.addEventListener("keyup", function () {
                const query = this.value.trim();
        
                // Make an AJAX request to fetch filtered employees
                fetch("employee.php?ajax=1&search=" + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        employeeTbody.innerHTML = ""; // Clear existing rows
        
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
                                    <button type="button" class="schedule-button" onclick="openScheduleModal('${employee.employee_id}', '${employee.fname} ${employee.lname}')">
                                        Schedule
                                    </button>
                                </td>
                            `;
                            employeeTbody.appendChild(row);
                        });
                    })
                    .catch(error => console.error("Error fetching employee data:", error));
            });
        });
        
   