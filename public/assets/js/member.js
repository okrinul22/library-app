// Member Library JavaScript

let currentPage = 1;
let currentFilters = {};

// Make API requests
async function apiRequest(endpoint, options = {}) {
    // Get CSRF token from meta tag (more reliable than PHP variable)
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || CSRF_TOKEN;

    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        credentials: 'same-origin'
    };

    // Only add Authorization header if we have a valid token
    if (AUTH_TOKEN && AUTH_TOKEN !== 'null' && AUTH_TOKEN !== '') {
        defaultOptions.headers['Authorization'] = `Bearer ${AUTH_TOKEN}`;
    }

    const response = await fetch(`${API_URL}${endpoint}`, {
        ...defaultOptions,
        ...options,
        headers: {
            ...defaultOptions.headers,
            ...options.headers
        }
    });

    if (response.status === 401) {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = '/login';
        return;
    }

    return response.json();
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
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    }).format(new Date(date));
}

// Show alert
function showAlert(message, type = 'success') {
    const alert = document.createElement('div');
    alert.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? 'rgba(16, 185, 129, 0.9)' : 'rgba(239, 68, 68, 0.9)'};
        color: white;
        padding: 16px 20px;
        border-radius: 12px;
        z-index: 2000;
        animation: slideIn 0.3s ease;
    `;
    alert.textContent = message;
    document.body.appendChild(alert);

    setTimeout(() => alert.remove(), 3000);
}

// Load user info
async function loadUserInfo() {
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    if (user.name) {
        document.getElementById('userName').textContent = user.name;
        document.getElementById('userAvatar').textContent = user.name.charAt(0).toUpperCase();
    }
}

// Load books
async function loadBooks(page = 1) {
    currentPage = page;
    const search = document.getElementById('searchBooks').value;
    const genre = document.getElementById('filterGenre').value;
    const freeOnly = document.getElementById('filterFreeOnly').checked;
    const availableOnly = document.getElementById('filterAvailableOnly').checked;

    let url = `/member/books?page=${page}&per_page=12`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (genre) url += `&genre=${encodeURIComponent(genre)}`;
    if (freeOnly) url += `&is_free=1`;

    const response = await apiRequest(url);
    let books = response.data.data;

    // Filter by availability if checked
    if (availableOnly) {
        books = books.filter(book => book.stock > 0);
    }

    renderBooksGrid(books);
    renderPagination(response.data);
}

// Render books grid
function renderBooksGrid(books) {
    const grid = document.getElementById('booksGrid');

    if (books.length === 0) {
        grid.innerHTML = '<div class="loading">No books found</div>';
        return;
    }

    grid.innerHTML = books.map(book => `
        <div class="book-card" onclick="viewBook(${book.id})">
            <div class="book-cover">
                ${book.cover_image
                    ? `<img src="/storage/${book.cover_image}" alt="${book.title}">`
                    : `<svg class="placeholder" width="64" height="64" viewBox="0 0 24 24" fill="none"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>`
                }
                ${book.is_free
                    ? '<span class="book-badge free">FREE</span>'
                    : `<span class="book-badge price">${formatCurrency(book.price)}</span>`
                }
                ${book.stock <= 0 ? '<span class="book-badge stock">OUT OF STOCK</span>' : ''}
            </div>
            <div class="book-info">
                <h3 class="book-title">${book.title}</h3>
                <p class="book-author">${book.author}</p>
                <div class="book-meta">
                    <span>${book.genre}</span>
                    <span>Stock: ${book.stock}</span>
                </div>
                <div class="book-actions">
                    ${book.stock > 0
                        ? `<button class="btn btn-primary" onclick="event.stopPropagation(); purchaseBook(${book.id})">
                                ${book.is_free ? 'Read Now' : 'Purchase'}
                           </button>`
                        : `<button class="btn btn-secondary" disabled>Out of Stock</button>`
                    }
                    <button class="btn btn-secondary" onclick="event.stopPropagation(); viewBook(${book.id})">Details</button>
                </div>
            </div>
        </div>
    `).join('');
}

// View book detail
async function viewBook(id) {
    const response = await apiRequest(`/member/books/${id}`);
    const book = response.data;

    const modal = document.getElementById('bookModal');
    const content = document.getElementById('bookModalContent');

    content.innerHTML = `
        <div class="book-detail">
            <div class="book-detail-cover">
                ${book.cover_image
                    ? `<img src="/storage/${book.cover_image}" alt="${book.title}">`
                    : `<div style="aspect-ratio: 2/3; background: var(--dark-bg); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" style="color: var(--dark-border);"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                       </div>`
                }
            </div>
            <div class="book-detail-info">
                <h2>${book.title}</h2>
                <p class="book-detail-author">${book.author}</p>

                <div class="book-detail-meta">
                    <div>
                        <label>ISBN</label>
                        <div>${book.isbn}</div>
                    </div>
                    <div>
                        <label>Genre</label>
                        <div>${book.genre}</div>
                    </div>
                    <div>
                        <label>Price</label>
                        <div>${book.is_free ? 'FREE' : formatCurrency(book.price)}</div>
                    </div>
                    <div>
                        <label>Stock</label>
                        <div>${book.stock} unit(s)</div>
                    </div>
                </div>

                ${book.description ? `<p class="book-detail-description">${book.description}</p>` : ''}

                <div class="book-detail-actions">
                    ${book.stock > 0
                        ? `<button class="btn btn-primary" onclick="purchaseBook(${book.id})">
                                ${book.is_free ? 'Read Now' : 'Purchase'}
                           </button>`
                        : `<button class="btn btn-secondary" disabled>Out of Stock</button>`
                    }
                    <button class="btn btn-secondary" onclick="closeBookModal()">Close</button>
                </div>
            </div>
        </div>
    `;

    modal.classList.add('show');
}

// Close book modal
function closeBookModal() {
    document.getElementById('bookModal').classList.remove('show');
}

// Purchase book
async function purchaseBook(id) {
    const response = await apiRequest(`/member/books/${id}/purchase`, {
        method: 'POST'
    });

    if (response.success) {
        const transaction = response.data;

        if (transaction.status === 'active') {
            showAlert('Buku berhasil ditambahkan ke koleksi Anda!', 'success');
        } else if (transaction.status === 'pending_payment') {
            showAlert('Silakan selesaikan pembayaran', 'warning');
            // Redirect to payment page
            // window.location.href = `/member/purchase/${transaction.id}`;
        }

        closeBookModal();
        loadBooks(currentPage);
    }
}

// Render pagination
function renderPagination(data) {
    const pagination = document.getElementById('pagination');
    const { links } = data;

    let html = '';

    if (links) {
        links.forEach(link => {
            if (link.url) {
                const page = new URL(link.url).searchParams.get('page');
                const active = link.active ? 'active' : '';
                const label = link.label.includes('Previous') ? '&laquo;' : link.label.includes('Next') ? '&raquo;' : page;
                html += `<a href="#" class="${active}" data-page="${page}" onclick="loadBooks(${page}); return false;">${label}</a>`;
            }
        });
    }

    pagination.innerHTML = html;
}

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// User dropdown toggle
document.addEventListener('DOMContentLoaded', () => {
    loadUserInfo();
    loadBooks();

    // User dropdown
    const userBtn = document.getElementById('userBtn');
    const userDropdown = document.getElementById('userDropdown');

    if (userBtn) {
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
        });
    }

    document.addEventListener('click', () => {
        userDropdown?.classList.remove('show');
    });

    // Search
    document.getElementById('searchBooks').addEventListener('input', debounce(() => loadBooks(1), 500));

    // Filters
    document.getElementById('filterGenre').addEventListener('change', () => loadBooks(1));
    document.getElementById('filterFreeOnly').addEventListener('change', () => loadBooks(1));
    document.getElementById('filterAvailableOnly').addEventListener('change', () => loadBooks(1));
});

// Close modal on outside click
document.getElementById('bookModal').addEventListener('click', (e) => {
    if (e.target.id === 'bookModal') {
        closeBookModal();
    }
});
