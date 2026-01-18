@extends('layouts.admin')

@section('title', 'Edit Book')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 text-gray-800">
                <i class="fas fa-edit text-warning"></i> Edit Book
            </h1>
            <p class="mb-0 text-muted">Update book information for <strong>"{{$book->title}}"</strong></p>
        </div>
        <a href="{{route('books.show', $book->id)}}" class="btn btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Book Details
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-gradient-warning">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-book"></i> Edit Book Information
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{route('books.update', $book->id)}}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <h6 class="heading-small text-muted mb-3">Basic Information</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-heading text-gray-400"></i> Title <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                       name="title" value="{{old('title', $book->title)}}" placeholder="Enter book title" required>
                                @error('title')
                                    <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-user text-gray-400"></i> Author <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('author') is-invalid @enderror"
                                       name="author" value="{{old('author', $book->author)}}" placeholder="Enter author name" required>
                                @error('author')
                                    <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-barcode text-gray-400"></i> ISBN <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('isbn') is-invalid @enderror"
                                       name="isbn" value="{{old('isbn', $book->isbn)}}" placeholder="XXX-X-XXXX-XXXX" required>
                                @error('isbn')
                                    <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-calendar text-gray-400"></i> Published Year <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('published_year') is-invalid @enderror"
                                       name="published_year" value="{{old('published_year', $book->published_year)}}" min="1000" max="2099" required>
                                @error('published_year')
                                    <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-tag text-gray-400"></i> Genre <span class="text-danger">*</span>
                                </label>
                                <select class="form-control @error('genre') is-invalid @enderror" name="genre" required>
                                    <option value="">Select Genre</option>
                                    <option value="Fiction" {{old('genre', $book->genre) == 'Fiction' ? 'selected' : ''}}>Fiction</option>
                                    <option value="Non-Fiction" {{old('genre', $book->genre) == 'Non-Fiction' ? 'selected' : ''}}>Non-Fiction</option>
                                    <option value="Science Fiction" {{old('genre', $book->genre) == 'Science Fiction' ? 'selected' : ''}}>Science Fiction</option>
                                    <option value="Fantasy" {{old('genre', $book->genre) == 'Fantasy' ? 'selected' : ''}}>Fantasy</option>
                                    <option value="Mystery" {{old('genre', $book->genre) == 'Mystery' ? 'selected' : ''}}>Mystery</option>
                                    <option value="Romance" {{old('genre', $book->genre) == 'Romance' ? 'selected' : ''}}>Romance</option>
                                    <option value="Thriller" {{old('genre', $book->genre) == 'Thriller' ? 'selected' : ''}}>Thriller</option>
                                    <option value="Biography" {{old('genre', $book->genre) == 'Biography' ? 'selected' : ''}}>Biography</option>
                                    <option value="History" {{old('genre', $book->genre) == 'History' ? 'selected' : ''}}>History</option>
                                    <option value="Self-Help" {{old('genre', $book->genre) == 'Self-Help' ? 'selected' : ''}}>Self-Help</option>
                                    <option value="Business" {{old('genre', $book->genre) == 'Business' ? 'selected' : ''}}>Business</option>
                                    <option value="Other" {{old('genre', $book->genre) == 'Other' ? 'selected' : ''}}>Other</option>
                                </select>
                                @error('genre')
                                    <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-image text-gray-400"></i> Cover Image URL
                                </label>
                                <input type="text" class="form-control @error('cover_image') is-invalid @enderror"
                                       name="cover_image" value="{{old('cover_image', $book->cover_image)}}" placeholder="https://example.com/cover.jpg">
                                @error('cover_image')
                                    <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-align-left text-gray-400"></i> Description
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      name="description" rows="3" placeholder="Enter book description">{{old('description', $book->description)}}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{$message}}</div>
                            @enderror
                        </div>

                        <!-- Pricing & Inventory -->
                        <h6 class="heading-small text-muted mb-3 mt-4">Pricing & Inventory</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-dollar-sign text-gray-400"></i> Price <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                           name="price" value="{{old('price', $book->price)}}" placeholder="0.00" min="0" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{$message}}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-boxes text-gray-400"></i> Stock Quantity <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                       name="stock" value="{{old('stock', $book->stock)}}" min="0" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{$message}}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Info Alert -->
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i>
                            <strong>Last updated:</strong> {{$book->updated_at->format('F d, Y - H:i')}}
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <a href="{{route('books.show', $book->id)}}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-sync"></i> Update Book
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
