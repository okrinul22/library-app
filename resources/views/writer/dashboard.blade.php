<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Writer Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --primary-color: #4F46E5;
            --primary-hover: #4338CA;
            --secondary-color: #10B981;
            --danger-color: #EF4444;
            --warning-color: #F59E0B;
            --dark-bg: #111827;
            --dark-card: #1F2937;
            --dark-border: #374151;
            --text-primary: #F9FAFB;
            --text-secondary: #9CA3AF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--dark-bg);
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            background: var(--dark-card);
            border-bottom: 1px solid var(--dark-border);
            padding: 0.75rem 0;
        }

        .navbar-brand {
            color: var(--text-primary);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand i {
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .navbar-brand:hover {
            color: var(--text-primary);
        }

        .navbar .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .navbar .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .dropdown-menu {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
        }

        .dropdown-item {
            color: var(--text-primary);
        }

        .dropdown-item:hover {
            background: var(--dark-bg);
        }

        /* Main Content */
        .main-content {
            padding: 2rem 0;
        }

        /* Browse Sidebar */
        .browse-sidebar {
            position: fixed;
            top: 56px;
            right: -400px;
            width: 400px;
            height: calc(100vh - 56px);
            background: var(--dark-card);
            border-left: 1px solid var(--dark-border);
            transition: right 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }

        .browse-sidebar.show {
            right: 0;
        }

        .browse-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .browse-sidebar::-webkit-scrollbar-track {
            background: var(--dark-bg);
        }

        .browse-sidebar::-webkit-scrollbar-thumb {
            background: var(--dark-border);
            border-radius: 3px;
        }

        .sidebar-header {
            padding: 1rem;
            border-bottom: 1px solid var(--dark-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            background: var(--dark-card);
            z-index: 10;
        }

        .sidebar-close {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 1.25rem;
            cursor: pointer;
        }

        .sidebar-close:hover {
            color: var(--text-primary);
        }

        .sidebar-book-item {
            padding: 1rem;
            border-bottom: 1px solid var(--dark-border);
            cursor: pointer;
            transition: background 0.2s;
        }

        .sidebar-book-item:hover {
            background: var(--dark-bg);
        }

        .sidebar-book-cover {
            width: 60px;
            height: 90px;
            object-fit: cover;
            border-radius: 6px;
        }

        .sidebar-book-cover-placeholder {
            width: 60px;
            height: 90px;
            background: var(--dark-bg);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
        }

        .toggle-browse-btn {
            position: fixed;
            top: 50%;
            right: 0;
            transform: translateY(-50%);
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 1rem 0.5rem;
            border-radius: 8px 0 0 8px;
            cursor: pointer;
            z-index: 999;
            writing-mode: vertical-rl;
            text-orientation: mixed;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .toggle-browse-btn:hover {
            background: var(--primary-hover);
        }

        .toggle-browse-btn.active {
            right: 400px;
        }

        /* Stats Cards */
        .stat-card {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .stat-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .stat-icon.primary {
            background: rgba(79, 70, 229, 0.2);
            color: var(--primary-color);
        }

        .stat-icon.success {
            background: rgba(16, 185, 129, 0.2);
            color: var(--secondary-color);
        }

        .stat-icon.warning {
            background: rgba(245, 158, 11, 0.2);
            color: var(--warning-color);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        /* Section */
        .section-header {
            margin-bottom: 1.5rem;
        }

        .section-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        /* Book Cards */
        .book-card {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }

        .book-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
            border-color: var(--primary-color);
        }

        .book-cover {
            width: 100%;
            aspect-ratio: 2/3;
            object-fit: cover;
            background: var(--dark-bg);
        }

        .book-cover-placeholder {
            width: 100%;
            aspect-ratio: 2/3;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--dark-bg);
            color: var(--text-secondary);
        }

        .book-info {
            padding: 1rem;
        }

        .book-title {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .book-author {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        /* Table */
        .table-container {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .table {
            color: var(--text-primary);
            margin-bottom: 0;
        }

        .table thead th {
            background: var(--dark-bg);
            border-color: var(--dark-border);
            color: var(--text-secondary);
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
            padding: 1rem;
        }

        .table tbody td {
            border-color: var(--dark-border);
            padding: 1rem;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: rgba(79, 70, 229, 0.05);
        }

        /* Badge */
        .badge {
            padding: 0.35rem 0.65rem;
            font-weight: 500;
        }

        .badge-pending {
            background: rgba(245, 158, 11, 0.2);
            color: var(--warning-color);
        }

        .badge-approved {
            background: rgba(16, 185, 129, 0.2);
            color: var(--secondary-color);
        }

        /* Buttons */
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .btn-outline-secondary {
            border-color: var(--dark-border);
            color: var(--text-primary);
        }

        .btn-outline-secondary:hover {
            background: var(--dark-bg);
            border-color: var(--dark-border);
            color: var(--text-primary);
        }

        /* Modal */
        .modal-content {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            color: var(--text-primary);
        }

        .modal-header {
            border-bottom: 1px solid var(--dark-border);
        }

        .modal-title {
            color: var(--text-primary);
        }

        .modal-body {
            color: var(--text-secondary);
        }

        .modal-footer {
            border-top: 1px solid var(--dark-border);
        }

        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        /* Form */
        .form-control, .form-select {
            background: var(--dark-bg);
            border: 1px solid var(--dark-border);
            color: var(--text-primary);
        }

        .form-control:focus, .form-select:focus {
            background: var(--dark-bg);
            border-color: var(--primary-color);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25);
        }

        .form-control::placeholder {
            color: var(--text-secondary);
        }

        .form-label {
            color: var(--text-primary);
            font-weight: 500;
        }

        /* Alert */
        .alert-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 9999;
            max-width: 400px;
        }

        .alert {
            border-radius: 0.5rem;
            border: none;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.2);
            color: var(--secondary-color);
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger-color);
        }

        /* File Upload Zone */
        .file-drop-zone {
            border: 2px dashed var(--dark-border);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: var(--dark-bg);
        }

        .file-drop-zone:hover {
            border-color: var(--primary-color);
            background: rgba(79, 70, 229, 0.05);
        }

        /* Loading */
        .loading {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }

        .spinner-border {
            color: var(--primary-color);
        }

        /* User Avatar */
        .user-avatar-circle {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-pen-fancy"></i>
                Library - Writer
            </a>

            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fas fa-upload me-1"></i> Upload Content
                </button>

                <a href="{{ route('writer.history') }}" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-history me-1"></i> My Books
                </a>

                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <div class="user-avatar-circle" id="userAvatar">W</div>
                        <span id="userName">Writer</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('writer.profile') }}"><i class="fas fa-user me-2"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('writer.messages') }}"><i class="fas fa-envelope me-2"></i> Messages</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            <!-- Stats -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon primary">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="stat-value" id="totalBooks">-</div>
                        <div class="stat-label">Total Books</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-value" id="approvedContents">-</div>
                        <div class="stat-label">Approved Contents</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon warning">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-value" id="pendingContents">-</div>
                        <div class="stat-label">Pending Contents</div>
                    </div>
                </div>
            </div>

            <!-- My Books Section -->
            <div class="section mb-5">
                <div class="section-header">
                    <h2>My Books</h2>
                </div>
                <div class="row" id="booksGrid">
                    <div class="col-12">
                        <div class="loading">
                            <div class="spinner-border" role="status"></div>
                            <p class="mt-3">Loading books...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contents Table -->
            <div class="section">
                <div class="section-header">
                    <h2>Uploaded Contents</h2>
                </div>
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Book</th>
                                    <th>Status</th>
                                    <th>Uploaded At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="contentsTableBody">
                                <tr>
                                    <td colspan="6" style="text-align: center; color: var(--text-secondary);">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Browse Books Toggle Button -->
    <button class="toggle-browse-btn" id="toggleBrowseBtn" onclick="toggleBrowseSidebar()">
        <i class="fas fa-book-open me-2"></i> Browse Books
    </button>

    <!-- Browse Books Sidebar -->
    <div class="browse-sidebar" id="browseSidebar">
        <div class="sidebar-header">
            <h5 class="mb-0"><i class="fas fa-book-open me-2"></i> Browse Books</h5>
            <button class="sidebar-close" onclick="toggleBrowseSidebar()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Filters -->
        <div class="p-3 border-bottom border-secondary">
            <div class="row g-2">
                <div class="col-6">
                    <select class="form-select form-select-sm" id="browseGenreFilter" onchange="loadBrowseBooks()">
                        <option value="">All Genres</option>
                        <option value="Fiction">Fiction</option>
                        <option value="Non-Fiction">Non-Fiction</option>
                        <option value="Mystery">Mystery</option>
                        <option value="Romance">Romance</option>
                        <option value="Sci-Fi">Sci-Fi</option>
                        <option value="Fantasy">Fantasy</option>
                        <option value="Horror">Horror</option>
                        <option value="Biography">Biography</option>
                    </select>
                </div>
                <div class="col-6">
                    <select class="form-select form-select-sm" id="browseStatusFilter" onchange="loadBrowseBooks()">
                        <option value="">All Books</option>
                        <option value="free">Free Only</option>
                        <option value="available">Available</option>
                    </select>
                </div>
            </div>
            <div class="mt-2">
                <input type="text" class="form-control form-control-sm" id="browseSearchInput" placeholder="Search books..." onkeyup="debounceSearch()">
            </div>
        </div>

        <!-- Books List -->
        <div id="browseBooksList">
            <div class="text-center p-4">
                <div class="spinner-border spinner-border-sm" role="status"></div>
                <p class="small text-secondary mt-2">Loading books...</p>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload New Content</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs mb-3" id="uploadTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="existing-tab" data-bs-toggle="tab" data-bs-target="#existing" type="button">Existing Book</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="newbook-tab" data-bs-toggle="tab" data-bs-target="#newbook" type="button">Create New Book</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="uploadTabContent">
                        <!-- Existing Book Tab -->
                        <div class="tab-pane fade show active" id="existing" role="tabpanel">
                            <form id="uploadForm">
                                <div class="mb-3">
                                    <label for="contentTitle" class="form-label">Content Title</label>
                                    <input type="text" class="form-control" id="contentTitle" name="title" required placeholder="e.g., Chapter 1">
                                </div>

                                <div class="mb-3">
                                    <label for="contentBook" class="form-label">Link to Book</label>
                                    <select class="form-select" id="contentBook" name="book_id">
                                        <option value="">Select a book...</option>
                                    </select>
                                    <small class="text-secondary">Select an existing book or create a new one</small>
                                </div>

                                <div class="mb-3">
                                    <label for="contentFile" class="form-label">Upload Word Document (.docx)</label>
                                    <div class="file-drop-zone" id="fileDropZone">
                                        <i class="fas fa-file-word fa-3x mb-2" style="color: var(--text-secondary);"></i>
                                        <p class="mb-1">Drag & drop your file here or click to browse</p>
                                        <p style="font-size: 12px; color: var(--text-secondary);">Maximum file size: 10MB</p>
                                        <input type="file" id="contentFile" name="file" accept=".doc,.docx" style="display: none;" required>
                                    </div>
                                    <div id="fileName" style="margin-top: 8px; font-size: 13px; color: var(--secondary-color);"></div>
                                </div>
                            </form>
                        </div>

                        <!-- New Book Tab -->
                        <div class="tab-pane fade" id="newbook" role="tabpanel">
                            <form id="newBookForm">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="bookTitle" class="form-label">Book Title <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="bookTitle" name="title" required placeholder="Enter book title">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="bookAuthor" class="form-label">Author <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="bookAuthor" name="author" required placeholder="Author name">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="bookIsbn" class="form-label">ISBN <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="bookIsbn" name="isbn" required placeholder="e.g., 978-0-123456-78-9">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="bookGenre" class="form-label">Genre <span class="text-danger">*</span></label>
                                        <select class="form-select" id="bookGenre" name="genre" required>
                                            <option value="">Select genre...</option>
                                            <option value="Fiction">Fiction</option>
                                            <option value="Non-Fiction">Non-Fiction</option>
                                            <option value="Mystery">Mystery</option>
                                            <option value="Romance">Romance</option>
                                            <option value="Sci-Fi">Sci-Fi</option>
                                            <option value="Fantasy">Fantasy</option>
                                            <option value="Horror">Horror</option>
                                            <option value="Biography">Biography</option>
                                            <option value="Self-Help">Self-Help</option>
                                            <option value="Business">Business</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="bookPrice" class="form-label">Price (IDR) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" id="bookPrice" name="price" required min="0" step="1" placeholder="0">
                                        </div>
                                        <small class="text-secondary">Enter price in Indonesian Rupiah</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" id="bookIsFree" name="is_free" onchange="togglePriceField()">
                                            <label class="form-check-label" for="bookIsFree">
                                                This book is free
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <h6>Upload First Chapter</h6>

                                <div class="mb-3">
                                    <label for="newBookContentTitle" class="form-label">Chapter Title</label>
                                    <input type="text" class="form-control" id="newBookContentTitle" name="content_title" required placeholder="e.g., Chapter 1">
                                </div>

                                <div class="mb-3">
                                    <label for="newBookContentFile" class="form-label">Upload Word Document (.docx)</label>
                                    <div class="file-drop-zone" id="newBookFileDropZone">
                                        <i class="fas fa-file-word fa-3x mb-2" style="color: var(--text-secondary);"></i>
                                        <p class="mb-1">Drag & drop your file here or click to browse</p>
                                        <p style="font-size: 12px; color: var(--text-secondary);">Maximum file size: 10MB</p>
                                        <input type="file" id="newBookContentFile" name="content_file" accept=".doc,.docx" style="display: none;" required>
                                    </div>
                                    <div id="newBookFileName" style="margin-top: 8px; font-size: 13px; color: var(--secondary-color);"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="uploadSubmitBtn" onclick="submitUpload()">Upload Content</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Book Detail Modal -->
    <div class="modal fade" id="bookDetailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bookDetailTitle">Book Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="bookDetailContent"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="borrowBookBtn">Borrow Book</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Borrow Modal -->
    <div class="modal fade" id="borrowModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Borrow Book - Upload Payment Proof</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="borrowForm">
                        <input type="hidden" id="borrowBookId" name="book_id">

                        <div class="mb-3">
                            <label for="paymentProof" class="form-label">Upload Payment Proof</label>
                            <input type="file" class="form-control" id="paymentProof" accept="image/*" required>
                            <small class="text-secondary">Upload screenshot of transfer receipt (max 5MB)</small>
                        </div>

                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Payment proof will be verified by admin before confirmation.
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="submitBorrowBtn" class="btn btn-primary" onclick="submitBorrowForm()">
                        <span class="btn-text">Submit & Borrow</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Content Modal -->
    <div class="modal fade" id="editContentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Content</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editContentForm">
                        <input type="hidden" id="editContentId" name="content_id">
                        <div class="mb-3">
                            <label for="editContentTitle" class="form-label">Content Title</label>
                            <input type="text" class="form-control" id="editContentTitle" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="editContentFile" class="form-label">Upload New Word Document (.docx)</label>
                            <div class="file-drop-zone" id="editFileDropZone">
                                <i class="fas fa-file-word fa-3x mb-2" style="color: var(--text-secondary);"></i>
                                <p class="mb-1">Drag & drop your file here or click to browse</p>
                                <p style="font-size: 12px; color: var(--text-secondary);">Leave empty to keep existing file</p>
                                <input type="file" id="editContentFile" name="file" accept=".doc,.docx" style="display: none;">
                            </div>
                            <div id="editFileName" style="margin-top: 8px; font-size: 13px; color: var(--secondary-color);"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitEditContent()">Update Content</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Container -->
    <div class="alert-container" id="alert-container"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const API_URL = window.location.origin + '/api';
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const AUTH_TOKEN = localStorage.getItem('token');

        // Format date
        function formatDate(date) {
            return new Intl.DateTimeFormat('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            }).format(new Date(date));
        }

        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        // Show alert
        function showAlert(message, type = 'success') {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.innerHTML = `
                <strong>${type === 'success' ? 'Success' : 'Error'}!</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.getElementById('alert-container').appendChild(alert);

            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        }

        // Load user info
        function loadUserInfo() {
            const user = JSON.parse(localStorage.getItem('user') || '{}');
            if (user.name) {
                document.getElementById('userName').textContent = user.name;
                document.getElementById('userAvatar').textContent = user.name.charAt(0).toUpperCase();
            }
        }

        // Load stats
        async function loadStats() {
            try {
                // Get total books
                const booksResponse = await fetch(`${API_URL}/writer/books`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    credentials: 'same-origin'
                });

                const booksResult = await booksResponse.json();
                if (booksResult.success) {
                    const books = booksResult.data.data;
                    document.getElementById('totalBooks').textContent = books.length;
                }

                // Get contents for approved/pending stats
                const contentsResponse = await fetch(`${API_URL}/writer/contents`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    credentials: 'same-origin'
                });

                const contentsResult = await contentsResponse.json();
                if (contentsResult.success) {
                    const contents = contentsResult.data;
                    const approved = contents.filter(c => c.status === 'approved').length;
                    const pending = contents.filter(c => c.status === 'pending').length;
                    document.getElementById('approvedContents').textContent = approved;
                    document.getElementById('pendingContents').textContent = pending;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        // Load books
        async function loadBooks() {
            try {
                const response = await fetch(`${API_URL}/writer/books`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    renderBooks(result.data.data);
                    loadContents();
                } else {
                    showAlert(result.message || 'Failed to load books', 'error');
                }
            } catch (error) {
                console.error('Error loading books:', error);
                showAlert('Failed to load books', 'error');
            }
        }

        // Render books
        function renderBooks(books) {
            const grid = document.getElementById('booksGrid');

            if (books.length === 0) {
                grid.innerHTML = '<div class="col-12 text-center py-5"><p class="text-secondary">No books found. Create your first book!</p></div>';
                return;
            }

            grid.innerHTML = books.map(book => `
                <div class="col-6 col-md-4 col-lg-3 mb-4">
                    <div class="book-card">
                        ${book.cover_image
                            ? `<img src="/storage/${book.cover_image}" alt="${book.title}" class="book-cover">`
                            : `<div class="book-cover-placeholder"><i class="fas fa-book fa-2x"></i></div>`
                        }
                        <div class="book-info">
                            <h6 class="book-title" title="${book.title}">${book.title}</h6>
                            <p class="book-author">${book.author}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Load contents
        async function loadContents() {
            try {
                const response = await fetch(`${API_URL}/writer/contents`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    renderContents(result.data);
                    updateStats(result.data);
                } else {
                    showAlert(result.message || 'Failed to load contents', 'error');
                }
            } catch (error) {
                console.error('Error loading contents:', error);
                showAlert('Failed to load contents', 'error');
            }
        }

        // Render contents
        function renderContents(contents) {
            const tbody = document.getElementById('contentsTableBody');

            if (contents.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: var(--text-secondary);">No contents uploaded yet</td></tr>';
                return;
            }

            tbody.innerHTML = contents.map(content => `
                <tr>
                    <td>${content.title}</td>
                    <td>${content.book ? content.book.title : '-'}</td>
                    <td><span class="badge ${content.status === 'approved' ? 'badge-approved' : 'badge-pending'}">${content.status}</span></td>
                    <td>${new Date(content.created_at).toLocaleDateString('id-ID')}</td>
                    <td>
                        <div class="btn-group btn-group-sm" role="group">
                            <button class="btn btn-sm btn-outline-secondary" onclick="previewContent(${content.id})" title="Preview">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="editContent(${content.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteContent(${content.id})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Update stats
        function updateStats(contents) {
            const approved = contents.filter(c => c.status === 'approved').length;
            const pending = contents.filter(c => c.status === 'pending').length;

            document.getElementById('approvedContents').textContent = approved;
            document.getElementById('pendingContents').textContent = pending;
        }

        // Delete content
        async function deleteContent(id) {
            if (!confirm('Are you sure you want to delete this content?')) return;

            try {
                const response = await fetch(`${API_URL}/contents/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('Content deleted successfully', 'success');
                    loadBooks();
                } else {
                    showAlert(result.message || 'Failed to delete content', 'error');
                }
            } catch (error) {
                console.error('Error deleting content:', error);
                showAlert('Failed to delete content', 'error');
            }
        }

        // File upload handling
        const fileDropZone = document.getElementById('fileDropZone');
        const contentFile = document.getElementById('contentFile');

        fileDropZone.addEventListener('click', () => contentFile.click());

        fileDropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileDropZone.style.borderColor = 'var(--primary-color)';
        });

        fileDropZone.addEventListener('dragleave', () => {
            fileDropZone.style.borderColor = 'var(--dark-border)';
        });

        fileDropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            fileDropZone.style.borderColor = 'var(--dark-border)';
            if (e.dataTransfer.files.length) {
                contentFile.files = e.dataTransfer.files;
                handleFileSelect();
            }
        });

        contentFile.addEventListener('change', handleFileSelect);

        function handleFileSelect() {
            const file = contentFile.files[0];
            if (file) {
                document.getElementById('fileName').textContent = `Selected: ${file.name}`;
            }
        }

        // New book file drop zone handling
        const newBookFileDropZone = document.getElementById('newBookFileDropZone');
        const newBookContentFile = document.getElementById('newBookContentFile');

        newBookFileDropZone.addEventListener('click', () => newBookContentFile.click());

        newBookFileDropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            newBookFileDropZone.style.borderColor = 'var(--primary-color)';
        });

        newBookFileDropZone.addEventListener('dragleave', () => {
            newBookFileDropZone.style.borderColor = 'var(--dark-border)';
        });

        newBookFileDropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            newBookFileDropZone.style.borderColor = 'var(--dark-border)';
            if (e.dataTransfer.files.length) {
                newBookContentFile.files = e.dataTransfer.files;
                handleNewBookFileSelect();
            }
        });

        newBookContentFile.addEventListener('change', handleNewBookFileSelect);

        function handleNewBookFileSelect() {
            const file = newBookContentFile.files[0];
            if (file) {
                document.getElementById('newBookFileName').textContent = `Selected: ${file.name}`;
            }
        }

        // Submit upload - handles both existing book and new book creation
        async function submitUpload() {
            const submitBtn = document.getElementById('uploadSubmitBtn');
            const btnText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';

            try {
                // Check which tab is active
                const isNewBookTab = document.getElementById('newbook-tab').classList.contains('active');

                if (isNewBookTab) {
                    // Create new book first, then upload content
                    await createBookAndUploadContent();
                } else {
                    // Upload content to existing book
                    await uploadToExistingBook();
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('An error occurred', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = btnText;
            }
        }

        // Upload content to existing book
        async function uploadToExistingBook() {
            const title = document.getElementById('contentTitle').value.trim();
            const bookId = document.getElementById('contentBook').value;
            const file = contentFile.files[0];

            if (!title) {
                showAlert('Please enter a title', 'error');
                return;
            }

            if (!file) {
                showAlert('Please select a file', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('title', title);
            if (bookId) formData.append('book_id', bookId);
            formData.append('file', file);

            const response = await fetch(`${API_URL}/writer/contents/upload`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: formData,
                credentials: 'same-origin'
            });

            const result = await response.json();

            if (result.success) {
                showAlert('Content uploaded successfully!', 'success');
                bootstrap.Modal.getInstance(document.getElementById('uploadModal')).hide();
                document.getElementById('uploadForm').reset();
                document.getElementById('fileName').textContent = '';
                loadBooks();
            } else {
                showAlert(result.message || 'Failed to upload content', 'error');
            }
        }

        // Create new book and upload content
        async function createBookAndUploadContent() {
            const title = document.getElementById('bookTitle').value.trim();
            const author = document.getElementById('bookAuthor').value.trim();
            const isbn = document.getElementById('bookIsbn').value.trim();
            const genre = document.getElementById('bookGenre').value;
            const price = document.getElementById('bookIsFree').checked ? 0 : document.getElementById('bookPrice').value;
            const isFree = document.getElementById('bookIsFree').checked;

            const contentTitle = document.getElementById('newBookContentTitle').value.trim();
            const contentFile = document.getElementById('newBookContentFile').files[0];

            // Validate book fields
            if (!title || !author || !isbn || !genre) {
                showAlert('Please fill in all required book fields', 'error');
                return;
            }

            if (!isFree && !price) {
                showAlert('Please enter a price or mark as free', 'error');
                return;
            }

            // Validate content fields
            if (!contentTitle || !contentFile) {
                showAlert('Please enter chapter title and select a file', 'error');
                return;
            }

            // Create book first
            const bookData = {
                title, author, isbn, genre, price, stock: 100, published_year: new Date().getFullYear(),
                description: '', is_free: isFree
            };

            const bookResponse = await fetch(`${API_URL}/writer/books`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(bookData),
                credentials: 'same-origin'
            });

            const bookResult = await bookResponse.json();

            if (!bookResult.success) {
                showAlert(bookResult.message || 'Failed to create book', 'error');
                return;
            }

            // Now upload content with the new book ID
            const formData = new FormData();
            formData.append('title', contentTitle);
            formData.append('book_id', bookResult.data.id);
            formData.append('file', contentFile);

            const contentResponse = await fetch(`${API_URL}/writer/contents/upload`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: formData,
                credentials: 'same-origin'
            });

            const contentResult = await contentResponse.json();

            if (contentResult.success) {
                const newBookId = bookResult.data.id;
                showAlert('Book and content created successfully!', 'success');
                bootstrap.Modal.getInstance(document.getElementById('uploadModal')).hide();
                document.getElementById('newBookForm').reset();
                document.getElementById('newBookFileName').textContent = '';
                // Switch back to existing tab
                document.getElementById('existing-tab').click();
                // Refresh all data
                await loadBooks();
                await loadContents();
                await loadStats();
                // Refresh the dropdown and select the new book
                await loadBooksForSelect(newBookId);
            } else {
                showAlert(contentResult.message || 'Failed to upload content', 'error');
            }
        }

        // Toggle price field
        function togglePriceField() {
            const isFree = document.getElementById('bookIsFree').checked;
            const priceField = document.getElementById('bookPrice');
            priceField.disabled = isFree;
            priceField.value = isFree ? '0' : '';
            priceField.required = !isFree;
        }

        // Toggle browse sidebar
        function toggleBrowseSidebar() {
            const sidebar = document.getElementById('browseSidebar');
            const toggleBtn = document.getElementById('toggleBrowseBtn');

            sidebar.classList.toggle('show');
            toggleBtn.classList.toggle('active');

            if (sidebar.classList.contains('show')) {
                loadBrowseBooks();
            }
        }

        // Load browse books
        async function loadBrowseBooks() {
            const genre = document.getElementById('browseGenreFilter')?.value || '';
            const status = document.getElementById('browseStatusFilter')?.value || '';
            const search = document.getElementById('browseSearchInput')?.value || '';

            try {
                let url = `${API_URL}/books`;
                const params = new URLSearchParams();

                if (genre) params.append('genre', genre);
                if (status) params.append('status', status);
                if (search) params.append('search', search);

                if (params.toString()) {
                    url += '?' + params.toString();
                }

                const response = await fetch(url, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    renderBrowseBooks(result.data.data);
                } else {
                    document.getElementById('browseBooksList').innerHTML = `
                        <div class="text-center p-4">
                            <p class="text-secondary small">Failed to load books</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading browse books:', error);
                document.getElementById('browseBooksList').innerHTML = `
                    <div class="text-center p-4">
                        <p class="text-secondary small">Failed to load books</p>
                    </div>
                `;
            }
        }

        // Render browse books
        function renderBrowseBooks(books) {
            const list = document.getElementById('browseBooksList');

            if (books.length === 0) {
                list.innerHTML = `
                    <div class="text-center p-4">
                        <p class="text-secondary small">No books found</p>
                    </div>
                `;
                return;
            }

            list.innerHTML = books.map(book => `
                <div class="sidebar-book-item" onclick="viewBookDetail(${book.id})">
                    <div class="d-flex gap-3">
                        ${book.cover_image
                            ? `<img src="/storage/${book.cover_image}" alt="${book.title}" class="sidebar-book-cover">`
                            : `<div class="sidebar-book-cover-placeholder"><i class="fas fa-book"></i></div>`
                        }
                        <div class="flex-grow-1">
                            <h6 class="mb-1" style="font-size: 14px;">${book.title}</h6>
                            <p class="text-secondary small mb-1">${book.author}</p>
                            <small class="badge ${book.is_free ? 'badge-approved' : 'badge-pending'}">${book.is_free ? 'Free' : 'Rp ' + Number(book.price).toLocaleString('id-ID')}</small>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Debounce search
        let searchTimeout;
        function debounceSearch() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadBrowseBooks();
            }, 500);
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', () => {
            loadUserInfo();
            loadStats();
            loadBooks();

            // Load books for the select dropdown
            loadBooksForSelect();
        });

        // Load books for select dropdown
        async function loadBooksForSelect(selectedBookId = null) {
            try {
                const response = await fetch(`${API_URL}/writer/books`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    const select = document.getElementById('contentBook');
                    select.innerHTML = '<option value="">Select a book...</option>' +
                        result.data.data.map(book =>
                            `<option value="${book.id}">${book.title}</option>`
                        ).join('');
                    // Auto-select the specified book
                    if (selectedBookId) {
                        select.value = selectedBookId;
                    }
                }
            } catch (error) {
                console.error('Error loading books for select:', error);
            }
        }

        // View book detail (from browse sidebar)
        async function viewBookDetail(id) {
            try {
                const response = await fetch(`${API_URL}/books/${id}`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    const book = result.data;
                    const modal = document.getElementById('bookDetailModal');

                    document.getElementById('bookDetailTitle').textContent = book.title;

                    const isFree = book.is_free;
                    const priceDisplay = isFree
                        ? '<span class="badge badge-success fs-6">FREE</span>'
                        : `<span class="text-warning fs-5 fw-bold">${formatCurrency(book.price)}</span>`;

                    document.getElementById('bookDetailContent').innerHTML = `
                        <div class="row">
                            <div class="col-md-4 text-center mb-3">
                                ${book.cover_image
                                    ? `<img src="/storage/${book.cover_image}" class="img-fluid rounded" style="max-height: 350px;">`
                                    : `<div class="book-cover-placeholder rounded" style="height: 350px; background: var(--dark-bg); display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-book fa-4x text-secondary"></i>
                                       </div>`
                                }
                            </div>
                            <div class="col-md-8">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="text-secondary mb-1">Author</h6>
                                        <p class="mb-0">${book.author}</p>
                                    </div>
                                    <div class="text-end">
                                        <h6 class="text-secondary mb-1">Price</h6>
                                        ${priceDisplay}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <h6 class="text-secondary mb-1">Genre</h6>
                                        <span class="badge bg-primary">${book.genre}</span>
                                    </div>
                                    <div class="col-sm-6">
                                        <h6 class="text-secondary mb-1">Stock</h6>
                                        <span class="badge ${book.stock > 5 ? 'bg-success' : book.stock > 0 ? 'bg-warning' : 'bg-danger'}">
                                            ${book.stock} available
                                        </span>
                                    </div>
                                </div>

                                ${book.description ? `
                                    <div class="mb-3">
                                        <h6 class="text-secondary">Description</h6>
                                        <p class="text-secondary mb-0">${book.description}</p>
                                    </div>
                                ` : ''}

                                ${isFree ? `
                                    <div class="alert alert-success mb-0">
                                        <i class="fas fa-gift me-2"></i>
                                        <strong>Free Book!</strong> Borrow this book without any payment.
                                    </div>
                                ` : `
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Paid Book</strong> - You'll need to upload payment proof to borrow this book.
                                    </div>
                                `}
                            </div>
                        </div>
                    `;

                    const borrowBtn = document.getElementById('borrowBookBtn');
                    const modalFooter = borrowBtn.parentElement;

                    if (book.stock > 0) {
                        borrowBtn.style.display = 'block';
                        borrowBtn.disabled = false;
                        borrowBtn.className = isFree ? 'btn btn-success' : 'btn btn-primary';
                        borrowBtn.innerHTML = isFree
                            ? '<i class="fas fa-book-reader me-2"></i>Borrow Now (Free)'
                            : '<i class="fas fa-credit-card me-2"></i>Borrow with Payment';
                        borrowBtn.onclick = () => borrowBook(book.id, isFree);
                    } else {
                        borrowBtn.style.display = 'none';
                    }

                    new bootstrap.Modal(modal).show();
                }
            } catch (error) {
                console.error('Error viewing book:', error);
                showAlert('Failed to load book details', 'error');
            }
        }

        // Borrow book function
        async function borrowBook(id, isFree = false) {
            if (isFree) {
                // Free books can be borrowed directly
                try {
                    const response = await fetch(`${API_URL}/member/books/${id}/borrow-free`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        credentials: 'same-origin'
                    });

                    const result = await response.json();

                    if (result.success) {
                        showAlert('Book borrowed successfully!', 'success');
                        bootstrap.Modal.getInstance(document.getElementById('bookDetailModal')).hide();
                        loadBooks();
                    } else {
                        showAlert(result.message || 'Failed to borrow book', 'error');
                    }
                } catch (error) {
                    console.error('Error borrowing book:', error);
                    showAlert('Failed to borrow book', 'error');
                }
            } else {
                // For paid books, show the borrow modal
                bootstrap.Modal.getInstance(document.getElementById('bookDetailModal')).hide();
                const modal = document.getElementById('borrowModal');
                document.getElementById('borrowBookId').value = id;
                new bootstrap.Modal(modal).show();
            }
        }

        // Submit borrow form with payment proof
        async function submitBorrowForm() {
            const bookId = document.getElementById('borrowBookId').value;
            const proofFile = document.getElementById('paymentProof').files[0];

            if (!proofFile) {
                showAlert('Please upload payment proof', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('payment_proof', proofFile);

            try {
                const response = await fetch(`${API_URL}/member/books/${bookId}/borrow`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: formData,
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('Book borrowed successfully! Waiting for admin approval.', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('borrowModal')).hide();
                    bootstrap.Modal.getInstance(document.getElementById('bookDetailModal')).hide();
                    loadBooks();
                } else {
                    showAlert(result.message || 'Failed to borrow book', 'error');
                }
            } catch (error) {
                console.error('Error borrowing book:', error);
                showAlert('Failed to borrow book', 'error');
            }
        }

        // Preview content
        async function previewContent(id) {
            try {
                const response = await fetch(`${API_URL}/writer/contents/${id}`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    const content = result.data;
                    // Open in new tab to read
                    if (content.file_path) {
                        window.open(`/storage/${content.file_path}`, '_blank');
                    } else {
                        showAlert('Content file not available', 'error');
                    }
                } else {
                    showAlert(result.message || 'Failed to load content', 'error');
                }
            } catch (error) {
                console.error('Error previewing content:', error);
                showAlert('Failed to preview content', 'error');
            }
        }

        // Edit content
        async function editContent(id) {
            try {
                const response = await fetch(`${API_URL}/writer/contents/${id}`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    const content = result.data;
                    document.getElementById('editContentId').value = content.id;
                    document.getElementById('editContentTitle').value = content.title;
                    document.getElementById('editFileName').textContent = content.file_path ? `Current: ${content.file_path}` : '';

                    // Setup file drop zone for edit
                    const editDropZone = document.getElementById('editFileDropZone');
                    const editContentFile = document.getElementById('editContentFile');

                    editDropZone.onclick = () => editContentFile.click();

                    editDropZone.ondragover = (e) => {
                        e.preventDefault();
                        editDropZone.style.borderColor = 'var(--primary-color)';
                    };

                    editDropZone.ondragleave = () => {
                        editDropZone.style.borderColor = 'var(--dark-border)';
                    };

                    editDropZone.ondrop = (e) => {
                        e.preventDefault();
                        editDropZone.style.borderColor = 'var(--dark-border)';
                        if (e.dataTransfer.files.length) {
                            editContentFile.files = e.dataTransfer.files;
                            document.getElementById('editFileName').textContent = `Selected: ${e.dataTransfer.files[0].name}`;
                        }
                    };

                    editContentFile.onchange = () => {
                        if (editContentFile.files[0]) {
                            document.getElementById('editFileName').textContent = `Selected: ${editContentFile.files[0].name}`;
                        }
                    };

                    new bootstrap.Modal(document.getElementById('editContentModal')).show();
                } else {
                    showAlert(result.message || 'Failed to load content', 'error');
                }
            } catch (error) {
                console.error('Error loading content:', error);
                showAlert('Failed to load content', 'error');
            }
        }

        // Submit edit content
        async function submitEditContent() {
            const contentId = document.getElementById('editContentId').value;
            const title = document.getElementById('editContentTitle').value.trim();
            const file = document.getElementById('editContentFile').files[0];

            if (!title) {
                showAlert('Please enter a title', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('title', title);
            if (file) {
                formData.append('file', file);
            }

            try {
                const response = await fetch(`${API_URL}/writer/contents/${contentId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: formData,
                    credentials: 'same-origin'
                });

                const result = await response.json();

                if (result.success) {
                    showAlert('Content updated successfully!', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('editContentModal')).hide();
                    loadContents();
                } else {
                    showAlert(result.message || 'Failed to update content', 'error');
                }
            } catch (error) {
                console.error('Error updating content:', error);
                showAlert('Failed to update content', 'error');
            }
        }
    </script>
</body>
</html>
