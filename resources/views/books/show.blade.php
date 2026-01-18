@extends('layouts.admin')

@section('title', 'Book Details')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-book-open text-primary"></i> Book Details
            </h1>
            <p class="mb-0 text-muted">View complete information about this book</p>
        </div>
        <div>
            <a href="{{route('books.edit', $book->id)}}" class="btn btn-warning shadow-sm">
                <i class="fas fa-edit fa-sm text-white-50"></i> Edit Book
            </a>
            <a href="{{route('books.index')}}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Books
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Book Cover -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    @if($book->cover_image)
                        <img src="{{$book->cover_image}}" alt="{{$book->title}}" class="img-fluid mb-3" style="max-height: 400px;">
                    @else
                        <div class="bg-gradient-primary rounded d-flex align-items-center justify-content-center mb-3" style="height: 400px;">
                            <i class="fas fa-book fa-5x text-white-50"></i>
                        </div>
                    @endif
                    <h5 class="card-title">{{$book->title}}</h5>
                    <p class="text-muted">by {{$book->author}}</p>
                    <h3 class="text-primary font-weight-bold">${{$book->price}}</h3>
                    <p class="text-muted small">ISBN: <code>{{$book->isbn}}</code></p>
                </div>
            </div>
        </div>

        <!-- Book Information -->
        <div class="col-lg-8">
            <!-- Basic Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-gradient-primary">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-info-circle"></i> Basic Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-700">Title:</label>
                            <p class="mb-0">{{$book->title}}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-700">Author:</label>
                            <p class="mb-0">{{$book->author}}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-700">ISBN:</label>
                            <p class="mb-0"><code>{{$book->isbn}}</code></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-700">Published Year:</label>
                            <p class="mb-0">{{$book->published_year}}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-700">Genre:</label>
                            <p class="mb-0">
                                <span class="badge badge-info">{{$book->genre}}</span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-700">Stock Status:</label>
                            <p class="mb-0">
                                @if($book->stock > 10)
                                    <span class="badge badge-success">In Stock ({{$book->stock}})</span>
                                @elseif($book->stock > 0)
                                    <span class="badge badge-warning">Low Stock ({{$book->stock}})</span>
                                @else
                                    <span class="badge badge-danger">Out of Stock</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing & Inventory Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-gradient-success">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-dollar-sign"></i> Pricing & Inventory
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-700">Price:</label>
                            <p class="mb-0 h4 text-success">${{$book->price}}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-700">Stock Quantity:</label>
                            <p class="mb-0 h4 {{$book->stock > 0 ? 'text-success' : 'text-danger'}}">{{$book->stock}} units</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-700">Total Value:</label>
                            <p class="mb-0 h4 text-primary">${{$book->price * $book->stock}}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-700">Reorder Status:</label>
                            <p class="mb-0">
                                @if($book->stock < 5)
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Reorder Needed</span>
                                @else
                                    <span class="text-success"><i class="fas fa-check-circle"></i> Stock OK</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description Card -->
            @if($book->description)
                <div class="card shadow mb-4">
                    <div class="card-header py-3 bg-gradient-info">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-align-left"></i> Description
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{$book->description}}</p>
                    </div>
                </div>
            @endif

            <!-- Metadata Card -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clock"></i> Metadata
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-700">Created At:</label>
                            <p class="mb-0 text-muted">{{$book->created_at->format('F d, Y - H:i')}}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-700">Updated At:</label>
                            <p class="mb-0 text-muted">{{$book->updated_at->format('F d, Y - H:i')}}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="font-weight-bold text-gray-700">Book ID:</label>
                            <p class="mb-0 text-muted">#{{$book->id}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between mt-4">
        <a href="{{route('books.index')}}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Books
        </a>
        <div>
            <form action="{{route('books.destroy', $book->id)}}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this book?')">
                    <i class="fas fa-trash"></i> Delete Book
                </button>
            </form>
            <a href="{{route('books.edit', $book->id)}}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit Book
            </a>
        </div>
    </div>
@endsection
