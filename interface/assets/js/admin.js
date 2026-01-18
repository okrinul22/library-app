// Admin Panel JavaScript

let authToken = localStorage.getItem('token');
let currentPage = 1;
let currentFilters = {};

// Make API requests
async function apiRequest(endpoint, options = {}) {
    const defaultOptions = {
        headers: {
            'Authorization': `Bearer ${authToken}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    };

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
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;

    const confirmBtn = document.getElementById('confirmBtn');
    confirmBtn.onclick = () => {
        onConfirm();
        closeModal();
    };

    modal.classList.add('show');
}

function closeModal() {
    document.getElementById('confirmModal').classList.remove('show');
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
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    }).format(new Date(date));
}

// Alerts functionality
async function loadAlerts() {
    const response = await apiRequest('/alerts');
    const alerts = response.data;
    const alertsCount = document.getElementById('alertsCount');
    const alertsDropdown = document.getElementById('alertsDropdown');

    if (alertsCount) {
        alertsCount.textContent = alerts.length;
        alertsCount.style.display = alerts.length > 0 ? 'inline' : 'none';
    }

    if (alertsDropdown) {
        if (alerts.length === 0) {
            alertsDropdown.innerHTML = '<div class="dropdown-item">Tidak ada notifikasi</div>';
        } else {
            alertsDropdown.innerHTML = alerts.map(alert => `
                <div class="dropdown-item alert-item">
                    <div class="alert-icon ${alert.type === 'low_stock' ? 'warning' : alert.type === 'overdue' ? 'danger' : 'info'}">
                        ${getAlertIcon(alert.type)}
                    </div>
                    <div class="alert-content">
                        <p>${alert.message}</p>
                    </div>
                </div>
            `).join('');
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

// Messages functionality
async function loadMessages() {
    const response = await apiRequest('/messages');
    const messages = response.data;
    const messagesCount = document.getElementById('messagesCount');
    const messagesDropdown = document.getElementById('messagesDropdown');

    const unreadMessages = messages.filter(m => !m.is_read);

    if (messagesCount) {
        messagesCount.textContent = unreadMessages.length;
        messagesCount.style.display = unreadMessages.length > 0 ? 'inline' : 'none';
    }

    if (messagesDropdown) {
        if (messages.length === 0) {
            messagesDropdown.innerHTML = '<div class="dropdown-item">Tidak ada pesan</div>';
        } else {
            messagesDropdown.innerHTML = messages.map(message => `
                <div class="dropdown-item message-item ${!message.is_read ? 'unread' : ''}">
                    <div class="message-avatar">${message.from.charAt(0)}</div>
                    <div class="message-content">
                        <h4>${message.from}</h4>
                        <p>${message.subject}</p>
                    </div>
                </div>
            `).join('');
        }
    }
}

// Toggle dropdowns
document.addEventListener('DOMContentLoaded', () => {
    const alertsBtn = document.getElementById('alertsBtn');
    const messagesBtn = document.getElementById('messagesBtn');
    const alertsDropdown = document.getElementById('alertsDropdown');
    const messagesDropdown = document.getElementById('messagesDropdown');

    if (alertsBtn) {
        alertsBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            alertsDropdown.classList.toggle('show');
            messagesDropdown.classList.remove('show');
        });
    }

    if (messagesBtn) {
        messagesBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            messagesDropdown.classList.toggle('show');
            alertsDropdown.classList.remove('show');
        });
    }

    document.addEventListener('click', () => {
        alertsDropdown?.classList.remove('show');
        messagesDropdown?.classList.remove('show');
    });

    // Load alerts and messages
    loadAlerts();
    loadMessages();

    // Global search
    const searchInput = document.getElementById('globalSearch');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(async () => {
                if (e.target.value.length >= 2) {
                    const response = await apiRequest(`/search?q=${encodeURIComponent(e.target.value)}`);
                    // Display search results
                    console.log(response.data);
                }
            }, 500);
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
