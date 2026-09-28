@extends('layouts.store')
@section('title', 'Toko Misth')
@section('content')

<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Produk Segar Kami</h2>
        <p class="text-sm text-gray-500">Hasil panen hidroponik terbaik hari ini</p>
    </div>
    <form action="{{ route('store.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari sayuran..." 
               class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 w-full sm:w-48">
        <select name="category" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 w-full sm:w-48">
            <option value="">Semua Kategori</option>
            @foreach($plantTypes as $type)
                <option value="{{ $type->name }}" {{ request('category') == $type->name ? 'selected' : '' }}>
                    {{ $type->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl transition flex items-center justify-center">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </button>
    </form>
</div>

<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
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
                    <h4 class="text-xl font-bold text-green-700 mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</h4>
                    <div class="mb-3">
                        <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-medium">Sisa: {{ $product->stock }}</span>
                    </div>
                    
                    <div class="flex flex-col gap-2">
                        <form action="{{ route('store.cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white text-sm font-semibold py-2 rounded-xl transition flex justify-center items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Keranjang
                            </button>
                        </form>
                        <a href="{{ route('store.product.show', $product->id) }}" class="w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold py-2 rounded-xl transition">
                            Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-20 text-gray-400">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            <h4 class="text-lg font-bold text-gray-600 mb-1">Produk Tidak Ditemukan</h4>
            <p class="text-sm">Coba ubah kata kunci pencarian atau kategori Anda.</p>
        </div>
    @endforelse
</div>

<div class="mt-8 flex justify-center">
    {{ $products->appends(request()->query())->links('pagination::tailwind') }}
</div>
@endsection
