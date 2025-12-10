<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - {{ config('app.name', 'E-Commerce') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --secondary-color: #64748b;
            --success-color: #059669;
            --danger-color: #dc2626;
            --warning-color: #d97706;
            --info-color: #0891b2;
            
            --body-bg: #f9fafb;
            --sidebar-bg: #ffffff;
            --navbar-bg: #ffffff;
            --card-bg: #ffffff;
            
            --text-primary: #111827;
            --text-secondary: #4b5563;
            --text-muted: #6b7280;
            
            --border-color: #e5e7eb;
            --border-light: #f3f4f6;
            
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        [data-bs-theme="dark"] {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --body-bg: #0f172a;
            --sidebar-bg: #1e293b;
            --navbar-bg: #1e293b;
            --card-bg: #1e293b;
            
            --text-primary: #f3f4f6;
            --text-secondary: #d1d5db;
            --text-muted: #9ca3af;
            
            --border-color: #334155;
            --border-light: #1f2937;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-primary);
            min-height: 100vh;
            transition: background-color 0.3s ease;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 280px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-light);
        }

        .sidebar .nav-link {
            color: var(--text-secondary);
            padding: 0.875rem 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 4px 12px;
        }

        .sidebar .nav-link:hover {
            color: var(--primary-color);
            background-color: color-mix(in srgb, var(--primary-color) 5%, transparent);
            transform: translateX(4px);
        }

        .sidebar .nav-link.active {
            color: var(--primary-color);
            background-color: color-mix(in srgb, var(--primary-color) 8%, transparent);
            font-weight: 600;
        }

        .sidebar .nav-link i {
            font-size: 1.25rem;
            width: 1.25rem;
            text-align: center;
        }

        /* Navbar Styles */
        .navbar {
            height: 70px;
            background-color: var(--navbar-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 0 1.5rem;
            margin-left: 280px;
            backdrop-filter: blur(8px);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .navbar-brand img {
            width: 32px;
            height: 32px;
            margin-right: 0.75rem;
        }

        .navbar .btn-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .navbar .btn-icon:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .navbar .dropdown-toggle {
            background-color: transparent;
            border: none;
            color: var(--text-primary);
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .navbar .dropdown-toggle:hover {
            background-color: var(--border-light);
        }

        .navbar .dropdown-menu {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: var(--shadow-md);
            margin-top: 10px;
        }

        .navbar .dropdown-item {
            color: var(--text-primary);
            padding: 0.65rem 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .navbar .dropdown-item:hover {
            background-color: var(--border-light);
            color: var(--primary-color);
            transform: translateX(4px);
        }

        [data-bs-theme="dark"] .navbar {
            box-shadow: 0 1px 8px rgba(0, 0, 0, 0.2);
        }

        [data-bs-theme="dark"] .navbar .btn-icon {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            padding: 2rem;
            padding-top: calc(70px + 2rem);
        }

        /* Card Styles */
        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .card-header h5 {
            font-weight: 600;
            color: var(--text-primary);
        }

        .card-header .text-muted {
            color: var(--text-muted) !important;
        }

        .card-header i {
            transition: color 0.3s ease;
        }

        [data-bs-theme="dark"] .card-header {
            background-color: var(--card-bg);
            border-bottom-color: var(--border-color);
        }

        [data-bs-theme="dark"] .card-header.bg-white {
            background-color: var(--card-bg) !important;
        }

        [data-bs-theme="dark"] .card-header h5 {
            color: var(--text-primary);
        }

        [data-bs-theme="dark"] .card-header i.text-primary {
            color: #818cf8 !important;
        }

        /* Button Styles */
        .btn {
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        /* Table Styles */
        .table {
            color: var(--text-primary);
        }

        .table th {
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 1rem;
            background-color: var(--card-bg);
            border-bottom-width: 1px;
        }

        .table td {
            padding: 1rem;
            color: var(--text-primary);
            vertical-align: middle;
        }

        /* Form Controls */
        .form-control {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.5rem 1rem;
            background-color: var(--card-bg);
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary-color) 15%, transparent);
        }

        /* Badges */
        .badge {
            padding: 0.35em 0.65em;
            font-weight: 600;
            border-radius: 6px;
        }

        /* Alerts */
        .alert {
            border-radius: 12px;
            border: 1px solid var(--border-color);
            padding: 1rem;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--body-bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--text-muted);
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .navbar, .main-content {
                margin-left: 0;
            }
        }

        /* Avatar styles */
        .avatar-circle {
            width: 32px;
            height: 32px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        /* Dropdown header styles */
        .dropdown-header {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            padding: 0.5rem 1rem;
        }

        /* Dark mode adjustments */
        [data-bs-theme="dark"] .dropdown-header {
            color: rgba(255, 255, 255, 0.6);
        }

        /* Modal styles */
        .modal-content {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
            background-color: var(--card-bg);
            padding: 1rem 1.5rem;
        }

        .modal-header .modal-title {
            font-weight: 600;
            color: var(--text-primary);
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
            background-color: var(--card-bg);
            padding: 1rem 1.5rem;
        }

        [data-bs-theme="dark"] .modal-content {
            background-color: var(--card-bg);
        }

        [data-bs-theme="dark"] .modal-header,
        [data-bs-theme="dark"] .modal-footer {
            border-color: var(--border-color);
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar fixed-top">
        <div class="container-fluid px-0">
            <div class="d-flex align-items-center">
                <button class="btn btn-icon d-lg-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="navbar-brand d-flex align-items-center m-0" href="{{ route('admin.dashboard') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="d-inline-block align-text-top">
                    <span>{{ config('', 'E-Commerce') }} Admin</span>
                </a>
            </div>

            <div class="d-flex align-items-center gap-3">
                <div class="d-none d-md-flex align-items-center me-3">
                    <span class="text-muted me-2">
                        <i class="far fa-calendar-alt"></i>
                    </span>
                    <span>{{ now()->format('F d, Y') }}</span>
                </div>
                
                <button class="btn btn-icon" id="themeToggle" title="Toggle Theme">
                    <i class="fas fa-sun" id="lightIcon"></i>
                    <i class="fas fa-moon d-none" id="darkIcon"></i>
                </button>

                <div class="dropdown">
                    <button class="btn dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle me-2">
                                <span class="initials">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="dropdown-header">Account</li>
                        <li><a class="dropdown-item" href="#">
                            <i class="fas fa-user-cog me-2"></i> My Profile
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('home') }}">
                            <i class="fas fa-store me-2"></i> View Store
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h5 class="mb-0">Admin Panel</h5>
        </div>
        <div class="py-3">
            <div class="nav flex-column">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i>
                    <span>Products</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i>
                    <span>Categories</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Orders</span>
                </a>
                
                <div class="mt-4 px-3">
                    <hr>
                    <a href="{{ route('home') }}" class="nav-link text-muted">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Store</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid p-0">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">@yield('title', 'Dashboard')</h1>
                @hasSection('actions')
                    @yield('actions')
                @endif
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Theme toggler
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            const lightIcon = document.getElementById('lightIcon');
            const darkIcon = document.getElementById('darkIcon');
            const html = document.documentElement;
            
            // Check saved theme or system preference
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            if (savedTheme === 'dark' || (!savedTheme && prefersDark)) {
                html.setAttribute('data-bs-theme', 'dark');
                lightIcon.classList.add('d-none');
                darkIcon.classList.remove('d-none');
            }
            
            themeToggle.addEventListener('click', function() {
                if (html.getAttribute('data-bs-theme') === 'dark') {
                    html.setAttribute('data-bs-theme', 'light');
                    localStorage.setItem('theme', 'light');
                    lightIcon.classList.remove('d-none');
                    darkIcon.classList.add('d-none');
                } else {
                    html.setAttribute('data-bs-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    lightIcon.classList.add('d-none');
                    darkIcon.classList.remove('d-none');
                }
            });
        });
    </script>

    @yield('scripts')
</body>
</html> 