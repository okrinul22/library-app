// Admin Panel JavaScript

let authToken = localStorage.getItem('token');
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
    if (authToken && authToken !== 'null' && authToken !== '') {
        defaultOptions.headers['Authorization'] = `Bearer ${authToken}`;
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

// Show confirmation modal
function showConfirm(title, message, onConfirm) {
    const modal = document.getElementById('confirmModal');
    if (!modal) {
        // Fallback to native confirm if modal doesn't exist
        if (confirm(message)) {
            onConfirm();
        }
        return;
    }

    const confirmTitle = document.getElementById('confirmModalLabel');
    const confirmMessage = document.getElementById('confirmMessage');
    const confirmBtn = document.getElementById('confirmBtn');

    if (confirmTitle) confirmTitle.textContent = title;
    if (confirmMessage) confirmMessage.textContent = message;

    if (confirmBtn) {
        // Remove old event listeners
        const newBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newBtn, confirmBtn);

        // Add new event listener
        newBtn.onclick = () => {
            onConfirm();
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) bsModal.hide();
        };
    }

    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}

// Show alert
function showAlert(message, type = 'success') {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
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

// Alerts functionality
async function loadAlerts() {
    const response = await apiRequest('/alerts');
    const alerts = response.data;
    const alertsCount = document.getElementById('alertsCount');
    const alertsDropdownMenu = document.getElementById('alertsDropdownMenu');

    // Group alerts by type for better display
    const pendingTransactionCount = alerts.filter(a => a.type === 'pending_transaction').reduce((sum, a) => sum + (a.count || 1), 0);
    const pendingContents = alerts.filter(a => a.type === 'pending_content');
    const overdueAlerts = alerts.filter(a => a.type === 'overdue');

    const totalAlerts = pendingTransactionCount + pendingContents.length + overdueAlerts.length;

    if (alertsCount) {
        alertsCount.textContent = totalAlerts > 0 ? totalAlerts : '';
        alertsCount.style.display = totalAlerts > 0 ? 'inline' : 'none';
    }

    if (alertsDropdownMenu) {
        if (totalAlerts === 0) {
            alertsDropdownMenu.innerHTML = '<li><h6 class="dropdown-header">Alerts Center</h6></li><li><span class="dropdown-item text-muted">Tidak ada notifikasi</span></li>';
        } else {
            let html = '<li><h6 class="dropdown-header">Alerts Center</h6></li>';

            // Pending transactions
            if (pendingTransactionCount > 0) {
                html += `
                    <li><a class="dropdown-item d-flex align-items-center" href="/admin/transactions?status=pending_payment">
                        <div class="me-3">
                            <div class="icon-circle bg-warning" style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                        </div>
                        <div>
                            <span class="fw-bold">${pendingTransactionCount} Transaksi Menunggu Pembayaran</span>
                        </div>
                    </a></li>
                `;
            }

            // Pending contents
            pendingContents.slice(0, 3).forEach(alert => {
                html += `
                    <li><a class="dropdown-item d-flex align-items-center" href="#" onclick="openContentApproval(${alert.content_id}); return false;">
                        <div class="me-3">
                            <div class="icon-circle bg-info" style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-file-alt text-white"></i>
                            </div>
                        </div>
                        <div>
                            <span class="fw-bold">${alert.message}</span>
                        </div>
                    </a></li>
                `;
            });

            // Show "more" link if there are more pending contents
            if (pendingContents.length > 3) {
                html += `<li><a class="dropdown-item text-center small text-secondary" href="/admin/contents?status=pending">Lihat ${pendingContents.length} konten pending</a></li>`;
            }

            // Overdue alerts (show max 2)
            overdueAlerts.slice(0, 2).forEach(alert => {
                html += `
                    <li><a class="dropdown-item d-flex align-items-center" href="/admin/transactions">
                        <div class="me-3">
                            <div class="icon-circle bg-danger" style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-exclamation-triangle text-white"></i>
                            </div>
                        </div>
                        <div>
                            <span class="small">${alert.message}</span>
                        </div>
                    </a></li>
                `;
            });

            alertsDropdownMenu.innerHTML = html;
        }
    }
}

function getAlertIcon(type) {
    const icons = {
        low_stock: '<svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
        overdue: '<svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
        pending_transaction: '<svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>',
        pending_content: '<svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>'
    };
    return icons[type] || icons.low_stock;
}

// Open content approval modal
async function openContentApproval(contentId) {
    try {
        const response = await apiRequest(`/contents/${contentId}/preview`);
        const content = response.data;

        const modal = document.getElementById('contentApprovalModal');
        if (!modal) {
            console.error('Content approval modal not found');
            return;
        }

        const modalTitle = document.getElementById('contentApprovalModalTitle');
        const modalBody = document.getElementById('contentApprovalModalBody');

        if (modalTitle) modalTitle.textContent = 'Approval Konten: ' + content.title;

        if (modalBody) {
            modalBody.innerHTML = `
                <div class="row">
                    <div class="col-md-8">
                        <h5>${content.title}</h5>
                        ${content.chapter ? `<p class="text-secondary mb-3">${content.chapter}</p>` : ''}

                        ${content.book ? `
                            <div class="alert alert-info mb-3">
                                <strong>Linked Book:</strong> ${content.book.title} by ${content.book.author}
                            </div>
                        ` : ''}

                        <div class="mb-3">
                            <label class="text-secondary small">Content</label>
                            <div class="bg-dark p-3 rounded" style="line-height: 1.8; white-space: pre-wrap; max-height: 400px; overflow-y: auto; font-size: 14px;">
                                ${content.content || 'No content'}
                            </div>
                        </div>

                        ${content.file_path ? `
                            <div class="mb-3">
                                <label class="text-secondary small">Attached File</label>
                                <div>
                                    <button type="button" class="btn btn-sm btn-info" onclick="previewContentFile('${content.file_path}')">
                                        <i class="fas fa-file-word"></i> View File
                                    </button>
                                </div>
                            </div>
                        ` : ''}
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title mb-3">Approval Action</h6>
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-success" onclick="approveContent(${contentId})">
                                        <i class="fas fa-check me-2"></i>Setujui
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="rejectContent(${contentId})">
                                        <i class="fas fa-times me-2"></i>Tolak
                                    </button>
                                    <a href="/admin/contents/${contentId}/edit" class="btn btn-warning">
                                        <i class="fas fa-edit me-2"></i>Edit
                                    </a>
                                </div>
                                <hr>
                                <small class="text-secondary d-block mb-1">Uploaded: ${formatDate(content.created_at)}</small>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    } catch (error) {
        console.error('Error loading content for approval:', error);
        showAlert('Gagal memuat konten untuk approval', 'error');
    }
}

// Approve content
async function approveContent(contentId) {
    try {
        await apiRequest(`/contents/${contentId}`, {
            method: 'POST',
            body: JSON.stringify({ status: 'approved', _method: 'PUT' })
        });
        showAlert('Konten berhasil disetujui!', 'success');

        // Close modal
        const modal = document.getElementById('contentApprovalModal');
        const bsModal = bootstrap.Modal.getInstance(modal);
        if (bsModal) bsModal.hide();

        // Reload alerts
        loadAlerts();
    } catch (error) {
        console.error('Error approving content:', error);
        showAlert('Gagal menyetujui konten', 'error');
    }
}

// Reject content
async function rejectContent(contentId) {
    const reason = prompt('Alasan penolakan (opsional):');
    if (reason !== null) {
        try {
            await apiRequest(`/contents/${contentId}`, {
                method: 'POST',
                body: JSON.stringify({ status: 'rejected', _method: 'PUT', rejection_reason: reason })
            });
            showAlert('Konten berhasil ditolak', 'success');

            // Close modal
            const modal = document.getElementById('contentApprovalModal');
            const bsModal = bootstrap.Modal.getInstance(modal);
            if (bsModal) bsModal.hide();

            // Reload alerts
            loadAlerts();
        } catch (error) {
            console.error('Error rejecting content:', error);
            showAlert('Gagal menolak konten', 'error');
        }
    }
}

// Preview content file in modal
function previewContentFile(filePath) {
    const modal = document.getElementById('filePreviewModal');
    const iframe = document.getElementById('filePreviewFrame');

    if (modal && iframe) {
        iframe.src = '/storage/' + filePath;
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    }
}

// Messages functionality
async function loadMessages() {
    const response = await apiRequest('/messages');
    const messages = response.data;
    const messagesCount = document.getElementById('messagesCount');
    const messagesDropdownMenu = document.getElementById('messagesDropdownMenu');

    const unreadMessages = messages.filter(m => !m.is_read);

    if (messagesCount) {
        messagesCount.textContent = unreadMessages.length;
        messagesCount.style.display = unreadMessages.length > 0 ? 'inline' : 'none';
    }

    if (messagesDropdownMenu) {
        if (messages.length === 0) {
            messagesDropdownMenu.innerHTML = '<li><h6 class="dropdown-header">Message Center</h6></li><li><span class="dropdown-item text-muted">Tidak ada pesan</span></li>';
        } else {
            let html = '<li><h6 class="dropdown-header">Message Center</h6></li>';
            html += messages.map(message => `
                <li><a class="dropdown-item ${!message.is_read ? 'fw-bold' : ''}">
                    <div class="d-flex gap-2">
                        <div class="message-avatar">${message.from.charAt(0)}</div>
                        <div>
                            <div class="text-truncate">${message.subject}</div>
                            <small class="text-secondary">${message.from}</small>
                        </div>
                    </div>
                </a></li>
            `).join('');
            messagesDropdownMenu.innerHTML = html;
        }
    }
}

// Load alerts and messages on page load
document.addEventListener('DOMContentLoaded', () => {
    loadAlerts();
    loadMessages();

    // Global search
    const searchInput = document.getElementById('globalSearch');
    const searchResults = document.getElementById('searchResults');
    const searchResultsContent = document.getElementById('searchResultsContent');

    if (searchInput) {
        let searchTimeout;

        // Perform search when typing
        searchInput.addEventListener('input', async (e) => {
            clearTimeout(searchTimeout);
            const query = e.target.value.trim();

            // Hide results if query is empty
            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }

            searchTimeout = setTimeout(async () => {
                try {
                    const response = await apiRequest(`/books?search=${encodeURIComponent(query)}`);
                    const books = response.data?.data || [];

                    if (books.length === 0) {
                        searchResultsContent.innerHTML = `
                            <div class="p-3 text-center text-secondary">
                                <i class="fas fa-search mb-2"></i>
                                <p class="mb-0">No books found for "${query}"</p>
                            </div>
                        `;
                    } else {
                        searchResultsContent.innerHTML = books.map(book => `
                            <a href="#" onclick="viewBookFromSearch(${book.id}); return false;" class="text-decoration-none d-block p-3 border-bottom search-result-item"
                               style="border-color: var(--dark-border) !important;">
                                <div class="d-flex gap-3">
                                    ${book.cover_image
                                        ? `<img src="/storage/${book.cover_image}" alt="${book.title}"
                                            style="width: 50px; height: 75px; object-fit: cover; border-radius: 6px;">`
                                        : `<div style="width: 50px; height: 75px; background: var(--dark-bg); border-radius: 6px;
                                            display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-book text-secondary"></i>
                                           </div>`
                                    }
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 text-white" style="font-size: 14px;">${book.title}</h6>
                                        <p class="mb-1 text-secondary" style="font-size: 12px;">${book.author}</p>
                                        <div class="d-flex gap-2 align-items-center">
                                            <span class="badge" style="background: rgba(79, 70, 229, 0.2); color: var(--primary-color); font-size: 10px;">
                                                ${book.genre || 'N/A'}
                                            </span>
                                            ${book.is_free
                                                ? '<span class="badge badge-success" style="font-size: 10px;">FREE</span>'
                                                : `<span class="badge badge-info" style="font-size: 10px;">${formatCurrency(book.price)}</span>`
                                            }
                                            <span class="badge ${book.stock > 5 ? 'badge-success' : book.stock > 0 ? 'badge-warning' : 'badge-danger'}"
                                                  style="font-size: 10px;">
                                                Stock: ${book.stock}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        `).join('');
                    }

                    searchResults.style.display = 'block';
                } catch (error) {
                    console.error('Search error:', error);
                    searchResultsContent.innerHTML = `
                        <div class="p-3 text-center text-danger">
                            <i class="fas fa-exclamation-circle mb-2"></i>
                            <p class="mb-0">Search failed. Please try again.</p>
                        </div>
                    `;
                    searchResults.style.display = 'block';
                }
            }, 300);
        });

        // Hide search results when clicking outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });

        // Search button click handler
        const searchBtn = document.getElementById('globalSearchBtn');
        if (searchBtn) {
            searchBtn.addEventListener('click', () => {
                const query = searchInput.value.trim();
                if (query.length >= 2) {
                    window.location.href = `/admin/books?search=${encodeURIComponent(query)}`;
                }
            });
        }

        // Handle Enter key
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                const query = searchInput.value.trim();
                if (query.length >= 2) {
                    window.location.href = `/admin/books?search=${encodeURIComponent(query)}`;
                }
            }
        });
    }
});

// Pagination
function setupPagination(callback) {
    const paginationLinks = document.querySelectorAll('.pagination a');
    paginationLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const page = link.dataset.page;
            if (page) {
                currentPage = parseInt(page);
                callback(page);
            }
        });
    });
}

// Common table actions
function setupTableActions() {
    // Delete buttons
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const endpoint = btn.dataset.endpoint;
            showConfirm(
                'Hapus Data',
                'Apakah Anda yakin ingin menghapus data ini?',
                async () => {
                    await apiRequest(endpoint, { method: 'DELETE' });
                    showAlert('Data berhasil dihapus');
                    setTimeout(() => window.location.reload(), 1000);
                }
            );
        });
    });

    // Edit buttons
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            // Navigate to edit page
            window.location.href = `${btn.dataset.base}/${id}/edit`;
        });
    });
}

// View book from global search
async function viewBookFromSearch(id) {
    try {
        const response = await apiRequest(`/books/${id}`);
        const book = response.data;

        const modal = document.getElementById('bookModal');
        const content = document.getElementById('bookModalContent');

        content.innerHTML = `
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="text-center">
                        ${book.cover_image
                            ? `<img src="/storage/${book.cover_image}" alt="${book.title}" class="img-fluid rounded">`
                            : `<div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="aspect-ratio: 2/3;">
                                <i class="fas fa-book fa-4x text-muted"></i>
                               </div>`
                        }
                    </div>
                </div>
                <div class="col-md-8">
                    <h4>${book.title}</h4>
                    <p class="text-secondary mb-3">${book.author}</p>

                    <div class="row mb-3">
                        <div class="col-sm-6 mb-2">
                            <label class="text-secondary small">ISBN</label>
                            <div class="fw-bold">${book.isbn}</div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <label class="text-secondary small">Genre</label>
                            <div><span class="badge badge-secondary">${book.genre}</span></div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <label class="text-secondary small">Harga</label>
                            <div class="fw-bold">${book.is_free ? 'GRATIS' : formatCurrency(book.price)}</div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <label class="text-secondary small">Stok</label>
                            <div class="fw-bold">${book.stock} unit</div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <label class="text-secondary small">Status</label>
                            <div>
                                <span class="badge badge-${book.stock > 5 ? 'success' : book.stock > 0 ? 'warning' : 'danger'}">
                                    ${book.stock > 5 ? 'In Stock' : book.stock > 0 ? 'Low Stock' : 'Out of Stock'}
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6 mb-2">
                            <label class="text-secondary small">Tipe</label>
                            <div>
                                ${book.is_free
                                    ? '<span class="badge badge-success">GRATIS</span>'
                                    : `<span class="badge badge-info">${formatCurrency(book.price)}</span>`
                                }
                            </div>
                        </div>
                    </div>

                    ${book.description ? `
                        <div class="mb-3">
                            <label class="text-secondary small">Deskripsi</label>
                            <p class="text-secondary">${book.description}</p>
                        </div>
                    ` : ''}

                    ${book.content ? `
                        <div class="mb-3">
                            <a href="/admin/contents/${book.content.id}" class="btn btn-primary btn-sm">
                                <i class="fas fa-file-alt"></i> Lihat Konten
                            </a>
                        </div>
                    ` : '<p class="text-secondary small">Belum ada konten</p>'}
                </div>
            </div>
        `;

        // Hide search results
        document.getElementById('searchResults').style.display = 'none';
        document.getElementById('globalSearch').value = '';

        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    } catch (error) {
        console.error('Error viewing book:', error);
        showAlert('Gagal memuat detail buku', 'error');
    }
}
