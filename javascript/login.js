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

    // Add error message handling
    const errorMessage = document.querySelector('.error-message');
    if (errorMessage && errorMessage.classList.contains('show')) {
        setTimeout(() => {
            errorMessage.style.opacity = '0';
            setTimeout(() => {
                errorMessage.remove();
            }, 300);
        }, 3000); // Message will show for 3 seconds
    }
});
