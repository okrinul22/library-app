@extends('admin.layout')

@section('title', 'Create Book - Admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Book</h1>
        <a href="{{ route('admin.books.index') }}" class="btn btn-sm btn-info shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Books
        </a>
    </div>

    <!-- Create Book Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Book Information</h6>
        </div>
        <div class="card-body">
            <form id="createBookForm" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="isbn" class="form-label">ISBN <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="isbn" name="isbn" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="author" class="form-label">Author <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="author" name="author" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="genre" class="form-label">Genre <span class="text-danger">*</span></label>
                        <select class="form-select" id="genre" name="genre" required>
                            <option value="">Select Genre</option>
                            <option value="Fiction">Fiction</option>
                            <option value="Non-Fiction">Non-Fiction</option>
                            <option value="Science">Science</option>
                            <option value="History">History</option>
                            <option value="Biography">Biography</option>
                            <option value="Technology">Technology</option>
                            <option value="Romance">Romance</option>
                            <option value="Mystery">Mystery</option>
                            <option value="Fantasy">Fantasy</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="price" class="form-label">Price (IDR) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="price" name="price" required min="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="stock" name="stock" required min="0" value="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="published_year" class="form-label">Published Year <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="published_year" name="published_year" required min="1900" max="2099" value="{{ date('Y') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="is_free" class="form-label">Price Type</label>
                        <select class="form-select" id="is_free" name="is_free">
                            <option value="0">Paid</option>
                            <option value="1">Free</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4"></textarea>
                </div>

                <div class="mb-3">
                    <label for="cover_image" class="form-label">Cover Image</label>
                    <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
                    <small class="text-secondary">Recommended size: 300x450px</small>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.books.index') }}" class="btn btn-info">Cancel</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="btn-text">Create Book</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.getElementById('is_free').addEventListener('change', function() {
    const priceInput = document.getElementById('price');
    if (this.value === '1') {
        priceInput.value = 0;
        priceInput.disabled = true;
    } else {
        priceInput.disabled = false;
        if (parseInt(priceInput.value) === 0) {
            priceInput.value = '';
        }
    }
});

document.getElementById('createBookForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.spinner-border');

    const isFree = document.getElementById('is_free').value === '1';
    const priceValue = document.getElementById('price').value;

    const formData = new FormData(e.target);
    const data = {
        title: formData.get('title'),
        isbn: formData.get('isbn'),
        author: formData.get('author'),
        genre: formData.get('genre'),
        price: isFree ? 0 : (parseInt(priceValue) || 0),
        stock: parseInt(formData.get('stock')),
        published_year: parseInt(formData.get('published_year')),
        is_free: isFree,
        description: formData.get('description') || ''
    };

    // Validate
    if (!data.title || !data.isbn || !data.author || !data.genre || !data.published_year) {
        showAlert('Please fill all required fields', 'error');
        return;
    }

    if (!isFree && (!data.price || data.price <= 0)) {
        showAlert('Please enter a valid price for paid books', 'error');
        return;
    }

    // Show loading
    submitBtn.disabled = true;
    btnText.classList.add('d-none');
    spinner.classList.remove('d-none');

    try {
        // First create the book
        const response = await apiRequest('/books', {
            method: 'POST',
            body: JSON.stringify(data)
        });

        if (response.success || response.data) {
            // If there's a cover image, upload it
            const coverFile = document.getElementById('cover_image').files[0];
            if (coverFile && response.data.id) {
                try {
                    const coverFormData = new FormData();
                    coverFormData.append('cover', coverFile);

                    const uploadResponse = await fetch(`${API_URL}/books/${response.data.id}/upload-cover`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: coverFormData,
                        credentials: 'same-origin'
                    });

                    if (!uploadResponse.ok) {
                        const errorData = await uploadResponse.json();
                        console.warn('Cover upload warning:', errorData.message);
                        // Continue anyway - book was created successfully
                    }
                } catch (uploadError) {
                    console.warn('Cover upload failed:', uploadError);
                    // Continue anyway - book was created successfully
                }
            }

            showAlert('Book created successfully!', 'success');
            setTimeout(() => {
                window.location.href = '{{ route('admin.books.index') }}';
            }, 1000);
        } else {
            showAlert(response.message || 'Failed to create book', 'error');
            submitBtn.disabled = false;
            btnText.classList.remove('d-none');
            spinner.classList.add('d-none');
        }
    } catch (error) {
        console.error('Error creating book:', error);
        showAlert('Failed to create book. Please try again.', 'error');
        submitBtn.disabled = false;
        btnText.classList.remove('d-none');
        spinner.classList.add('d-none');
    }
});
</script>
@endpush
