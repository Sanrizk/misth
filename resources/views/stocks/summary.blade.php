@extends('layouts.app')

@section('title', 'Cek Stok Keseluruhan Bahan')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-1">
                <a href="{{ route('stocks.index') }}" class="hover:text-green-600 dark:hover:text-green-400">Stok</a>
                <span>&bull;</span>
                <span>Ringkasan Keseluruhan</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Cek Stok Keseluruhan Bahan
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Informasi ketersediaan stok aktual, batas peringatan, nilai aset, dan mutasi periode berjalan.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('stocks.mutations') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-xl shadow-sm transition">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
                Kartu Mutasi
            </a>
            <a href="{{ route('stocks.opname') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Stok Opname
            </a>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm">
        <form method="GET" action="{{ route('stocks.summary') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1.5">Dari Tanggal (Periode)</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1.5">Sampai Tanggal (Periode)</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1.5">Kategori Bahan</label>
                <select name="category" class="w-full text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1.5">Cari Bahan / Kode</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Nama / Kode..." class="w-full text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Terapkan
                </button>
                <a href="{{ route('stocks.summary') }}" class="p-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-xl transition" title="Reset Filter">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </a>
            </div>
        </form>

        @if($startDate && $endDate)
        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 flex items-center gap-2 text-xs text-green-700 dark:text-green-400 bg-green-50/50 dark:bg-green-950/30 px-3 py-2 rounded-lg">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Menampilkan aktivitas Masuk (Pembelian + Opname Masuk) dan Keluar (Pemakaian Perawatan + Opname Keluar) periode: 
            <span class="font-bold">{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</span> s/d <span class="font-bold">{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</span>.
        </div>
        @endif
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                <thead class="bg-gray-50 dark:bg-gray-750 text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="py-3 px-4">Bahan</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4 text-right">Stok Aktual</th>
                        <th class="py-3 px-4 text-right">Batas Min.</th>
                        @if($startDate && $endDate)
                        <th class="py-3 px-4 text-right bg-emerald-50/50 dark:bg-emerald-950/20 text-emerald-800 dark:text-emerald-400">Masuk Periode</th>
                        <th class="py-3 px-4 text-right bg-rose-50/50 dark:bg-rose-950/20 text-rose-800 dark:text-rose-400">Keluar Periode</th>
                        @endif
                        <th class="py-3 px-4 text-right">Harga Satuan</th>
                        <th class="py-3 px-4 text-right">Valuasi Nilai</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($summaryData as $row)
                    @php $isLow = $row->current_stock <= $row->min_stock; @endphp
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition {{ $isLow ? 'bg-red-50/30 dark:bg-red-950/10' : '' }}">
                        <td class="py-3 px-4">
                            <div class="font-bold text-gray-800 dark:text-gray-100">{{ $row->name }}</div>
                            <div class="text-xs font-mono text-gray-400 dark:text-gray-500">{{ $row->code }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                {{ ucfirst($row->category) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right font-bold {{ $isLow ? 'text-red-600 dark:text-red-400' : 'text-gray-800 dark:text-gray-100' }}">
                            {{ number_format($row->current_stock, 2) }} {{ $row->unit }}
                        </td>
                        <td class="py-3 px-4 text-right text-gray-500 text-xs">
                            {{ number_format($row->min_stock, 2) }} {{ $row->unit }}
                        </td>
                        @if($startDate && $endDate)
                        <td class="py-3 px-4 text-right font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50/30 dark:bg-emerald-950/10">
                            +{{ number_format($row->in_quantity, 2) }}
                        </td>
                        <td class="py-3 px-4 text-right font-semibold text-rose-600 dark:text-rose-400 bg-rose-50/30 dark:bg-rose-950/10">
                            -{{ number_format($row->out_quantity, 2) }}
                        </td>
                        @endif
                        <td class="py-3 px-4 text-right text-xs">
                            Rp {{ number_format($row->price_per_unit, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-right font-semibold text-gray-800 dark:text-gray-100">
                            Rp {{ number_format($row->valuation, 0, ',', '.') }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if($row->current_stock <= 0)
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400">Habis</span>
                            @elseif($isLow)
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-400">Menipis</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400">Aman</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('stocks.mutations', ['material_id' => $row->id, 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 text-blue-600 dark:text-blue-400 rounded-lg transition" title="Lihat Riwayat Mutasi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('stocks.opname', ['material_id' => $row->id]) }}" class="p-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 text-green-600 dark:text-green-400 rounded-lg transition" title="Opname Bahan Ini">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ ($startDate && $endDate) ? '10' : '8' }}" class="py-12 text-center text-gray-400">
                            Tidak ada bahan ditemukan sesuai filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

