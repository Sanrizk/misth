@extends('layouts.store')
@section('title', 'Riwayat Pesanan - Toko Misth')
@section('content')

<h2 class="text-2xl font-bold text-gray-800 mb-6">Riwayat Pesanan</h2>

<div class="space-y-4">
    @forelse($transactions as $transaction)
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition">
            <div class="bg-gray-50 border-b border-gray-100 p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                    <span class="font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <span class="font-mono">{{ $transaction->invoice_number }}</span>
                    </span>
                    <span class="text-xs text-gray-500 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        {{ $transaction->created_at->format('d M Y, H:i') }}
                    </span>
                </div>
                
                <div>
                    @php
                        $badgeClass = 'bg-gray-100 text-gray-700';
                        $label = ucfirst($transaction->status);
                        if($transaction->status === 'pending') {
                            $badgeClass = 'bg-yellow-100 text-yellow-700';
                            $label = 'Menunggu Pembayaran';
                        } elseif($transaction->status === 'paid') {
                            $badgeClass = 'bg-blue-100 text-blue-700';
                            $label = 'Dibayar';
                        } elseif($transaction->status === 'shipping') {
                            $badgeClass = 'bg-purple-100 text-purple-700';
                            $label = 'Dalam Pengiriman';
                        } elseif($transaction->status === 'completed') {
                            $badgeClass = 'bg-green-100 text-green-700';
                            $label = 'Selesai';
                        } elseif($transaction->status === 'cancelled') {
                            $badgeClass = 'bg-red-100 text-red-700';
                            $label = 'Dibatalkan';
                        }
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                        {{ $label }}
                    </span>
                </div>
            </div>
            
            <div class="p-5 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="w-full md:flex-1">
                    @php $firstItem = $transaction->transactionDetails->first(); @endphp
                    @if($firstItem)
                        <div class="flex items-center gap-4">
                            @if(optional($firstItem->product)->image_url)
                                <img src="{{ optional($firstItem->product)->image_url }}" alt="{{ optional($firstItem->product)->name }}" class="w-16 h-16 rounded-lg object-cover">
                            @else
                                <div class="w-16 h-16 rounded-lg bg-green-50 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-500 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div>
                                <h6 class="font-bold text-gray-800 mb-1">{{ optional($firstItem->product)->name ?? 'Produk Dihapus' }}</h6>
                                <p class="text-sm text-gray-500">{{ $firstItem->quantity }} item x Rp {{ number_format(optional($firstItem->product)->price ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        
                        @if($transaction->transactionDetails->count() > 1)
                            <div class="mt-2 pl-20">
                                <p class="text-xs text-gray-400 font-medium">+ {{ $transaction->transactionDetails->count() - 1 }} produk lainnya</p>
                            </div>
                        @endif
                    @endif
                </div>
                
                <div class="w-full md:w-auto flex flex-row md:flex-col justify-between items-center md:items-end md:pl-6 md:border-l md:border-gray-100 gap-2">
                    <div class="text-left md:text-right">
                        <p class="text-xs text-gray-500 mb-1">Total Belanja</p>
                        <p class="text-lg font-bold text-green-600">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</p>
                    </div>
                    <a href="{{ route('store.orders.show', $transaction->id) }}" class="px-4 py-2 bg-white border border-green-600 text-green-600 hover:bg-green-50 rounded-xl text-sm font-semibold transition">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-20">
            <svg class="w-24 h-24 mx-auto mb-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <h4 class="text-xl font-bold text-gray-800 mb-3">Belum Ada Pesanan</h4>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">Anda belum pernah melakukan pesanan apapun. Mulai penuhi meja makan Anda dengan sayuran segar dari kami!</p>
            <a href="{{ route('store.index') }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-xl transition">
                Mulai Belanja
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    @endforelse
</div>

<div class="mt-8 flex justify-center">
    {{ $transactions->links('pagination::tailwind') }}
</div>

@endsection
