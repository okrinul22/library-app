<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Books - Writer</title>
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

        /* History Table */
        .history-table {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .table {
            color: var(--text-primary);
            margin-bottom: 0;
        }

        .table thead th {
            background: var(--dark-bg);
            border-color: var(--dark-border);
            color: var(--text-secondary);
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
            padding: 1rem;
            border-bottom: 1px solid var(--dark-border);
        }

        .table tbody td {
            border-color: var(--dark-border);
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: rgba(79, 70, 229, 0.05);
        }

        .badge {
            padding: 0.35rem 0.65rem;
            font-weight: 500;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.2);
            color: var(--secondary-color);
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.2);
            color: var(--warning-color);
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger-color);
        }

        .badge-info {
            background: rgba(79, 70, 229, 0.2);
            color: var(--primary-color);
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .btn-outline-secondary {
            border-color: var(--dark-border);
            color: var(--text-primary);
        }

        .btn-outline-secondary:hover {
            background: var(--dark-bg);
            border-color: var(--dark-border);
            color: var(--text-primary);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        /* User Avatar */
        .user-avatar-circle {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
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

            <div class="ms-auto d-flex align-items-center gap-3">
                <a href="{{ route('writer.dashboard') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
                <a href="{{ route('writer.history') }}" class="btn btn-sm btn-primary active">
                    <i class="fas fa-history me-2"></i> My Books
                </a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">My Borrowed Books</h1>
            </div>

            <!-- History Table -->
            <div class="history-table">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Borrow Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="historyTableBody">
                            <tr>
                                <td colspan="5" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
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

        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        // Format date
        function formatDate(date) {
            if (!date) return '-';
            const d = new Date(date);
            if (isNaN(d.getTime())) return '-';
            return new Intl.DateTimeFormat('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            }).format(d);
        }

        // Get status badge class
        function getStatusBadgeClass(status) {
            const classes = {
                'active': 'success',
                'completed': 'info',
                'overdue': 'danger',
                'pending_payment': 'warning',
                'cancelled': 'danger'
            };
            return classes[status] || 'info';
        }

        // Get status text
        function getStatusText(status) {
            const texts = {
                'active': 'Active',
                'completed': 'Completed',
                'overdue': 'Overdue',
                'pending_payment': 'Pending Payment',
                'cancelled': 'Cancelled'
            };
            return texts[status] || status;
        }

        // Load history data
        async function loadHistory() {
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            };

            if (AUTH_TOKEN && AUTH_TOKEN !== 'null' && AUTH_TOKEN !== 'undefined') {
                headers['Authorization'] = `Bearer ${AUTH_TOKEN}`;
            }

            try {
                const response = await fetch(`${API_URL}/writer/history`, {
                    headers: headers,
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    renderHistoryTable(result.data.data);
                }
            } catch (error) {
                console.error('Error loading history:', error);
                showAlert('Failed to load history', 'error');
            }
        }

        // Render history table
        function renderHistoryTable(transactions) {
            const tbody = document.getElementById('historyTableBody');

            if (!transactions || transactions.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center">
                            <div class="empty-state">
                                <i class="fas fa-book-open"></i>
                                <p>No borrowed books yet</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = transactions.map(trx => {
                let actionButton = '';

                if (trx.status === 'active' || trx.status === 'completed') {
                    // Check if book has chapters to read
                    const hasChapter = trx.book?.first_chapter;
                    const canRead = trx.status === 'active' || trx.status === 'completed';

                    actionButton = `<div class="btn-group btn-group-sm" role="group">`;

                    if (canRead && hasChapter) {
                        actionButton += `<a href="/writer/read/${trx.book.first_chapter.id}" class="btn btn-sm btn-primary" title="Read Book">
                            <i class="fas fa-book-reader"></i> Read
                        </a>`;
                    }

                    if (trx.status === 'active') {
                        actionButton += `<button class="btn btn-sm btn-success" onclick="returnBook(${trx.id})" title="Return Book">
                            <i class="fas fa-undo"></i>
                        </button>`;
                    } else if (trx.status === 'completed') {
                        actionButton += `<span class="text-secondary ms-2"><i class="fas fa-check"></i> Returned</span>`;
                    }

                    actionButton += `</div>`;
                } else {
                    actionButton = `<span class="text-secondary">${getStatusText(trx.status)}</span>`;
                }

                return `
                    <tr>
                        <td>
                            <div class="d-flex gap-3">
                                ${trx.book?.cover_image
                                    ? `<img src="/storage/${trx.book.cover_image}" alt="${trx.book.title}" style="width: 50px; height: 75px; object-fit: cover; border-radius: 6px;">`
                                    : `<div style="width: 50px; height: 75px; display: flex; align-items: center; justify-content: center; background: var(--dark-bg); border-radius: 6px;"><i class="fas fa-book"></i></div>`
                                }
                                <div>
                                    <h6 class="mb-1">${trx.book?.title || 'N/A'}</h6>
                                    <p class="text-secondary small">ISBN: ${trx.book?.isbn || '-'}</p>
                                    ${trx.book?.price && !trx.book?.is_free ? `<p class="text-primary small fw-bold">${formatCurrency(trx.book.price)}</p>` : '<span class="badge badge-success small">FREE</span>'}
                                </div>
                            </div>
                        </td>
                        <td>${formatDate(trx.transaction_date)}</td>
                        <td>${formatDate(trx.due_date)}</td>
                        <td>
                            <span class="badge badge-${getStatusBadgeClass(trx.status)}">
                                ${getStatusText(trx.status)}
                            </span>
                        </td>
                        <td>${actionButton}</td>
                    </tr>
                `;
            }).join('');
        }

        // Return book function
        async function returnBook(id) {
            if (!confirm('Are you sure you want to return this book?')) return;

            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            };

            if (AUTH_TOKEN && AUTH_TOKEN !== 'null' && AUTH_TOKEN !== 'undefined') {
                headers['Authorization'] = `Bearer ${AUTH_TOKEN}`;
            }

            try {
                const response = await fetch(`${API_URL}/transactions/${id}/return`, {
                    method: 'POST',
                    headers: headers,
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('Book successfully returned!', 'success');
                    loadHistory();
                } else {
                    showAlert(result.message || 'Failed to return book', 'error');
                }
            } catch (error) {
                console.error('Error returning book:', error);
                showAlert('Failed to return book', 'error');
            }
        }

        // Load history on page load
        document.addEventListener('DOMContentLoaded', loadHistory);
    </script>
</body>
</html>
