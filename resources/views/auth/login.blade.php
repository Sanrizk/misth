<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Misth</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="min-h-screen flex">

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
            <div>
                <span class="text-white font-bold text-2xl"><i class="bi bi-flower1"></i> Misth</span>
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
                                class="h-2 rounded-full transition-all duration-300">
                        </button>
                    </template>
                </div>
            </div>

        </div>
    </div>

    {{-- RIGHT SIDE: Login Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 p-6">
        <div class="w-full max-w-sm">

            {{-- Mobile logo (only visible on mobile) --}}
            <div class="text-center mb-8 lg:hidden">
                <div class="w-14 h-14 bg-green-600 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                </div>
                <h1 class="text-xl font-bold text-gray-800">Misth</h1>
            </div>

            {{-- Form Card --}}
            <div class="bg-white rounded-2xl shadow-sm p-8">

                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Selamat Datang</h2>
                    <p class="text-sm text-gray-400 mt-1">Masuk ke akun Misth Anda</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="email@misth.com"
                               class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500
                                      {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }}">
                        @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'"
                                   name="password"
                                   placeholder="••••••••"
                                   class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 pr-10">
                            <button type="button" @click="show = !show"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
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
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-xl text-sm transition">
                        Masuk
                    </button>

                </form>

                <p class="text-center text-xs text-gray-400 mt-5">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-green-600 font-medium hover:underline">Daftar sekarang</a>
                </p>

            </div>

            <p class="text-center text-xs text-gray-400 mt-4">© 2026 Misth. Semua hak dilindungi.</p>

        </div>
    </div>

</body>
</html>
