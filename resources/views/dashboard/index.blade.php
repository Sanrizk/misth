@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

{{-- Summary Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    {{-- Card: Active Plantings --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500">Penanaman Aktif</p>
            <p class="text-2xl font-bold text-gray-800">{{ $activePlantings }}</p>
        </div>
    </div>

    {{-- Card: Available Products --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500">Produk Tersedia</p>
            <p class="text-2xl font-bold text-gray-800">{{ $availableProducts }}</p>
        </div>
    </div>

    {{-- Card: Today Transactions --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500">Transaksi Hari Ini</p>
            <p class="text-2xl font-bold text-gray-800">{{ $transactionsToday }}</p>
        </div>
    </div>

    {{-- Card: This Month Harvest --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500">Panen Bulan Ini</p>
            <p class="text-2xl font-bold text-gray-800">{{ $harvestsThisMonth }}</p>
        </div>
    </div>

</div>

{{-- Charts Row 1 --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Tren Panen (6 Bulan Terakhir)</h2>
        <canvas id="harvestChart" class="w-full" style="max-height: 250px;"></canvas>
    </div>
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Produk Terlaris</h2>
        <canvas id="productChart" class="w-full" style="max-height: 250px;"></canvas>
    </div>
</div>

{{-- Charts Row 2 & Recent Transactions --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    {{-- Finance Chart --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Pembelian Bahan vs Penjualan (6 Bulan Terakhir)</h2>
        <div class="flex-1 flex items-center justify-center">
            <canvas id="financeChart" class="w-full" style="max-height: 300px;"></canvas>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col">
        <h2 class="text-sm font-semibold text-gray-700 mb-4">Transaksi Terbaru</h2>
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="text-left py-2 px-3 text-xs font-medium text-gray-500">Invoice</th>
                        <th class="text-left py-2 px-3 text-xs font-medium text-gray-500">Customer</th>
                        <th class="text-left py-2 px-3 text-xs font-medium text-gray-500">Total</th>
                        <th class="text-left py-2 px-3 text-xs font-medium text-gray-500">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentTransactions as $trx)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2.5 px-3 font-mono text-xs text-gray-600">{{ $trx->invoice_number }}</td>
                        <td class="py-2.5 px-3">{{ optional($trx->user)->name }}</td>
                        <td class="py-2.5 px-3 font-semibold">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                        <td class="py-2.5 px-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                @if($trx->status === 'completed') bg-green-100 text-green-700
                                @elseif($trx->status === 'paid') bg-blue-100 text-blue-700
                                @elseif($trx->status === 'shipping') bg-purple-100 text-purple-700
                                @elseif($trx->status === 'cancelled') bg-red-100 text-red-700
                                @else bg-yellow-100 text-yellow-700 @endif">
                                {{ ucfirst($trx->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-gray-400 text-xs">Belum ada transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } }
        }
    };

    // 1. Harvest Line Chart
    new Chart(document.getElementById('harvestChart'), {
        type: 'line',
        data: {
            labels: @json($harvestChart['labels']),
            datasets: [{
                label: 'Total Panen (kg)',
                data: @json($harvestChart['data']),
                borderColor: '#16a34a',
                backgroundColor: 'rgba(22, 163, 74, 0.1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            ...chartOptions,
            scales: { y: { beginAtZero: true } }
        }
    });

    // 2. Product Pie Chart
    new Chart(document.getElementById('productChart'), {
        type: 'doughnut',
        data: {
            labels: @json($productChart['labels']),
            datasets: [{
                data: @json($productChart['data']),
                backgroundColor: [
                    '#16a34a', '#2563eb', '#d97706', '#9333ea', '#db2777'
                ],
                borderWidth: 0
            }]
        },
        options: {
            ...chartOptions,
            cutout: '60%'
        }
    });

    // 3. Finance Bar Chart
    new Chart(document.getElementById('financeChart'), {
        type: 'bar',
        data: {
            labels: @json($financeChart['labels']),
            datasets: [
                {
                    label: 'Pembelian Bahan (Rp)',
                    data: @json($financeChart['purchases']),
                    backgroundColor: '#dc2626',
                    borderRadius: 4
                },
                {
                    label: 'Penjualan (Rp)',
                    data: @json($financeChart['sales']),
                    backgroundColor: '#16a34a',
                    borderRadius: 4
                }
            ]
        },
        options: {
            ...chartOptions,
            scales: { y: { beginAtZero: true } }
        }
    });
});
</script>
@endsection