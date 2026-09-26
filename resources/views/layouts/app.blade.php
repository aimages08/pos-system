<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>POS System</title>

    {{-- Theme init (prevents flash of wrong theme) --}}
    <script>
        (() => {
            'use strict';
            const root = document.documentElement;
            if (root.getAttribute('data-lte-color-mode') === 'off') return;

            const STORAGE_KEY = 'lte-theme';
            let stored = null;
            try {
                stored = localStorage.getItem(STORAGE_KEY);
            } catch {}

            const authored = root.getAttribute('data-bs-theme');
            let resolved = 'light';
            if (stored === 'dark' || stored === 'light') resolved = stored;
            else if (authored === 'dark' || authored === 'light') resolved = authored;
            else if (globalThis.matchMedia('(prefers-color-scheme: dark)').matches) resolved = 'dark';

            root.setAttribute('data-bs-theme', resolved);
            root.style.colorScheme = resolved;
            if (resolved !== authored) root.setAttribute('data-lte-theme-resolved', '');
        })();
    </script>

    {{-- Meta --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />

    {{-- Fonts --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print"
        onload="this.media='all'" />

    {{-- OverlayScrollbars CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
        crossorigin="anonymous" />

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous" />

    {{-- AdminLTE --}}
    <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.css') }}">

    {{-- My custom CSS --}}
    <link rel="stylesheet" href="{{ asset('customcss/laravel_pagination.css') }}">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">

        {{-- ========== HEADER ========== --}}
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">

                {{-- Left: sidebar toggle --}}
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"
                            aria-label="Toggle sidebar">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                </ul>

                {{-- Right: fullscreen + theme + user --}}
                <ul class="navbar-nav ms-auto">

                    {{-- Fullscreen --}}
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-lte-toggle="fullscreen" aria-label="Toggle fullscreen">
                            <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                            <i data-lte-icon="minimize" class="bi bi-fullscreen-exit d-none"></i>
                        </a>
                    </li>

                    {{-- Color mode --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="bd-theme" aria-label="Toggle color scheme"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-sun-fill" data-lte-theme-icon="light"></i>
                            <i class="bi bi-moon-fill d-none" data-lte-theme-icon="dark"></i>
                            <i class="bi bi-circle-half d-none" data-lte-theme-icon="auto"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme"
                            style="--bs-dropdown-min-width: 8rem">
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center"
                                    data-bs-theme-value="light" aria-pressed="false">
                                    <i class="bi bi-sun-fill me-2"></i> Light
                                    <i class="bi bi-check-lg ms-auto d-none"></i>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center"
                                    data-bs-theme-value="dark" aria-pressed="false">
                                    <i class="bi bi-moon-fill me-2"></i> Dark
                                    <i class="bi bi-check-lg ms-auto d-none"></i>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center active"
                                    data-bs-theme-value="auto" aria-pressed="true">
                                    <i class="bi bi-circle-half me-2"></i> Auto
                                    <i class="bi bi-check-lg ms-auto d-none"></i>
                                </button>
                            </li>
                        </ul>
                    </li>

                    {{-- User menu --}}
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'User' }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <li class="user-header text-bg-primary">
                                <i class="bi bi-person-circle" style="font-size: 3rem;"></i>
                                <p>
                                    {{ Auth::user()->name ?? 'User' }}
                                    <small>{{ Auth::user()->role->label ?? 'No Role' }}</small>
                                </p>
                            </li>
                            <li class="user-footer d-flex justify-content-between align-items-center">
                                <a href="{{ url('/profile') }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-person"></i> Profile
                                </a>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-box-arrow-right"></i> Sign out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        {{-- ========== /HEADER ========== --}}


        {{-- ========== SIDEBAR ========== --}}
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">

            {{-- Brand --}}
            <div class="sidebar-brand">
                <a href="{{ url('/') }}" class="brand-link">
                    <img src="{{ asset('adminlte/assets/img/AdminLTELogo.png') }}" alt="POS Logo"
                        class="brand-image opacity-75 shadow" />
                    <span class="brand-text fw-light">POS System</span>
                </a>
            </div>

            {{-- Sidebar wrapper --}}
            <div class="sidebar-wrapper">
                <nav class="mt-2" aria-label="Main navigation">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false"
                        id="navigation">

                        {{-- Dashboard --}}
                        <li class="nav-item">
                            <a href="{{ url('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-speedometer2"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        {{-- Categories --}}
                        @if (Auth::user()->hasPermission('categories.view'))
                            <li class="nav-item {{ request()->is('categories*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->is('categories*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-tags"></i>
                                    <p>Categories <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @if (Auth::user()->hasPermission('categories.manage'))
                                        <li class="nav-item">
                                            <a href="{{ url('/categories/create') }}"
                                                class="nav-link {{ request()->is('categories/create') ? 'active' : '' }}">
                                                <i class="nav-icon bi bi-plus-circle"></i>
                                                <p>Add Category</p>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="nav-item">
                                        <a href="{{ url('/categories') }}"
                                            class="nav-link {{ request()->is('categories') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-list"></i>
                                            <p>View All Categories</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        {{-- Products --}}
                        @if (Auth::user()->hasPermission('products.view'))
                            <li class="nav-item {{ request()->is('products*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->is('products*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-box-seam"></i>
                                    <p>Products <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @if (Auth::user()->hasPermission('products.manage'))
                                        <li class="nav-item">
                                            <a href="{{ url('/products/create') }}"
                                                class="nav-link {{ request()->is('products/create') ? 'active' : '' }}">
                                                <i class="nav-icon bi bi-plus-circle"></i>
                                                <p>Add Product</p>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="nav-item">
                                        <a href="{{ url('/products') }}"
                                            class="nav-link {{ request()->is('products') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-list"></i>
                                            <p>View All Products</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        {{-- Customers --}}
                        @if (Auth::user()->hasPermission('customers.view'))
                            <li class="nav-item {{ request()->is('customers*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->is('customers*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-people"></i>
                                    <p>Customers <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @if (Auth::user()->hasPermission('customers.manage'))
                                        <li class="nav-item">
                                            <a href="{{ url('/customers/create') }}"
                                                class="nav-link {{ request()->is('customers/create') ? 'active' : '' }}">
                                                <i class="nav-icon bi bi-plus-circle"></i>
                                                <p>Add Customer</p>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="nav-item">
                                        <a href="{{ url('/customers') }}"
                                            class="nav-link {{ request()->is('customers') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-list"></i>
                                            <p>View All Customers</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        {{-- Suppliers --}}
                        @if (Auth::user()->hasPermission('suppliers.view'))
                            <li class="nav-item {{ request()->is('suppliers*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->is('suppliers*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-truck"></i>
                                    <p>Suppliers <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @if (Auth::user()->hasPermission('suppliers.manage'))
                                        <li class="nav-item">
                                            <a href="{{ url('/suppliers/create') }}"
                                                class="nav-link {{ request()->is('suppliers/create') ? 'active' : '' }}">
                                                <i class="nav-icon bi bi-plus-circle"></i>
                                                <p>Add Supplier</p>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="nav-item">
                                        <a href="{{ url('/suppliers') }}"
                                            class="nav-link {{ request()->is('suppliers') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-list"></i>
                                            <p>View All Suppliers</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        {{-- Purchases --}}
                        @if (Auth::user()->hasPermission('purchases.view'))
                            <li class="nav-item {{ request()->is('purchases*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->is('purchases*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-cart-plus"></i>
                                    <p>Purchases <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @if (Auth::user()->hasPermission('purchases.manage'))
                                        <li class="nav-item">
                                            <a href="{{ url('/purchases/create') }}"
                                                class="nav-link {{ request()->is('purchases/create') ? 'active' : '' }}">
                                                <i class="nav-icon bi bi-plus-circle"></i>
                                                <p>New Purchase</p>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="nav-item">
                                        <a href="{{ url('/purchases') }}"
                                            class="nav-link {{ request()->is('purchases') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-clock-history"></i>
                                            <p>Purchase History</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        {{-- Sales / POS --}}
                        @if (Auth::user()->hasPermission('sales.view'))
                            <li class="nav-item {{ request()->is('sales*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->is('sales*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-cart-check"></i>
                                    <p>Sales / POS <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @if (Auth::user()->hasPermission('sales.create'))
                                        <li class="nav-item">
                                            <a href="{{ url('/sales/create') }}"
                                                class="nav-link {{ request()->is('sales/create') ? 'active' : '' }}">
                                                <i class="nav-icon bi bi-plus-circle"></i>
                                                <p>New Sale</p>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="nav-item">
                                        <a href="{{ url('/sales') }}"
                                            class="nav-link {{ request()->is('sales') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-clock-history"></i>
                                            <p>Sales History</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        {{-- Customer Payments --}}
                        @if (Auth::user()->hasPermission('customer-payments.view'))
                            <li class="nav-item {{ request()->is('customer-payments*') ? 'menu-open' : '' }}">
                                <a href="#"
                                    class="nav-link {{ request()->is('customer-payments*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-cash-stack"></i>
                                    <p>Customer Payments <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @if (Auth::user()->hasPermission('customer-payments.manage'))
                                        <li class="nav-item">
                                            <a href="{{ url('/customer-payments/create') }}"
                                                class="nav-link {{ request()->is('customer-payments/create') ? 'active' : '' }}">
                                                <i class="nav-icon bi bi-plus-circle"></i>
                                                <p>Record Payment</p>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="nav-item">
                                        <a href="{{ url('/customer-payments') }}"
                                            class="nav-link {{ request()->is('customer-payments') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-list"></i>
                                            <p>Payment History</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        {{-- Expenses --}}
                        @if (Auth::user()->hasPermission('expenses.view'))
                            <li class="nav-item {{ request()->is('expenses*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->is('expenses*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-wallet2"></i>
                                    <p>Expenses <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @if (Auth::user()->hasPermission('expenses.manage'))
                                        <li class="nav-item">
                                            <a href="{{ url('/expenses/create') }}"
                                                class="nav-link {{ request()->is('expenses/create') ? 'active' : '' }}">
                                                <i class="nav-icon bi bi-plus-circle"></i>
                                                <p>Add Expense</p>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="nav-item">
                                        <a href="{{ url('/expenses') }}"
                                            class="nav-link {{ request()->is('expenses') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-list"></i>
                                            <p>View All Expenses</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        {{-- Reports --}}
                        @if (Auth::user()->hasPermission('reports.view'))
                            <li class="nav-item {{ request()->is('reports*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->is('reports*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-bar-chart"></i>
                                    <p>Reports <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{ url('/reports/sales') }}"
                                            class="nav-link {{ request()->is('reports/sales') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-graph-up"></i>
                                            <p>Sales Report</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/reports/purchases') }}"
                                            class="nav-link {{ request()->is('reports/purchases') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-cart-down"></i>
                                            <p>Purchase Report</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/reports/stock') }}"
                                            class="nav-link {{ request()->is('reports/stock') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-boxes"></i>
                                            <p>Stock Report</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/reports/profit') }}"
                                            class="nav-link {{ request()->is('reports/profit') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-cash-coin"></i>
                                            <p>Profit / Loss</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ url('/reports/udhaar') }}"
                                            class="nav-link {{ request()->is('reports/udhaar*') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-person-badge"></i>
                                            <p>Udhaar Report</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        {{-- Users --}}
                        @if (Auth::user()->hasPermission('users.view'))
                            <li class="nav-item {{ request()->is('users*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-person-gear"></i>
                                    <p>Users <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @if (Auth::user()->hasPermission('users.manage'))
                                        <li class="nav-item">
                                            <a href="{{ url('/users/create') }}"
                                                class="nav-link {{ request()->is('users/create') ? 'active' : '' }}">
                                                <i class="nav-icon bi bi-plus-circle"></i>
                                                <p>Add User</p>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="nav-item">
                                        <a href="{{ url('/users') }}"
                                            class="nav-link {{ request()->is('users') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-list"></i>
                                            <p>View All Users</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        {{-- Roles & Permissions --}}
                        @if (Auth::user()->hasPermission('roles.view'))
                            <li class="nav-item {{ request()->is('roles*') ? 'menu-open' : '' }}">
                                <a href="#" class="nav-link {{ request()->is('roles*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-shield-lock"></i>
                                    <p>Roles &amp; Permissions <i class="nav-arrow bi bi-chevron-right"></i></p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @if (Auth::user()->hasPermission('roles.manage'))
                                        <li class="nav-item">
                                            <a href="{{ url('/roles/create') }}"
                                                class="nav-link {{ request()->is('roles/create') ? 'active' : '' }}">
                                                <i class="nav-icon bi bi-plus-circle"></i>
                                                <p>Add Role</p>
                                            </a>
                                        </li>
                                    @endif
                                    <li class="nav-item">
                                        <a href="{{ url('/roles') }}"
                                            class="nav-link {{ request()->is('roles') ? 'active' : '' }}">
                                            <i class="nav-icon bi bi-list"></i>
                                            <p>View All Roles</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        {{-- Settings --}}
                        @if (Auth::user()->hasPermission('settings.view'))
                            <li class="nav-item {{ request()->is('settings*') ? 'menu-open' : '' }}">
                                <a href="{{ url('/settings') }}"
                                    class="nav-link {{ request()->is('settings*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-gear"></i>
                                    <p>Settings</p>
                                </a>
                            </li>
                        @endif

                        {{-- Backups --}}
                        @if (Auth::user()->hasPermission('backup.view'))
                            <li class="nav-item {{ request()->is('backups*') ? 'menu-open' : '' }}">
                                <a href="{{ url('/backups') }}"
                                    class="nav-link {{ request()->is('backups*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-database-down"></i>
                                    <p>Backups</p>
                                </a>
                            </li>
                        @endif

                    </ul>
                </nav>
            </div>
        </aside>
        {{-- ========== /SIDEBAR ========== --}}

        {{-- ========== MAIN ========== --}}
        <main class="app-main">
            @yield('content')
        </main>

        {{-- ========== FOOTER ========== --}}
        <footer class="app-footer">
            <div class="float-end d-none d-sm-inline">POS System v1.0</div>
            <strong>&copy; {{ date('Y') }} POS System.</strong> All rights reserved.
        </footer>
        {{-- ========== /FOOTER ========== --}}

    </div>

    {{-- ========== SCRIPTS ========== --}}

    {{-- OverlayScrollbars --}}
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
        crossorigin="anonymous"></script>

    {{-- Popper --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous">
    </script>

    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>

    {{-- AdminLTE --}}
    <script src="{{ asset('adminlte/js/adminlte.js') }}"></script>

    {{-- OverlayScrollbars configure --}}
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            const isMobile = window.innerWidth <= 992;

            if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined && !isMobile) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>