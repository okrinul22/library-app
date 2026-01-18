<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content->title }} - Reading</title>
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

        /* Chapter Sidebar */
        .chapter-sidebar {
            background: var(--dark-card);
            border-right: 1px solid var(--dark-border);
            height: calc(100vh - 56px);
            overflow-y: auto;
            position: sticky;
            top: 56px;
        }

        .chapter-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .chapter-sidebar::-webkit-scrollbar-track {
            background: var(--dark-bg);
        }

        .chapter-sidebar::-webkit-scrollbar-thumb {
            background: var(--dark-border);
            border-radius: 3px;
        }

        .chapter-list {
            list-style: none;
            padding: 0;
        }

        .chapter-item {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--dark-border);
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .chapter-item:hover {
            background: var(--dark-bg);
        }

        .chapter-item.active {
            background: var(--primary-color);
            border-left: 3px solid var(--secondary-color);
        }

        .chapter-item .chapter-number {
            font-weight: 600;
            color: var(--secondary-color);
            min-width: 80px;
        }

        .chapter-item.active .chapter-number {
            color: #fff;
        }

        .chapter-item .chapter-title {
            flex: 1;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .chapter-item .chapter-status {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        /* Reading Area */
        .reading-area {
            background: var(--dark-bg);
            min-height: calc(100vh - 56px);
        }

        .reading-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .book-info {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .book-info h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .book-info .chapter-label {
            color: var(--secondary-color);
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .book-info .book-title {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        .book-info .book-title a {
            color: var(--text-secondary);
            text-decoration: none;
        }

        .book-info .book-title a:hover {
            color: var(--primary-color);
        }

        /* Content */
        .content-area {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            line-height: 1.8;
            font-size: 1.05rem;
        }

        .content-area p {
            margin-bottom: 1rem;
        }

        /* Navigation */
        .chapter-navigation {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            margin-top: 2rem;
        }

        .nav-btn {
            flex: 1;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
            text-decoration: none;
        }

        .nav-btn-prev {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            color: var(--text-primary);
        }

        .nav-btn-prev:hover:not(:disabled) {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .nav-btn-next {
            background: var(--primary-color);
            border: 1px solid var(--primary-color);
            color: #fff;
        }

        .nav-btn-next:hover:not(:disabled) {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .nav-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Back Button */
        .back-btn {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            color: var(--text-primary);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
            transition: all 0.2s;
            margin-bottom: 1rem;
        }

        .back-btn:hover {
            background: var(--dark-bg);
            border-color: var(--primary-color);
        }

        /* Empty State */
        .empty-chapters {
            text-align: center;
            padding: 2rem;
            color: var(--text-secondary);
        }

        .empty-chapters i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .chapter-sidebar {
                height: auto;
                max-height: 200px;
                position: relative;
                top: 0;
            }

            .reading-container {
                padding: 1rem;
            }

            .content-area {
                padding: 1.5rem;
                font-size: 1rem;
            }

            .chapter-navigation {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a href="{{ route('member.library') }}" class="navbar-brand">
                <i class="fas fa-book-reader"></i>
                <span>Digital Library</span>
            </a>
            <div class="ms-auto">
                <div class="dropdown">
                    <button class="btn btn-outline-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>
                        <span id="navUsername">Loading...</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('member.library') }}"><i class="fas fa-book me-2"></i> Library</a></li>
                        <li><a class="dropdown-item" href="{{ route('member.history') }}"><i class="fas fa-history me-2"></i> History</a></li>
                        <li><a class="dropdown-item" href="{{ route('member.profile') }}"><i class="fas fa-user me-2"></i> Profile</a></li>
                        <li><hr class="dropdown-divider bg-secondary"></li>
                        <li><a class="dropdown-item" href="#" onclick="logout()"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            <!-- Chapter Sidebar -->
            <div class="col-lg-3 col-xl-2 p-0">
                <div class="chapter-sidebar">
                    <div class="p-3 border-bottom border-secondary">
                        <h6 class="mb-0"><i class="fas fa-list me-2"></i> Chapters</h6>
                        <small class="text-secondary">{{ $chapters->count() }} chapters</small>
                    </div>
                    @if($chapters->count() > 0)
                        <ul class="chapter-list">
                            @foreach($chapters as $index => $chapter)
                                <li class="chapter-item {{ $chapter->id === $content->id ? 'active' : '' }}"
                                    onclick="window.location.href='{{ route('member.read', $chapter->id) }}'">
                                    <span class="chapter-number">Ch. {{ $chapter->chapter }}</span>
                                    <span class="chapter-title">{{ $chapter->title }}</span>
                                    @if($chapter->id === $content->id)
                                        <span class="chapter-status"><i class="fas fa-book-open"></i></span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="empty-chapters">
                            <i class="fas fa-folder-open"></i>
                            <p>No chapters available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Reading Area -->
            <div class="col-lg-9 col-xl-10">
                <div class="reading-area">
                    <div class="reading-container">
                        <a href="{{ route('member.library') }}" class="back-btn">
                            <i class="fas fa-arrow-left"></i>
                            Back to Library
                        </a>

                        <!-- Book Info -->
                        <div class="book-info">
                            <div class="chapter-label">
                                <i class="fas fa-bookmark me-2"></i>
                                Chapter {{ $content->chapter }}
                            </div>
                            <h1>{{ $content->title }}</h1>
                            @if($content->book)
                                <p class="book-title mb-0">
                                    <i class="fas fa-book me-2"></i>
                                    <a href="{{ route('member.library') }}">{{ $content->book->title }}</a>
                                    <span class="text-secondary mx-2">•</span>
                                    <span>{{ $content->book->author }}</span>
                                </p>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="content-area">
                            {!! $content->content ?? '<p class="text-secondary">No content available.</p>' !!}
                        </div>

                        <!-- Chapter Navigation -->
                        <div class="chapter-navigation">
                            @if($previousChapter)
                                <a href="{{ route('member.read', $previousChapter->id) }}" class="nav-btn nav-btn-prev">
                                    <i class="fas fa-chevron-left"></i>
                                    <div class="text-start">
                                        <small class="d-block" style="opacity: 0.7;">Previous</small>
                                        <span>Chapter {{ $previousChapter->chapter }}</span>
                                    </div>
                                </a>
                            @else
                                <button class="nav-btn nav-btn-prev" disabled>
                                    <i class="fas fa-chevron-left"></i>
                                    <div class="text-start">
                                        <small class="d-block" style="opacity: 0.7;">Previous</small>
                                        <span>None</span>
                                    </div>
                                </button>
                            @endif

                            @if($nextChapter)
                                <a href="{{ route('member.read', $nextChapter->id) }}" class="nav-btn nav-btn-next">
                                    <div class="text-end">
                                        <small class="d-block" style="opacity: 0.7;">Next</small>
                                        <span>Chapter {{ $nextChapter->chapter }}</span>
                                    </div>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <button class="nav-btn nav-btn-next" disabled>
                                    <div class="text-end">
                                        <small class="d-block" style="opacity: 0.7;">Next</small>
                                        <span>None</span>
                                    </div>
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Load user info
        function loadUserInfo() {
            const user = localStorage.getItem('user');
            if (user) {
                const userData = JSON.parse(user);
                document.getElementById('navUsername').textContent = userData.name || 'Member';
            }
        }

        // Logout function
        function logout() {
            event.preventDefault();
            if (confirm('Are you sure you want to logout?')) {
                localStorage.removeItem('user');
                localStorage.removeItem('token');
                window.location.href = '/login';
            }
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            const prevBtn = document.querySelector('.nav-btn-prev:not([disabled])');
            const nextBtn = document.querySelector('.nav-btn-next:not([disabled])');

            if (e.key === 'ArrowLeft' && prevBtn) {
                prevBtn.click();
            } else if (e.key === 'ArrowRight' && nextBtn) {
                nextBtn.click();
            }
        });

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            loadUserInfo();
        });
    </script>
</body>
</html>
