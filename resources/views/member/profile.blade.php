<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Profile - Library</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --primary-color: #4F46E5;
            --primary-hover: #4338CA;
            --secondary-color: #10B981;
            --danger-color: #EF4444;
            --warning-color: #F59E0B;
            --dark-bg: #111827;
            --dark-card: #1F2937;
            --dark-border: #374151;
            --text-primary: #F9FAFB;
            --text-secondary: #9CA3AF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--dark-bg);
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            background: var(--dark-card);
            border-bottom: 1px solid var(--dark-border);
            padding: 0.75rem 0;
        }

        .navbar-brand {
            color: var(--text-primary);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand i {
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .navbar-brand:hover {
            color: var(--text-primary);
        }

        .navbar .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .navbar .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .dropdown-menu {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
        }

        .dropdown-item {
            color: var(--text-primary);
        }

        .dropdown-item:hover {
            background: var(--dark-bg);
        }

        /* Main Content */
        .main-content {
            padding: 2rem 0;
        }

        /* Profile Card */
        .profile-card {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .avatar-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            font-size: 3rem;
            color: white;
        }

        .form-control, .form-select {
            background: var(--dark-bg);
            border: 1px solid var(--dark-border);
            color: var(--text-primary);
        }

        .form-control:focus, .form-select:focus {
            background: var(--dark-bg);
            border-color: var(--primary-color);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25);
        }

        .form-label {
            color: var(--text-primary) !important;
            font-weight: 500;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        /* Alert */
        .alert {
            border-radius: 0.5rem;
            border: none;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.2);
            color: var(--secondary-color);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger-color);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="/member/library">
                <i class="fas fa-book"></i>
                <span class="ms-2">Library Member</span>
            </a>

            <div class="ms-auto d-flex align-items-center gap-3">
                <a href="/member/profile" class="btn btn-primary">
                    <i class="fas fa-user me-2"></i> Profile
                </a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="profile-card">
                <div class="profile-header">
                    <h3>My Profile</h3>
                </div>

                <form id="profileForm">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Enter your full name">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required placeholder="Enter your email">
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your phone number">
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" placeholder="Enter your address"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea class="form-control" id="bio" name="bio" rows="4" placeholder="Tell us about yourself"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>
    </main>

    <!-- Alert Container -->
    <div class="alert-container" id="alert-container"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const API_URL = window.location.origin + '/api';
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const AUTH_TOKEN = localStorage.getItem('token');

        // Show alert
        function showAlert(message, type = 'success') {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.innerHTML = `
                <strong>${type === 'success' ? 'Success' : 'Error'}!</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.getElementById('alert-container').appendChild(alert);

            setTimeout(() => {
                alert.remove();
            }, 5000);
        }

        // Load user data
        async function loadUserProfile() {
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            };

            // Add authorization header if token exists
            if (AUTH_TOKEN && AUTH_TOKEN !== 'null' && AUTH_TOKEN !== 'undefined') {
                headers['Authorization'] = `Bearer ${AUTH_TOKEN}`;
            }

            try {
                const response = await fetch(`${API_URL}/member/profile`, {
                    headers: headers,
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    const userData = result.data;
                    document.getElementById('name').value = userData.name || '';
                    document.getElementById('email').value = userData.email || '';
                    document.getElementById('phone').value = userData.phone || '';
                    document.getElementById('address').value = userData.address || '';
                    document.getElementById('bio').value = userData.bio || '';
                }
            } catch (error) {
                console.error('Error loading profile:', error);
            }
        }

        // Handle profile form submission
        document.getElementById('profileForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());

            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            };

            // Add authorization header if token exists
            if (AUTH_TOKEN && AUTH_TOKEN !== 'null' && AUTH_TOKEN !== 'undefined') {
                headers['Authorization'] = `Bearer ${AUTH_TOKEN}`;
            }

            try {
                const response = await fetch(`${API_URL}/member/profile`, {
                    method: 'PUT',
                    headers: headers,
                    credentials: 'same-origin',
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('Profile updated successfully!', 'success');
                    // Update localStorage user data
                    const user = JSON.parse(localStorage.getItem('user') || '{}');
                    user.name = data.name;
                    localStorage.setItem('user', JSON.stringify(user));
                } else {
                    showAlert(result.message || 'Failed to update profile', 'error');
                }
            } catch (error) {
                console.error('Error updating profile:', error);
                showAlert('Failed to update profile', 'error');
            }
        });

        // Load profile on page load
        document.addEventListener('DOMContentLoaded', loadUserProfile);
    </script>
</body>
</html>
