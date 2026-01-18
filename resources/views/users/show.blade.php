@extends('layouts.admin')

@section('title', 'View User')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user text-primary"></i> User Details
        </h1>
        <div>
            <a href="{{route('users.edit', $user->id)}}" class="btn btn-warning btn-sm shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit
            </a>
            <a href="{{route('users.index')}}" class="btn btn-secondary btn-sm shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x text-gray-300"></i>
                    </div>
                    <h4 class="mb-1">{{$user->name}}</h4>
                    <p class="text-muted mb-0">{{$user->email}}</p>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-700">ID:</label>
                            <p class="mb-0">{{$user->id}}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-700">Name:</label>
                            <p class="mb-0">{{$user->name}}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-700">Email:</label>
                            <p class="mb-0">{{$user->email}}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-700">Created At:</label>
                            <p class="mb-0">{{$user->created_at->format('F d, Y - H:i')}}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-700">Updated At:</label>
                            <p class="mb-0">{{$user->updated_at->format('F d, Y - H:i')}}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-700">Email Verified:</label>
                            <p class="mb-0">
                                @if($user->email_verified_at)
                                    <span class="badge badge-success">Yes</span>
                                @else
                                    <span class="badge badge-warning">No</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
