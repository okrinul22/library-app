@extends('admin.layout')

@section('title', 'Edit User - Admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit User</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-info shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Users
        </a>
    </div>

    <!-- Edit User Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">User Information</h6>
        </div>
        <div class="card-body">
            <form id="editUserForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="userId" name="id" value="{{ $id ?? '' }}">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" minlength="6">
                        <small class="text-secondary">Leave blank to keep current password</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" minlength="6">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="member">Member</option>
                            <option value="writer">Writer</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="+62xxx">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="is_active" class="form-label">Status</label>
                        <select class="form-select" id="is_active" name="is_active">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-info">Cancel</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <span class="btn-text">Update User</span>
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
const userId = '{{ $id ?? '' }}';

// Load user data
async function loadUserData() {
    try {
        const response = await apiRequest(`/users/${userId}`);
        const user = response.data;

        document.getElementById('name').value = user.name || '';
        document.getElementById('email').value = user.email || '';
        document.getElementById('role').value = user.role || 'member';
        document.getElementById('phone').value = user.phone || '';
        document.getElementById('is_active').value = user.is_active ? '1' : '0';
    } catch (error) {
        console.error('Error loading user:', error);
        showAlert('Failed to load user data', 'error');
    }
}

document.getElementById('editUserForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const submitBtn = document.getElementById('submitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const spinner = submitBtn.querySelector('.spinner-border');

    // Get form data
    const formData = new FormData(e.target);
    const data = {
        name: formData.get('name'),
        email: formData.get('email'),
        role: formData.get('role'),
        phone: formData.get('phone'),
        is_active: formData.get('is_active') === '1'
    };

    // Only include password if it's provided
    const password = formData.get('password');
    if (password) {
        data.password = password;
        data.password_confirmation = formData.get('password_confirmation');
    }

    // Validate
    if (!data.name || !data.email || !data.role) {
        showAlert('Please fill all required fields', 'error');
        return;
    }

    if (password && password !== formData.get('password_confirmation')) {
        showAlert('Passwords do not match', 'error');
        return;
    }

    // Show loading
    submitBtn.disabled = true;
    btnText.classList.add('d-none');
    spinner.classList.remove('d-none');

    try {
        const response = await apiRequest(`/users/${userId}`, {
            method: 'PUT',
            body: JSON.stringify(data)
        });

        if (response.success || response.data) {
            showAlert('User updated successfully!', 'success');
            setTimeout(() => {
                window.location.href = '{{ route('admin.users.index') }}';
            }, 1000);
        } else {
            showAlert(response.message || 'Failed to update user', 'error');
            submitBtn.disabled = false;
            btnText.classList.remove('d-none');
            spinner.classList.add('d-none');
        }
    } catch (error) {
        console.error('Error updating user:', error);
        showAlert('Failed to update user. Please try again.', 'error');
        submitBtn.disabled = false;
        btnText.classList.remove('d-none');
        spinner.classList.add('d-none');
    }
});

// Load user data on page load
document.addEventListener('DOMContentLoaded', loadUserData);
</script>
@endpush
