@extends('layouts.store')
@section('title', 'Pesanan Berhasil')
@section('content')

<div class="max-w-md mx-auto">
    <div class="bg-white rounded-2xl shadow-sm p-8 text-center">

        {{-- Success Icon --}}
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h2 class="text-xl font-bold text-gray-800 mb-1">Pesanan Berhasil Dibuat!</h2>
        <p class="text-sm text-gray-400 mb-6">Tunjukkan QR Code ini kepada kasir untuk menyelesaikan pembayaran.</p>

        {{-- QR Code --}}
        <div x-data="qrPayment('{{ $transaction->invoice_number }}')"
             class="flex flex-col items-center mb-6">

            <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 mb-3">
                <canvas x-ref="canvas" class="w-48 h-48"></canvas>
            </div>

            <p class="font-mono text-sm font-bold text-gray-700 bg-gray-100 px-4 py-2 rounded-xl">
                {{ $transaction->invoice_number }}
            </p>
            <p class="text-xs text-gray-400 mt-2">Scan QR atau tunjukkan nomor invoice ke kasir</p>

            {{-- Download QR --}}
            <button @click="downloadQR()"
                    class="mt-3 text-xs text-green-600 hover:underline flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Unduh QR Code
            </button>

        </div>

        {{-- Transaction Summary --}}
        <div class="border-t border-gray-100 pt-4 mb-6">
            <div class="space-y-2">
                @foreach($transaction->transactionDetails as $detail)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">{{ optional($detail->product)->name }} ×{{ $detail->quantity }}</span>
                    <span class="font-medium">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>
                @endforeach
                <div class="flex justify-between text-base font-bold text-green-700 border-t border-gray-100 pt-2 mt-2">
                    <span>Total</span>
                    <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="bg-yellow-50 border border-yellow-100 rounded-xl px-4 py-3 mb-6">
            <p class="text-xs text-yellow-700">
                Status: <strong>Menunggu Pembayaran</strong> — Admin akan mengkonfirmasi setelah QR di-scan.
            </p>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3">
            <a href="{{ route('store.orders.show', $transaction->id) }}"
               class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-xl text-sm transition text-center">
                Detail Pesanan
            </a>
            <a href="{{ route('store.index') }}"
               class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-xl text-sm transition text-center">
                Belanja Lagi
            </a>
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
                    width: 192,
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
