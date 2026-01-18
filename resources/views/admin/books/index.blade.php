@extends('admin.layout')

@section('title', 'Books - Admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Books Management</h1>
        <a href="{{ route('admin.books.create') }}" class="btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Buku
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
                        <label for="searchBook" class="form-label text-secondary">Search</label>
                        <input type="text" class="form-control" id="searchBook" placeholder="Cari judul, penulis, ISBN...">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="filterGenre" class="form-label text-secondary">Genre</label>
                        <select class="form-select" id="filterGenre">
                            <option value="">Semua Genre</option>
                            <option value="Fiction">Fiction</option>
                            <option value="Non-Fiction">Non-Fiction</option>
                            <option value="Science">Science</option>
                            <option value="History">History</option>
                            <option value="Biography">Biography</option>
                            <option value="Technology">Technology</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="filterStock" class="form-label text-secondary">Stock Status</label>
                        <select class="form-select" id="filterStock">
                            <option value="">Semua Stok</option>
                            <option value="in_stock">In Stock</option>
                            <option value="out_of_stock">Out of Stock</option>
                            <option value="low_stock">Low Stock</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="filterFree" class="form-label text-secondary">Price Type</label>
                        <select class="form-select" id="filterFree">
                            <option value="">Semua Tipe</option>
                            <option value="1">Gratis</option>
                            <option value="0">Berbayar</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-secondary w-100" onclick="loadBooks(1)">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Books Grid -->
    <div class="row" id="booksGrid">
        <div class="col-12 text-center text-secondary py-5">Loading...</div>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center" id="pagination"></ul>
    </nav>

</div>

<!-- Book Detail Modal -->
<div class="modal fade" id="bookModal" tabindex="-1" aria-labelledby="bookModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookModalLabel">Detail Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="bookModalContent"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentBooksPage = 1;

async function loadBooks(page = 1) {
    currentBooksPage = page;
    const search = document.getElementById('searchBook').value;
    const genre = document.getElementById('filterGenre').value;
    const stock = document.getElementById('filterStock').value;
    const isFree = document.getElementById('filterFree').value;

    let url = `/books?page=${page}&per_page=12`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (genre) url += `&genre=${encodeURIComponent(genre)}`;
    if (stock) url += `&stock_status=${stock}`;
    if (isFree) url += `&is_free=${isFree}`;

    try {
        const response = await apiRequest(url);
        const books = response.data;
        renderBooksGrid(books.data);
        renderPagination(books, 'loadBooks');
    } catch (error) {
        console.error('Error loading books:', error);
        document.getElementById('booksGrid').innerHTML =
            '<div class="col-12 text-center text-secondary py-5">Error loading books</div>';
    }
}

function renderBooksGrid(books) {
    const grid = document.getElementById('booksGrid');

    if (books.length === 0) {
        grid.innerHTML = '<div class="col-12 text-center text-secondary py-5">Tidak ada buku ditemukan</div>';
        return;
    }

    grid.innerHTML = books.map(book => `
        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow">
                <div class="position-relative" style="height: 250px; overflow: hidden;">
                    ${book.cover_image
                        ? `<img src="/storage/${book.cover_image}" alt="${book.title}" class="card-img-top" style="width: 100%; height: 100%; object-fit: cover;">`
                        : `<div class="card-img-top d-flex align-items-center justify-content-center bg-secondary">
                            <i class="fas fa-book fa-4x text-muted"></i>
                           </div>`
                    }
                    ${book.is_free
                        ? '<span class="position-absolute top-0 end-0 badge badge-success m-2">GRATIS</span>'
                        : `<span class="position-absolute top-0 end-0 badge badge-info m-2">${formatCurrency(book.price)}</span>`
                    }
                    ${book.stock <= 0
                        ? '<span class="position-absolute top-0 start-0 badge badge-danger m-2">OUT OF STOCK</span>'
                        : book.stock <= 5
                            ? '<span class="position-absolute top-0 start-0 badge badge-warning m-2">LOW STOCK</span>'
                            : ''
                    }
                </div>
                <div class="card-body">
                    <h5 class="card-title text-truncate" title="${book.title}">${book.title}</h5>
                    <p class="card-text text-secondary mb-2"><small>${book.author}</small></p>
                    <p class="card-text"><span class="badge badge-secondary">${book.genre}</span></p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <small class="text-secondary">Stok: ${book.stock}</small>
                        <span class="badge badge-${book.stock > 5 ? 'success' : book.stock > 0 ? 'warning' : 'danger'}">
                            ${book.stock > 5 ? 'Tersedia' : book.stock > 0 ? 'Terbatas' : 'Habis'}
                        </span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0">
                    <div class="btn-group w-100" role="group">
                        <button type="button" class="btn btn-sm btn-info" onclick="viewBook(${book.id})">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                        <button type="button" class="btn btn-sm btn-warning" onclick="editBook(${book.id})">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteBook(${book.id})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

async function viewBook(id) {
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

        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    } catch (error) {
        console.error('Error viewing book:', error);
        showAlert('Gagal memuat detail buku', 'error');
    }
}

function editBook(id) {
    window.location.href = `/admin/books/${id}/edit`;
}

function deleteBook(id) {
    showConfirm(
        'Hapus Buku',
        'Apakah Anda yakin ingin menghapus buku ini? Tindakan ini tidak dapat dibatalkan.',
        async () => {
            try {
                await apiRequest(`/books/${id}`, { method: 'DELETE' });
                showAlert('Buku berhasil dihapus', 'success');
                loadBooks(currentBooksPage);
            } catch (error) {
                console.error('Error deleting book:', error);
                showAlert('Gagal menghapus buku', 'error');
            }
        }
    );
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
                const label = link.label.includes('Previous') ? '&laquo; Previous' :
                             link.label.includes('Next') ? 'Next &raquo;' : page;

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
    loadBooks();

    document.getElementById('searchBook').addEventListener('input', debounce(() => loadBooks(1), 500));
    document.getElementById('filterGenre').addEventListener('change', () => loadBooks(1));
    document.getElementById('filterStock').addEventListener('change', () => loadBooks(1));
    document.getElementById('filterFree').addEventListener('change', () => loadBooks(1));
});
</script>
@endpush
