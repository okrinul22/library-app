<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Writer Profile</title>
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

        /* Card */
        .card {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 12px;
        }

        .card-header {
            background: var(--dark-bg);
            border-bottom: 1px solid var(--dark-border);
            color: var(--text-primary);
        }

        /* Profile Header */
        .profile-header {
            text-align: center;
            padding: 2rem;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0 auto 1rem;
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .profile-email {
            color: var(--text-secondary);
            margin-top: 0.5rem;
        }

        /* Stats */
        .stat-row {
            display: flex;
            justify-content: space-around;
            padding: 1.5rem 0;
            border-top: 1px solid var(--dark-border);
            border-bottom: 1px solid var(--dark-border);
            margin: 1.5rem 0;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        /* Message Form */
        .form-label {
            color: var(--text-primary);
            font-weight: 500;
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

        .form-control::placeholder {
            color: var(--text-secondary);
        }

        /* Buttons */
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        /* Alert */
        .alert-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
            max-width: 400px;
        }

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

        /* Messages List */
        .message-item {
            padding: 1rem;
            border-bottom: 1px solid var(--dark-border);
        }

        .message-item:last-child {
            border-bottom: none;
        }

        .message-subject {
            font-weight: 600;
            color: var(--text-primary);
        }

        .message-date {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .message-content {
            color: var(--text-secondary);
            margin-top: 0.5rem;
            font-size: 0.9rem;
        }

        .badge-pending {
            background: rgba(245, 158, 11, 0.2);
            color: var(--warning-color);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
        }

        .badge-replied {
            background: rgba(16, 185, 129, 0.2);
            color: var(--secondary-color);
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('writer.dashboard') }}">
                <i class="fas fa-pen-fancy"></i>
                Library - Writer
            </a>

            <div class="ms-auto">
                <div class="dropdown">
                    <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>
                        <span id="navUsername">Loading...</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('writer.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                        <li><a class="dropdown-item" href="{{ route('writer.profile') }}"><i class="fas fa-user me-2"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('writer.messages') }}"><i class="fas fa-envelope me-2"></i> Messages</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="row">
                <!-- Profile Card -->
                <div class="col-lg-4 mb-4">
                    <div class="card">
                        <div class="profile-header">
                            <div class="profile-avatar" id="profileAvatar">W</div>
                            <h3 class="profile-name" id="profileName">Writer Name</h3>
                            <p class="profile-email" id="profileEmail">writer@example.com</p>
                        </div>

                        <div class="stat-row">
                            <div class="stat-item">
                                <div class="stat-value" id="statBooks">-</div>
                                <div class="stat-label">Books</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value" id="statContents">-</div>
                                <div class="stat-label">Contents</div>
                            </div>
                        </div>

                        <div class="card-body">
                            <h6 class="mb-3">Account Information</h6>
                            <div class="mb-2">
                                <small class="text-secondary">Role</small>
                                <p class="mb-0">Writer</p>
                            </div>
                            <div class="mb-2">
                                <small class="text-secondary">Member Since</small>
                                <p class="mb-0" id="memberSince">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message Section -->
                <div class="col-lg-8 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-envelope me-2"></i> Message to Admin</h5>
                        </div>
                        <div class="card-body">
                            <form id="messageForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="subject" name="subject" required placeholder="Enter message subject">
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="message" name="message" rows="6" required placeholder="Type your message to admin here..."></textarea>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <span class="btn-text">Send Message</span>
                                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Message History -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-history me-2"></i> Message History</h5>
                        </div>
                        <div class="card-body p-0">
                            <div id="messagesList">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>No messages yet</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        }

        // Load user info
        async function loadUserInfo() {
            try {
                const user = JSON.parse(localStorage.getItem('user') || '{}');

                if (user.name) {
                    document.getElementById('navUsername').textContent = user.name;
                    document.getElementById('profileName').textContent = user.name;
                    document.getElementById('profileAvatar').textContent = user.name.charAt(0).toUpperCase();
                    document.getElementById('profileEmail').textContent = user.email || 'writer@example.com';
                    document.getElementById('memberSince').textContent = user.created_at
                        ? new Date(user.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long' })
                        : '-';
                }

                // Load stats
                const contentsResponse = await fetch(`${API_URL}/writer/contents`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    credentials: 'same-origin'
                });

                const contentsResult = await contentsResponse.json();

                if (contentsResult.success) {
                    const contents = contentsResult.data;
                    const uniqueBooks = [...new Set(contents.map(c => c.book_id).filter(id => id))];

                    document.getElementById('statBooks').textContent = uniqueBooks.length;
                    document.getElementById('statContents').textContent = contents.length;
                }
            } catch (error) {
                console.error('Error loading user info:', error);
            }
        }

        // Load messages
        async function loadMessages() {
            try {
                const response = await fetch(`${API_URL}/writer/messages`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    renderMessages(result.data);
                }
            } catch (error) {
                console.error('Error loading messages:', error);
            }
        }

        // Render messages
        function renderMessages(messages) {
            const container = document.getElementById('messagesList');

            if (!messages || messages.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>No messages yet</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = messages.map(msg => `
                <div class="message-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="message-subject">${msg.subject}</div>
                            <div class="message-content">${msg.message}</div>
                        </div>
                        <div class="text-end">
                            <div class="message-date">${new Date(msg.created_at).toLocaleDateString('id-ID')}</div>
                            <span class="badge ${msg.status === 'replied' ? 'badge-replied' : 'badge-pending'}">
                                ${msg.status === 'replied' ? 'Replied' : 'Pending'}
                            </span>
                        </div>
                    </div>
                    ${msg.reply ? `
                        <div class="mt-2 p-2" style="background: var(--dark-bg); border-radius: 8px;">
                            <small class="text-secondary">Admin Reply:</small>
                            <p class="mb-0 mt-1" style="font-size: 0.9rem;">${msg.reply}</p>
                        </div>
                    ` : ''}
                </div>
            `).join('');
        }

        // Send message
        document.getElementById('messageForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const spinner = submitBtn.querySelector('.spinner-border');

            const formData = new FormData(e.target);
            const data = {
                subject: formData.get('subject'),
                message: formData.get('message')
            };

            // Validate
            if (!data.subject || !data.message) {
                showAlert('Please fill in all fields', 'error');
                return;
            }

            // Show loading
            submitBtn.disabled = true;
            btnText.classList.add('d-none');
            spinner.classList.remove('d-none');

            try {
                const response = await fetch(`${API_URL}/writer/messages`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify(data),
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('Message sent successfully!', 'success');
                    e.target.reset();
                    loadMessages();
                } else {
                    showAlert(result.message || 'Failed to send message', 'error');
                }
            } catch (error) {
                console.error('Error sending message:', error);
                showAlert('Failed to send message', 'error');
            } finally {
                submitBtn.disabled = false;
                btnText.classList.remove('d-none');
                spinner.classList.add('d-none');
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            loadUserInfo();
            loadMessages();
        });
    </script>
</body>
</html>
