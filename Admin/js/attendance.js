document.addEventListener('DOMContentLoaded', function() {
    // Get pagination elements
    const prevPageBtn = document.getElementById('prevPage');
    const nextPageBtn = document.getElementById('nextPage');

    // Add event listeners for pagination
    prevPageBtn.addEventListener('click', previousPage);
    nextPageBtn.addEventListener('click', nextPage);

    // Add event listener to count attendance statuses
    countAttendanceStatuses();

    // Pagination functions
    function previousPage() {
        // Add your previous page logic here
        console.log('Going to previous page...');
    }

    function nextPage() {
        // Add your next page logic here
        console.log('Going to next page...');
    }

    // Function to count attendance statuses
    function countAttendanceStatuses() {
        const attendanceRecords = document.querySelectorAll('#attendanceRecords tr');
        let presentCount = 0;
        let absentCount = 0;
        let lateCount = 0;
        let leaveCount = 0;

        attendanceRecords.forEach(row => {
            const status = row.querySelector('td:nth-child(7)').textContent.trim(); // Assuming status is in the 7th column
            switch (status) {
                case 'ON TIME':
                    presentCount++;
                    break;
                case 'LATE':
                    lateCount++;
                    break;
                case 'ABSENT':
                    absentCount++;
                    break;
                case 'ON LEAVE':
                    leaveCount++;
                    break;
            }
        });

        // Update the counts in the attendance overview
        document.getElementById('presentCount').textContent = presentCount;
        document.getElementById('absentCount').textContent = absentCount;
        document.getElementById('lateCount').textContent = lateCount;
        document.getElementById('leaveCount').textContent = leaveCount;
    }
}); 