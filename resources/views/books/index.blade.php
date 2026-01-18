@extends('layouts.admin')

@section('title', 'Books Management')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-book-reader text-primary"></i> Books Management
            </h1>
            <p class="mb-0 text-muted">Manage your book inventory, track stock, and monitor prices</p>
        </div>
        <a href="{{route('books.create')}}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Book
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{session('success')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Books</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$books->total() ?? 0}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Value</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{$books->sum('price') ?? 0}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Genres</div>
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$books->pluck('genre')->unique()->count() ?? 0}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Low Stock</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$books->where('stock', '<', 5)->count() ?? 0}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Books Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> Books Collection
            </h6>
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">All Books</a>
                    <a class="dropdown-item" href="#">In Stock</a>
                    <a class="dropdown-item" href="#">Out of Stock</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">By Genre</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @forelse($books as $book)
                <div class="row mb-3 pb-3 border-bottom">
                    <div class="col-md-2">
                        <div class="bg-gradient-primary rounded d-flex align-items-center justify-content-center" style="height: 120px;">
                            <i class="fas fa-book fa-3x text-white-50"></i>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h5 class="font-weight-bold text-gray-800">{{$book->title}}</h5>
                        <p class="text-muted mb-1"><i class="fas fa-user"></i> <strong>Author:</strong> {{$book->author}}</p>
                        <p class="text-muted mb-1"><i class="fas fa-barcode"></i> <strong>ISBN:</strong> <code>{{$book->isbn}}</code></p>
                        <p class="text-muted mb-1">
                            <span class="badge badge-info">{{$book->genre}}</span>
                            <span class="badge badge-secondary">{{$book->published_year}}</span>
                            <span class="badge {{$book->stock > 0 ? 'badge-success' : 'badge-danger'}}">
                                {{$book->stock > 0 ? 'In Stock: ' . $book->stock : 'Out of Stock'}}
                            </span>
                        </p>
                        <p class="text-primary mb-0"><strong>${{$book->price}}</strong></p>
                    </div>
                    <div class="col-md-2 text-right">
                        <div class="btn-group-vertical btn-group-sm" style="width: 100%;">
                            <a href="{{route('books.show', $book->id)}}" class="btn btn-info">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{route('books.edit', $book->id)}}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{route('books.destroy', $book->id)}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to delete this book?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-book-open fa-5x text-gray-300 mb-4"></i>
                    <h4 class="text-gray-600 mb-3">No Books Found</h4>
                    <p class="text-muted mb-4">Start building your library by adding your first book.</p>
                    <a href="{{route('books.create')}}" class="btn btn-primary btn-lg">
                        <i class="fas fa-plus"></i> Add Your First Book
                    </a>
                </div>
            @endforelse

            <!-- Pagination -->
            @if($books->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{$books->links()}}
                </div>
            @endif
        </div>
    </div>
@endsection
