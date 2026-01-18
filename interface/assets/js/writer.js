// Writer Dashboard JavaScript

// Make API requests
async function apiRequest(endpoint, options = {}) {
    const defaultOptions = {
        headers: {
            'Authorization': `Bearer ${AUTH_TOKEN}`,
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

// Load stats
async function loadStats() {
    const booksResponse = await apiRequest('/writer/books');
    const books = booksResponse.data.data;

    const totalBooks = books.length;
    let approvedContents = 0;
    let pendingContents = 0;

    books.forEach(book => {
        if (book.content) {
            if (book.content.status === 'approved') approvedContents++;
            if (book.content.status === 'pending') pendingContents++;
        }
    });

    document.getElementById('totalBooks').textContent = totalBooks;
    document.getElementById('approvedContents').textContent = approvedContents;
    document.getElementById('pendingContents').textContent = pendingContents;
}

// Load books
async function loadBooks() {
    const response = await apiRequest('/writer/books');
    const books = response.data.data;

    const grid = document.getElementById('booksGrid');

    if (books.length === 0) {
        grid.innerHTML = '<div class="loading">No books found. Start by uploading content!</div>';
        return;
    }

    grid.innerHTML = books.map(book => `
        <div class="book-card">
            <div class="book-cover">
                ${book.cover_image
                    ? `<img src="/storage/${book.cover_image}" alt="${book.title}">`
                    : `<div style="color: var(--dark-border);">No Cover</div>`
                }
            </div>
            <div class="book-info">
                <h3 class="book-title">${book.title}</h3>
                <div class="book-meta">
                    <div>${book.genre}</div>
                    <div>Stock: ${book.stock}</div>
                </div>
            </div>
        </div>
    `).join('');

    // Populate book dropdown in upload modal
    const bookSelect = document.getElementById('contentBook');
    bookSelect.innerHTML = '<option value="">Select a book...</option>' +
        books.map(book => `<option value="${book.id}">${book.title}</option>`).join('');
}

// Load uploaded contents
async function loadContents() {
    const response = await apiRequest('/writer/contents');
    const contents = response.data.data;

    const tbody = document.getElementById('contentsTableBody');

    if (contents.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: var(--text-secondary);">No contents uploaded</td></tr>';
        return;
    }

    tbody.innerHTML = contents.map(content => `
        <tr>
            <td>${content.title}</td>
            <td>${content.book?.title || 'Not linked'}</td>
            <td>
                <span class="badge badge-${content.status === 'approved' ? 'success' : content.status === 'pending' ? 'warning' : 'danger'}">
                    ${content.status.toUpperCase()}
                </span>
            </td>
            <td>${formatDate(content.created_at)}</td>
            <td>
                <button class="btn btn-sm btn-secondary" onclick="alert('View functionality coming soon')">View</button>
            </td>
        </tr>
    `).join('');
}

// Upload modal
function showUploadModal() {
    document.getElementById('uploadModal').classList.add('show');
}

function closeUploadModal() {
    document.getElementById('uploadModal').classList.remove('show');
    document.getElementById('uploadForm').reset();
    document.getElementById('fileName').textContent = '';
}

// File upload handling
const fileDropZone = document.getElementById('fileDropZone');
const fileInput = document.getElementById('contentFile');

fileDropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    fileDropZone.classList.add('dragover');
});

fileDropZone.addEventListener('dragleave', () => {
    fileDropZone.classList.remove('dragover');
});

fileDropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    fileDropZone.classList.remove('dragover');

    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        updateFileName();
    }
});

fileInput.addEventListener('change', updateFileName);

function updateFileName() {
    if (fileInput.files.length > 0) {
        document.getElementById('fileName').textContent = `Selected: ${fileInput.files[0].name}`;
    }
}

// Upload form submission
document.getElementById('uploadForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData();
    formData.append('title', document.getElementById('contentTitle').value);
    formData.append('file', fileInput.files[0]);

    const bookId = document.getElementById('contentBook').value;
    if (bookId) {
        formData.append('book_id', bookId);
    }

    try {
        const response = await fetch(`${API_URL}/writer/contents/upload`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${AUTH_TOKEN}`
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            showAlert('Content uploaded successfully!', 'success');
            closeUploadModal();
            loadBooks();
            loadContents();
            loadStats();
        } else {
            showAlert(result.message || 'Upload failed', 'error');
        }
    } catch (error) {
        showAlert('An error occurred. Please try again.', 'error');
    }
});

// User dropdown toggle
document.addEventListener('DOMContentLoaded', () => {
    loadUserInfo();
    loadBooks();
    loadContents();
    loadStats();

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
});

// Close modal on outside click
document.getElementById('uploadModal').addEventListener('click', (e) => {
    if (e.target.id === 'uploadModal') {
        closeUploadModal();
    }
});
