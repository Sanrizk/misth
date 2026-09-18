<nav class="navbar top-navbar fixed-top px-3 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
        <button class="btn btn-outline-secondary d-lg-none me-3" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <h4 class="mb-0 fw-bold">@yield('title', 'Dashboard')</h4>
    </div>
    
    <div class="d-flex align-items-center">
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle d-flex align-items-center border-0 bg-transparent" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                @php
                    $initials = collect(explode(' ', Auth::user()->name ?? 'U S'))->map(fn($n) => $n[0])->take(2)->join('');
                @endphp
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px; font-weight: bold;">
                    {{ strtoupper($initials) }}
                </div>
                <span class="fw-medium">{{ Auth::user()->name ?? 'User' }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

