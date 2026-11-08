<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Misth</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}?v=2">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="min-h-screen flex bg-white dark:bg-gray-900 transition-colors" x-data="{ showAlert: false }">

    {{-- LEFT SIDE: Photo Slideshow --}}
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden"
         x-data="{ current: 0, photos: [
             'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
             'https://images.unsplash.com/photo-1530836369250-ef72a3f5cda8?w=800',
             'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=800',
             'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=800',
             'https://images.unsplash.com/photo-1591086002523-97b76c82ffc2?w=800'
         ]}"
         x-init="setInterval(() => current = (current + 1) % photos.length, 4000)">

        {{-- Photos --}}
        <template x-for="(photo, index) in photos" :key="index">
            <div x-show="current === index"
                 x-transition:enter="transition-opacity duration-1000"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity duration-1000"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 bg-cover bg-center"
                 :style="`background-image: url('${photo}')`">
            </div>
        </template>

        {{-- Green Overlay --}}
        <div class="absolute inset-0" style="background: rgba(20, 83, 45, 0.65);"></div>

        {{-- Content over overlay --}}
        <div class="relative z-10 flex flex-col justify-between p-10 w-full">

            {{-- Logo --}}
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Misth Logo" class="w-10 h-10 rounded-full object-cover shadow-sm ring-2 ring-white/30">
                <span class="text-white font-bold text-2xl tracking-wide">Misth</span>
            </div>

            {{-- Quote --}}
            <div>
                <blockquote class="text-white">
                    <p class="text-2xl font-bold leading-snug mb-4">
                        "Dari kebun hidroponik kami, langsung ke meja makanmu."
                    </p>
                    <p class="text-green-200 text-sm">
                        Sayuran segar berkualitas tinggi, dipanen setiap hari untuk keluarga Indonesia.
                    </p>
                </blockquote>

                {{-- Slideshow dots --}}
                <div class="flex gap-2 mt-8">
                    <template x-for="(photo, index) in photos" :key="index">
                        <button @click="current = index"
                                :class="current === index ? 'bg-white w-6' : 'bg-white/40 w-2'"
                                class="h-2 rounded-full transition-all duration-300 cursor-pointer">
                        </button>
                    </template>
                </div>
            </div>

        </div>
    </div>

    {{-- RIGHT SIDE: Login Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 dark:bg-gray-900 p-6 transition-colors">
        <div class="w-full max-w-sm">

            {{-- Logo Header above form (visible on both desktop & mobile) --}}
            <div class="text-center mb-6">
                <a href="/" class="inline-block group">
                    <img src="{{ asset('images/logo.png') }}" alt="Misth Logo" class="w-20 h-20 rounded-full mx-auto mb-2.5 shadow-md object-cover ring-4 ring-green-600/20 group-hover:scale-105 transition-transform duration-200">
                </a>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white tracking-tight">Misth</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Sistem Monitoring & Inventori Hidroponik</p>
            </div>

            {{-- Form Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-8 border border-gray-100 dark:border-gray-700 transition-colors">

                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white">Selamat Datang</h2>
                    <p class="text-sm text-gray-400 dark:text-gray-400 mt-1">Masuk ke akun Misth Anda</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="email@misth.my.id"
                               class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white border-gray-200 dark:border-gray-600 transition-colors
                                      {{ $errors->has('email') ? 'border-red-400' : '' }}">
                        @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Password</label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'"
                                   name="password"
                                   placeholder="••••••••"
                                   class="w-full px-4 py-2.5 border border-gray-200 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 pr-10 transition-colors">
                            <button type="button" @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 cursor-pointer">
                                <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 4.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-xl text-sm transition shadow-sm cursor-pointer">
                        Masuk
                    </button>

                    <div class="flex items-center justify-between text-xs pt-1">
                        <span class="text-gray-500 dark:text-gray-400">Belum punya akun?</span>
                        <button type="button"
                                @click="showAlert = true"
                                class="font-semibold text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 hover:underline transition cursor-pointer">
                            Daftar
                        </button>
                    </div>

                </form>

            </div>

            <p class="text-center text-xs text-gray-400 dark:text-gray-500 mt-4">© 2026 Misth. Semua hak dilindungi.</p>

        </div>
    </div>

    {{-- Modal Alert Hubungi Admin --}}
    <div x-cloak
         x-show="showAlert"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title"
         role="dialog"
         aria-modal="true">
        
        {{-- Backdrop --}}
        <div x-show="showAlert"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"
             @click="showAlert = false"></div>

        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div x-show="showAlert"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-gray-800 p-6 sm:p-7 text-center shadow-2xl transition-all my-8 w-full max-w-sm border border-gray-100 dark:border-gray-700">
                
                {{-- Decorative Icon --}}
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-tr from-green-500/20 to-emerald-500/10 text-green-600 dark:text-green-400 ring-8 ring-green-50 dark:ring-green-950/40 mb-4">
                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.199l-.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>

                {{-- Title & Message --}}
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2" id="modal-title">
                    Pendaftaran Akun
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-6">
                    Silakan hubungi <span class="font-semibold text-green-600 dark:text-green-400">admin</span> untuk mendaftar akun dan mendapatkan hak akses ke dalam sistem.
                </p>

                {{-- Action Button --}}
                <button type="button"
                        @click="showAlert = false"
                        class="w-full inline-flex justify-center items-center py-2.5 px-4 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl shadow-md shadow-green-600/20 transition-all focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 cursor-pointer">
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    {{-- Floating Dark Mode Button --}}
    @include('layouts.partials.dark-mode', ['position' => 'fixed bottom-6 right-6 z-50'])

</body>
</html>
