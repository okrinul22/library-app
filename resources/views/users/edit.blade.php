@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-edit text-primary"></i> Edit User
        </h1>
        <a href="{{route('users.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Users
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit User Information</h6>
        </div>
        <div class="card-body">
            <form action="{{route('users.update', $user)}}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">
                            <i class="fas fa-user text-gray-400"></i> Name
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{old('name', $user->name)}}" placeholder="Enter full name" required>
                        @error('name')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope text-gray-400"></i> Email
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{old('email', $user->email)}}" placeholder="Enter email address" required>
                        @error('email')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock text-gray-400"></i> Password <small class="text-muted">(leave blank to keep current)</small>
                        </label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" placeholder="Enter new password">
                        @error('password')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock text-gray-400"></i> Confirm Password
                        </label>
                        <input type="password" class="form-control"
                               id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{route('users.index')}}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-sync"></i> Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
