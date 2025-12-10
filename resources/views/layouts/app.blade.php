<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'E-Commerce') }} - @yield('title', 'Shop')</title>
    
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
            --primary-color: #0d9488;
            --primary-hover: #0f766e;
            --primary-light: #f0fdfa;
            --primary-light-hover: #ccfbf1;
            --secondary-color: #475569;
            --success-color: #059669;
            --danger-color: #e11d48;
            --warning-color: #eab308;
            --info-color: #0ea5e9;
            
            --body-bg: #f8fafc;
            --navbar-bg: rgba(255, 255, 255, 0.98);
            --card-bg: #ffffff;
            --footer-bg: #ffffff;
            
            --text-primary: #0f172a;
            --text-secondary: #334155;
            --text-muted: #64748b;
            
            --border-color: #e2e8f0;
            --border-light: #f1f5f9;
            
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.07), 0 2px 4px -2px rgb(0 0 0 / 0.06);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.08), 0 4px 6px -4px rgb(0 0 0 / 0.05);
        }

        [data-bs-theme="dark"] {
            --primary-color: #14b8a6;
            --primary-hover: #0d9488;
            --primary-light: #042f2e;
            --primary-light-hover: #134e4a;
            --body-bg: #0f172a;
            --navbar-bg: rgba(15, 23, 42, 0.98);
            --card-bg: #1e293b;
            --footer-bg: #1e293b;
            
            --text-primary: #f1f5f9;
            --text-secondary: #cbd5e1;
            --text-muted: #94a3b8;
            
            --border-color: #334155;
            --border-light: #1e293b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: background-color 0.3s ease;
        }

        /* Navbar Styles */
        .navbar {
            background-color: var(--navbar-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.025em;
        }

        .navbar-brand img {
            width: 32px;
            height: 32px;
            margin-right: 0.75rem;
        }

        .nav-link {
            color: var(--text-secondary);
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            color: var(--primary-color);
        }

        .nav-link.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Search Bar */
        .search-form .form-control {
            border: 1px solid var(--border-color);
            border-radius: 9999px;
            padding: 0.625rem 1.25rem;
            background-color: var(--card-bg);
            color: var(--text-primary);
            min-width: 300px;
            transition: all 0.2s ease;
        }

        .search-form .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary-color) 15%, transparent);
        }

        .search-form .btn {
            border-radius: 9999px;
            padding: 0.625rem 1.25rem;
            margin-left: -3rem;
            z-index: 1000;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            padding: 2rem 0;
        }

        /* Card Styles */
        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .card-img-top {
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            object-fit: cover;
        }

        /* Button Styles */
        .btn {
            padding: 0.5rem 1rem;
            font-weight: 500;
            border-radius: 0.7rem;
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

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-1px);
        }

        /* Form Controls */
        .form-control {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            background-color: var(--card-bg);
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary-color) 15%, transparent);
        }

        /* Dropdown Styles */
        .dropdown-menu {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.75rem;
            box-shadow: var(--shadow-lg);
            padding: 0.5rem;
        }

        .dropdown-item {
            color: var(--text-primary);
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            color: var(--primary-color);
            background-color: color-mix(in srgb, var(--primary-color) 5%, transparent);
        }

        /* Alert Styles */
        .alert {
            border-radius: 0.75rem;
            border: 1px solid var(--border-color);
            padding: 1rem;
        }

        /* Badge Styles */
        .badge {
            padding: 0.35em 0.65em;
            font-weight: 600;
            border-radius: 9999px;
        }

        /* Footer Styles */
        .footer {
            background-color: var(--footer-bg);
            border-top: 1px solid var(--border-color);
            padding: 4rem 0 2rem;
            margin-top: auto;
        }

        .footer h5 {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 1.25rem;
        }

        .footer .text-muted {
            color: var(--text-secondary) !important;
        }

        .footer a.text-muted {
            transition: color 0.2s ease;
        }

        .footer a.text-muted:hover {
            color: var(--primary-color) !important;
            text-decoration: none;
        }

        .social-links a {
            color: var(--text-secondary);
            margin-right: 1rem;
            font-size: 1.25rem;
            transition: all 0.2s ease;
        }

        .social-links a:hover {
            color: var(--primary-color);
            transform: translateY(-2px);
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

        /* Responsive Styles */
        @media (max-width: 768px) {
            .search-form .form-control {
                min-width: auto;
        }
        
        .navbar-brand {
                font-size: 1.25rem;
            }
            
            .footer {
                padding: 2rem 0;
            }
        }

        /* Filter and Category Styles */
        .filter-section {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .filter-section h5 {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .filter-section .form-label {
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        /* Category List Styles */
        .category-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .category-list li {
            margin-bottom: 0.5rem;
        }

        .category-list .category-link {
            display: flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .category-list .category-link:hover {
            color: var(--primary-color);
            background-color: var(--primary-light);
        }

        .category-list .category-link.active {
            color: var(--primary-color);
            background-color: var(--primary-light);
            font-weight: 600;
        }

        .category-list .category-link i {
            margin-right: 0.75rem;
            font-size: 1.1rem;
            color: var(--text-muted);
            transition: color 0.2s ease;
        }

        .category-list .category-link:hover i,
        .category-list .category-link.active i {
            color: var(--primary-color);
        }

        /* Price Filter Styles */
        .price-filter {
            padding: 1rem 0;
        }

        .price-filter .form-control {
            border-color: var(--border-color);
            background-color: var(--card-bg);
            color: var(--text-primary);
        }

        .price-filter .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary-color) 15%, transparent);
        }

        /* Checkbox and Radio Styles */
        .form-check {
            margin-bottom: 0.5rem;
        }

        .form-check-input {
            border-color: var(--border-color);
            background-color: var(--card-bg);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary-color) 15%, transparent);
        }

        .form-check-label {
            color: var(--text-secondary);
            font-weight: 500;
            cursor: pointer;
        }

        /* Filter Actions */
        .filter-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border-color);
        }

        .filter-actions .btn {
            flex: 1;
        }

        .btn-filter {
            background-color: var(--primary-light);
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            font-weight: 600;
        }

        .btn-filter:hover {
            background-color: var(--primary-light-hover);
            color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .btn-clear {
            background-color: transparent;
            color: var(--text-secondary);
            border: 1px solid var(--border-color);
        }

        .btn-clear:hover {
            background-color: var(--border-light);
            color: var(--text-primary);
        }

        /* Filter Tags */
        .filter-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .filter-tag {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            background-color: var(--primary-light);
            color: var(--primary-color);
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .filter-tag:hover {
            background-color: var(--primary-light-hover);
        }

        .filter-tag .close {
            margin-left: 0.5rem;
            font-size: 1rem;
            line-height: 1;
            cursor: pointer;
        }

        /* Mobile Filter Toggle */
        .filter-toggle {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 1000;
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            border: none;
            box-shadow: var(--shadow-lg);
            display: none;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .filter-toggle:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        @media (max-width: 991.98px) {
            .filter-toggle {
                display: flex;
            }

            .filter-section {
                display: none;
                position: fixed;
                left: 0;
                right: 0;
                bottom: 0;
                top: auto;
                height: 80vh;
                margin: 0;
                border-radius: 1rem 1rem 0 0;
                z-index: 1050;
                overflow-y: auto;
                transform: translateY(100%);
                transition: transform 0.3s ease-in-out;
            }

            .filter-section.show {
                transform: translateY(0);
                display: block;
            }
        }

        /* Auth Pages Styles */
        .auth-wrapper {
            min-height: calc(100vh - 180px);
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }

        .auth-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            box-shadow: var(--shadow-lg);
            padding: 2rem;
            width: 100%;
            max-width: 420px;
            margin: 0 auto;
        }

        .auth-card .card-header {
            background: transparent;
            border: none;
            padding: 0 0 1.5rem 0;
            text-align: center;
        }

        .auth-card .card-header h4 {
            color: var(--text-primary);
            font-weight: 600;
            font-size: 1.5rem;
            margin: 0;
        }

        .auth-card .card-body {
            padding: 0;
        }

        .auth-card .form-label {
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .auth-card .form-control {
            padding: 0.75rem 1rem;
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .auth-card .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary-color) 15%, transparent);
        }

        .auth-card .btn-primary {
            width: 100%;
            padding: 0.75rem;
            font-weight: 600;
            margin-top: 1rem;
        }

        .auth-card .btn-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .auth-card .btn-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        .auth-divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0;
            color: var(--text-muted);
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border-color);
        }

        .auth-divider span {
            padding: 0 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .social-login {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .social-login .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem;
            font-weight: 500;
            border: 1px solid var(--border-color);
            background-color: var(--card-bg);
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .social-login .btn:hover {
            background-color: var(--border-light);
            transform: translateY(-1px);
        }

        .social-login .btn i {
            font-size: 1.25rem;
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-secondary);
        }

        .auth-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        .form-check-remember {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0;
        }

        .form-check-remember .form-check {
            margin: 0;
        }

        @media (max-width: 576px) {
            .auth-card {
                padding: 1.5rem;
            }

            .social-login {
                grid-template-columns: 1fr;
            }
        }

        /* Product Card Styles */
        .product-card {
            position: relative;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .product-card .card-img-wrapper {
            position: relative;
            padding-top: 100%;
            overflow: hidden;
            background: var(--border-light);
        }

        .product-card .card-img-top {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 1rem;
            transition: transform 0.3s ease;
        }

        .product-card:hover .card-img-top {
            transform: scale(1.05);
        }

        .product-card .card-body {
            padding: 1.25rem;
        }

        .product-card .product-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: 2.75rem;
        }

        .product-card .product-category {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
        }

        .product-card .product-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
            display: block;
        }

        .product-card .btn-group {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 0.5rem;
            width: 100%;
        }

        .product-card .btn-view {
            padding: 0.625rem 1.25rem;
            color: var(--primary-color);
            background-color: var(--primary-light);
            font-weight: 600;
            flex-grow: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .product-card .btn-view:hover {
            background-color: var(--primary-light-hover);
            color: var(--primary-hover);
        }

        .product-card .btn-add-cart {
            background-color: var(--primary-color);
            border: none;
            color: white;
            aspect-ratio: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .product-card .btn-add-cart:hover {
            background-color: var(--primary-hover);
        }

        .product-card .btn-add-cart i {
            font-size: 1.1rem;
        }

        .product-card .product-badge {
            position: absolute;
            top: 1rem;
            left: 1rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            z-index: 1;
        }

        .product-card .badge-new {
            background-color: var(--primary-light);
            color: var(--primary-color);
        }

        .product-card .badge-sale {
            background-color: var(--danger-color);
            color: white;
        }

        @media (max-width: 576px) {
            .product-card .product-title {
                font-size: 1rem;
            }

            .product-card .product-price {
                font-size: 1.1rem;
            }

            .product-card .btn-group {
                grid-template-columns: 1fr;
            }

            .product-card .btn-add-cart {
                aspect-ratio: auto;
                width: 100%;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="d-inline-block align-text-top">
                <span>{{ config('', 'E-Commerce') }}</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.catalog') ? 'active' : '' }}" href="{{ route('products.catalog') }}">Products</a>
                    </li>
                </ul>
                
                <form class="search-form d-flex mx-lg-3 mb-3 mb-lg-0" action="{{ route('products.catalog') }}" method="GET">
                    <div class="position-relative w-100">
                        <input class="form-control" type="search" name="search" placeholder="Search products..." value="{{ request('search') }}">
                        <button class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-muted pe-3" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item me-2">
                        <button class="btn nav-link" id="themeToggle">
                            <i class="fas fa-sun" id="lightIcon"></i>
                            <i class="fas fa-moon d-none" id="darkIcon"></i>
                        </button>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative {{ request()->routeIs('cart.index') ? 'active' : '' }}" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-bag"></i>
                            @if(session()->has('cart') && count(session('cart')) > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ count(session('cart')) }}
                                </span>
                            @endif
                        </a>
                    </li>
                    
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary ms-2" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('orders.my') }}">
                                    <i class="fas fa-box me-2"></i>My Orders
                                </a></li>
                                
                                @if(Auth::user()->is_admin)
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-chart-line me-2"></i>Admin Dashboard
                                    </a></li>
                                @endif
                                
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}" 
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
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
    </div>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5>{{ config('app.name', 'E-Commerce') }}</h5>
                    <p class="text-muted">Your trusted store for quality products. Shop with confidence.</p>
                    <div class="social-links mt-4">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
                
                <div class="col-6 col-lg-2 mb-4 mb-lg-0">
                    <h5>Shop</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('products.catalog') }}" class="text-muted">All Products</a></li>
                        <li class="mb-2"><a href="#" class="text-muted">New Arrivals</a></li>
                        <li class="mb-2"><a href="#" class="text-muted">Featured</a></li>
                    </ul>
                </div>
                
                <div class="col-6 col-lg-2 mb-4 mb-lg-0">
                    <h5>Support</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-muted">Contact Us</a></li>
                        <li class="mb-2"><a href="#" class="text-muted">FAQs</a></li>
                        <li class="mb-2"><a href="#" class="text-muted">Shipping</a></li>
                        <li class="mb-2"><a href="#" class="text-muted">Returns</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4">
                    <h5>Stay Connected</h5>
                    <p class="text-muted mb-3">Subscribe to our newsletter for the latest updates</p>
                    <form class="mb-3">
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Email Address">
                            <button class="btn btn-primary" type="submit">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-muted small mb-3 mb-md-0">&copy; {{ date('Y') }} {{ config('app.name', 'E-Commerce') }}. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-muted small me-3">Privacy Policy</a>
                    <a href="#" class="text-muted small">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
    
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    
    <!-- Scripts -->
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