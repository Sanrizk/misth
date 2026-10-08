<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — Misth</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('styles')
</head>
<body class="bg-gray-100 font-sans text-gray-900 antialiased" x-data="{}">

    {{-- Desktop Sidebar --}}
    <aside class="hidden lg:block fixed top-0 left-0 h-full w-64 bg-green-900 text-white z-30">
        @include('layouts.partials.sidebar')
    </aside>

    {{-- Main Wrapper --}}
    <div class="lg:ml-64 min-h-screen flex flex-col">

        {{-- Navbar --}}
        <header class="bg-white shadow-sm sticky top-0 z-10">
            @include('layouts.partials.navbar')
        </header>

        {{-- Content --}}
        <main class="flex-1 p-4 lg:p-6 pb-24 lg:pb-6">
            @include('layouts.partials.flash')
            @include('layouts.partials.confirm-dialog')
            @yield('content')
        </main>

    </div>

    {{-- Mobile Bottom Navbar --}}
    @include('layouts.partials.bottom-nav')

    @yield('scripts')
</body>
</html>