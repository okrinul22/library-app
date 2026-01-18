<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Library Management</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        /* Custom styles for dark theme login */
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
            max-width: 450px;
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

        .role-card {
            background: #111827;
            border: 1px solid #374151;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .role-card:hover {
            border-color: #4F46E5;
            background: rgba(79, 70, 229, 0.1);
        }

        .role-icon {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .role-card h4 {
            color: #F9FAFB;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .role-card p {
            color: #9CA3AF;
            font-size: 11px;
            margin: 0;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #374151;
        }

        .divider span {
            padding: 0 16px;
            color: #9CA3AF;
            font-size: 14px;
        }

        .alert-container {
            margin-bottom: 20px;
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
                <h1 class="text-white mb-2">Library Management</h1>
                <p class="text-secondary-custom">Silakan masuk ke akun Anda</p>
            </div>

            <!-- Alert Container -->
            <div class="alert-container" id="alert-container"></div>

            <!-- Login Form -->
            <form id="loginForm">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                           placeholder="nama@email.com" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="Masukkan password" required>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                            <i class="far fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-secondary-custom" for="remember">
                            Ingat saya
                        </label>
                    </div>
                    <a href="#" class="link-custom small">Lupa password?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 mb-3" id="loginBtn">
                    <span class="btn-text">Masuk</span>
                    <span class="spinner-border spinner-border-sm d-none" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </span>
                </button>
            </form>

            <!-- Divider -->
            <div class="divider">
                <span>atau</span>
            </div>

            <!-- Register Link -->
            <div class="text-center">
                <p class="text-secondary-custom mb-0">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="link-custom fw-semibold">Daftar sekarang</a>
                </p>
            </div>

            <!-- Role Info Cards -->
            <div class="row g-2 mt-4">
                <div class="col-4">
                    <div class="role-card">
                        <div class="role-icon">👨‍💼</div>
                        <h4>Admin</h4>
                        <p>Kelola sistem lengkap</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="role-card">
                        <div class="role-icon">👤</div>
                        <h4>Member</h4>
                        <p>Akses library & baca buku</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="role-card">
                        <div class="role-icon">✍️</div>
                        <h4>Writer</h4>
                        <p>Upload & manage karya</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Use window.location.origin to get the current origin with correct port
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
            const loginBtn = document.getElementById('loginBtn');
            const btnText = loginBtn.querySelector('.btn-text');
            const spinner = loginBtn.querySelector('.spinner-border');

            loginBtn.disabled = loading;
            btnText.classList.toggle('d-none', loading);
            spinner.classList.toggle('d-none', !loading);
        }

        // Login form handler
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            setLoading(true);

            const formData = new FormData(e.target);
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

                if (response.ok && result.success) {
                    showAlert('Login berhasil! Mengalihkan...', 'success');

                    // Store token
                    localStorage.setItem('token', result.data.token);
                    localStorage.setItem('user', JSON.stringify(result.data.user));

                    // Redirect based on role
                    setTimeout(() => {
                        const role = result.data.user.role;
                        switch (role) {
                            case 'admin':
                                window.location.href = '{{ route('admin.dashboard') }}';
                                break;
                            case 'member':
                                window.location.href = '{{ route('member.library') }}';
                                break;
                            case 'writer':
                                window.location.href = '{{ route('writer.dashboard') }}';
                                break;
                            default:
                                window.location.href = '/';
                        }
                    }, 1000);
                } else {
                    showAlert(result.message || 'Login gagal', 'error');
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
