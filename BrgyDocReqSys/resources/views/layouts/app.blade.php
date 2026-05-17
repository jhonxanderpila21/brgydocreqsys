<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Barangay Document Request System') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <style>
            body {
                background: #eef1f5;
                color: #212529;
            }
            .sidebar {
                min-height: 100vh;
                width: 250px;
                background: #0f1720;
                color: #ffffff;
                position: fixed;
                top: 0;
                left: 0;
                overflow-y: auto;
                z-index: 101;
            }
            .sidebar a {
                color: #ffffff !important;
            }
            .sidebar a.active,
            .sidebar a:hover {
                background: #f8f9fa;
                color: #0f1720 !important;
            }
            .main-content {
                min-height: 100vh;
                margin-left: 250px;
            }
            .sidebar-bottom {
                margin-top: auto;
                padding-top: 1rem;
                border-top: 1px solid rgba(255, 255, 255, 0.12);
            }
            @media (max-width: 991.98px) {
                .sidebar {
                    position: static;
                    width: 100%;
                }
                .main-content {
                    margin-left: 0;
                }
            }
            .sidebar .brand-title {
                font-size: 1.05rem;
                letter-spacing: .02em;
                color: #ffffff;
            }
            .sidebar .brand-logo {
                width: 45px;
                height: 45px;
                border-radius: 12px;
                background: #0ea5e9;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                font-weight: 700;
                margin-right: .75rem;
            }
            .sidebar .nav-link {
                border-radius: .75rem;
                padding: .75rem 1rem;
                display: flex;
                align-items: center;
                gap: 0.75rem;
                transition: all 0.2s ease;
            }
            .sidebar .nav-link i {
                width: 20px;
                text-align: center;
                font-size: 1.1rem;
            }
            .sidebar .nav-link.active i,
            .sidebar .nav-link:hover i {
                color: #0f1720;
            }

            .main-content {
                min-height: 100vh;
            }
            .topbar {
                background: #ffffff;
                border-bottom: 1px solid rgba(15, 23, 32, .08);
            }
            .topbar .user-label {
                font-size: .95rem;
                color: #475569;
            }

            /* Enhanced Table Styling */
            .table {
                margin-bottom: 0;
            }
            .table th,
            .table td {
                padding: 1rem 1.25rem;
                vertical-align: middle;
                border-bottom: 1px solid #e9ecef;
            }
            .table thead th {
                background-color: #f8f9fa;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.875rem;
                letter-spacing: 0.05em;
                border-bottom: 2px solid #dee2e6;
                padding-top: 1.25rem;
                padding-bottom: 1.25rem;
            }
            .table tbody tr {
                transition: all 0.2s ease;
            }
            .table tbody tr:hover {
                background-color: #f8f9fa;
                transform: translateY(-1px);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }
            .table-responsive {
                border-radius: 0.5rem;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            /* Enhanced Card Styling */
            .card {
                transition: all 0.3s ease;
                border: 1px solid #e9ecef;
            }
            .card:hover {
                transform: translateY(-4px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
                border-color: #dee2e6;
            }
            .card-body {
                padding: 1.5rem;
            }
            .card-header {
                background-color: #f8f9fa;
                border-bottom: 1px solid #e9ecef;
                padding: 1.25rem 1.5rem;
            }
        </style>
    </head>
    <body>
        <div class="d-flex">
            <aside class="sidebar p-4 d-none d-md-flex flex-column gap-4">
                <div>
                    <div class="d-flex align-items-center mb-3">
                        <div>
                            <div class="brand-title fw-bold">Barangay Document Request System</div>
                            <div class="text-white-50 small">
                                @auth
                                    {{ auth()->user()->name }}
                                @else
                                    Administrator
                                @endauth
                            </div>
                        </div>
                    </div>
                    <div class="small text-secondary mb-4">
                        @auth
                            Logged in as {{ auth()->user()->name }}
                        @else
                            Hello, Administrator
                        @endauth
                    </div>
                </div>

                <nav class="nav flex-column gap-1">
                    @php $role = auth()->user()->role ?? null; @endphp

                    <a class="nav-link{{ request()->routeIs('dashboard.*') ? ' active' : '' }}" href="{{ route('dashboard.index') }}">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>

                    @if(in_array($role, ['admin', 'staff']))
                        <a class="nav-link{{ request()->routeIs('residents.*') ? ' active' : '' }}" href="{{ route('residents.index') }}">
                            <i class="bi bi-people"></i>
                            Residents
                        </a>
                        <a class="nav-link{{ request()->routeIs('households.*') ? ' active' : '' }}" href="{{ route('households.index') }}">
                            <i class="bi bi-house"></i>
                            Households
                        </a>
                        <a class="nav-link{{ request()->routeIs('document-requests.*') ? ' active' : '' }}" href="{{ route('document-requests.index') }}">
                            <i class="bi bi-clipboard-check"></i>
                            Document Requests
                        </a>
                        <a class="nav-link{{ request()->routeIs('reports.*') ? ' active' : '' }}" href="{{ route('reports.dashboard') }}">
                            <i class="bi bi-bar-chart"></i>
                            Reports
                        </a>
                    @endif

                    @if($role === 'admin')
                        <a class="nav-link{{ request()->routeIs('document-types.*') ? ' active' : '' }}" href="{{ route('document-types.index') }}">
                            <i class="bi bi-file-earmark-text"></i>
                            Document Types
                        </a>
                    @endif
                </nav>

                <div class="sidebar-bottom">
                    @auth
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-box-arrow-right"></i>
                                Logout
                            </button>
                        </form>
                    @endauth
                </div>
            </aside>

            <main class="main-content flex-fill">
                <div class="container-fluid py-4 px-4">
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
