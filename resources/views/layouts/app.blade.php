<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>@yield('title') — Misth</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body class="bg-gray-100 font-sans" x-data="{ sidebarOpen: false }">

    {{-- Sidebar Overlay (mobile) --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/50 z-20 lg:hidden"
         x-transition></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed top-0 left-0 h-full w-64 bg-green-900 text-white z-30
                  transition-transform duration-300 lg:translate-x-0">
        @include('layouts.partials.sidebar')
    </aside>

    {{-- Main Wrapper --}}
    <div class="lg:ml-64 min-h-screen flex flex-col">

        {{-- Navbar --}}
        <header class="bg-white shadow-sm sticky top-0 z-10">
            @include('layouts.partials.navbar')
        </header>

        {{-- Content --}}
        <main class="flex-1 p-6">
            @include('layouts.partials.flash')
            @yield('content')
        </main>

    </div>

    @yield('scripts')
</body>
</html>
