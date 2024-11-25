// Get the modal
var modal = document.getElementById("payslipModal");
var viewBtns = document.querySelectorAll(".view-btn");
var span = document.querySelector(".close");
var printBtn = document.querySelector(".print-btn");
var downloadBtn = document.querySelector(".download-btn");

// Payroll data for each month
const payrollData = {
    'october': {
        month: 'October 2023',
        period: 'October 1st-31st, 2023',
        regularHours: '160 HRS',
        overtimeHours: '24 HRS',
        basicPay: '₱25,000.00',
        overtimePay: '₱3,500.00',
        deductions: {
            tax: '₱1,500.00',
            sss: '₱500.00',
            philhealth: '₱300.00',
            pagibig: '₱200.00',
            total: '₱2,500.00'
        },
        netPay: '₱28,500.00'
    },
    'september': {
        month: 'September 2023',
        period: 'September 1st-30th, 2023',
        regularHours: '160 HRS',
        overtimeHours: '16 HRS',
        basicPay: '₱25,000.00',
        overtimePay: '₱2,000.00',
        deductions: {
            tax: '₱1,500.00',
            sss: '₱500.00',
            philhealth: '₱300.00',
            pagibig: '₱200.00',
            total: '₱2,500.00'
        },
        netPay: '₱27,000.00'
    }
};

// Modal functionality
viewBtns.forEach((btn, index) => {
    btn.onclick = function() {
        const month = index === 0 ? 'october' : 'september';
        const data = payrollData[month];
        
        // Update modal content
        document.querySelector('.payslip-details').innerHTML = `
            <div class="detail-group">
                <h4>Pay Period:</h4>
                <p>${data.period}</p>
            </div>
            <div class="detail-group">
                <h4>Regular Hours:</h4>
                <p>${data.regularHours}</p>
            </div>
            <div class="detail-group">
                <h4>Overtime Hours:</h4>
                <p>${data.overtimeHours}</p>
            </div>
            <div class="detail-group">
                <h4>Basic Pay:</h4>
                <p>${data.basicPay}</p>
            </div>
            <div class="detail-group">
                <h4>Overtime Pay:</h4>
                <p>${data.overtimePay}</p>
            </div>
            <div class="detail-group deductions-group">
                <h4>Deductions:</h4>
                <div class="deductions-breakdown">
                    <p>Tax: ${data.deductions.tax}</p>
                    <p>SSS: ${data.deductions.sss}</p>
                    <p>PhilHealth: ${data.deductions.philhealth}</p>
                    <p>Pag-IBIG: ${data.deductions.pagibig}</p>
                    <p class="total">Total Deductions: ${data.deductions.total}</p>
                </div>
            </div>
            <div class="detail-group">
                <h4>Net Pay:</h4>
                <p>${data.netPay}</p>
            </div>
        `;
        
        modal.style.display = "block";
    }
});

// Print payslip
printBtn.onclick = function() {
    window.print();
}

// Download PDF
downloadBtn.onclick = function() {
    alert('Downloading PDF...');
}

// Close modal when X is clicked
span.onclick = function() {
    modal.style.display = "none";
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}
