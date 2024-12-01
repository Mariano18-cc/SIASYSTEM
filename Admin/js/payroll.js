console.log('Script loaded');
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
    const modal = document.getElementById('payrollModal');
    const buttons = document.querySelectorAll('.view-button');
    const closeBtn = document.querySelector('.close');
    
    console.log('Found buttons:', buttons.length);

    // Improve modal display/hide with proper animation handling
    function showModal() {
        modal.style.display = 'block';
        // Allow DOM to update before adding animation class
        requestAnimationFrame(() => {
            modal.classList.add('show');
        });
    }

    function hideModal() {
        modal.classList.remove('show');
        // Wait for animation to complete before hiding
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300); // Match the CSS transition duration
    }

    buttons.forEach(button => {
        button.onclick = function() {
            const employeeId = this.getAttribute('data-employee-id');
            showModal();
            
            fetch(`payroll.php?action=get_employee_details&id=${employeeId}`)
                .then(response => response.json())
                .then(data => {
                    // Update modal content with animation
                    fadeIn(document.getElementById('employeeName'), data.full_name);
                    fadeIn(document.getElementById('employeeEmail'), data.email);
                    fadeIn(document.getElementById('balanceAmount'), `₱ ${data.net_salary}`);
                    
                    const currentDate = new Date().toLocaleDateString('en-US', {
                        month: 'long',
                        day: 'numeric',
                        year: 'numeric'
                    }).toUpperCase();
                    fadeIn(document.getElementById('currentDate'), currentDate);

                    // Create earnings and deductions details
                    const detailsHTML = `
                        <tr class="earnings">
                            <td class="label">Basic Salary</td>
                            <td class="value">₱ ${parseFloat(data.monthly_salary).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                        </tr>
                        <tr class="deduction">
                            <td class="label">Income Tax</td>
                            <td class="value">- ₱ ${data.deductions['Income Tax']}</td>
                        </tr>
                        <tr class="deduction">
                            <td class="label">PhilHealth</td>
                            <td class="value">- ₱ ${data.deductions['PhilHealth']}</td>
                        </tr>
                        <tr class="deduction">
                            <td class="label">Pag-IBIG</td>
                            <td class="value">- ₱ ${data.deductions['Pag-IBIG']}</td>
                        </tr>
                        <tr class="deduction">
                            <td class="label">SSS</td>
                            <td class="value">- ₱ ${data.deductions['SSS']}</td>
                        </tr>
                    `;
                    
                    const table = document.getElementById('detailsTable');
                    fadeIn(table, detailsHTML);

                    // Update total section with animation
                    const totalHTML = `
                        <p><span>Total Deductions:</span> <span>₱ ${data.total_deductions}</span></p>
                        <p><span>Net Salary:</span> <span>₱ ${data.net_salary}</span></p>
                    `;
                    fadeIn(document.getElementById('totalSection'), totalHTML);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading employee details');
                });
        };
    });

    // Animation helper function
    function fadeIn(element, content) {
        element.style.opacity = '0';
        element.innerHTML = content;
        let opacity = 0;
        const timer = setInterval(() => {
            if (opacity >= 1) {
                clearInterval(timer);
            }
            element.style.opacity = opacity;
            opacity += 0.1;
        }, 50);
    }

    // Enhance print functionality
    document.querySelector('.print-btn').onclick = function() {
        // Add print-specific class to body
        document.body.classList.add('printing');
        
        // Print after brief delay to ensure styles are applied
        setTimeout(() => {
            window.print();
            // Remove print class after printing
            document.body.classList.remove('printing');
        }, 100);
    };

    // Improve modal close handlers
    closeBtn.onclick = hideModal;
    
    window.onclick = function(event) {
        if (event.target === modal) {
            hideModal();
        }
    };

    // Add escape key handler
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && modal.style.display === 'block') {
            hideModal();
        }
    });

    // Send functionality (you can customize this)
    document.querySelector('.send-btn').onclick = function() {
        alert('Payslip sent successfully!');
    };
});
