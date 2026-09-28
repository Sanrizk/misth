<!DOCTYPE html>
<html lang="id" style="scroll-behavior: smooth;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Misth — Hidroponik Segar</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-800">

    {{-- Navbar --}}
    <nav x-data="{ scrolled: false, atTop: true }"
         @scroll.window="atTop = window.scrollY < 50"
         :class="atTop ? 'bg-transparent py-4' : 'bg-white shadow-md py-2'"
         class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="max-w-6xl mx-auto px-6 h-12 flex items-center justify-between">
            <a href="/" :class="atTop ? 'text-white' : 'text-green-700'" class="font-bold text-xl transition flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Misth
            </a>
            
            <div class="flex items-center gap-4">
                <a href="#produk" :class="atTop ? 'text-white/80 hover:text-white' : 'text-gray-500 hover:text-green-700'" class="text-sm font-medium transition hidden sm:block">Produk</a>
                @auth
                <a href="{{ route('store.index') }}"
                   :class="atTop ? 'border-white text-white hover:bg-white hover:text-green-700' : 'border-green-600 bg-green-600 text-white hover:bg-green-700 hover:border-green-700'"
                   class="border text-sm font-semibold px-5 py-2 rounded-xl transition">
                    Ke Toko
                </a>
                @else
                <a href="{{ route('login') }}"
                   :class="atTop ? 'border-white text-white hover:bg-white hover:text-green-700' : 'border-green-600 text-green-600 hover:bg-green-600 hover:text-white'"
                   class="border border-solid text-sm font-semibold px-5 py-2 rounded-xl transition">
                    Masuk
                </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="min-h-screen bg-gradient-to-br from-green-900 via-green-700 to-green-500 flex items-center justify-center relative overflow-hidden">
        {{-- Background decoration --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-20 w-64 h-64 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-20 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        </div>
        <div class="relative text-center text-white max-w-3xl mx-auto px-6 pt-16">
            <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur px-4 py-1.5 rounded-full text-sm font-medium mb-6 border border-white/20">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                100% Hidroponik Segar
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold leading-tight mb-6">
                Sayuran Hidroponik Segar, Langsung dari Kebun ke Mejamu
            </h1>
            <p class="text-green-50 text-lg mb-10 max-w-xl mx-auto leading-relaxed">
                Nikmati sayuran berkualitas tinggi yang ditanam dengan teknologi hidroponik modern. Bebas pestisida berbahaya, kaya nutrisi, dan dipanen segar setiap hari.
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <a href="#produk"
                   class="bg-white text-green-700 font-bold px-8 py-3.5 rounded-xl hover:bg-green-50 transition shadow-lg shadow-green-900/20">
                    Lihat Produk Kami
                </a>
                @auth
                <a href="{{ route('store.index') }}"
                   class="border-2 border-white text-white font-bold px-8 py-3.5 rounded-xl hover:bg-white/10 transition">
                    Ke Toko
                </a>
                @else
                <a href="{{ route('login') }}"
                   class="border-2 border-white text-white font-bold px-8 py-3.5 rounded-xl hover:bg-white/10 transition">
                    Masuk ke Akun
                </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- Keunggulan Section --}}
    <section class="py-20 bg-white">
        <div class="max-w-5xl mx-auto px-6">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Mengapa Misth?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-2xl hover:shadow-lg hover:-translate-y-1 transition duration-300 bg-gray-50/50 border border-gray-100">
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg mb-2">Hidroponik Modern</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Ditanam tanpa tanah menggunakan sistem NFT & DFT yang memastikan nutrisi terserap optimal.</p>
                </div>
                
                <div class="text-center p-6 rounded-2xl hover:shadow-lg hover:-translate-y-1 transition duration-300 bg-gray-50/50 border border-gray-100">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.956 11.956 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg mb-2">Bebas Pestisida</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Dirawat di dalam greenhouse tertutup tanpa menggunakan pestisida berbahaya, aman untuk keluarga.</p>
                </div>

                <div class="text-center p-6 rounded-2xl hover:shadow-lg hover:-translate-y-1 transition duration-300 bg-gray-50/50 border border-gray-100">
                    <div class="w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg mb-2">Dipanen Segar</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Pesanan Anda adalah prioritas kami. Sayuran dipanen di hari yang sama saat pengiriman dilakukan.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Produk Section --}}
    <section id="produk" class="py-20 bg-gray-50">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800 mb-3">Produk Segar Kami</h2>
                <p class="text-gray-500">Pilih dari berbagai sayuran segar hasil panen hidroponik kami hari ini.</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($products as $product)
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:-translate-y-1 hover:shadow-md transition duration-200 flex flex-col h-full">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-44 object-cover">
                    @else
                        <div class="bg-gradient-to-br from-green-100 to-green-200 h-44 w-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-green-600 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                    
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-2 gap-2">
                            <h5 class="font-bold text-gray-800 leading-tight">{{ $product->name }}</h5>
                            <span class="px-2 py-0.5 bg-green-50 text-green-700 border border-green-200 rounded-full text-xs font-medium whitespace-nowrap">
                                Grade {{ optional($product->harvest)->quality_grade ?? 'A' }}
                            </span>
                        </div>
                        
                        <p class="text-xs text-gray-500 mb-4 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg>
                            {{ optional(optional($product->harvest)->planting)->plantType->name ?? 'Sayuran Hidroponik' }}
                        </p>
                        
                        <div class="mt-auto">
                            <h4 class="text-xl font-bold text-green-700 mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</h4>
                            
                            <a href="{{ route('store.product.show', $product->id) }}" class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold py-2.5 rounded-xl transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-16 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    <p class="text-sm font-medium">Produk segera hadir.</p>
                </div>
                @endforelse
            </div>
            
            @if($products->count() > 0)
            <div class="text-center mt-12">
                <a href="{{ route('store.index') }}" class="inline-flex items-center gap-2 text-green-600 hover:text-green-700 font-semibold transition">
                    Lihat Semua Produk
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
            @endif
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-24 bg-gradient-to-br from-green-900 to-green-600 text-white text-center">
        <div class="max-w-xl mx-auto px-6">
            <h2 class="text-3xl md:text-4xl font-bold mb-6">Siap Memesan Sayuran Segar?</h2>
            <p class="text-green-100 text-lg mb-10 leading-relaxed">Masuk ke akun Anda dan mulai berbelanja sayuran hidroponik berkualitas langsung dari aplikasi kami.</p>
            @auth
            <a href="{{ route('store.index') }}"
               class="bg-white text-green-700 font-bold px-8 py-4 rounded-xl hover:bg-green-50 transition inline-block shadow-lg">
                Ke Toko Sekarang
            </a>
            @else
            <a href="{{ route('login') }}"
               class="bg-white text-green-700 font-bold px-8 py-4 rounded-xl hover:bg-green-50 transition inline-block shadow-lg">
                Masuk Sekarang
            </a>
            @endauth
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-gray-400 text-center py-10 text-sm">
        <div class="max-w-4xl mx-auto px-6">
            <div class="flex items-center justify-center gap-2 mb-4 text-white opacity-80">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="font-bold text-lg">Misth</span>
            </div>
            <p>© 2026 Misth. Semua hak dilindungi.</p>
            <p class="mt-2 text-xs opacity-75">Dari kebun hidroponik kami, langsung ke meja makanmu.</p>
        </div>
    </footer>

</body>
</html>
