@extends('layouts.store')
@section('title', 'Detail Pesanan - Toko Misth')
@section('content')

<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Detail Pesanan</h2>
    <a href="{{ route('store.orders.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h5 class="font-bold text-gray-800">Daftar Produk</h5>
                <span class="font-mono text-sm font-semibold text-gray-500">{{ $transaction->invoice_number }}</span>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($transaction->transactionDetails as $detail)
                    <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex items-center gap-4">
                            @if(optional($detail->product)->image_url)
                                <img src="{{ optional($detail->product)->image_url }}" alt="{{ optional($detail->product)->name }}" class="w-16 h-16 rounded-lg object-cover">
                            @else
                                <div class="w-16 h-16 rounded-lg bg-green-50 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-500 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div>
                                <h6 class="font-bold text-gray-800 mb-1">{{ optional($detail->product)->name ?? 'Produk Dihapus' }}</h6>
                                <p class="text-sm text-gray-500">{{ $detail->quantity }} x Rp {{ number_format(optional($detail->product)->price ?? 0, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="font-bold text-gray-800 w-full sm:w-auto text-right">
                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <div class="lg:col-span-1">

        @if($transaction->status === 'pending')
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-4 text-center"
             x-data="qrPayment('{{ $transaction->invoice_number }}')">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">QR Code Pembayaran</h3>
            <div class="flex flex-col items-center">
                <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 mb-3">
                    <canvas x-ref="canvas" class="w-40 h-40"></canvas>
                </div>
                <p class="font-mono text-xs font-bold text-gray-600 bg-gray-100 px-3 py-1.5 rounded-lg">
                    {{ $transaction->invoice_number }}
                </p>
                <button @click="downloadQR()"
                        class="mt-2 text-xs text-green-600 hover:underline">
                    Unduh QR Code
                </button>
            </div>
        </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm p-6 sticky top-24">
            <h5 class="font-bold text-gray-800 mb-6">Info Transaksi</h5>
            
            <div class="space-y-4 text-sm">
                <div>
                    <span class="block text-gray-500 mb-1">Tanggal Pesanan</span>
                    <span class="font-medium text-gray-800">{{ $transaction->created_at->format('d F Y, H:i') }}</span>
                </div>
                
                <div>
                    <span class="block text-gray-500 mb-2">Status Pesanan</span>
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
                    <span class="px-3 py-1 rounded-full text-xs font-bold inline-block {{ $badgeClass }}">
                        {{ $label }}
                    </span>
                </div>

                <div>
                    <p class="text-xs text-gray-400">Metode Bayar</p>
                    <p class="text-sm font-medium text-gray-700 flex items-center gap-1 mt-1">
                        <svg class="w-3.5 h-3.5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Bayar di Tempat (COD)
                    </p>
                </div>
            </div>
            
            <hr class="my-5 border-gray-100">
            
            <div class="flex justify-between items-center">
                <span class="font-bold text-gray-800">Total Belanja</span>
                <span class="text-xl font-bold text-green-600">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function qrPayment(invoiceNumber) {
    return {
        init() {
            this.$nextTick(() => {
                QRCode.toCanvas(this.$refs.canvas, invoiceNumber, {
                    width: 160,
                    margin: 2,
                    color: {
                        dark: '#166534',
                        light: '#f9fafb'
                    }
                }, (error) => {
                    if (error) console.error('QR Error:', error);
                });
            });
        },
        downloadQR() {
            QRCode.toDataURL(invoiceNumber, {
                width: 400,
                margin: 2,
                color: {
                    dark: '#166534',
                    light: '#ffffff'
                }
            }, (error, url) => {
                if (error) return;
                const link = document.createElement('a');
                link.download = `QR-${invoiceNumber}.png`;
                link.href = url;
                link.click();
            });
        }
    }
}
</script>
@endsection
