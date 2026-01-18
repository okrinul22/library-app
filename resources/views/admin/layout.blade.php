<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Library Management')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- SB Admin 2 CSS -->
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

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

        body {
            background: var(--dark-bg) !important;
            color: var(--text-primary);
        }

        #wrapper {
            background: var(--dark-bg);
        }

        #sidebar {
            background: var(--dark-card) !important;
        }

        #sidebar .sidebar-brand {
            height: 4.375rem;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: 800;
            padding: 1.5rem 1rem;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            color: var(--primary-color);
            display: block;
        }

        #sidebar .sidebar-brand .sidebar-brand-icon i {
            font-size: 2rem;
        }

        .sidebar-heading {
            background: var(--dark-bg);
            color: var(--text-secondary);
            font-size: 0.65rem;
            font-weight: 800;
            padding: 0 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
        }

        .nav-item .nav-link {
            color: var(--text-secondary);
            display: block;
            padding: 0.75rem 1rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .nav-item .nav-link:hover {
            color: var(--text-primary);
            background: rgba(79, 70, 229, 0.1);
        }

        .nav-item .nav-link.active {
            color: var(--primary-color);
            font-weight: 700;
        }

        .nav-item .nav-link i {
            margin-right: 0.5rem;
            width: 1.25rem;
            text-align: center;
        }

        .sidebar-card {
            background: rgba(79, 70, 229, 0.1);
            border: 1px solid var(--primary-color);
            color: var(--text-primary);
            border-radius: 0.35rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .sidebar-card .card-body {
            padding: 0.5rem 1rem;
        }

        .sidebar-footer {
            padding: 1rem;
            background: var(--dark-bg);
            border-top: 1px solid var(--dark-border);
        }

        .user-avatar-circle {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Topbar */
        #content-wrapper {
            background: var(--dark-bg);
        }

        .topbar {
            background: var(--dark-card);
            border-bottom: 1px solid var(--dark-border);
            padding: 0.5rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 4rem;
        }

        .topbar .navbar-brand {
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.2rem;
        }

        .topbar #sidebarToggleTop {
            color: var(--text-primary);
            background: transparent;
            border: none;
            padding: 0.25rem;
            cursor: pointer;
        }

        .topbar .nav-link {
            color: var(--text-primary) !important;
            position: relative;
            display: flex;
            align-items: center;
            padding: 0 0.5rem;
            font-size: 0.9rem;
        }

        .topbar .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .topbar .nav-link i {
            font-size: 1.1rem;
        }

        .topbar .dropdown-toggle {
            background: transparent;
            border: none;
            color: var(--text-primary);
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .topbar .dropdown-toggle:hover {
            color: var(--primary-color) !important;
        }

        .topbar .dropdown-toggle::after {
            margin-left: 0.25rem;
        }

        .topbar .badge-counter {
            background: var(--danger-color);
            color: white !important;
            font-size: 0.7rem;
            padding: 0.25rem 0.4rem;
            border-radius: 10px;
        }

        .topbar .badge-success.badge-counter {
            background: var(--secondary-color);
            color: white !important;
        }

        .topbar .navbar-search {
            max-width: 400px;
        }

        .topbar .form-control {
            color: var(--text-primary) !important;
            background: var(--dark-bg) !important;
            border-color: var(--dark-border) !important;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .topbar .form-control::placeholder {
            color: var(--text-secondary);
        }

        .topbar .input-group-append .btn {
            background: transparent;
            border: 1px solid var(--dark-border);
            color: var(--text-primary);
            border-radius: 0 20px 20px 0;
            padding: 0.375rem 0.75rem;
        }

        .topbar .input-group-append .btn:hover {
            background: var(--dark-bg);
        }

        .topbar-divider {
            border-right: 1px solid var(--dark-border);
        }

        .topbar .dropdown-menu {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.5rem rgba(0, 0, 0, 0.15);
            margin-top: 0.5rem;
        }

        .topbar .dropdown-item {
            color: var(--text-primary);
            background: transparent;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .topbar .dropdown-item:hover {
            background: var(--dark-bg);
        }

        .topbar .dropdown-item i {
            color: var(--text-secondary);
            margin-right: 0.5rem;
            width: 1rem;
            text-align: center;
        }

        .topbar .dropdown-divider {
            border-top: 1px solid var(--dark-border);
            margin: 0.5rem 0;
        }

        .topbar .fa-sign-out-alt {
            color: var(--text-primary) !important;
            transition: color 0.2s ease;
        }

        .topbar .fa-sign-out-alt:hover {
            color: var(--danger-color) !important;
            transform: scale(1.1);
            transition: all 0.2s ease;
        }

        .topbar .fa-sign-out-alt:active {
            transform: scale(0.95);
        }

        .topbar #logoutForm {
            position: relative;
        }

        .topbar #logoutForm:hover .fa-sign-out-alt {
            color: var(--danger-color) !important;
        }

        /* Footer */
        footer {
            background: var(--dark-card) !important;
            border-top: 1px solid var(--dark-border);
            color: var(--text-secondary);
        }

        footer .copyright {
            color: var(--text-secondary);
        }

        /* Content */
        .container-fluid {
            padding: 1.5rem;
        }

        .card {
            background: var(--dark-card);
            border: 1px solid var(--dark-border);
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.5rem rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: var(--dark-bg);
            border-bottom: 1px solid var(--dark-border);
            color: var(--text-primary);
            font-weight: 600;
        }

        /* Tables */
        .table {
            color: var(--text-primary);
        }

        .table thead th {
            background: var(--dark-bg);
            border-color: var(--dark-border);
            color: var(--text-secondary);
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 700;
        }

        .table tbody td {
            border-color: var(--dark-border);
        }

        .table-hover tbody tr:hover {
            background: rgba(79, 70, 229, 0.05);
        }

        /* Forms */
        .form-control {
            background: var(--dark-bg);
            border: 1px solid var(--dark-border);
            color: var(--text-primary);
        }

        .form-control:focus {
            background: var(--dark-bg);
            border-color: var(--primary-color);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25);
        }

        .form-select {
            background: var(--dark-bg);
            border: 1px solid var(--dark-border);
            color: var(--text-primary);
        }

        .form-label {
            color: var(--text-primary) !important;
            font-weight: 500;
        }

        label {
            color: var(--text-primary) !important;
        }

        small {
            color: var(--text-secondary) !important;
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

        .btn-success {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-danger {
            background: var(--danger-color);
            border-color: var(--danger-color);
        }

        .btn-warning {
            background: var(--warning-color);
            border-color: var(--warning-color);
        }

        .btn-secondary {
            background: var(--dark-border);
            border-color: var(--dark-border);
            color: var(--text-primary);
        }

        /* Badges */
        .badge {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.2);
            color: var(--secondary-color);
        }

        .badge-warning {
            background: rgba(245, 158, 11, 0.2);
            color: var(--warning-color);
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger-color);
        }

        .badge-info {
            background: rgba(79, 70, 229, 0.2);
            color: var(--primary-color);
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
            font-weight: 600;
        }

        .modal-body {
            color: #D1D5DB;
        }

        .modal-footer {
            border-top: 1px solid var(--dark-border);
        }

        .modal-body p {
            color: #D1D5DB;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .modal-body .form-label {
            color: var(--text-primary);
            font-weight: 500;
        }

        .modal-body small {
            color: var(--text-secondary);
        }

        .modal .text-secondary {
            color: var(--text-secondary) !important;
        }

        .modal .text-secondary small {
            color: var(--text-secondary) !important;
        }

        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        /* Pagination */
        .page-link {
            background: var(--dark-bg);
            border-color: var(--dark-border);
            color: var(--text-secondary);
        }

        .page-link:hover {
            background: var(--dark-border);
            color: var(--text-primary);
        }

        .page-item.active .page-link {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Stats Cards */
        .stats-card {
            border-left: 4px solid;
        }

        .stats-card.primary { border-left-color: var(--primary-color); }
        .stats-card.success { border-left-color: var(--secondary-color); }
        .stats-card.warning { border-left-color: var(--warning-color); }
        .stats-card.danger { border-left-color: var(--danger-color); }

        .stats-card .card-body {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stats-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stats-icon.primary { background: rgba(79, 70, 229, 0.1); color: var(--primary-color); }
        .stats-icon.success { background: rgba(16, 185, 129, 0.1); color: var(--secondary-color); }
        .stats-icon.warning { background: rgba(245, 158, 11, 0.1); color: var(--warning-color); }
        .stats-icon.danger { background: rgba(239, 68, 68, 0.1); color: var(--danger-color); }

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
    </style>
    @stack('styles')
</head>
<body class="sb-nav-fixed">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-book"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Library</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Items -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Users</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed {{ request()->routeIs('admin.books.*') ? 'active' : '' }}" href="{{ route('admin.books.index') }}">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Books</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed {{ request()->routeIs('admin.contents.*') ? 'active' : '' }}" href="{{ route('admin.contents.index') }}">
                    <i class="fas fa-fw fa-file-alt"></i>
                    <span>Contents</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}" href="{{ route('admin.transactions.index') }}">
                    <i class="fas fa-fw fa-credit-card"></i>
                    <span>Transactions</span>
                </a>
            </li>

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle">
                    <i class="fas fa-angle-left"></i>
                </button>
            </div>

            <!-- Sidebar User Info -->
            <div class="sidebar-card d-none d-lg-block">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="user-avatar-circle me-2">A</div>
                        <div>
                            <div class="fw-bold">Admin</div>
                            <div class="text-xs" style="color: var(--text-secondary);">Administrator</div>
                        </div>
                    </div>
                </div>
            </div>
        </ul>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search position-relative">
                        <div class="input-group">
                            <input type="text" class="form-control bg-dark border-0" placeholder="Search books by title, author, genre..." id="globalSearch" autocomplete="off" style="background: var(--dark-bg) !important;">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" id="globalSearchBtn">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                        <!-- Search Results Dropdown -->
                        <div id="searchResults" class="position-absolute w-100 mt-2" style="z-index: 1050; display: none;">
                            <div class="card shadow">
                                <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                                    <div id="searchResultsContent"></div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Alerts Icon -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <span class="badge badge-danger badge-counter" id="alertsCount"></span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                                aria-labelledby="alertsDropdown" id="alertsDropdownMenu">
                                <!-- Alerts will be loaded here dynamically -->
                            </ul>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Messages Icon -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <span class="badge badge-success badge-counter" id="messagesCount"></span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in"
                                aria-labelledby="messagesDropdown" id="messagesDropdownMenu">
                                <!-- Messages will be loaded here dynamically -->
                            </ul>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Logout Button -->
                        <li class="nav-item no-arrow mx-1">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline" id="logoutForm">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link" style="border: none; background: none; padding: 0.5rem 0.75rem; cursor: pointer;" title="Logout">
                                    <i class="fas fa-sign-out-alt fa-fw"></i>
                                </button>
                            </form>
                        </li>

                        <!-- User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="me-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                                <div class="user-avatar-circle d-none d-lg-inline" style="width: 2rem; height: 2rem; font-size: 0.9rem;">A</div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow animated--grow-in">
                                <li><a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>
                                    Profile
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>
                                            Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Page Content -->
                @yield('content')
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white mt-auto">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Library Management {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Alert Container -->
    <div class="alert-container" id="alert-container"></div>

    <!-- Confirm Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Confirm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="confirmMessage"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Approval Modal -->
    <div class="modal fade" id="contentApprovalModal" tabindex="-1" aria-labelledby="contentApprovalModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contentApprovalModalLabel">Content Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="contentApprovalModalBody">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- File Preview Modal -->
    <div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filePreviewModalLabel">File Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <iframe id="filePreviewFrame" style="width: 100%; height: 70vh; border: none;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- jQuery (required for SB Admin 2) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SB Admin 2 JS -->
    <script src="{{ asset('assets/js/sb-admin-2.js') }}"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- jsPDF for PDF generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- SheetJS for Excel generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        const API_URL = window.location.origin + '/api';
        const CSRF_TOKEN = '{{ csrf_token() }}';
        const AUTH_TOKEN = localStorage.getItem('token');

        // Handle logout form submission
        document.addEventListener('DOMContentLoaded', function() {
            const logoutForm = document.getElementById('logoutForm');
            const logoutBtn = logoutForm?.querySelector('button');

            if (logoutForm && logoutBtn) {
                logoutBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    logoutForm.submit();
                });
            }
        });
    </script>
    <script src="{{ asset('assets/js/admin.js') }}"></script>
    @stack('scripts')
</body>
</html>
