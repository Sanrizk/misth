<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>@yield('code', 'Error') - @yield('title', 'Terjadi Kesalahan') — Misth</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}?v=2">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-950 font-sans text-gray-900 dark:text-gray-100 antialiased min-h-screen flex flex-col items-center justify-center p-4 selection:bg-green-500 selection:text-white transition-colors duration-200">

    {{-- Background glowing accents --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-green-500/10 dark:bg-green-500/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-emerald-500/10 dark:bg-emerald-500/5 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 w-full max-w-lg text-center">
        {{-- Brand / Logo --}}
    <div class="relative z-10 w-full max-w-lg text-center">
        {{-- Brand / Logo --}}
        <div class="flex items-center justify-center gap-3 mb-8">
            <img src="{{ asset('images/logo.png') }}" alt="Misth Logo" class="w-10 h-10 rounded-full object-cover shadow-sm ring-2 ring-green-600/20">
            <span class="text-2xl font-bold bg-gradient-to-r from-green-700 to-emerald-800 dark:from-green-400 dark:to-emerald-500 bg-clip-text text-transparent">Misth</span>
        </div>

        {{-- Card Container --}}
        <div class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl border border-gray-100 dark:border-gray-800 rounded-3xl p-8 sm:p-10 shadow-xl shadow-gray-200/50 dark:shadow-none">
            {{-- Big Status Icon di Atas --}}
            <div class="relative inline-flex items-center justify-center mb-4">
                <div class="w-24 h-24 rounded-3xl @yield('badge_bg', 'bg-red-50 dark:bg-red-950/40 text-red-500 dark:text-red-400') flex items-center justify-center shadow-inner">
                    @yield('icon')
                </div>
            </div>

            {{-- Kata Error di Bawah Icon --}}
            <div class="block mb-3">
                <span class="inline-block px-3 py-1 rounded-full text-xs font-mono font-bold tracking-wider @yield('code_badge', 'bg-red-100 dark:bg-red-900/40 text-red-700 dark:text-red-300') uppercase">
                    Error @yield('code')
                </span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-800 dark:text-gray-100 mb-3 tracking-tight">
                @yield('title')
            </h1>

            <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 leading-relaxed mb-8 max-w-md mx-auto">
                @yield('message')
            </p>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ url()->previous() !== url()->current() ? url()->previous() : url('/') }}" 
                   class="w-full sm:w-auto px-6 py-2.5 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-medium text-sm rounded-xl transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>

                <a href="{{ Auth::check() ? (Auth::user()->role?->name === 'customer' ? route('store.index') : route('dashboard')) : url('/') }}" 
                   class="w-full sm:w-auto px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium text-sm rounded-xl shadow-lg shadow-green-600/25 transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Ke Halaman Utama
                </a>
            </div>
        </div>

        {{-- Footer notes --}}
        <p class="mt-8 text-xs text-gray-400 dark:text-gray-600">
            &copy; {{ date('Y') }} Misth
        </p>
    </div>

    @include('layouts.partials.dark-mode')
</body>
</html>

