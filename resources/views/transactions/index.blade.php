@extends('layouts.app')
@section('title', 'Transaksi')
@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold text-gray-700">Transaksi</h2>
</div>

{{-- Search Bar --}}
<div class="bg-white rounded-2xl shadow-sm p-4 mb-4"
     x-data="barcodeSearch()">

    <form method="GET" action="{{ route('transactions.index') }}" class="flex gap-3">

        {{-- Invoice Search --}}
        <div class="flex-1 relative">
            <input type="text"
                   name="search"
                   x-ref="searchInput"
                   value="{{ request('search') }}"
                   placeholder="Cari nomor invoice atau scan barcode..."
                   class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        {{-- Filter Status --}}
        <select name="status"
                class="px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">Semua Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="shipping" {{ request('status') === 'shipping' ? 'selected' : '' }}>Shipping</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>

        <button type="submit"
                class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition">
            Cari
        </button>

        {{-- Barcode Scanner Button --}}
        <button type="button" @click="startScanner()"
                class="bg-gray-700 hover:bg-gray-800 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
            </svg>
            Scan
        </button>

    </form>

    {{-- Barcode Scanner Modal --}}
    <div x-show="scanning" x-cloak x-teleport="body">
        <div class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4"
             @click.self="stopScanner()">
            <div class="bg-white rounded-2xl w-full max-w-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-gray-700 text-sm">Scan Barcode / QR Code</h3>
                    <button @click="stopScanner()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Camera Preview --}}
                <div class="bg-gray-900 rounded-xl overflow-hidden mb-4 relative" style="aspect-ratio: 1;">
                    <video x-ref="video" class="w-full h-full object-cover" playsinline></video>
                    {{-- Scan frame overlay --}}
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-48 h-48 border-2 border-green-400 rounded-xl relative">
                            <div class="absolute top-0 left-0 w-6 h-6 border-t-4 border-l-4 border-green-400 rounded-tl-lg"></div>
                            <div class="absolute top-0 right-0 w-6 h-6 border-t-4 border-r-4 border-green-400 rounded-tr-lg"></div>
                            <div class="absolute bottom-0 left-0 w-6 h-6 border-b-4 border-l-4 border-green-400 rounded-bl-lg"></div>
                            <div class="absolute bottom-0 right-0 w-6 h-6 border-b-4 border-r-4 border-green-400 rounded-br-lg"></div>
                        </div>
                    </div>
                </div>

                <p class="text-xs text-gray-400 text-center">Arahkan kamera ke QR Code pada struk customer</p>

                {{-- Manual input fallback --}}
                <div class="mt-4">
                    <p class="text-xs text-gray-400 text-center mb-2">atau ketik manual</p>
                    <div class="flex gap-2">
                        <input type="text" x-ref="manualInput"
                               placeholder="INV-..."
                               class="flex-1 px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-green-500">
                        <button @click="manualSearch()"
                                class="bg-green-600 text-white text-xs px-3 py-2 rounded-xl hover:bg-green-700 transition">
                            Cari
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Invoice</th>
                    <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Customer</th>
                    <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Total</th>
                    <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Metode</th>
                    <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Status</th>
                    <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Tanggal</th>
                    <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($transactions as $trx)
                <tr class="hover:bg-gray-50 {{ $trx->status === 'pending' ? 'bg-yellow-50/30' : '' }}">
                    <td class="py-3 px-4 font-mono text-xs text-gray-600">{{ $trx->invoice_number }}</td>
                    <td class="py-3 px-4 text-gray-700">{{ optional($trx->user)->name }}</td>
                    <td class="py-3 px-4 font-semibold text-gray-800">
                        Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                    </td>
                    <td class="py-3 px-4 text-gray-500 text-xs">{{ $trx->payment_method }}</td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            @if($trx->status === 'completed') bg-green-100 text-green-700
                            @elseif($trx->status === 'paid') bg-blue-100 text-blue-700
                            @elseif($trx->status === 'shipping') bg-purple-100 text-purple-700
                            @elseif($trx->status === 'cancelled') bg-red-100 text-red-700
                            @else bg-yellow-100 text-yellow-700 @endif">
                            {{ ucfirst($trx->status) }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-gray-400 text-xs">
                        {{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y H:i') }}
                    </td>
                    <td class="py-3 px-4">
                        <a href="{{ route('transactions.show', $trx->id) }}"
                           class="text-xs px-3 py-1 bg-sky-100 text-sky-700 rounded-lg hover:bg-sky-200 transition">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 text-center text-gray-400 text-xs">
                        @if(request('search'))
                            Invoice "{{ request('search') }}" tidak ditemukan.
                        @else
                            Belum ada transaksi.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $transactions->links('pagination::tailwind') }}
    </div>
</div>

@endsection

@section('scripts')
<script>
function barcodeSearch() {
    return {
        scanning: false,
        stream: null,

        async startScanner() {
            this.scanning = true;
            await this.$nextTick();

            try {
                this.stream = await navigator.mediaDevices.getUserMedia({
                    video: { facingMode: 'environment' }
                });
                this.$refs.video.srcObject = this.stream;
                this.$refs.video.play();

                // Use BarcodeDetector API if available
                if ('BarcodeDetector' in window) {
                    const detector = new BarcodeDetector({ formats: ['qr_code', 'code_128', 'code_39'] });
                    const scan = async () => {
                        if (!this.scanning) return;
                        try {
                            const barcodes = await detector.detect(this.$refs.video);
                            if (barcodes.length > 0) {
                                const result = barcodes[0].rawValue;
                                this.stopScanner();
                                window.location.href = `{{ route('transactions.index') }}?search=${encodeURIComponent(result)}`;
                                return;
                            }
                        } catch (e) {}
                        requestAnimationFrame(scan);
                    };
                    requestAnimationFrame(scan);
                }
            } catch (err) {
                alert('Tidak bisa mengakses kamera. Gunakan input manual.');
                this.scanning = false;
            }
        },

        stopScanner() {
            this.scanning = false;
            if (this.stream) {
                this.stream.getTracks().forEach(track => track.stop());
                this.stream = null;
            }
        },

        manualSearch() {
            const val = this.$refs.manualInput.value.trim();
            if (!val) return;
            this.stopScanner();
            window.location.href = `{{ route('transactions.index') }}?search=${encodeURIComponent(val)}`;
        }
    }
}
</script>
@endsection
