@extends('layouts.store')
@section('title', 'Keranjang Belanja - Toko Misth')
@section('content')

<h2 class="text-2xl font-bold text-gray-800 mb-6">Keranjang Belanja</h2>

@if($cart && $cart->cartItems->count() > 0)
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase font-medium">
                        <tr>
                            <th class="py-4 px-6">Produk</th>
                            <th class="py-4 px-6">Harga Satuan</th>
                            <th class="py-4 px-6 w-32">Kuantitas</th>
                            <th class="py-4 px-6">Subtotal</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-gray-700">
                        @foreach($cart->cartItems as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4">
                                    @if(optional($item->product)->image_url)
                                        <img src="{{ optional($item->product)->image_url }}" alt="{{ optional($item->product)->name }}" class="w-16 h-16 rounded-lg object-cover">
                                    @else
                                        <div class="w-16 h-16 rounded-lg bg-green-50 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-green-500 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                    <div>
                                        <h6 class="font-bold text-gray-800 mb-1">{{ optional($item->product)->name }}</h6>
                                        <p class="text-xs text-gray-500">Stok sisa: {{ optional($item->product)->stock }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">Rp {{ number_format(optional($item->product)->price, 0, ',', '.') }}</td>
                            <td class="py-4 px-6">
                                <form action="{{ route('store.cart.update') }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="{{ optional($item->product)->stock }}" 
                                           class="w-20 px-2 py-1.5 border border-gray-200 rounded-lg text-center text-sm focus:outline-none focus:ring-2 focus:ring-green-500" 
                                           onchange="this.form.submit()">
                                </form>
                            </td>
                            <td class="py-4 px-6 font-bold text-green-600 whitespace-nowrap">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <form action="{{ route('store.cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                <a href="{{ route('store.index') }}" class="text-sm font-medium text-green-600 hover:text-green-700 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Lanjut Belanja
                </a>
                <form action="{{ route('store.cart.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-600 flex items-center gap-1" onclick="return confirm('Kosongkan keranjang?')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Kosongkan Keranjang
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm p-6 sticky top-24">
            <h5 class="text-lg font-bold text-gray-800 mb-6">Ringkasan Belanja</h5>
            
            <div class="flex justify-between text-sm text-gray-500 mb-4 border-b border-gray-100 pb-4">
                <span>Total Item</span>
                <span class="font-semibold text-gray-800">{{ $cart->cartItems->sum('quantity') }} Produk</span>
            </div>
            
            <div class="flex justify-between items-end mb-6">
                <span class="font-bold text-gray-800">Total Harga</span>
                <span class="text-2xl font-bold text-green-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            
            <a href="{{ route('store.checkout.index') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center font-bold py-3.5 rounded-xl transition flex justify-center items-center gap-2">
                Lanjut Checkout
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </div>
</div>
@else
<div class="text-center py-20">
    <svg class="w-24 h-24 mx-auto mb-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
    <h3 class="text-2xl font-bold text-gray-800 mb-2">Keranjang Belanja Kosong</h3>
    <p class="text-gray-500 mb-8">Anda belum menambahkan produk apapun ke keranjang.</p>
    <a href="{{ route('store.index') }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-xl transition">
        Mulai Belanja
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
    </a>
</div>
@endif

@endsection
