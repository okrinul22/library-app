@extends('admin.layout')

@section('title', 'Edit Content - Admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Content</h1>
        <a href="{{ route('admin.contents.index') }}" class="btn btn-sm btn-info shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Contents
        </a>
    </div>

    <!-- Edit Content Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">Content Information</h6>
        </div>
        <div class="card-body">
            <form id="editContentForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="contentId" name="id" value="{{ $id ?? '' }}">

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="chapter" class="form-label">Chapter</label>
                        <input type="text" class="form-control" id="chapter" name="chapter" placeholder="e.g., Chapter 1">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="book_id" class="form-label">Link to Book (Optional)</label>
                    <select class="form-select" id="book_id" name="book_id">
                        <option value="">Select Book (Optional)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="content" name="content" rows="15" required></textarea>
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
                        <span class="btn-text">Update Content</span>
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
const contentId = '{{ $id ?? '' }}';

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

async function loadContentData() {
    try {
        const response = await apiRequest(`/contents/${contentId}`);
        const content = response.data;

        document.getElementById('title').value = content.title || '';
        document.getElementById('chapter').value = content.chapter || '';
        document.getElementById('book_id').value = content.book_id || '';
        document.getElementById('content').value = content.content || '';
        document.getElementById('status').value = content.status || 'pending';
    } catch (error) {
        console.error('Error loading content:', error);
        showAlert('Failed to load content data', 'error');
    }
}

document.getElementById('editContentForm').addEventListener('submit', async (e) => {
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
    if (!data.title) {
        showAlert('Please enter a title', 'error');
        return;
    }

    if (!data.content) {
        showAlert('Please enter content', 'error');
        return;
    }

    // Show loading
    submitBtn.disabled = true;
    btnText.classList.add('d-none');
    spinner.classList.remove('d-none');

    try {
        const response = await apiRequest(`/contents/${contentId}`, {
            method: 'PUT',
            body: JSON.stringify(data)
        });

        if (response.success || response.data) {
            showAlert('Content updated successfully!', 'success');
            setTimeout(() => {
                window.location.href = '{{ route('admin.contents.index') }}';
            }, 1000);
        } else {
            showAlert(response.message || 'Failed to update content', 'error');
            submitBtn.disabled = false;
            btnText.classList.remove('d-none');
            spinner.classList.add('d-none');
        }
    } catch (error) {
        console.error('Error updating content:', error);
        showAlert('Failed to update content. Please try again.', 'error');
        submitBtn.disabled = false;
        btnText.classList.remove('d-none');
        spinner.classList.add('d-none');
    }
});

document.addEventListener('DOMContentLoaded', () => {
    loadBooks();
    loadContentData();
});
</script>
@endpush
