@extends('layouts.store')
@section('title', $product->name . ' - Toko Misth')
@section('content')

<div class="max-w-4xl mx-auto">
    <nav class="mb-6 text-sm">
        <a href="{{ route('store.index') }}" class="text-green-600 hover:underline">Toko</a>
        <span class="text-gray-400 mx-2">/</span>
        <span class="text-gray-600 font-medium">{{ $product->name }}</span>
    </nav>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2">
            <div class="h-64 md:h-auto">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" class="w-full h-full object-cover min-h-[300px]" alt="{{ $product->name }}">
                @else
                    <div class="bg-gradient-to-br from-green-100 to-green-200 w-full h-full min-h-[300px] flex items-center justify-center">
                        <svg class="w-24 h-24 text-green-600 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
            </div>
            
            <div class="p-6 md:p-8 flex flex-col">
                <div class="flex items-center gap-2 mb-3">
                    <span class="px-3 py-1 bg-green-50 text-green-700 border border-green-200 rounded-full text-xs font-medium">
                        Grade {{ optional($product->harvest)->quality_grade ?? 'A' }}
                    </span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg>
                        {{ optional(optional($product->harvest)->planting)->plantType->name ?? 'Sayuran Hidroponik' }}
                    </span>
                </div>
                
                <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $product->name }}</h1>
                <h2 class="text-3xl font-bold text-green-600 mb-6">Rp {{ number_format($product->price, 0, ',', '.') }} <span class="text-xl font-normal text-gray-500">/ {{ optional($product->harvest)->unit ?? 'pcs' }}</span></h2>
                
                <div class="mb-6">
                    <h5 class="text-sm font-semibold text-gray-800 mb-2">Deskripsi Produk</h5>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $product->description ?? 'Sayuran hidroponik segar berkualitas tinggi, dipanen hari ini dari kebun greenhouse modern kami. Bebas pestisida dan kaya akan nutrisi.' }}</p>
                </div>

                <div class="mb-6 p-4 bg-gray-50 rounded-xl flex items-center gap-6">
                    <div>
                        <span class="block text-xs text-gray-500 mb-1">Stok Tersedia</span>
                        <span class="text-xl font-bold text-gray-800">{{ $product->stock }} <span class="text-sm font-normal text-gray-500">{{ optional($product->harvest)->unit ?? 'pcs' }}</span></span>
                    </div>
                    <div class="border-l border-gray-200 pl-6">
                        <span class="block text-xs text-gray-500 mb-1">Status</span>
                        @if($product->stock > 0)
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium">Tersedia</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-medium">Habis</span>
                        @endif
                    </div>
                </div>

                <div class="mt-auto">
                    @if(!Auth::check() || Auth::user()->role->name === 'customer')
                        @if($product->stock > 0)
                            <form action="{{ route('store.cart.add') }}" method="POST" class="flex gap-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <div class="w-24">
                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Jumlah</label>
                                    <input type="number" name="quantity" class="w-full px-3 py-3 border border-gray-200 rounded-xl text-center focus:outline-none focus:ring-2 focus:ring-green-500" value="1" min="1" max="{{ $product->stock }}" required>
                                </div>
                                <div class="flex-1 flex items-end">
                                    <button type="submit" class="w-full h-12 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl transition flex justify-center items-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        Tambah ke Keranjang
                                    </button>
                                </div>
                            </form>
                        @else
                            <button class="w-full h-12 bg-gray-300 text-gray-500 font-bold rounded-xl cursor-not-allowed" disabled>
                                Stok Habis
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
