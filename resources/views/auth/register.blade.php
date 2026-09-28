<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — Misth</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-green-900 to-green-600 flex items-center justify-center p-4">

    <div class="w-full max-w-sm bg-white rounded-2xl shadow-xl p-8">

        {{-- Logo --}}
        <div class="text-center mb-6">
            <div class="w-14 h-14 bg-green-600 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
            </div>
            <h1 class="text-xl font-bold text-gray-800">Buat Akun Baru</h1>
            <p class="text-xs text-gray-400 mt-1">Daftar sebagai customer Misth</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('register.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Nama --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       placeholder="Nama lengkap Anda"
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500
                              {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200' }}">
                @error('name')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
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

            {{-- Phone --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                       placeholder="08xxxxxxxxxx"
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500
                              {{ $errors->has('phone') ? 'border-red-400' : 'border-gray-200' }}">
                @error('phone')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password"
                       placeholder="Minimal 8 karakter"
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500
                              {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }}">
                @error('password')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <input type="password" name="password_confirmation"
                       placeholder="Ulangi password"
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-xl text-sm transition">
                Daftar Sekarang
            </button>

        </form>

        {{-- Link ke login --}}
        <p class="text-center text-xs text-gray-400 mt-5">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-green-600 font-medium hover:underline">Masuk di sini</a>
        </p>

    </div>

</body>
</html>
