document.addEventListener('DOMContentLoaded', function() {
    // Password visibility functionality only
    const showPasswordBtn = document.getElementById('showPassword');
    const passwordInput = document.getElementById('loginPassword');

    // For mouse events (desktop)
    showPasswordBtn.addEventListener('mousedown', function() {
        passwordInput.type = 'text';
        this.textContent = 'Hide';
    });

    showPasswordBtn.addEventListener('mouseup', function() {
        passwordInput.type = 'password';
        this.textContent = 'Show';
    });

    showPasswordBtn.addEventListener('mouseleave', function() {
        passwordInput.type = 'password';
        this.textContent = 'Show';
    });

    // For touch events (mobile)
    showPasswordBtn.addEventListener('touchstart', function(e) {
        e.preventDefault();
        passwordInput.type = 'text';
        this.textContent = 'Hide';
    });

    showPasswordBtn.addEventListener('touchend', function() {
        passwordInput.type = 'password';
        this.textContent = 'Show';
    });

    // Add error message handling with longer duration (5 seconds)
    const errorMessage = document.querySelector('.error-message');
    if (errorMessage && errorMessage.classList.contains('show')) {
        setTimeout(() => {
            errorMessage.style.transition = 'opacity 0.5s ease';
            errorMessage.style.opacity = '0';
            setTimeout(() => {
                errorMessage.remove();
            }, 500);
        }, 5000); // Changed from 3000 to 5000 (5 seconds)
    }
});
