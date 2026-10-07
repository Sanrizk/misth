@extends('layouts.app')
@section('title', 'Laporan Penjualan')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Laporan Penjualan</h2>
        <button onclick="window.print()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2-2v4h10z"></path></svg>
            Cetak Laporan
        </button>
    </div>

    {{-- Filter Form --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm hide-on-print">
        <form method="GET" action="{{ route('reports.transactions') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-green-500 focus:border-green-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-green-500 focus:border-green-500">
            </div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                Filter
            </button>
            @if(request('start_date') || request('end_date'))
                <a href="{{ route('reports.transactions') }}" class="text-sm text-gray-500 hover:text-gray-700 underline">Reset</a>
            @endif
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-green-500">
            <p class="text-sm text-gray-500 font-medium mb-1">Total Pendapatan (Sukses)</p>
            <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($summary->sum('total_revenue'), 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border-l-4 border-blue-500">
            <p class="text-sm text-gray-500 font-medium mb-1">Total Transaksi</p>
            <p class="text-2xl font-bold text-gray-800">{{ $summary->sum('total_transactions') }} <span class="text-sm font-normal text-gray-400">Order</span></p>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-700">Rincian Transaksi Penjualan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3">Tanggal</th>
                        <th class="px-6 py-3">Invoice</th>
                        <th class="px-6 py-3">Pelanggan</th>
                        <th class="px-6 py-3">Metode Bayar</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3">{{ $trx->created_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-3 font-mono text-xs">{{ $trx->invoice_number }}</td>
                            <td class="px-6 py-3">{{ optional($trx->user)->name ?? '-' }}</td>
                            <td class="px-6 py-3 uppercase">{{ $trx->payment_method }}</td>
                            <td class="px-6 py-3 font-medium">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-3">
                                <span class="px-2 py-1 rounded text-xs font-medium 
                                    {{ $trx->status === 'paid' || $trx->status === 'completed' ? 'bg-green-100 text-green-700' : 
                                      ($trx->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                                      ($trx->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700')) }}">
                                    {{ ucfirst($trx->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-400">Tidak ada data transaksi pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<style>
    @media print {
        body * { visibility: hidden; }
        .space-y-6, .space-y-6 * { visibility: visible; }
        .hide-on-print { display: none !important; }
        .space-y-6 { position: absolute; left: 0; top: 0; width: 100%; }
        nav, aside { display: none !important; }
    }
</style>
@endsection

