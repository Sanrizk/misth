@extends('layouts.app')
@section('title', 'Daftar Produk')
@section('content')

<div x-data="{ 
    showModal: false, 
    selectedProduct: null,
    openModal(product) {
        this.selectedProduct = product;
        this.showModal = true;
    }
}">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Produk</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
            @php
                $batchCode = optional(optional($product->harvest)->planting)->batch_code ?? 'Tanpa Batch';
                $productData = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => 'Rp ' . number_format($product->price, 0, ',', '.'),
                    'stock' => $product->stock,
                    'status' => $product->status == 'available' ? 'Tersedia' : 'Habis',
                    'image_url' => $product->image_url,
                    'description' => $product->description ?? 'Tidak ada deskripsi',
                    'batch_code' => $batchCode
                ];
            @endphp
            <div class="bg-white rounded-2xl shadow-sm overflow-hidden flex flex-col h-full hover:shadow-md transition">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                @else
                    <div class="bg-gradient-to-br from-green-100 to-green-200 h-48 flex items-center justify-center">
                        <svg class="w-12 h-12 text-green-500 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                @endif
                
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex justify-between items-start mb-2">
                        <h5 class="text-lg font-bold text-gray-800">{{ $product->name }}</h5>
                        <span class="px-2 py-1 rounded-lg text-[10px] font-mono font-bold bg-gray-100 text-gray-600 border border-gray-200">
                            {{ $batchCode }}
                        </span>
                    </div>
                    
                    <p class="text-xl text-green-600 font-bold mb-4">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    
                    <div class="space-y-2 mb-5">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500 font-medium">Stok Tersedia</span>
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $product->stock > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $product->stock }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500 font-medium">Status</span>
                            <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $product->status == 'available' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $product->status == 'available' ? 'Tersedia' : 'Habis' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-auto flex gap-2 pt-3 border-t border-gray-100">
                        <button type="button" @click="openModal({{ json_encode($productData) }})" class="cursor-pointer flex-1 text-center bg-sky-50 hover:bg-sky-100 text-sky-600 font-semibold py-2 rounded-xl text-sm transition">
                            Detail
                        </button>
                        <a href="{{ route('products.edit', $product->id) }}" class="flex-1 text-center bg-yellow-50 hover:bg-yellow-100 text-yellow-600 font-semibold py-2 rounded-xl text-sm transition">
                            Edit
                        </a>
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

    <!-- Product Detail Modal -->
    <template x-teleport="body">
        <div x-show="showModal" class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div x-show="showModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75 backdrop-blur-sm" @click="showModal = false"></div>

                <div x-show="showModal" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="relative w-full max-w-lg p-6 my-8 overflow-hidden text-left transition-all transform bg-white shadow-2xl rounded-2xl">
                    
                    <div class="flex justify-between items-center mb-5">
                        <h3 class="text-xl font-bold text-gray-900" x-text="selectedProduct?.name"></h3>
                        <button @click="showModal = false" class="text-gray-400 hover:text-gray-500 focus:outline-none cursor-pointer">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div class="w-full h-48 bg-gray-100 rounded-xl overflow-hidden flex items-center justify-center relative">
                            <template x-if="selectedProduct?.image_url">
                                <img :src="selectedProduct.image_url" :alt="selectedProduct.name" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!selectedProduct?.image_url">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </template>
                            
                            <div class="absolute top-2 right-2 px-2 py-1 rounded-lg text-xs font-mono font-bold bg-white text-gray-800 shadow-sm border border-gray-100" x-text="'Batch: ' + selectedProduct?.batch_code"></div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-xl space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Harga</span>
                                <span class="font-bold text-green-600 text-lg" x-text="selectedProduct?.price"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Stok</span>
                                <span class="font-bold text-gray-800" x-text="selectedProduct?.stock"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Status</span>
                                <span class="font-bold text-gray-800" x-text="selectedProduct?.status"></span>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Deskripsi Produk</h4>
                            <p class="text-sm text-gray-600 whitespace-pre-line bg-gray-50 p-4 rounded-xl border border-gray-100" x-text="selectedProduct?.description"></p>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="button" @click="showModal = false" class="cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-800 font-semibold py-2 px-6 rounded-xl text-sm transition">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
