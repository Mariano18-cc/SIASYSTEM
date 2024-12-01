document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('payrollModal');
    const buttons = document.querySelectorAll('.view-button');
    const closeBtn = document.querySelector('.close');
    
    function showModal() {
        if (!modal) return;
        modal.style.display = 'block';
        modal.classList.add('show');
        const modalContent = modal.querySelector('.modal-content');
        if (modalContent) modalContent.classList.add('show');
    }

    function hideModal() {
        if (!modal) return;
        const modalContent = modal.querySelector('.modal-content');
        if (modalContent) modalContent.classList.remove('show');
        setTimeout(() => {
            modal.classList.remove('show');
            modal.style.display = 'none';
        }, 300);
    }

    buttons.forEach(button => {
        button.onclick = function() {
            const employeeId = this.getAttribute('data-employee-id');
            if (!employeeId) {
                alert('Invalid employee ID');
                return;
            }
            
            showModal();
            
            fetch(`payroll.php?action=get_employee_details&id=${employeeId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data || data.error) {
                        throw new Error(data?.error || 'Invalid data received');
                    }
                    
                    // Update modal content with null checks
                    const elements = {
                        'employeeName': data.full_name || 'N/A',
                        'employeeEmail': data.email || 'N/A',
                        'balanceAmount': `₱ ${data.net_salary || '0.00'}`,
                        'currentDate': new Date().toLocaleDateString('en-US', {
                            month: 'long',
                            day: 'numeric',
                            year: 'numeric'
                        }).toUpperCase()
                    };

                    Object.entries(elements).forEach(([id, value]) => {
                        const element = document.getElementById(id);
                        if (element) element.innerHTML = value;
                    });

                    // Update details table
                    document.getElementById('detailsTable').innerHTML = `
                        <tr class="earnings">
                            <td class="label">Basic Salary</td>
                            <td class="value">₱ ${parseFloat(data.monthly_salary).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                        </tr>
                        ${Object.entries(data.deductions).map(([key, value]) => `
                            <tr class="deduction">
                                <td class="label">${key}</td>
                                <td class="value">- ₱ ${value}</td>
                            </tr>
                        `).join('')}
                    `;

                    document.getElementById('totalSection').innerHTML = `
                        <p><span>Total Deductions:</span> <span>₱ ${data.total_deductions}</span></p>
                    `;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Unable to load employee details. Please try again later.');
                    hideModal();
                });
        };
    });

    // Event handlers
    document.querySelector('.print-btn').onclick = () => {
        const modalContent = modal.querySelector('.modal-content');
        if (!modalContent) return;
        
        document.body.classList.add('printing');
        modalContent.classList.add('printing');
        
        setTimeout(() => {
            window.print();
            document.body.classList.remove('printing');
            modalContent.classList.remove('printing');
        }, 100);
    };

    closeBtn.onclick = hideModal;
    window.onclick = (event) => event.target === modal && hideModal();
    document.addEventListener('keydown', (event) => event.key === 'Escape' && modal.style.display === 'block' && hideModal());
    document.querySelector('.send-btn').onclick = () => alert('Payslip sent successfully!');
});
