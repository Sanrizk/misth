<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Misth</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-green-900 to-green-600 flex items-center justify-center p-4">

    <div class="w-full max-w-sm bg-white rounded-2xl shadow-xl p-8">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-green-600 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Misth</h1>
            <p class="text-sm text-gray-500 mt-1">Masuk ke akun Anda</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500
                              {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }}"
                       placeholder="email@misth.com" required>
                @error('email')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password"
                       class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm
                              focus:outline-none focus:ring-2 focus:ring-green-500"
                       placeholder="••••••••" required>
            </div>

            <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold
                           py-2.5 rounded-xl text-sm transition">
                Masuk
            </button>
        </form>

        {{-- Tambahkan setelah tombol submit --}}
        <p class="text-center text-xs text-gray-400 mt-5">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-green-600 font-medium hover:underline">Daftar sekarang</a>
        </p>

    </div>

</body>
</html>
