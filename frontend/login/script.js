// frontend/login/script.js

document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. Password Toggle Logic ---
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener('click', function () {
            // Check current type
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            
            // Switch Input Type
            passwordInput.setAttribute('type', type);
            
            // Switch Button Text
            this.textContent = type === 'password' ? 'Show' : 'Hide';
        });
    }

    // --- 2. Auto-Hide Error Messages ---
    const alertBox = document.getElementById('errorAlert');
    if (alertBox) {
        // Wait 3 seconds, then fade out
        setTimeout(() => {
            alertBox.style.opacity = '0';
            
            // Wait for fade animation (0.5s) then remove from HTML
            setTimeout(() => {
                alertBox.remove();
            }, 500);
        }, 3000);
    }

    // --- 3. Simple Form Validation (Prevent Empty Submit) ---
    const form = document.getElementById('loginForm');
    form.addEventListener('submit', function(e) {
        const userVal = document.getElementById('username').value.trim();
        const passVal = document.getElementById('password').value.trim();

        if (!userVal || !passVal) {
            e.preventDefault(); // Stop sending to server
            alert("Please fill in all fields.");
        }
    });

});

document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('toggleBtn');
    const passwordInput = document.getElementById('password');

    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener('click', function() {
            // 1. Toggle the type attribute between 'password' and 'text'
            const currentType = passwordInput.getAttribute('type');
            const newType = currentType === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', newType);
            
            // 2. Toggle the icon classes
            // Removes 'fa-eye' and adds 'fa-eye-slash', or vice-versa
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }
});