<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko Misth')</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary: #2d8653;
            --primary-dark: #1a4731;
            --primary-light: #4caf50;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar-brand {
            color: var(--primary-dark) !important;
        }

        .text-primary { color: var(--primary) !important; }
        .bg-primary { background-color: var(--primary) !important; }
        .btn-primary { 
            background-color: var(--primary); 
            border-color: var(--primary); 
        }
        .btn-primary:hover { 
            background-color: var(--primary-dark); 
            border-color: var(--primary-dark); 
        }
        
        main { flex-grow: 1; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="{{ route('store.index') }}">
                <i class="bi bi-flower2 me-2 text-success"></i> Misth
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-4">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('store.index') ? 'active fw-bold text-success' : '' }}" href="{{ route('store.index') }}">Toko</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('store.orders.*') ? 'active fw-bold text-success' : '' }}" href="{{ route('store.orders.index') }}">Pesanan</a>
                    </li>
                </ul>
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item me-3">
                        @php
                            $cartCount = Auth::check()
                                ? optional(Auth::user()->cart)->cartItems()->count() ?? 0
                                : 0;
                        @endphp
                        <a href="{{ route('store.cart.index') }}" class="nav-link position-relative">
                            <i class="bi bi-cart3 fs-5"></i>
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-medium" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name ?? 'Customer' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                            <li><a class="dropdown-item" href="{{ route('store.orders.index') }}">Riwayat Pesanan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-5">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="py-4 text-center text-white mt-auto" style="background-color: #1a1a2e;">
        <div class="container">
            <p class="mb-1">© 2026 Misth. Semua hak dilindungi.</p>
            <p class="text-muted small mb-0">Dari kebun hidroponik kami, langsung ke meja makanmu. <i class="bi bi-flower2 fs-6 ms-1"></i></p>
        </div>
    </footer>


    @yield('scripts')
</body>
</html>
