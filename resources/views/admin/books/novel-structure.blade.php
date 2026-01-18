@extends('admin.layout')

@section('title', 'Novel Structure - Admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Novel Structure</h1>
        <div>
            <button class="btn btn-sm btn-secondary shadow-sm" onclick="window.close()">
                <i class="fas fa-times fa-sm text-white-50"></i> Close
            </button>
        </div>
    </div>

    <!-- Book Info Card -->
    <div class="card shadow mb-4" id="bookInfoCard">
        <div class="card-body text-center">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-3 text-secondary">Loading novel structure...</p>
        </div>
    </div>

    <!-- Chapters List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Chapters Overview</h6>
        </div>
        <div class="card-body">
            <div id="chaptersContainer">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-3 text-secondary">Loading chapters...</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const bookId = window.location.pathname.split('/').pop();

async function loadNovelStructure() {
    try {
        const response = await apiRequest(`/books/${bookId}`);
        const book = response.data;

        // Display book info
        const bookInfoHtml = `
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    ${book.cover_image
                        ? `<img src="/storage/${book.cover_image}" alt="${book.title}" class="img-fluid rounded" style="max-height: 250px;">`
                        : `<div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="aspect-ratio: 2/3; height: 250px;">
                            <i class="fas fa-book fa-4x text-muted"></i>
                           </div>`
                    }
                </div>
                <div class="col-md-9">
                    <h3>${book.title}</h3>
                    <p class="text-secondary mb-2">by ${book.author}</p>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>ISBN:</strong> ${book.isbn || 'N/A'}</p>
                            <p class="mb-1"><strong>Genre:</strong> <span class="badge badge-secondary">${book.genre || 'N/A'}</span></p>
                            <p class="mb-1"><strong>Price:</strong> ${book.is_free ? '<span class="badge badge-success">FREE</span>' : formatCurrency(book.price)}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Stock:</strong> <span class="badge ${book.stock > 5 ? 'badge-success' : book.stock > 0 ? 'badge-warning' : 'badge-danger'}">${book.stock} available</span></p>
                            <p class="mb-1"><strong>Published:</strong> ${book.published_year || 'N/A'}</p>
                            <p class="mb-1"><strong>Status:</strong> <span class="badge badge-info">${book.is_free ? 'Free Book' : 'Premium Book'}</span></p>
                        </div>
                    </div>
                    ${book.description ? `<p class="mt-3"><strong>Description:</strong> ${book.description}</p>` : ''}
                    <div class="mt-3">
                        <a href="/admin/books/${book.id}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Edit Book
                        </a>
                        <a href="/admin/contents/create?book_id=${book.id}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus"></i> Add Chapter
                        </a>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('bookInfoCard').innerHTML = `<div class="card-body">${bookInfoHtml}</div>`;

        // Load chapters
        await loadChapters(book.id);
    } catch (error) {
        console.error('Error loading novel structure:', error);
        document.getElementById('bookInfoCard').innerHTML = `
            <div class="card-body">
                <div class="alert alert-danger">
                    Failed to load novel structure. Please try again.
                </div>
            </div>
        `;
    }
}

async function loadChapters(bookId) {
    try {
        const response = await apiRequest(`/books/${bookId}/contents`);
        const contents = response.data || [];

        const chaptersContainer = document.getElementById('chaptersContainer');

        if (contents.length === 0) {
            chaptersContainer.innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-3x text-secondary mb-3"></i>
                    <p class="text-secondary">No chapters uploaded yet for this novel.</p>
                    <a href="/admin/contents/create?book_id=${bookId}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Upload First Chapter
                    </a>
                </div>
            `;
            return;
        }

        // Sort by chapter number/title
        const sortedContents = contents.sort((a, b) => {
            const aChapter = parseInt(a.chapter) || 0;
            const bChapter = parseInt(b.chapter) || 0;
            if (aChapter !== bChapter) return aChapter - bChapter;
            return new Date(a.created_at) - new Date(b.created_at);
        });

        chaptersContainer.innerHTML = `
            <div class="row">
                ${sortedContents.map((content, index) => `
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm ${content.status === 'approved' ? 'border-success' : content.status === 'pending' ? 'border-warning' : 'border-danger'}"
                             style="border-width: 2px;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="card-title mb-1">${content.title}</h6>
                                        ${content.chapter ? `<small class="text-secondary">Chapter ${content.chapter}</small>` : ''}
                                    </div>
                                    <span class="badge badge-${content.status === 'approved' ? 'success' : content.status === 'pending' ? 'warning' : 'danger'}">
                                        ${content.status}
                                    </span>
                                </div>
                                <p class="text-secondary small mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    ${content.content ? content.content.substring(0, 100) + '...' : 'No preview available'}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-secondary">
                                        <i class="fas fa-calendar"></i> ${formatDate(content.created_at)}
                                    </small>
                                    <div class="btn-group btn-group-sm">
                                        <a href="/admin/contents/${content.id}" class="btn btn-info" title="View" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/admin/contents/${content.id}/edit" class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    } catch (error) {
        console.error('Error loading chapters:', error);
        document.getElementById('chaptersContainer').innerHTML = `
            <div class="alert alert-danger">
                Failed to load chapters. Please try again.
            </div>
        `;
    }
}

function formatDate(date) {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    }).format(new Date(date));
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

document.addEventListener('DOMContentLoaded', loadNovelStructure);
</script>
@endpush
