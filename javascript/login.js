document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        var email = document.getElementById('loginEmail').value;
        var password = document.getElementById('loginPassword').value;
        var errorMessage = document.getElementById('errorMessage');
        
        if (email === 'admin@gmail.com' && password === 'admin123') {
            window.location.href = 'Admin/dashboard.php';
        } else {
            errorMessage.style.display = 'block'; 
            document.getElementById('loginEmail').value = '';
            document.getElementById('loginPassword').value = '';
        }
    });
});
