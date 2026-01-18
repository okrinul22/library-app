<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrowing History - Library Member</title>
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

        .empty-state p {
            color: var(--text-secondary);
        }

        /* Payment Proof Upload Modal */
        .modal-content {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            color: var(--text-primary);
        }

        .modal-header {
            border-bottom: 1px solid var(--dark-border);
        }

        .modal-footer {
            border-top: 1px solid var(--dark-border);
        }

        .btn-close {
            filter: invert(1);
        }

        .form-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        .form-control {
            background: var(--dark-bg);
            border: 1px solid var(--dark-border);
            color: var(--text-primary);
        }

        .form-control:focus {
            background: var(--dark-bg);
            border-color: var(--primary-color);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
        }

        .upload-area {
            border: 2px dashed var(--dark-border);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .upload-area:hover {
            border-color: var(--primary-color);
            background: rgba(79, 70, 229, 0.05);
        }

        .upload-area i {
            font-size: 3rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .upload-area.dragover {
            border-color: var(--primary-color);
            background: rgba(79, 70, 229, 0.1);
        }

        .preview-image {
            max-width: 100%;
            max-height: 300px;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .payment-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary-color);
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
                <a href="/member/profile" class="btn btn-sm btn-primary">
                    <i class="fas fa-user me-2"></i> Profile
                </a>
                <a href="/member/history" class="btn btn-sm btn-primary active">
                    <i class="fas fa-history me-2"></i> History
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
                <h1 class="h3 mb-0 text-gray-800">Borrowing History</h1>
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

    <!-- Payment Proof Upload Modal -->
    <div class="modal fade" id="paymentProofModal" tabindex="-1" aria-labelledby="paymentProofModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentProofModalLabel">Upload Payment Proof</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="paymentProofForm">
                        <input type="hidden" id="transactionId" name="transaction_id">

                        <div class="mb-3">
                            <label class="form-label">Book</label>
                            <div class="fw-bold" id="modalBookTitle">-</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Amount to Pay</label>
                            <div class="payment-amount" id="modalAmount">-</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Payment Proof (PNG/JPG)</label>
                            <div class="upload-area" id="uploadArea">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p class="mb-0">Click to upload or drag and drop</p>
                                <small class="text-secondary">PNG or JPG up to 2MB</small>
                            </div>
                            <input type="file" id="paymentProofFile" name="proof" accept="image/png,image/jpeg,image/jpg" class="d-none">
                            <img id="previewImage" class="preview-image d-none" alt="Preview">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitPaymentProof()">
                        <i class="fas fa-paper-plane me-2"></i>Submit Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const API_URL = window.location.origin + '/api';
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const AUTH_TOKEN = localStorage.getItem('token');
        let currentTransactionForPayment = null;

        // Show alert
        function showAlert(message, type = 'success') {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.innerHTML = `
                <strong>${type === 'success' ? 'Success' : 'Error'}!</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);

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

            // Add authorization header if token exists
            if (AUTH_TOKEN && AUTH_TOKEN !== 'null' && AUTH_TOKEN !== 'undefined') {
                headers['Authorization'] = `Bearer ${AUTH_TOKEN}`;
            }

            try {
                const response = await fetch(`${API_URL}/member/history`, {
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
                                <p class="empty-state p">No history yet</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = transactions.map(trx => {
                let actionButton = '';

                if (trx.status === 'pending_payment') {
                    actionButton = `<button class="btn btn-sm btn-warning" onclick="openPaymentModal(${trx.id})">
                        <i class="fas fa-upload me-1"></i> Upload Payment
                    </button>`;
                } else if (trx.status === 'active' || trx.status === 'completed') {
                    // Check if book has chapters to read
                    const hasChapter = trx.book?.first_chapter;
                    const canRead = trx.status === 'active' || trx.status === 'completed';

                    actionButton = `<div class="btn-group btn-group-sm" role="group">`;

                    if (canRead && hasChapter) {
                        actionButton += `<a href="/member/read/${trx.book.first_chapter.id}" class="btn btn-sm btn-primary" title="Read Book">
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
                                    ? `<img src="/storage/${trx.book.cover_image}" alt="${trx.book.title}" class="book-cover" style="width: 50px; height: 75px; object-fit: cover; border-radius: 6px;">`
                                    : `<div class="book-cover-placeholder" style="width: 50px; height: 75px; display: flex; align-items: center; justify-content: center; background: var(--dark-bg); border-radius: 6px;"><i class="fas fa-book"></i></div>`
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

        // Open payment modal
        function openPaymentModal(transactionId) {
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            };

            if (AUTH_TOKEN && AUTH_TOKEN !== 'null' && AUTH_TOKEN !== 'undefined') {
                headers['Authorization'] = `Bearer ${AUTH_TOKEN}`;
            }

            fetch(`${API_URL}/transactions/${transactionId}`, {
                headers: headers,
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    const trx = result.data;
                    currentTransactionForPayment = trx;

                    document.getElementById('transactionId').value = trx.id;
                    document.getElementById('modalBookTitle').textContent = trx.book?.title || 'Unknown';
                    document.getElementById('modalAmount').textContent = formatCurrency(trx.book?.price || 0);

                    // Reset file input
                    document.getElementById('paymentProofFile').value = '';
                    document.getElementById('previewImage').classList.add('d-none');
                    document.getElementById('previewImage').src = '';

                    const modal = new bootstrap.Modal(document.getElementById('paymentProofModal'));
                    modal.show();
                }
            })
            .catch(error => {
                console.error('Error loading transaction:', error);
                showAlert('Failed to load transaction details', 'error');
            });
        }

        // Submit payment proof
        async function submitPaymentProof() {
            const fileInput = document.getElementById('paymentProofFile');
            const file = fileInput.files[0];

            if (!file) {
                showAlert('Please select a payment proof image', 'error');
                return;
            }

            // Validate file type
            const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
            if (!allowedTypes.includes(file.type)) {
                showAlert('Please upload a PNG or JPG image', 'error');
                return;
            }

            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                showAlert('File size must be less than 2MB', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('transaction_id', document.getElementById('transactionId').value);
            formData.append('amount', currentTransactionForPayment.book?.price || 0);
            formData.append('proof', file);
            formData.append('_token', CSRF_TOKEN);

            try {
                const response = await fetch(`${API_URL}/payments`, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: formData,
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('Payment proof uploaded successfully! Waiting for admin approval.', 'success');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('paymentProofModal'));
                    modal.hide();
                    loadHistory();
                } else {
                    showAlert(result.message || 'Failed to upload payment proof', 'error');
                }
            } catch (error) {
                console.error('Error uploading payment proof:', error);
                showAlert('Failed to upload payment proof', 'error');
            }
        }

        // Return book function
        async function returnBook(id) {
            if (!confirm('Are you sure you want to return this book?')) return;

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

        // Upload area click handler
        document.getElementById('uploadArea')?.addEventListener('click', () => {
            document.getElementById('paymentProofFile').click();
        });

        // File input change handler
        document.getElementById('paymentProofFile')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('previewImage');
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });

        // Drag and drop handlers
        const uploadArea = document.getElementById('uploadArea');
        if (uploadArea) {
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('dragover');

                const file = e.dataTransfer.files[0];
                if (file && ['image/png', 'image/jpeg', 'image/jpg'].includes(file.type)) {
                    document.getElementById('paymentProofFile').files = e.dataTransfer.files;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('previewImage');
                        preview.src = e.target.result;
                        preview.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                } else {
                    showAlert('Please drop a PNG or JPG image', 'error');
                }
            });
        }

        // Load history on page load
        document.addEventListener('DOMContentLoaded', loadHistory);
    </script>
</body>
</html>
