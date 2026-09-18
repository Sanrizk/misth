<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hydroponic Farm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar { height: 100vh; position: fixed; top: 0; left: 0; width: 250px; padding-top: 56px; background-color: #343a40; }
        .sidebar .nav-link { color: #c2c7d0; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background-color: #495057; }
        .main-content { margin-left: 250px; padding-top: 76px; padding-bottom: 20px; }
        .navbar { z-index: 1050; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') ?? '#' }}">Farm System</a>
            <div class="d-flex ms-auto align-items-center">
                <span class="text-white me-3">Welcome, {{ auth()->user()->name ?? 'User' }}</span>
                <form action="{{ route('logout') ?? '#' }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="sidebar">
        <ul class="nav flex-column mt-3">
            <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') ?? '#' }}">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('plant-types.index') ?? '#' }}">Jenis Tanaman</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('plantings.index') ?? '#' }}">Penanaman</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('maintenance-logs.index') ?? '#' }}">Perawatan</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('water-quality-logs.index') ?? '#' }}">Kualitas Air</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('harvests.index') ?? '#' }}">Panen</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('products.index') ?? '#' }}">Produk</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('transactions.index') ?? '#' }}">Transaksi</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="container-fluid">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

