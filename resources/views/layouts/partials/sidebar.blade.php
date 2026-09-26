<div class="sidebar d-flex flex-column" id="sidebar">
    <div class="p-3 text-white text-center fs-4 fw-bold border-bottom border-secondary">
        <i class="bi bi-tree-fill text-success"></i> Misth
    </div>
    
    @php
        $role = Auth::user()->role->name ?? '';
    @endphp

    <ul class="nav flex-column mt-3 flex-grow-1">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        @if($role === 'admin' || $role === 'petani')
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('plant-types.*') ? 'active' : '' }}" href="{{ route('plant-types.index') }}">
                <i class="bi bi-flower1 me-2"></i> Jenis Tanaman
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('plantings.*') ? 'active' : '' }}" href="{{ route('plantings.index') }}">
                <i class="bi bi-tree me-2"></i> Penanaman
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('maintenance-logs.*') ? 'active' : '' }}" href="{{ route('maintenance-logs.index') }}">
                <i class="bi bi-clipboard-check me-2"></i> Perawatan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('water-quality-logs.*') ? 'active' : '' }}" href="{{ route('water-quality-logs.index') }}">
                <i class="bi bi-droplet-half me-2"></i> Kualitas Air
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('harvests.*') ? 'active' : '' }}" href="{{ route('harvests.index') }}">
                <i class="bi bi-basket me-2"></i> Panen
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('materials.*') ? 'active' : '' }}" href="{{ route('materials.index') }}">
                <i class="bi bi-box-seam me-2"></i> Bahan
            </a>
        </li>
        @endif

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                <i class="bi bi-shop me-2"></i> Produk
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}" href="{{ route('transactions.index') }}">
                <i class="bi bi-receipt me-2"></i> Transaksi
            </a>
        </li>
    </ul>
</div>
