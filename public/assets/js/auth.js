// Auth JavaScript
let API_URL = window.location.origin + '/api';

// Toggle password visibility
function togglePassword() {
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    const eyeIcon = document.querySelector('.eye-icon');

    passwordInputs.forEach(input => {
        if (input.type === 'password') {
            input.type = 'text';
            if (eyeIcon) {
                eyeIcon.innerHTML = `
                    <path d="M17.94 17.94C16.4 18.9 14.3 19.5 12 19.5C7 19.5 3 15.5 3 11.5C3 9.3 3.9 7.3 5.4 5.8M1 1L19 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                `;
            }
        } else {
            input.type = 'password';
            if (eyeIcon) {
                eyeIcon.innerHTML = `
                    <path d="M10 4C5 4 1 8 1 10C1 12 5 16 10 16C15 16 19 12 19 10C19 8 15 4 10 4Z" stroke="currentColor" stroke-width="1.5" fill="none"/>
                    <circle cx="10" cy="10" r="3" stroke="currentColor" stroke-width="1.5" fill="none"/>
                `;
            }
        }
    });
}

// Show alert
function showAlert(message, type = 'success') {
    const container = document.getElementById('alert-container');
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;

    const icon = type === 'success'
        ? `<svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
           </svg>`
        : `<svg class="alert-icon" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
           </svg>`;

    alert.innerHTML = `
        ${icon}
        <div class="alert-content">
            <strong>${type === 'success' ? 'Berhasil' : 'Error'}</strong>
            <p>${message}</p>
        </div>
        <button class="alert-close" onclick="this.parentElement.remove()">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    `;

    container.appendChild(alert);

    setTimeout(() => {
        alert.remove();
    }, 5000);
}

// Set loading state
function setLoading(form, loading) {
    const submitBtn = form.querySelector('button[type="submit"]');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoader = submitBtn.querySelector('.btn-loader');

    submitBtn.disabled = loading;
    btnText.style.display = loading ? 'none' : 'inline';
    btnLoader.style.display = loading ? 'inline-flex' : 'none';
}

// Check password strength
function checkPasswordStrength(password) {
    const strengthBar = document.querySelector('.strength-bar');
    const strengthText = document.querySelector('.strength-text');

    if (!strengthBar) return;

    let strength = 0;

    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;

    strengthBar.className = 'strength-bar';

    if (strength <= 1) {
        strengthBar.classList.add('weak');
        if (strengthText) strengthText.textContent = 'Lemah';
    } else if (strength <= 2) {
        strengthBar.classList.add('medium');
        if (strengthText) strengthText.textContent = 'Sedang';
    } else {
        strengthBar.classList.add('strong');
        if (strengthText) strengthText.textContent = 'Kuat';
    }
}

// Login form handler
const loginForm = document.getElementById('loginForm');
if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        setLoading(loginForm, true);

        const formData = new FormData(loginForm);
        const data = Object.fromEntries(formData);

        try {
            const response = await fetch(`${API_URL}/login`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                showAlert('Login berhasil! Mengalihkan...', 'success');

                // Store token
                localStorage.setItem('token', result.token);
                localStorage.setItem('user', JSON.stringify(result.user));

                // Redirect based on role
                setTimeout(() => {
                    switch (result.user.role) {
                        case 'admin':
                            window.location.href = '/admin/dashboard';
                            break;
                        case 'member':
                            window.location.href = '/member/library';
                            break;
                        case 'writer':
                            window.location.href = '/writer/dashboard';
                            break;
                        default:
                            window.location.href = '/';
                    }
                }, 1000);
            } else {
                showAlert(result.message || 'Login gagal', 'error');
                setLoading(loginForm, false);
            }
        } catch (error) {
            showAlert('Terjadi kesalahan. Silakan coba lagi.', 'error');
            setLoading(loginForm, false);
        }
    });
}

// Register form handler
const registerForm = document.getElementById('registerForm');
if (registerForm) {
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', (e) => {
            checkPasswordStrength(e.target.value);
        });
    }

    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        setLoading(registerForm, true);

        const formData = new FormData(registerForm);
        const data = Object.fromEntries(formData);

        try {
            const response = await fetch(`${API_URL}/register`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (response.ok) {
                showAlert('Registrasi berhasil! Silakan login.', 'success');
                setTimeout(() => {
                    window.location.href = '/login';
                }, 1500);
            } else {
                showAlert(result.message || 'Registrasi gagal', 'error');
                setLoading(registerForm, false);
            }
        } catch (error) {
            showAlert('Terjadi kesalahan. Silakan coba lagi.', 'error');
            setLoading(registerForm, false);
        }
    });
}
