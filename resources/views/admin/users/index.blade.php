@extends('admin.layout')

@section('title', 'Users - Admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Users Management</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah User
        </a>
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Filter & Search</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="searchUser" class="form-label text-secondary">Search</label>
                        <input type="text" class="form-control" id="searchUser" placeholder="Cari nama atau email...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="filterRole" class="form-label text-secondary">Role</label>
                        <select class="form-select" id="filterRole">
                            <option value="">Semua Role</option>
                            <option value="admin">Admin</option>
                            <option value="member">Member</option>
                            <option value="writer">Writer</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="filterStatus" class="form-label text-secondary">Status</label>
                        <select class="form-select" id="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-secondary w-100" onclick="loadUsers(1)">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Daftar Users</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <tr>
                            <td colspan="6" class="text-center text-secondary">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="Page navigation" class="mt-3">
                <ul class="pagination justify-content-center" id="pagination"></ul>
            </nav>
        </div>
    </div>

</div>

<!-- User Detail Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Detail User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="userModalContent"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Make API requests
async function apiRequest(endpoint, options = {}) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="csrf_token"]')?.value;

    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        credentials: 'same-origin'
    };

    const response = await fetch(`${window.location.origin}/api${endpoint}`, {
        ...defaultOptions,
        ...options,
        headers: {
            ...defaultOptions.headers,
            ...options.headers
        }
    });

    return response.json();
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
    `;
    alert.textContent = message;
    document.body.appendChild(alert);

    setTimeout(() => alert.remove(), 3000);
}

// Format date
function formatDate(date) {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    }).format(new Date(date));
}

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

let currentUsersPage = 1;

async function loadUsers(page = 1) {
    currentUsersPage = page;
    const search = document.getElementById('searchUser').value;
    const role = document.getElementById('filterRole').value;
    const status = document.getElementById('filterStatus').value;

    let url = `/users?page=${page}&per_page=10`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (role) url += `&role=${role}`;
    if (status) url += `&is_active=${status === 'active' ? '1' : '0'}`;

    try {
        const response = await apiRequest(url);
        const users = response.data;
        renderUsersTable(users.data);
        renderPagination(users, 'loadUsers');
    } catch (error) {
        console.error('Error loading users:', error);
        document.getElementById('usersTableBody').innerHTML =
            '<tr><td colspan="6" class="text-center text-secondary">Error loading users</td></tr>';
    }
}

function renderUsersTable(users) {
    const tbody = document.getElementById('usersTableBody');

    if (users.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center text-secondary">Tidak ada data user</td></tr>';
        return;
    }

    tbody.innerHTML = users.map(user => `
        <tr>
            <td>
                <div class="d-flex align-items-center">
                    <div class="user-avatar-circle me-2">
                        ${user.name.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <div class="fw-bold">${user.name}</div>
                        <div class="text-secondary small">${user.email}</div>
                    </div>
                </div>
            </td>
            <td>
                <span class="badge badge-${user.role === 'admin' ? 'danger' : user.role === 'writer' ? 'warning' : 'info'}">
                    ${user.role.toUpperCase()}
                </span>
            </td>
            <td>${user.phone || '-'}</td>
            <td>
                <span class="badge badge-${user.is_active ? 'success' : 'danger'}">
                    ${user.is_active ? 'Aktif' : 'Nonaktif'}
                </span>
            </td>
            <td>${formatDate(user.created_at)}</td>
            <td>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-info" onclick="viewUser(${user.id})" title="View">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button type="button" class="btn btn-warning" onclick="editUser(${user.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn ${user.is_active ? 'btn-danger' : 'btn-success'}"
                            onclick="toggleUserStatus(${user.id})" title="${user.is_active ? 'Deactivate' : 'Activate'}">
                        <i class="fas ${user.is_active ? 'fa-user-slash' : 'fa-user-check'}"></i>
                    </button>
                    <button type="button" class="btn btn-danger" onclick="deleteUser(${user.id})" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

async function viewUser(id) {
    try {
        const response = await apiRequest(`/users/${id}`);
        const user = response.data;

        const modal = document.getElementById('userModal');
        const content = document.getElementById('userModalContent');

        content.innerHTML = `
            <div class="text-center mb-4">
                <div class="user-avatar-circle mx-auto mb-3" style="width: 80px; height: 80px; font-size: 32px;">
                    ${user.name.charAt(0).toUpperCase()}
                </div>
                <h4>${user.name}</h4>
                <p class="text-secondary mb-0">${user.email}</p>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="text-secondary small">Role</label>
                    <div><span class="badge badge-${user.role === 'admin' ? 'danger' : user.role === 'writer' ? 'warning' : 'info'}">
                        ${user.role.toUpperCase()}
                    </span></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="text-secondary small">Phone</label>
                    <div class="fw-bold">${user.phone || '-'}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="text-secondary small">Status</label>
                    <div><span class="badge badge-${user.is_active ? 'success' : 'danger'}">
                        ${user.is_active ? 'Aktif' : 'Nonaktif'}
                    </span></div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="text-secondary small">Member Since</label>
                    <div class="fw-bold">${formatDate(user.created_at)}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="text-secondary small">Email Verified</label>
                    <div class="fw-bold">${user.email_verified_at ? formatDate(user.email_verified_at) : 'Not verified'}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="text-secondary small">Last Updated</label>
                    <div class="fw-bold">${formatDate(user.updated_at)}</div>
                </div>
            </div>
        `;

        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    } catch (error) {
        console.error('Error viewing user:', error);
        showAlert('Gagal memuat detail user', 'error');
    }
}

function editUser(id) {
    window.location.href = `/admin/users/${id}/edit`;
}

async function toggleUserStatus(id) {
    showConfirm(
        'Ubah Status User',
        'Apakah Anda yakin ingin mengubah status user ini?',
        async () => {
            try {
                await apiRequest(`/users/${id}/toggle-status`, { method: 'POST' });
                showAlert('Status user berhasil diubah', 'success');
                loadUsers(currentUsersPage);
            } catch (error) {
                console.error('Error toggling user status:', error);
                showAlert('Gagal mengubah status user', 'error');
            }
        }
    );
}

function deleteUser(id) {
    showConfirm(
        'Hapus User',
        'Apakah Anda yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.',
        async () => {
            try {
                await apiRequest(`/users/${id}`, { method: 'DELETE' });
                showAlert('User berhasil dihapus', 'success');
                loadUsers(currentUsersPage);
            } catch (error) {
                console.error('Error deleting user:', error);
                showAlert('Gagal menghapus user', 'error');
            }
        }
    );
}

function renderPagination(data, callbackName) {
    const pagination = document.getElementById('pagination');
    const { current_page, last_page, links } = data;

    let html = '';

    if (links) {
        links.forEach(link => {
            if (link.url) {
                const page = new URL(link.url).searchParams.get('page');
                const active = link.active ? 'active' : '';
                const label = link.label.includes('Previous') ? 'Previous' :
                             link.label.includes('Next') ? 'Next' : page;

                html += `
                    <li class="page-item ${active}">
                        <a class="page-link" href="#" data-page="${page}"
                           onclick="${callbackName}(${page}); return false;">${label}</a>
                    </li>
                `;
            }
        });
    }

    pagination.innerHTML = html;
}

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

// Event listeners
document.addEventListener('DOMContentLoaded', () => {
    loadUsers();

    document.getElementById('searchUser').addEventListener('input', debounce(() => loadUsers(1), 500));
    document.getElementById('filterRole').addEventListener('change', () => loadUsers(1));
    document.getElementById('filterStatus').addEventListener('change', () => loadUsers(1));
});
</script>
@endpush
