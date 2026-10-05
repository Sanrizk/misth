<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Misth</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">

    {{-- Navbar --}}
    <nav class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="{{ route('store.index') }}" class="font-bold text-green-700 text-lg flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Misth
            </a>

            <div class="flex items-center gap-6">
                <a href="{{ route('store.index') }}"
                   class="text-sm text-gray-600 hover:text-green-700 {{ request()->routeIs('store.index') ? 'font-semibold text-green-700' : '' }}">
                    Toko
                </a>

                @auth
                    @if(in_array(Auth::user()->role->name, ['admin', 'petani']))
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 hover:text-green-700">
                            Dashboard
                        </a>
                    @endif
                    
                    @if(Auth::user()->role->name === 'customer')
                        <a href="{{ route('store.orders.index') }}"
                           class="text-sm text-gray-600 hover:text-green-700 {{ request()->routeIs('store.orders.*') ? 'font-semibold text-green-700' : '' }}">
                            Pesanan
                        </a>
                    @endif
                @endauth

                {{-- Cart (Show for Guest and Customer, hide for Admin/Petani) --}}
                @if(!Auth::check() || Auth::user()->role->name === 'customer')
                    @php
                        $cartCount = Auth::check() && Auth::user()->cart ? Auth::user()->cart->cartItems()->count() : 0;
                    @endphp
                    <a href="{{ route('store.cart.index') }}" class="relative p-2 text-gray-600 hover:text-green-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        @if($cartCount > 0)
                        <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold">
                            {{ $cartCount }}
                        </span>
                        @endif
                    </a>
                @endif

                {{-- User dropdown --}}
                @auth
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center gap-2 text-sm text-gray-700 focus:outline-none">
                        <div class="w-8 h-8 rounded-full bg-green-600 text-white flex items-center justify-center text-xs font-bold hover:bg-green-700 transition">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </button>
                    <div x-show="open" @click.outside="open = false"
                         class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         style="display: none;">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-green-700">Masuk</a>
                    <a href="{{ route('register') }}" class="text-sm font-semibold text-white bg-green-600 px-4 py-2 rounded-lg hover:bg-green-700">Daftar</a>
                @endguest
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-6">
        @include('layouts.partials.flash')
        @yield('content')
    </main>

    <footer class="border-t border-gray-100 mt-12 py-6 text-center text-xs text-gray-400">
        © 2026 Misth. Semua hak dilindungi.
    </footer>

    @yield('scripts')
</body>
</html>
