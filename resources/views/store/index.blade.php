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

@if($popularProducts->count() > 0)
    <div class="mb-10" x-data="{ search: '' }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-4">
            <div class="flex items-center gap-3">
                <h3 class="text-xl font-bold text-gray-800">Rutin Dipesan</h3>
                <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full font-semibold">Populer</span>
            </div>
            <div class="w-full sm:w-auto">
                <input type="text" x-model="search" placeholder="Cari produk populer..." 
                       class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 w-full sm:w-64">
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($popularProducts as $product)
                <div x-show="search === '' || '{{ strtolower(addslashes($product->name)) }}'.includes(search.toLowerCase())">
                    @include('store.partials.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>
    </div>
@endif

@if($upcomingPlantings->count() > 0)
    <div class="mb-10" x-data="{ search: '' }">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-4">
            <div class="flex items-center gap-3">
                <h3 class="text-xl font-bold text-gray-800">Segera Panen</h3>
                <span class="text-xs bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full font-semibold">Pre-order / Info</span>
            </div>
            <div class="w-full sm:w-auto">
                <input type="text" x-model="search" placeholder="Cari tanaman..." 
                       class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 w-full sm:w-64">
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($upcomingPlantings as $planting)
                <div x-show="search === '' || '{{ strtolower(addslashes($planting->plantType->name ?? 'sayuran')) }}'.includes(search.toLowerCase())"
                     class="bg-gradient-to-b from-yellow-50 to-white rounded-2xl p-5 border border-yellow-100 flex flex-col justify-center items-center text-center shadow-sm">
                    <svg class="w-12 h-12 text-yellow-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <h5 class="font-bold text-gray-800">{{ $planting->plantType->name ?? 'Sayuran' }}</h5>
                    <p class="text-xs text-gray-500 mt-2 mb-3">Estimasi panen dalam beberapa hari lagi.</p>
                    <span class="px-3 py-1 bg-yellow-200 text-yellow-800 text-xs font-bold rounded-full">Progress {{ $planting->progress_percentage }}%</span>
                </div>
            @endforeach
        </div>
    </div>
@endif

<div class="mb-4">
    <h3 class="text-xl font-bold text-gray-800">Semua Produk</h3>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    @forelse($products as $product)
        @include('store.partials.product-card', ['product' => $product])
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
