<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Misth</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans flex flex-col min-h-screen pb-16 md:pb-0">

    {{-- Navbar --}}
    <nav class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="{{ route('store.index') }}" class="font-bold text-green-700 text-lg flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Misth
            </a>

            <div class="flex items-center gap-4 md:gap-6">
                <a href="{{ route('store.index') }}"
                   class="hidden md:block text-sm text-gray-600 hover:text-green-700 {{ request()->routeIs('store.index') ? 'font-semibold text-green-700' : '' }}">
                    Toko
                </a>

                @auth
                    @if(in_array(Auth::user()->role->name, ['admin', 'petani']))
                        <a href="{{ route('dashboard') }}" class="hidden md:block text-sm text-gray-600 hover:text-green-700">
                            Dashboard
                        </a>
                    @endif
                    
                    @if(Auth::user()->role->name === 'customer')
                        <a href="{{ route('store.orders.index') }}"
                           class="hidden md:block text-sm text-gray-600 hover:text-green-700 {{ request()->routeIs('store.orders.*') ? 'font-semibold text-green-700' : '' }}">
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
                         class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         style="display: none;">
                        
                        <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            Profil Saya
                        </a>
                        <hr class="my-1 border-gray-100">

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
                    <a href="{{ route('login') }}" class="hidden md:block text-sm font-semibold text-gray-700 hover:text-green-700">Masuk</a>
                    <a href="{{ route('register') }}" class="hidden md:block text-sm font-semibold text-white bg-green-600 px-4 py-2 rounded-lg hover:bg-green-700">Daftar</a>
                @endguest
            </div>
        </div>
    </nav>

    <main class="max-w-6xl w-full mx-auto px-4 py-6 flex-grow">
        @include('layouts.partials.flash')
        @yield('content')
    </main>

    <footer class="border-t border-gray-100 mt-12 py-6 text-center text-xs text-gray-400">
        © 2026 Misth. Semua hak dilindungi.
    </footer>

    {{-- Mobile Bottom Navigation --}}
    <div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 flex justify-around items-center h-16 pb-safe">
        <a href="{{ route('store.index') }}" class="flex flex-col items-center justify-center w-full h-full text-gray-600 {{ request()->routeIs('store.index') ? 'text-green-700' : '' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="text-[10px] font-medium">Toko</span>
        </a>

        @auth
            @if(in_array(Auth::user()->role->name, ['admin', 'petani']))
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center justify-center w-full h-full text-gray-600">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span class="text-[10px] font-medium">Dashboard</span>
                </a>
            @endif
            
            @if(Auth::user()->role->name === 'customer')
                <a href="{{ route('store.orders.index') }}" class="flex flex-col items-center justify-center w-full h-full text-gray-600 {{ request()->routeIs('store.orders.*') ? 'text-green-700' : '' }}">
                    <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    <span class="text-[10px] font-medium">Pesanan</span>
                </a>
            @endif
        @endauth

        @guest
            <a href="{{ route('login') }}" class="flex flex-col items-center justify-center w-full h-full text-gray-600">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span class="text-[10px] font-medium">Masuk</span>
            </a>
        @endguest
    </div>

    @yield('scripts')
</body>
</html>
