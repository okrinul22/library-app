@extends('admin.layout')

@section('title', 'Book Details - Admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Book Details</h1>
        <div>
            <a href="{{ route('admin.books.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Books
            </a>
            <a href="{{ route('admin.books.edit', $id) }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Book
            </a>
        </div>
    </div>

    <!-- Book Detail Card -->
    <div class="card shadow mb-4">
        <div class="card-body" id="bookDetailContainer">
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-3 text-white">Loading book details...</p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const bookId = {{ $id }};

async function loadBookDetail() {
    try {
        console.log('Fetching book detail for bookId:', bookId);
        const result = await apiRequest(`/books/${bookId}`);
        console.log('API Response:', result);

        if (!result.success) {
            throw new Error(result.message || 'Failed to load book details');
        }

        const book = result.data;

        if (!book) {
            throw new Error('Book data not found in response');
        }

        // Display book info
        const bookDetailHtml = `
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    ${book.cover_image
                        ? `<img src="/storage/${book.cover_image}" alt="${book.title}" class="img-fluid rounded" style="max-height: 350px;">`
                        : `<div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="aspect-ratio: 2/3; height: 350px;">
                            <i class="fas fa-book fa-4x text-muted"></i>
                           </div>`
                    }
                </div>
                <div class="col-md-9">
                    <h2 class="text-white">${book.title}</h2>
                    <p class="text-white mb-3">by ${book.author}</p>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2 text-white"><strong>ISBN:</strong> ${book.isbn || 'N/A'}</p>
                            <p class="mb-2 text-white"><strong>Genre:</strong> <span class="badge badge-secondary">${book.genre || 'N/A'}</span></p>
                            <p class="mb-2 text-white"><strong>Price:</strong> ${book.is_free ? '<span class="badge badge-success">FREE</span>' : formatCurrency(book.price)}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2 text-white"><strong>Stock:</strong> <span class="badge ${book.stock > 5 ? 'badge-success' : book.stock > 0 ? 'badge-warning' : 'badge-danger'}">${book.stock} available</span></p>
                            <p class="mb-2 text-white"><strong>Published:</strong> ${book.published_year || 'N/A'}</p>
                            <p class="mb-2 text-white"><strong>Status:</strong> <span class="badge badge-info">${book.is_free ? 'Free Book' : 'Premium Book'}</span></p>
                        </div>
                    </div>
                    ${book.description ? `<p class="mt-3 text-white"><strong>Description:</strong> ${book.description}</p>` : ''}
                    <div class="mt-4">
                        <a href="/admin/books/${book.id}/edit" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Edit Book
                        </a>
                        <a href="/admin/books/${book.id}/novel-structure" class="btn btn-sm btn-info">
                            <i class="fas fa-list"></i> Novel Structure
                        </a>
                        <a href="/admin/contents/create?book_id=${book.id}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus"></i> Add Chapter
                        </a>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('bookDetailContainer').innerHTML = bookDetailHtml;
    } catch (error) {
        console.error('Error loading book detail:', error);
        document.getElementById('bookDetailContainer').innerHTML = `
            <div class="alert alert-danger">
                <p class="mb-0 text-white">Failed to load book details. Please try again.</p>
                <p class="mb-0 text-white small">${error.message}</p>
            </div>
        `;
    }
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

document.addEventListener('DOMContentLoaded', loadBookDetail);
</script>
@endpush
