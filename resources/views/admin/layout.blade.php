<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель | ONEONE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background: #212529; }
        .sidebar .nav-link { color: rgba(255,255,255,.7); padding: 12px 20px; font-size: 15px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: rgba(255,255,255,.1); }
        .sidebar .nav-link i { margin-right: 10px; }
        .main-content { padding: 30px; }
        .card-stat { border-left: 4px solid; }
        .card-stat.products { border-left-color: #0d6efd; }
        .card-stat.orders { border-left-color: #198754; }
        .card-stat.users { border-left-color: #ffc107; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Боковое меню -->
        <div class="col-md-2 sidebar d-flex flex-column p-0">
            <div class="text-center py-4">
                <h4 class="text-white mb-0">ONEONE</h4>
                <small class="text-white-50">Админ-панель</small>
            </div>
            <hr class="text-white-50 mx-3">
            <nav class="flex-grow-1">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Дашборд
                </a>
                <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam"></i> Товары
                </a>
                <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                    <i class="bi bi-cart3"></i> Заказы
                </a>
                <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i> Категории
                </a>
            </nav>
            <hr class="text-white-50 mx-3">
            <div class="px-3 pb-3">
                <a href="{{ route('home') }}" class="nav-link" target="_blank">
                    <i class="bi bi-shop"></i> На сайт
                </a>
                <a href="{{ route('logout') }}" class="nav-link text-danger">
                    <i class="bi bi-box-arrow-right"></i> Выйти
                </a>
            </div>
        </div>

        <!-- Основной контент -->
        <div class="col-md-10 main-content">
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
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>