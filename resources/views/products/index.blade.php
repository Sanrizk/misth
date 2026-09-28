@extends('layouts.app')
@section('title', 'Daftar Produk')
@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold text-gray-700">Daftar Produk</h2>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($products as $product)
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col h-full hover:shadow-md transition">
            @if(optional($product)->image_url)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-40 object-cover">
            @else
                <div class="bg-gradient-to-br from-green-100 to-green-200 h-40 flex items-center justify-center">
                    <svg class="w-12 h-12 text-green-500 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            @endif
            
            <div class="p-5 flex-1 flex flex-col">
                <h5 class="text-lg font-bold text-gray-800 mb-1">{{ $product->name }}</h5>
                <p class="text-xl text-green-600 font-bold mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                
                <div class="space-y-2 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500 w-12">Stok</span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $product->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $product->stock }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-500 w-12">Status</span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $product->status == 'available' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $product->status == 'available' ? 'Tersedia' : 'Habis' }}
                        </span>
                    </div>
                </div>
                
                <div class="mt-auto flex gap-2 pt-2 border-t border-gray-50">
                    <a href="{{ route('products.show', $product->id) }}" class="flex-1 text-center bg-sky-50 hover:bg-sky-100 text-sky-600 font-medium py-2 rounded-xl text-sm transition">Detail</a>
                    <a href="{{ route('products.edit', $product->id) }}" class="flex-1 text-center bg-yellow-50 hover:bg-yellow-100 text-yellow-600 font-medium py-2 rounded-xl text-sm transition">Edit</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-16 text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            <p class="text-sm">Tidak ada produk yang ditemukan.</p>
        </div>
    @endforelse
</div>

<div class="flex justify-end mt-6">
    {{ $products->links('pagination::tailwind') }}
</div>

@endsection
