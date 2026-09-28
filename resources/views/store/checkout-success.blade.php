@extends('layouts.store')
@section('title', 'Pembayaran Berhasil - Toko Misth')
@section('content')

<div class="max-w-md mx-auto mt-10">
    <div class="bg-white rounded-3xl shadow-sm overflow-hidden text-center">
        
        <div class="bg-green-600 text-white py-10 px-6 flex flex-col items-center">
            <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mb-4 ring-8 ring-green-500/30">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <h2 class="text-2xl font-bold">Pesanan Berhasil!</h2>
        </div>
        
        <div class="p-8">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Nomor Invoice</p>
            <h4 class="text-xl font-bold font-mono text-gray-800 mb-6">{{ $transaction->invoice_number }}</h4>
            
            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 mb-8 text-left space-y-3 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Tanggal</span>
                    <span class="font-semibold text-gray-800">{{ $transaction->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Pembayaran</span>
                    <span class="font-semibold text-gray-800">{{ $transaction->payment_method }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Status</span>
                    <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">Pending</span>
                </div>
                <div class="pt-3 mt-3 border-t border-gray-200 flex justify-between items-center">
                    <span class="font-bold text-gray-500">Total Bayar</span>
                    <span class="text-lg font-bold text-green-600">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <a href="{{ route('store.orders.show', $transaction->id) }}" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl transition">
                    Lihat Detail Pesanan
                </a>
                <a href="{{ route('store.index') }}" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3.5 rounded-xl transition">
                    Kembali ke Toko
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
