<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library - Member</title>
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

        .navbar .btn-outline-success {
            color: var(--secondary-color);
            border-color: var(--dark-border);
        }

        .navbar .btn-outline-success:hover {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
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

        /* Filters */
        .filter-card {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
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

        .form-label {
            color: var(--text-primary);
            font-weight: 500;
        }

        .form-check-input {
            background-color: var(--dark-bg);
            border-color: var(--dark-border);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Book Cards */
        .book-card {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }

        .book-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
            border-color: var(--primary-color);
        }

        .book-cover {
            width: 100%;
            aspect-ratio: 2/3;
            object-fit: cover;
            background: var(--dark-bg);
        }

        .book-cover-placeholder {
            width: 100%;
            aspect-ratio: 2/3;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--dark-bg);
            color: var(--text-secondary);
        }

        .book-cover-placeholder i {
            font-size: 3rem;
        }

        .book-info {
            padding: 1rem;
        }

        .book-title {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .book-author {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }

        .book-genre {
            display: inline-block;
            background: rgba(79, 70, 229, 0.2);
            color: var(--primary-color);
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .book-price {
            font-weight: 600;
            color: var(--secondary-color);
        }

        .book-price.paid {
            color: var(--warning-color);
        }

        .book-stock {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
        }

        .book-stock.available {
            background: rgba(16, 185, 129, 0.2);
            color: var(--secondary-color);
        }

        .book-stock.limited {
            background: rgba(245, 158, 11, 0.2);
            color: var(--warning-color);
        }

        .book-stock.out {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger-color);
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

        .btn-success {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-success:hover {
            background: #059669;
            border-color: #059669;
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

        /* Modal */
        .modal-content {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            color: var(--text-primary);
        }

        .modal-header {
            border-bottom: 1px solid var(--dark-border);
        }

        .modal-title {
            color: var(--text-primary);
        }

        .modal-body {
            color: var(--text-secondary);
        }

        .modal-footer {
            border-top: 1px solid var(--dark-border);
        }

        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        /* Pagination */
        .pagination .page-link {
            background: var(--dark-bg);
            border-color: var(--dark-border);
            color: var(--text-secondary);
        }

        .pagination .page-link:hover {
            background: var(--dark-border);
            color: var(--text-primary);
        }

        .pagination .page-item.active .page-link {
            background: var(--primary-color);
            border-color: var(--primary-color);
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

        /* Loading */
        .loading {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }

        .spinner-border {
            color: var(--primary-color);
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

        /* Badge */
        .badge {
            padding: 0.35rem 0.65rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-book"></i>
                Library
            </a>

            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('member.history') }}" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-history me-1"></i> My Books
                </a>

                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <div class="user-avatar-circle" id="userAvatar">M</div>
                        <span id="userName">Member</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('member.profile') }}"><i class="fas fa-user me-2"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('member.history') }}"><i class="fas fa-history me-2"></i> Borrowing History</a></li>
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
            <!-- Filters -->
            <div class="filter-card">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Search Books</label>
                        <input type="text" class="form-control" id="searchBooks" placeholder="Search by title, author...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Genre</label>
                        <select class="form-select" id="filterGenre">
                            <option value="">All Genres</option>
                            <option value="Fiction">Fiction</option>
                            <option value="Non-Fiction">Non-Fiction</option>
                            <option value="Science">Science</option>
                            <option value="History">History</option>
                            <option value="Biography">Biography</option>
                            <option value="Technology">Technology</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Filters</label>
                        <div class="d-flex gap-3 mt-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="filterFreeOnly">
                                <label class="form-check-label" for="filterFreeOnly">Free Only</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="filterAvailableOnly" checked>
                                <label class="form-check-label" for="filterAvailableOnly">Available</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary w-100" onclick="loadBooks(1)">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                    </div>
                </div>
            </div>

            <!-- Books Grid -->
            <div class="row" id="booksGrid">
                <div class="col-12">
                    <div class="loading">
                        <div class="spinner-border" role="status"></div>
                        <p class="mt-3">Loading books...</p>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation" class="mt-4" id="paginationNav" style="display: none;">
                <ul class="pagination justify-content-center" id="pagination"></ul>
            </nav>
        </div>
    </main>

    <!-- Book Detail Modal -->
    <div class="modal fade" id="bookModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookModalTitle">Book Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="bookModalContent"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="borrowBookBtn">Borrow Book</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Borrow Modal -->
    <div class="modal fade" id="borrowModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Borrow Book - Upload Payment Proof</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="borrowForm">
                        <input type="hidden" id="borrowBookId" name="book_id">

                        <div class="mb-3">
                            <label for="paymentProof" class="form-label">Upload Payment Proof</label>
                            <input type="file" class="form-control" id="paymentProof" accept="image/*" required>
                            <small class="text-secondary">Upload screenshot of transfer receipt (max 5MB)</small>
                        </div>

                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Payment proof will be verified by admin before confirmation.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="submitBorrowBtn" class="btn btn-primary">
                        <span class="btn-text">Submit & Borrow</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Container -->
    <div class="alert-container" id="alert-container"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const API_URL = window.location.origin + '/api';
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const AUTH_TOKEN = localStorage.getItem('token');

        // Format date
        function formatDate(date) {
            return new Intl.DateTimeFormat('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            }).format(new Date(date));
        }

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
        function loadUserInfo() {
            const user = JSON.parse(localStorage.getItem('user') || '{}');
            if (user.name) {
                document.getElementById('userName').textContent = user.name;
                document.getElementById('userAvatar').textContent = user.name.charAt(0).toUpperCase();
            }
        }

        // Load books
        async function loadBooks(page = 1) {
            const search = document.getElementById('searchBooks').value;
            const genre = document.getElementById('filterGenre').value;
            const freeOnly = document.getElementById('filterFreeOnly').checked;
            const availableOnly = document.getElementById('filterAvailableOnly').checked;

            let url = `/member/books?page=${page}&per_page=12`;
            if (search) url += `&search=${encodeURIComponent(search)}`;
            if (genre) url += `&genre=${genre}`;
            if (freeOnly) url += `&is_free=1`;
            if (availableOnly) url += `&available=1`;

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
                const response = await fetch(`${API_URL}${url}`, {
                    headers: headers,
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    renderBooks(result.data.data);
                    renderPagination(result.data);
                } else {
                    showAlert(result.message || 'Failed to load books', 'error');
                }
            } catch (error) {
                console.error('Error loading books:', error);
                showAlert('Failed to load books', 'error');
            }
        }

        // Render books
        function renderBooks(books) {
            const grid = document.getElementById('booksGrid');

            if (books.length === 0) {
                grid.innerHTML = '<div class="col-12 text-center py-5"><p class="text-secondary">No books found</p></div>';
                return;
            }

            grid.innerHTML = books.map(book => `
                <div class="col-6 col-md-4 col-lg-3 mb-4">
                    <div class="book-card">
                        ${book.cover_image
                            ? `<img src="/storage/${book.cover_image}" alt="${book.title}" class="book-cover">`
                            : `<div class="book-cover-placeholder"><i class="fas fa-book"></i></div>`
                        }
                        <div class="book-info">
                            <h6 class="book-title" title="${book.title}">${book.title}</h6>
                            <p class="book-author">${book.author}</p>
                            <span class="book-genre">${book.genre}</span>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="book-price ${book.is_free ? '' : 'paid'}">
                                    ${book.is_free ? 'FREE' : formatCurrency(book.price)}
                                </span>
                                <span class="book-stock ${book.stock > 5 ? 'available' : book.stock > 0 ? 'limited' : 'out'}">
                                    ${book.stock > 5 ? 'Available' : book.stock > 0 ? 'Low Stock' : 'Out of Stock'}
                                </span>
                            </div>
                            <button class="btn btn-primary btn-sm w-100 mt-2" onclick="viewBook(${book.id})" ${book.stock <= 0 ? 'disabled' : ''}>
                                <i class="fas fa-eye me-1"></i> View Details
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Render pagination
        function renderPagination(data) {
            const nav = document.getElementById('paginationNav');
            const pagination = document.getElementById('pagination');

            if (!data.links || data.links.length <= 1) {
                nav.style.display = 'none';
                return;
            }

            nav.style.display = 'block';
            let html = '';

            data.links.forEach(link => {
                if (link.url) {
                    const page = new URL(link.url).searchParams.get('page');
                    const active = link.active ? 'active' : '';
                    const label = link.label.includes('Previous') ? '&laquo;' : link.label.includes('Next') ? '&raquo;' : page;

                    html += `<li class="page-item ${active}">
                        <a class="page-link" href="#" onclick="loadBooks(${page}); return false;">${label}</a>
                    </li>`;
                }
            });

            pagination.innerHTML = html;
        }

        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        // View book details
        async function viewBook(id) {
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
                const response = await fetch(`${API_URL}/member/books/${id}`, {
                    headers: headers,
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    const book = result.data;
                    const modal = document.getElementById('bookModal');

                    document.getElementById('bookModalTitle').textContent = book.title;

                    // Different content for free vs paid books
                    const isFree = book.is_free;
                    const priceDisplay = isFree
                        ? '<span class="badge bg-success fs-6">FREE</span>'
                        : `<span class="text-warning fs-5 fw-bold">${formatCurrency(book.price)}</span>`;

                    document.getElementById('bookModalContent').innerHTML = `
                        <div class="row">
                            <div class="col-md-4 text-center mb-3">
                                ${book.cover_image
                                    ? `<img src="/storage/${book.cover_image}" class="img-fluid rounded" style="max-height: 350px;">`
                                    : `<div class="book-cover-placeholder rounded" style="height: 350px; background: var(--dark-bg); display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-book fa-4x text-secondary"></i>
                                       </div>`
                                }
                            </div>
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="text-secondary mb-1">Author</h6>
                                        <p class="mb-0">${book.author}</p>
                                    </div>
                                    <div class="text-end">
                                        <h6 class="text-secondary mb-1">Price</h6>
                                        ${priceDisplay}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <h6 class="text-secondary mb-1">Genre</h6>
                                        <span class="badge bg-primary">${book.genre}</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <h6 class="text-secondary mb-1">Stock</h6>
                                        <span class="badge ${book.stock > 5 ? 'bg-success' : book.stock > 0 ? 'bg-warning' : 'bg-danger'}">
                                            ${book.stock} available
                                        </span>
                                    </div>
                                </div>

                                ${book.description ? `
                                    <div class="mb-3">
                                        <h6 class="text-secondary">Description</h6>
                                        <p class="text-secondary mb-0">${book.description}</p>
                                    </div>
                                ` : ''}

                                ${isFree ? `
                                    <div class="alert alert-success mb-0">
                                        <i class="fas fa-gift me-2"></i>
                                        <strong>Free Book!</strong> Borrow this book without any payment.
                                    </div>
                                ` : `
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Paid Book</strong> - You'll need to upload payment proof to borrow this book.
                                    </div>
                                `}
                            </div>
                        </div>
                    `;

                    const borrowBtn = document.getElementById('borrowBookBtn');
                    const modalFooter = borrowBtn.parentElement;

                    if (book.stock > 0) {
                        borrowBtn.style.display = 'block';
                        borrowBtn.disabled = false;
                        borrowBtn.className = isFree ? 'btn btn-success' : 'btn btn-primary';
                        borrowBtn.innerHTML = isFree
                            ? '<i class="fas fa-book-reader me-2"></i>Borrow Now (Free)'
                            : '<i class="fas fa-credit-card me-2"></i>Borrow with Payment';
                        borrowBtn.onclick = () => borrowBook(book.id, isFree);
                    } else {
                        borrowBtn.style.display = 'none';
                    }

                    new bootstrap.Modal(modal).show();
                }
            } catch (error) {
                console.error('Error viewing book:', error);
                showAlert('Failed to load book details', 'error');
            }
        }

        // Borrow book (for free books)
        async function borrowBook(id, isFree = false) {
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            };

            // Add authorization header if token exists
            if (AUTH_TOKEN && AUTH_TOKEN !== 'null' && AUTH_TOKEN !== 'undefined') {
                headers['Authorization'] = `Bearer ${AUTH_TOKEN}`;
            }

            if (isFree) {
                // Free books can be borrowed directly
                try {
                    const response = await fetch(`${API_URL}/member/books/${id}/borrow-free`, {
                        method: 'POST',
                        headers: headers,
                        credentials: 'same-origin'
                    });

                    const result = await response.json();

                    if (result.success) {
                        showAlert('Book borrowed successfully!', 'success');
                        bootstrap.Modal.getInstance(document.getElementById('bookModal')).hide();
                        loadBooks();
                    } else {
                        showAlert(result.message || 'Failed to borrow book', 'error');
                    }
                } catch (error) {
                    console.error('Error borrowing book:', error);
                    showAlert('Failed to borrow book', 'error');
                }
            } else {
                // For paid books, show the borrow modal
                showBorrowModal(id);
            }
        }

        // Show borrow modal for paid books
        function showBorrowModal(bookId) {
            bootstrap.Modal.getInstance(document.getElementById('bookModal')).hide();

            const modal = document.getElementById('borrowModal');
            document.getElementById('borrowBookId').value = bookId;

            new bootstrap.Modal(modal).show();
        }

        // Submit borrow form with payment proof
        async function submitBorrowForm() {
            const bookId = document.getElementById('borrowBookId').value;
            const proofFile = document.getElementById('paymentProof').files[0];

            if (!proofFile) {
                showAlert('Please upload payment proof', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('book_id', bookId);
            formData.append('payment_proof', proofFile);

            const headers = {
                'X-CSRF-TOKEN': CSRF_TOKEN
            };

            // Add authorization header if token exists
            if (AUTH_TOKEN && AUTH_TOKEN !== 'null' && AUTH_TOKEN !== 'undefined') {
                headers['Authorization'] = `Bearer ${AUTH_TOKEN}`;
            }

            const submitBtn = document.getElementById('submitBorrowBtn');
            const btnText = submitBtn.querySelector('.btn-text');
            const spinner = submitBtn.querySelector('.spinner-border');

            submitBtn.disabled = true;
            btnText.classList.add('d-none');
            spinner.classList.remove('d-none');

            try {
                const response = await fetch(`${API_URL}/member/books/${bookId}/borrow`, {
                    method: 'POST',
                    headers: headers,
                    body: formData,
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('Book borrowed successfully! Waiting for admin approval.', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('borrowModal')).hide();
                    bootstrap.Modal.getInstance(document.getElementById('bookModal')).hide();
                    loadBooks();
                } else {
                    showAlert(result.message || 'Failed to borrow book', 'error');
                }
            } catch (error) {
                console.error('Error borrowing book:', error);
                showAlert('Failed to borrow book', 'error');
            } finally {
                submitBtn.disabled = false;
                btnText.classList.remove('d-none');
                spinner.classList.add('d-none');
            }
        }

        // Open content reader
        function openContentReader(contentId) {
            window.location.href = `/member/read/${contentId}`;
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', () => {
            loadUserInfo();
            loadBooks();

            // Debounce search
            let searchTimeout;
            document.getElementById('searchBooks').addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => loadBooks(1), 500);
            });

            document.getElementById('filterGenre').addEventListener('change', () => loadBooks(1));
            document.getElementById('filterFreeOnly').addEventListener('change', () => loadBooks(1));
            document.getElementById('filterAvailableOnly').addEventListener('change', () => loadBooks(1));
        });
    </script>
</body>
</html>
