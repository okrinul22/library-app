@extends('admin.layout')

@section('title', 'View Content - Admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Content Details</h1>
        <div>
            <a href="{{ route('admin.contents.index') }}" class="btn btn-sm btn-info shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Contents
            </a>
            @if(isset($id))
            <a href="{{ route('admin.contents.edit', $id) }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit
            </a>
            @endif
        </div>
    </div>

    <!-- Content Details Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Content Information</h6>
        </div>
        <div class="card-body">
            <div id="contentDetails">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-secondary">Loading content details...</p>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- File Preview Modal -->
<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filePreviewModalLabel">File Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="filePreviewContent">
                <iframe id="filePreviewFrame" style="width: 100%; height: 70vh; border: none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const contentId = '{{ $id ?? '' }}';
const editContentUrl = '/admin/contents/';
const showContentUrl = '/admin/contents/';
let contentFilePath = '';

async function loadContentDetails() {
    try {
        const response = await apiRequest(`/contents/${contentId}`);
        const content = response.data;
        contentFilePath = content.file_path || '';

        const detailsHtml = `
            <div class="row mb-4">
                <div class="col-md-8">
                    <h4>${content.title}</h4>
                    ${content.chapter ? `<p class="text-secondary">${content.chapter}</p>` : ''}
                </div>
                <div class="col-md-4 text-end">
                    <span class="badge badge-${content.status === 'approved' ? 'success' : content.status === 'pending' ? 'warning' : 'danger'}" style="font-size: 14px;">
                        ${content.status.toUpperCase()}
                    </span>
                </div>
            </div>

            ${content.book ? `
                <div class="alert alert-info">
                    <strong>Linked Book:</strong> ${content.book.title} by ${content.book.author}
                </div>
            ` : ''}

            <div class="mb-3">
                <label class="text-secondary small">Content</label>
                <div class="bg-dark p-4 rounded" style="line-height: 1.8; white-space: pre-wrap; max-height: 600px; overflow-y: auto;">${content.content || 'No content'}</div>
            </div>

            ${content.file_path ? `
                <div class="mb-3">
                    <label class="text-secondary small">Attached File</label>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-info" onclick="previewFile()">
                            <i class="fas fa-file-word"></i> View File
                        </button>
                        <a href="/storage/${content.file_path}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-secondary">
                            <i class="fas fa-external-link-alt"></i> Open in New Tab
                        </a>
                    </div>
                </div>
            ` : ''}

            <div class="row mt-4">
                <div class="col-md-6">
                    <small class="text-secondary">Created At</small>
                    <div>${formatDate(content.created_at)}</div>
                </div>
                <div class="col-md-6">
                    <small class="text-secondary">Last Updated</small>
                    <div>${formatDate(content.updated_at)}</div>
                </div>
            </div>

            <div class="mt-4 pt-4 border-top">
                <div class="d-flex gap-2">
                    <a href="${showContentUrl.replace(':id', contentId)}" target="_blank" rel="noopener noreferrer" class="btn btn-info">
                        <i class="fas fa-external-link-alt"></i> Open in New Tab
                    </a>
                    <a href="${editContentUrl.replace(':id', contentId)}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Content
                    </a>
                    ${content.status !== 'approved' ? `
                        <button class="btn btn-success" onclick="changeStatus('approved')">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    ` : `
                        <button class="btn btn-warning" onclick="changeStatus('pending')">
                            <i class="fas fa-thumbs-down"></i> Unapprove
                        </button>
                    `}
                    <button class="btn btn-danger" onclick="deleteContent()">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        `;

        document.getElementById('contentDetails').innerHTML = detailsHtml;
    } catch (error) {
        console.error('Error loading content:', error);
        document.getElementById('contentDetails').innerHTML = `
            <div class="alert alert-danger">
                Failed to load content details. Please try again.
            </div>
        `;
    }
}

async function changeStatus(newStatus) {
    showConfirm(
        'Change Status',
        `Are you sure you want to change the status to ${newStatus.toUpperCase()}?`,
        async () => {
            try {
                await apiRequest(`/contents/${contentId}`, {
                    method: 'PUT',
                    body: JSON.stringify({ status: newStatus })
                });
                showAlert('Status updated successfully!', 'success');
                loadContentDetails();
            } catch (error) {
                console.error('Error updating status:', error);
                showAlert('Failed to update status', 'error');
            }
        }
    );
}

function deleteContent() {
    showConfirm(
        'Delete Content',
        'Are you sure you want to delete this content? This action cannot be undone.',
        async () => {
            try {
                await apiRequest(`/contents/${contentId}`, { method: 'DELETE' });
                showAlert('Content deleted successfully!', 'success');
                setTimeout(() => {
                    window.location.href = '/admin/contents';
                }, 1000);
            } catch (error) {
                console.error('Error deleting content:', error);
                showAlert('Failed to delete content', 'error');
            }
        }
    );
}

function previewFile() {
    if (!contentFilePath) {
        showAlert('No file attached to this content', 'error');
        return;
    }

    const modal = document.getElementById('filePreviewModal');
    const iframe = document.getElementById('filePreviewFrame');
    iframe.src = '/storage/' + contentFilePath;

    if (typeof bootstrap !== 'undefined') {
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    } else if (typeof $ !== 'undefined' && $.fn.modal) {
        $(modal).modal('show');
    } else {
        modal.style.display = 'block';
        modal.classList.add('show');
    }
}

document.addEventListener('DOMContentLoaded', loadContentDetails);
</script>
@endpush
