document.addEventListener('DOMContentLoaded', function() {
    const employeeForm = document.querySelector('form[action="attendance_tracker.php"]');
    const detailsContent = document.getElementById('details-content');

    if (employeeForm) {
        employeeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const employeeId = document.querySelector('input[name="employee_id"]').value;
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'attendance_tracker.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.error) {
                                alert(response.error);
                            } else {
                                displayEmployeeDetails(response);
                            }
                        } catch (e) {
                            console.error('Error parsing JSON:', e);
                            alert('An error occurred while processing the response.');
                        }
                    } else {
                        alert('An error occurred while fetching employee details.');
                    }
                }
            };
            xhr.send(`check_id=1&employee_id=${employeeId}`);
        });
    }

    function displayEmployeeDetails(employee) {
        detailsContent.style.display = 'block';

        // Update the name
        const nameElement = detailsContent.querySelector('p strong:contains("Name:")');
        if (nameElement) {
            nameElement.parentNode.innerHTML = `<strong>Name:</strong> ${employee.name}`;
        }

        // Update the employee ID
        const idElement = detailsContent.querySelector('#displayedEmployeeId');
        if (idElement) {
            idElement.textContent = employee.employee_id;
        }

        // Update the email
        const emailElement = detailsContent.querySelector('p strong:contains("Email:")');
        if (emailElement) {
            emailElement.parentNode.innerHTML = `<strong>Email:</strong> ${employee.email}`;
        }

        // Clear previous time in/out status
        const timeInStatus = document.getElementById('timeInStatus');
        const timeOutStatus = document.getElementById('timeOutStatus');
        if (timeInStatus) timeInStatus.textContent = '';
        if (timeOutStatus) timeOutStatus.textContent = '';

        // Make sure the Time In and Time Out buttons are visible
        const timeInButton = document.getElementById('timeInButton');
        const timeOutButton = document.getElementById('timeOutButton');
        if (timeInButton) timeInButton.style.display = 'inline-block';
        if (timeOutButton) timeOutButton.style.display = 'inline-block';

        // Add event listeners for time in and time out buttons
        addTimeInOutListeners();
    }

    function addTimeInOutListeners() {
        const timeInButton = document.getElementById('timeInButton');
        const timeOutButton = document.getElementById('timeOutButton');
        const timeInStatus = document.getElementById('timeInStatus');
        const timeOutStatus = document.getElementById('timeOutStatus');

        if (timeInButton) {
            timeInButton.addEventListener('click', function(e) {
                handleTimeInOut(e, 'time_in', timeInStatus);
            });
        }

        if (timeOutButton) {
            timeOutButton.addEventListener('click', function(e) {
                handleTimeInOut(e, 'time_out', timeOutStatus);
            });
        }
    }

    function handleTimeInOut(e, action, statusElement) {
        e.preventDefault();
        const employeeId = document.getElementById('displayedEmployeeId').textContent;
        if (!employeeId) {
            alert('Employee ID is missing. Please check in again.');
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'attendance_tracker.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            statusElement.textContent = `${action === 'time_in' ? 'Time In' : 'Time Out'}: ${response.time}`;
                        } else {
                            alert(response.message || 'An error occurred');
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        alert('An error occurred while processing the request.');
                    }
                } else {
                    alert('An error occurred while processing the request.');
                }
            }
        };
        xhr.send(`employee_id=${employeeId}&${action}=1`);
    }
});