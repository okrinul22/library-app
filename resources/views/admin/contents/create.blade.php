@extends('admin.layout')

@section('title', 'Create Content - Admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Content</h1>
        <a href="{{ route('admin.contents.index') }}" class="btn btn-sm btn-info shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Contents
        </a>
    </div>

    <!-- Create Content Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Content Information</h6>
        </div>
        <div class="card-body">
            <form id="createContentForm">
                @csrf
                <div class="mb-3">
                    <label for="book_id" class="form-label">Book <span class="text-danger">*</span></label>
                    <select class="form-select" id="book_id" name="book_id" required>
                        <option value="">Select a Book</option>
                    </select>
                    <small class="text-secondary">Select the book this content belongs to</small>
                </div>

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="title" class="form-label">Content Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" required placeholder="e.g., Chapter 1 - The Beginning">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="chapter" class="form-label">Chapter Number</label>
                        <input type="number" class="form-control" id="chapter" name="chapter" min="1" placeholder="e.g., 1">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="content" name="content" rows="15" required></textarea>
                    <small class="text-secondary">Write your content here or paste from Word</small>
                </div>

                <div class="mb-3">
                    <label for="word_file" class="form-label">Or Upload Word File</label>
                    <input type="file" class="form-control" id="word_file" name="word_file" accept=".doc,.docx">
                    <small class="text-secondary">Upload a .doc or .docx file instead</small>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.contents.index') }}" class="btn btn-info">Cancel</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="btn-text">Create Content</span>
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
// Load books for dropdown
async function loadBooks() {
    try {
        const response = await apiRequest('/books?per_page=100');
        const books = response.data?.data || [];

        const select = document.getElementById('book_id');
        books.forEach(book => {
            const option = document.createElement('option');
            option.value = book.id;
            option.textContent = `${book.title} - ${book.author}`;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading books:', error);
    }
}

document.getElementById('createContentForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.spinner-border');

    const formData = new FormData(e.target);
    const data = {
        title: formData.get('title'),
        chapter: formData.get('chapter') || null,
        book_id: formData.get('book_id') || null,
        content: formData.get('content') || '',
        status: formData.get('status')
    };

    // Validate
    if (!data.book_id) {
        showAlert('Please select a book', 'error');
        return;
    }

    if (!data.title) {
        showAlert('Please enter a title', 'error');
        return;
    }

    if (!data.content && !document.getElementById('word_file').files[0]) {
        showAlert('Please enter content or upload a file', 'error');
        return;
    }

    // Show loading
    submitBtn.disabled = true;
    btnText.classList.add('d-none');
    spinner.classList.remove('d-none');

    try {
        // First create the content
        const response = await apiRequest('/contents', {
            method: 'POST',
            body: JSON.stringify(data)
        });

        if (response.success || response.data) {
            // If there's a word file, upload it
            const wordFile = document.getElementById('word_file').files[0];
            if (wordFile && response.data.id) {
                const fileFormData = new FormData();
                fileFormData.append('word_file', wordFile);

                await fetch(`${API_URL}/contents/${response.data.id}/upload-word`, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${AUTH_TOKEN}`
                    },
                    body: fileFormData
                });
            }

            showAlert('Content created successfully!', 'success');
            setTimeout(() => {
                window.location.href = '{{ route('admin.contents.index') }}';
            }, 1000);
        } else {
            showAlert(response.message || 'Failed to create content', 'error');
            submitBtn.disabled = false;
            btnText.classList.remove('d-none');
            spinner.classList.add('d-none');
        }
    } catch (error) {
        console.error('Error creating content:', error);
        showAlert('Failed to create content. Please try again.', 'error');
        submitBtn.disabled = false;
        btnText.classList.remove('d-none');
        spinner.classList.add('d-none');
    }
});

document.addEventListener('DOMContentLoaded', loadBooks);
</script>
@endpush
