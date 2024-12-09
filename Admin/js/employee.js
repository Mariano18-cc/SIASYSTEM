document.addEventListener("DOMContentLoaded", function () {
    const mainContent = document.querySelector('.main-content');
    
    // Modal Elements
    const addEmployeeModal = document.getElementById("add-employee-modal");
    const scheduleModal = document.getElementById("schedule-modal");
    const employeeDetailsModal = document.getElementById("employee-details-modal");
    
    // Button Elements
    const addButton = document.getElementById("add-button");
    const closeModal = document.getElementById("close-modal");
    const closeScheduleModal = document.getElementById("close-schedule-modal");
    const closeDetailsModal = document.getElementById("close-details-modal");
    const searchInput = document.getElementById("search-input");
    const employeeTbody = document.getElementById("employee-tbody");

    // Utility Functions
    function openModal(modal) {
        if (modal) {
            modal.style.display = "block";
            mainContent.classList.remove('blur');
            document.body.classList.add('modal-open');
        }
    }

    function closeModalHandler(modal) {
        if (modal) {
            modal.style.display = "none";
            mainContent.classList.remove('blur');
            document.body.classList.remove('modal-open');
        }
    }

    // Add Employee Modal
    if (addButton) {
        addButton.onclick = function() {
            openModal(addEmployeeModal);
        };
    }
    if (closeModal) {
        closeModal.onclick = function(e) {
            e.stopPropagation();
            closeModalHandler(addEmployeeModal);
        };
    }

    // Schedule Modal
    window.openScheduleModal = function(employeeId, employeeName) {
        document.getElementById("schedule-employee-id").value = employeeId;
        document.getElementById("schedule-employee-name").value = employeeName;
        openModal(scheduleModal);
    };
    
    if (closeScheduleModal) {
        closeScheduleModal.onclick = function(e) {
            e.stopPropagation();
            closeModalHandler(scheduleModal);
        };
    }

    // Employee Details Modal
    window.showEmployeeDetails = function(employeeId) {
        // Show the modal first
        openModal(employeeDetailsModal);
        
        // Then fetch and populate the data
        fetch(`employee.php?get_employee_details=1&employee_id=${employeeId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Server response:', text);
                        throw new Error('Invalid JSON response from server');
                    }
                });
            })
            .then(employee => {
                if (!employee || employee.error) {
                    throw new Error(employee.error || 'Failed to fetch employee details');
                }
                
                // Format birthday if it exists
                const formattedBirthday = employee.birthday ? new Date(employee.birthday).toLocaleDateString() : 'Not specified';
                
                // Update modal content with null checks
                const elements = {
                    "employee-name": `${employee.fname} ${employee.mname ? employee.mname + ' ' : ''}${employee.lname}`,
                    "employee-position": employee.position,
                    "detail-employee-id": employee.employee_id,
                    "detail-email": employee.email,
                    "detail-phone": employee.phone_number || 'Not specified',
                    "detail-birthday": formattedBirthday,
                    "detail-employment-type": employee.position || 'Not specified',
                    "detail-hired-date": employee.hired_date,
                    "detail-status": employee.status,
                    "detail-salary": employee.salary ? `₱${parseFloat(employee.salary).toLocaleString()}` : 'Not specified'
                };

                Object.entries(elements).forEach(([id, value]) => {
                    const element = document.getElementById(id);
                    if (element) {
                        element.textContent = value || 'Not specified';
                    }
                });
            })
            .catch(error => {
                console.error("Error fetching employee details:", error);
                alert('Failed to load employee details. Please try again.');
                closeModalHandler(employeeDetailsModal);
            });
    };

    // Modal Event Listeners
    if (closeDetailsModal) {
        closeDetailsModal.onclick = function(e) {
            e.stopPropagation();
            closeModalHandler(employeeDetailsModal);
        };
    }

    // Prevent modal content clicks from closing the modal
    document.querySelectorAll('.modal-content').forEach(content => {
        content.addEventListener('click', (e) => e.stopPropagation());
    });

    // Global click handler for all modals
    window.addEventListener('click', (event) => {
        console.log("Clicked:", event.target);
        const modals = [addEmployeeModal, scheduleModal, employeeDetailsModal];
        modals.forEach(modal => {
            if (event.target === modal) {
                console.log("Closing modal:", modal);
                closeModalHandler(modal);
            }
        });
    });

    // Search Functionality
    if (searchInput && employeeTbody) {
        searchInput.addEventListener("keyup", function() {
            const query = this.value.trim();

            fetch("employee.php?ajax=1&search=" + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    employeeTbody.innerHTML = ""; // Clear existing rows
                    data.forEach(employee => {
                        const row = createEmployeeRow(employee);
                        employeeTbody.appendChild(row);
                    });
                })
                .catch(error => console.error("Error fetching employee data:", error));
        });
    }

    // Helper function to create employee row
    function createEmployeeRow(employee) {
        const row = document.createElement("tr");
        row.className = "employee-row";
        row.dataset.employeeId = employee.employee_id;

        row.innerHTML = `
            <td>${employee.employee_id}</td>
            <td>
                ${employee.fname} ${employee.lname}
                <button class="info-button" onclick="showEmployeeDetails('${employee.employee_id}')">
                    <i class="fas fa-info-circle"></i>
                </button>
            </td>
            <td>${employee.position}</td>
            <td>${employee.hired_date}</td>
            <td>${employee.status}</td>
            <td>
                <form method="POST" action="employee.php" style="display:inline-block;">
                    <input type="hidden" name="employee_id" value="${employee.employee_id}">
                    <select name="new_status" required>
                        <option value="Active" ${employee.status === 'Active' ? 'selected' : ''}>Active</option>
                        <option value="Inactive" ${employee.status === 'Inactive' ? 'selected' : ''}>Inactive</option>
                        <option value="Delete">Delete</option>
                    </select>
                    <button type="submit" class="update-button">&nbsp; Update&nbsp; </button>
                </form>
                <button type="button" class="schedule-button" 
                    onclick="openScheduleModal('${employee.employee_id}', '${employee.fname} ${employee.lname}')">
                    &nbsp; Schedule&nbsp;
                </button>
            </td>
        `;
        return row;
    }
});
        
   