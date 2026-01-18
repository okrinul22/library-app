@extends('admin.layout')

@section('title', 'Contents - Admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Contents Management</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.contents.create') }}" class="btn btn-sm btn-success shadow-sm">
                <i class="fas fa-upload fa-sm text-white-50"></i> Upload Content
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Filter</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label for="filterStatus" class="form-label text-secondary">Status</label>
                    <select class="form-select" id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterSource" class="form-label text-secondary">Source</label>
                    <select class="form-select" id="filterSource">
                        <option value="">Semua Source</option>
                        <option value="admin">Admin</option>
                        <option value="writer">Writer</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button class="btn btn-secondary" onclick="loadContents(1)">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Contents Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Daftar Konten</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Book</th>
                            <th>Chapter</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="contentsTableBody">
                        <tr>
                            <td colspan="7" class="text-center text-secondary">Loading...</td>
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

<!-- Content Preview Modal -->
<div class="modal fade" id="contentPreviewModal" tabindex="-1" aria-labelledby="contentPreviewModalLabel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contentPreviewModalLabel">Preview Konten</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contentPreviewContent"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentContentsPage = 1;

async function loadContents(page = 1) {
    currentContentsPage = page;

    const status = document.getElementById('filterStatus').value;
    const source = document.getElementById('filterSource').value;

    let url = `/contents?page=${page}&per_page=10`;
    if (status) url += `&status=${status}`;
    if (source) url += `&source=${source}`;

    try {
        const response = await apiRequest(url);
        const contents = response.data;
        renderContentsTable(contents.data);
        renderPagination(contents, 'loadContents');
    } catch (error) {
        console.error('Error loading contents:', error);
        document.getElementById('contentsTableBody').innerHTML =
            '<tr><td colspan="6" class="text-center text-secondary">Error loading contents</td></tr>';
    }
}

function renderContentsTable(contents) {
    const tbody = document.getElementById('contentsTableBody');

    if (contents.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-secondary">Tidak ada konten</td></tr>';
        return;
    }

    tbody.innerHTML = contents.map(content => `
        <tr>
            <td>
                <div class="fw-bold">${content.title}</div>
                ${content.file_path ? `<small class="text-secondary">File: ${content.file_path.split('/').pop()}</small>` : ''}
            </td>
            <td>${content.book?.title || '-'}</td>
            <td>${content.chapter || '-'}</td>
            <td>
                <span class="badge badge-${content.source === 'admin' ? 'danger' : 'primary'}">
                    ${content.source ? content.source.toUpperCase() : 'SYSTEM'}
                </span>
            </td>
            <td>
                <span class="badge badge-${content.status === 'approved' ? 'success' : content.status === 'pending' ? 'warning' : 'danger'}">
                    ${content.status.toUpperCase()}
                </span>
            </td>
            <td>${formatDate(content.created_at)}</td>
            <td>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" class="btn btn-info" onclick="previewContent(${content.id})" title="Preview">
                        <i class="fas fa-eye"></i>
                    </button>
                    ${content.book ? `<button type="button" class="btn btn-primary" onclick="viewNovelStructure(${content.book.id}, '${content.book.title.replace(/'/g, "\\'")}')" title="View Novel Structure">
                        <i class="fas fa-book"></i>
                    </button>` : ''}
                    <button type="button" class="btn btn-warning" onclick="editContent(${content.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn ${content.status === 'approved' ? 'btn-warning' : 'btn-success'}"
                            onclick="changeStatus(${content.id}, '${content.status === 'approved' ? 'pending' : 'approved'}')"
                            title="${content.status === 'approved' ? 'Unapprove' : 'Approve'}">
                        <i class="fas ${content.status === 'approved' ? 'fa-thumbs-down' : 'fa-thumbs-up'}"></i>
                    </button>
                    <button type="button" class="btn btn-danger" onclick="deleteContent(${content.id})" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

async function previewContent(id) {
    try {
        const response = await apiRequest(`/contents/${id}/preview`);
        const content = response.data;

        const modal = document.getElementById('contentPreviewModal');
        const modalContent = document.getElementById('contentPreviewContent');

        modalContent.innerHTML = `
            <div class="mb-3">
                <h4>${content.title}</h4>
                <p class="text-secondary">Chapter ${content.chapter || '-'}</p>
                ${content.book ? `<p class="text-secondary small">Book: ${content.book.title}</p>` : ''}
            </div>
            <div class="bg-dark p-4 rounded" style="line-height: 1.8; white-space: pre-wrap;">${content.content}</div>
        `;

        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    } catch (error) {
        console.error('Error previewing content:', error);
        showAlert('Gagal memuat preview konten', 'error');
    }
}

function editContent(id) {
    window.location.href = `/admin/contents/${id}/edit`;
}

async function changeStatus(id, status) {
    showConfirm(
        'Ubah Status Konten',
        `Apakah Anda yakin ingin mengubah status menjadi ${status.toUpperCase()}?`,
        async () => {
            try {
                await apiRequest(`/contents/${id}`, {
                    method: 'PUT',
                    body: JSON.stringify({ status })
                });
                showAlert('Status konten berhasil diubah', 'success');
                loadContents(currentContentsPage);
            } catch (error) {
                console.error('Error changing content status:', error);
                showAlert('Gagal mengubah status konten', 'error');
            }
        }
    );
}

function deleteContent(id) {
    showConfirm(
        'Hapus Konten',
        'Apakah Anda yakin ingin menghapus konten ini? Tindakan ini tidak dapat dibatalkan.',
        async () => {
            try {
                await apiRequest(`/contents/${id}`, { method: 'DELETE' });
                showAlert('Konten berhasil dihapus', 'success');
                loadContents(currentContentsPage);
            } catch (error) {
                console.error('Error deleting content:', error);
                showAlert('Gagal menghapus konten', 'error');
            }
        }
    );
}

function viewNovelStructure(bookId, bookTitle) {
    // Open novel structure view in new tab
    const url = `/admin/books/${bookId}/novel-structure`;
    window.open(url, '_blank');
}

function renderPagination(data, callbackName) {
    const pagination = document.getElementById('pagination');
    const { links } = data;

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

document.addEventListener('DOMContentLoaded', () => {
    loadContents();
});
</script>
@endpush
