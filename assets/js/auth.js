document.addEventListener('DOMContentLoaded', () => {
    const password = document.querySelector('input[name="password"]');
    const confirmPassword = document.querySelector('input[name="confirm_password"]');
    const toggleButtons = document.querySelectorAll('.toggle-password');

    if (password && confirmPassword) {
        // Only for register page
        const passwordError = document.createElement('div');
        passwordError.className = 'error-message';
        passwordError.style.color = 'crimson';
        passwordError.style.fontFamily = 'var(--text-font-family)';
        passwordError.style.fontSize = '0.85rem';
        passwordError.style.marginTop = '0.25rem';
        passwordError.style.display = 'none';
        confirmPassword.parentNode.appendChild(passwordError);

        const validatePasswordMatch = () => {
            if (password.value && confirmPassword.value && password.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity("Passwords do not match");
                passwordError.textContent = "Passwords do not match";
                passwordError.style.display = 'block';
            } else {
                confirmPassword.setCustomValidity("");
                passwordError.textContent = "";
                passwordError.style.display = 'none';
            }
        };

        password.addEventListener('input', validatePasswordMatch);
        confirmPassword.addEventListener('input', validatePasswordMatch);
    }

    // Password toggle works for both login & register
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const input = this.previousElementSibling;
            if (input && input.type === 'password') {
                input.type = 'text';
                this.innerHTML = '<ion-icon name="eye-outline"></ion-icon>';
            } else if (input) {
                input.type = 'password';
                this.innerHTML = '<ion-icon name="eye-off-outline"></ion-icon>';
            }
        });
    });
});
