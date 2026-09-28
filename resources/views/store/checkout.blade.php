@extends('layouts.store')
@section('title', 'Checkout - Toko Misth')
@section('content')

<h2 class="text-2xl font-bold text-gray-800 mb-6">Checkout</h2>

<form action="{{ route('store.checkout.store') }}" method="POST">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-7">
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-6">
                <h5 class="font-bold text-gray-800 mb-4">Ringkasan Pesanan</h5>
                <div class="divide-y divide-gray-100">
                    @foreach($cart->cartItems as $item)
                        <div class="py-4 flex justify-between items-center">
                            <div class="flex items-center gap-4">
                                @if(optional($item->product)->image_url)
                                    <img src="{{ optional($item->product)->image_url }}" alt="{{ optional($item->product)->name }}" class="w-14 h-14 rounded-lg object-cover">
                                @else
                                    <div class="w-14 h-14 rounded-lg bg-green-50 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-500 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="font-bold text-gray-800">{{ optional($item->product)->name }}</h6>
                                    <p class="text-xs text-gray-500">{{ $item->quantity }} x Rp {{ number_format(optional($item->product)->price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="font-bold text-gray-800">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h5 class="font-bold text-gray-800 mb-4">Metode Pembayaran</h5>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($paymentMethods as $index => $method)
                        <label class="relative flex items-center p-4 cursor-pointer border rounded-xl hover:bg-green-50 hover:border-green-200 transition {{ old('payment_method') == $method ? 'border-green-500 bg-green-50 ring-1 ring-green-500' : 'border-gray-200 bg-white' }}">
                            <input type="radio" name="payment_method" value="{{ $method }}" required {{ old('payment_method') == $method ? 'checked' : '' }}
                                   class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500 mr-3">
                            <span class="font-medium text-gray-800 text-sm">
                                {{ $method }}
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('payment_method')
                    <p class="text-xs text-red-500 mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="lg:col-span-5">
            <div class="bg-white rounded-2xl shadow-sm p-6 sticky top-24">
                <h5 class="font-bold text-gray-800 mb-6">Total Pembayaran</h5>
                
                <div class="flex justify-between text-sm text-gray-500 mb-3">
                    <span>Subtotal ({{ $cart->cartItems->count() }} Produk)</span>
                    <span class="font-medium text-gray-800">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-500 mb-4 border-b border-gray-100 pb-4">
                    <span>Biaya Pengiriman</span>
                    <span class="font-medium text-green-600">Gratis</span>
                </div>
                
                <div class="flex justify-between items-center mb-6">
                    <span class="font-bold text-gray-800">Total Bayar</span>
                    <span class="text-2xl font-bold text-green-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition flex justify-center items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Bayar Sekarang
                </button>
                
                <p class="text-center text-xs text-gray-400 mt-4 leading-relaxed">
                    Dengan menekan tombol bayar, Anda menyetujui syarat & ketentuan yang berlaku.
                </p>
            </div>
        </div>

    </div>
</form>

@endsection
