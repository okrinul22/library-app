<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Library Management</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        /* Custom styles for dark theme register */
        body {
            background: linear-gradient(135deg, #111827 0%, #1a1a2e 100%);
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .auth-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        .auth-box {
            background: #1F2937;
            border: 1px solid #374151;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            max-width: 500px;
            width: 100%;
        }

        .logo-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #4F46E5, #10B981);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .form-control {
            background: #111827;
            border: 1px solid #374151;
            color: #F9FAFB;
            padding: 12px 16px;
        }

        .form-control:focus {
            background: #111827;
            border-color: #4F46E5;
            color: #F9FAFB;
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25);
        }

        .form-control::placeholder {
            color: #9CA3AF;
        }

        .form-label {
            color: #F9FAFB;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4F46E5, #4338CA);
            border: none;
            padding: 14px 20px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #4338CA, #3730A3);
            border: none;
        }

        .btn-primary:disabled {
            opacity: 0.7;
        }

        .input-group .btn-outline-secondary {
            background: #111827;
            border: 1px solid #374151;
            color: #9CA3AF;
        }

        .input-group .btn-outline-secondary:hover {
            background: #374151;
            color: #F9FAFB;
        }

        .form-check-input {
            background-color: #111827;
            border-color: #374151;
        }

        .form-check-input:checked {
            background-color: #4F46E5;
            border-color: #4F46E5;
        }

        .text-secondary-custom {
            color: #9CA3AF;
        }

        .link-custom {
            color: #4F46E5;
            text-decoration: none;
        }

        .link-custom:hover {
            text-decoration: underline;
        }

        /* Role Selector Styles */
        .role-selector {
            display: flex;
            gap: 12px;
        }

        .role-option {
            flex: 1;
            cursor: pointer;
            margin: 0;
        }

        .role-option input[type="radio"] {
            display: none;
        }

        .role-card-select {
            background: #111827;
            border: 2px solid #374151;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            transition: all 0.2s;
            height: 100%;
        }

        .role-option input[type="radio"]:checked + .role-card-select {
            border-color: #4F46E5;
            background: rgba(79, 70, 229, 0.1);
        }

        .role-card-select:hover {
            border-color: #4F46E5;
            background: rgba(79, 70, 229, 0.1);
        }

        .role-icon-select {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .role-card-select strong {
            display: block;
            color: #F9FAFB;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .role-card-select p {
            color: #9CA3AF;
            font-size: 11px;
            margin: 0;
        }

        /* Password Strength */
        .password-strength {
            margin-top: 8px;
        }

        .strength-bar {
            height: 4px;
            background: #374151;
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-bar::before {
            content: '';
            display: block;
            height: 100%;
            width: 0;
            background: #EF4444;
            transition: all 0.3s;
        }

        .strength-bar.weak::before {
            width: 33%;
            background: #EF4444;
        }

        .strength-bar.medium::before {
            width: 66%;
            background: #F59E0B;
        }

        .strength-bar.strong::before {
            width: 100%;
            background: #10B981;
        }

        .strength-text {
            display: block;
            font-size: 12px;
            color: #9CA3AF;
            margin-top: 4px;
        }

        .alert-container {
            margin-bottom: 20px;
        }

        .checkbox-label {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            color: #9CA3AF;
            font-size: 14px;
            margin-bottom: 20px;
            cursor: pointer;
        }

        .checkbox-label input {
            margin-top: 3px;
        }

        .checkbox-label .link {
            color: #4F46E5;
            text-decoration: none;
        }

        .checkbox-label .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <!-- Logo & Header -->
            <div class="text-center mb-4">
                <div class="logo-icon">
                    <i class="fas fa-book"></i>
                </div>
                <h1 class="text-white mb-2">Buat Akun Baru</h1>
                <p class="text-secondary-custom">Bergabung dengan Library Management</p>
            </div>

            <!-- Alert Container -->
            <div class="alert-container" id="alert-container"></div>

            <!-- Register Form -->
            <form id="registerForm">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name"
                           placeholder="Masukkan nama lengkap" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                           placeholder="nama@email.com" required>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Nomor Telepon</label>
                    <input type="tel" class="form-control" id="phone" name="phone"
                           placeholder="08xxxxxxxxxx">
                </div>

                <div class="mb-3">
                    <label class="form-label">Daftar Sebagai</label>
                    <div class="role-selector">
                        <label class="role-option">
                            <input type="radio" name="role" value="member" checked>
                            <div class="role-card-select">
                                <div class="role-icon-select">👤</div>
                                <strong>Member</strong>
                                <p>Akses library & baca buku</p>
                            </div>
                        </label>
                        <label class="role-option">
                            <input type="radio" name="role" value="writer">
                            <div class="role-card-select">
                                <div class="role-icon-select">✍️</div>
                                <strong>Writer</strong>
                                <p>Upload & manage karya</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="Minimal 8 karakter" required>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                            <i class="far fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    <div class="password-strength">
                        <div class="strength-bar" id="strengthBar"></div>
                        <span class="strength-text" id="strengthText">Password strength</span>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                           placeholder="Ulangi password" required>
                </div>

                <label class="checkbox-label">
                    <input type="checkbox" name="terms" required>
                    <span>Saya setuju dengan <a href="#" class="link">Syarat & Ketentuan</a></span>
                </label>

                <button type="submit" class="btn btn-primary w-100" id="registerBtn">
                    <span class="btn-text">Daftar</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </span>
                </button>
            </form>

            <!-- Register Link -->
            <div class="text-center mt-4">
                <p class="text-secondary-custom mb-0">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="link-custom fw-semibold">Masuk</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const API_URL = window.location.origin + '/api';
        const CSRF_TOKEN = '{{ csrf_token() }}';

        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');

            let strength = 0;
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;

            strengthBar.className = 'strength-bar';
            if (strength <= 1) {
                strengthBar.classList.add('weak');
                strengthText.textContent = 'Lemah';
            } else if (strength <= 2) {
                strengthBar.classList.add('medium');
                strengthText.textContent = 'Sedang';
            } else {
                strengthBar.classList.add('strong');
                strengthText.textContent = 'Kuat';
            }
        });

        // Show alert
        function showAlert(message, type = 'success') {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <strong>${type === 'success' ? 'Berhasil' : 'Error'}!</strong> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            document.getElementById('alert-container').innerHTML = alertHtml;

            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        }

        // Set loading state
        function setLoading(loading) {
            const registerBtn = document.getElementById('registerBtn');
            const btnText = registerBtn.querySelector('.btn-text');
            const spinner = registerBtn.querySelector('.spinner-border');

            registerBtn.disabled = loading;
            btnText.classList.toggle('d-none', loading);
            spinner.classList.toggle('d-none', !loading);
        }

        // Register form handler
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            setLoading(true);

            const formData = new FormData(e.target);
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

                if (response.ok && result.success) {
                    showAlert('Registrasi berhasil! Mengalihkan ke halaman login...', 'success');

                    setTimeout(() => {
                        window.location.href = '{{ route("login") }}';
                    }, 2000);
                } else {
                    showAlert(result.message || 'Registrasi gagal', 'error');
                    setLoading(false);
                }
            } catch (error) {
                showAlert('Terjadi kesalahan. Silakan coba lagi.', 'error');
                setLoading(false);
            }
        });
    </script>
</body>
</html>
