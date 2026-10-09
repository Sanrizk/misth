@extends('layouts.store')
@section('title', 'Checkout')
@section('content')

<div class="max-w-2xl mx-auto space-y-4">

    <h2 class="text-lg font-semibold text-gray-700">Konfirmasi Pesanan</h2>

    {{-- Order Summary --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-sm font-semibold text-gray-700">Item Pesanan</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach($cart->cartItems as $item)
            <div class="flex justify-between items-center px-6 py-3">
                <div>
                    <p class="text-sm font-medium text-gray-700">{{ optional($item->product)->name }}</p>
                    <p class="text-xs text-gray-400">× {{ $item->quantity }}</p>
                </div>
                <p class="text-sm font-semibold text-gray-800">
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                </p>
            </div>
            @endforeach
        </div>
        <div class="px-6 py-4 border-t border-gray-100 flex justify-between items-center">
            <span class="font-semibold text-gray-700">Total</span>
            <span class="text-lg font-bold text-green-700">
                Rp {{ number_format($total, 0, ',', '.') }}
            </span>
        </div>
    </div>

    {{-- Metode Pembayaran: COD only --}}
    <div class="bg-white rounded-2xl shadow-sm p-5">
        <h3 class="text-sm font-semibold text-gray-700 mb-3">Metode Pembayaran</h3>
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 rounded-xl px-4 py-3">
            <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-green-800">Bayar di Tempat (COD)</p>
                <p class="text-xs text-green-600">Tunjukkan QR Code kepada admin saat pengambilan barang</p>
            </div>
            <div class="ml-auto">
                <div class="w-5 h-5 bg-green-600 rounded-full flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Pengambilan --}}
    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
        <div class="flex gap-3">
            <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-xs text-blue-700 space-y-1">
                <p class="font-semibold">Cara Pengambilan:</p>
                <ol class="list-decimal list-inside space-y-0.5 text-blue-600">
                    <li>Buat pesanan dan dapatkan QR Code</li>
                    <li>Datang ke lokasi kebun Misth</li>
                    <li>Tunjukkan QR Code kepada admin</li>
                    <li>admin scan QR dan konfirmasi pembayaran</li>
                    <li>Ambil barang Anda</li>
                </ol>
            </div>
        </div>
    </div>

    {{-- Submit --}}
    <form action="{{ route('store.checkout.store') }}" method="POST">
        @csrf
        <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-2xl text-sm transition cursor-pointer">
            Buat Pesanan & Dapatkan QR Code
        </button>
    </form>

    <a href="{{ route('store.cart.index') }}"
       class="block text-center text-sm text-gray-400 hover:text-gray-600">
        ← Kembali ke Keranjang
    </a>

</div>

@endsection
