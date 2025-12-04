<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Perpustakaan</title>
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --sidebar-bg: #1e2124;
            --sidebar-hover: #2c3034;
            --sidebar-active: #0d6efd;
            --header-height: 60px;
        }

        body {
            font-family: 'Source Sans Pro', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            min-height: 100vh;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        .main-sidebar {
            background-color: var(--sidebar-bg);
            color: white;
            width: 250px;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            transition: all 0.3s;
            z-index: 1000;
        }

        .content-wrapper {
            margin-left: 250px;
            width: calc(100% - 250px);
            min-height: 100vh;
            background: #f8f9fa;
            padding: 20px;
        }

        .main-header {
            background: white;
            border-bottom: 1px solid #dee2e6;
            height: var(--header-height);
            position: fixed;
            top: 0;
            right: 0;
            left: 250px;
            z-index: 1000;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }

        .nav-sidebar {
            padding: 0;
            margin-top: 20px;
        }

        .nav-sidebar .nav-item {
            margin: 4px 8px;
        }

        .nav-sidebar .nav-link {
            color: #fff;
            padding: 12px 16px;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .nav-sidebar .nav-link:hover {
            background-color: var(--sidebar-hover);
        }

        .nav-sidebar .nav-link.active {
            background-color: var(--sidebar-active);
        }

        .nav-sidebar .nav-icon {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid #eee;
            padding: 15px 20px;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            padding: 8px 16px;
            border-radius: 6px;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            border-top: none;
            background-color: #f8f9fa;
        }

        .btn-edit {
            background-color: #ffc107;
            color: #000;
        }

        .btn-hapus {
            background-color: #dc3545;
            color: #fff;
        }

        .main-content {
            padding-top: calc(var(--header-height) + 20px);
        }

        .content-header {
            margin-bottom: 20px;
        }

        .content-header h1 {
            font-size: 24px;
            margin: 0;
        }

        /* Welcome Message Styles */
        .display-4 {
            font-weight: 300;
            color: #2c3034;
        }

        .lead {
            font-size: 1.25rem;
            font-weight: 300;
        }

        hr.my-4 {
            border-color: rgba(0,0,0,0.1);
            margin: 2rem auto;
            width: 60%;
        }

        .main-footer {
            background-color: #fff;
            border-top: 1px solid #dee2e6;
            padding: 1rem;
        }

        .alert {
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .alert-dismissible {
            animation: fadeOut 5s forwards;
            animation-delay: 3s;
        }

        @keyframes fadeOut {
            from {opacity: 1;}
            to {opacity: 0; display: none;}
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <aside class="main-sidebar">
        <nav class="nav-sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ url('anggota') }}" class="nav-link {{ request()->is('anggota*') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-users"></i>
                        <span>Anggota</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('detail_peminjaman') }}" class="nav-link {{ request()->is('detail_peminjaman*') ? 'active' : '' }}">
                        <i class="nav-icon fa-solid fa-file-lines"></i>
                        <span>Detail Peminjaman</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <div class="content-wrapper">
        <header class="main-header">
            <button class="btn btn-link text-dark" type="button">
                <i class="fas fa-bars"></i>
            </button>
        </header>

        <div class="main-content">
            <!-- Welcome Message -->
            @if(Route::currentRouteName() === null || Route::currentRouteName() === 'dashboard')
            <div class="text-center py-5">
                <h1 class="display-4 mb-4">Welcome to Library System</h1>
                <p class="lead text-muted">Manage your library data efficiently and effectively</p>
                <hr class="my-4">
            </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">
                Sistem Perpustakaan
            </div>
            <strong>Copyright &copy; {{ date('Y') }}</strong> All rights reserved.
        </footer>
    </div>
</div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS -->
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>