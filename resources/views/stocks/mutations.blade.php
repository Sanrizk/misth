@extends('layouts.app')

@section('title', 'Mutasi Stok Bahan')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-1">
                <a href="{{ route('stocks.index') }}" class="hover:text-green-600 dark:hover:text-green-400">Stok</a>
                <span>&bull;</span>
                <span>Kartu Mutasi Bahan</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                </svg>
                Mutasi & Kartu Stok Bahan
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Lacak jejak masuk dan keluarnya bahan per item dalam rentang tanggal yang dipilih.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('stocks.summary') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-xl shadow-sm transition">
                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Stok Keseluruhan
            </a>
            <a href="{{ route('stocks.opname') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Input Opname
            </a>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm">
        <form method="GET" action="{{ route('stocks.mutations') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div class="sm:col-span-2 lg:col-span-1">
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1.5">Pilih Bahan <span class="text-red-500">*</span></label>
                <select name="material_id" required class="w-full text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- Pilih Bahan --</option>
                    @foreach($materials as $mat)
                        <option value="{{ $mat->id }}" {{ $materialId == $mat->id ? 'selected' : '' }}>
                            {{ $mat->name }} ({{ $mat->code }}) - Stok: {{ number_format($mat->stock, 2) }} {{ $mat->unit }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1.5">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1.5">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Tampilkan Mutasi
                </button>
            </div>
        </form>
    </div>

    @if($selectedMaterial)
    {{-- Material Highlight Card --}}
    <div class="bg-gradient-to-r from-blue-900 to-indigo-900 rounded-2xl p-6 text-white shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 bg-blue-800/80 text-blue-200 text-xs rounded-full font-mono">{{ $selectedMaterial->code }}</span>
                <span class="px-2.5 py-0.5 bg-white/10 text-white text-xs rounded-full uppercase tracking-wider">{{ $selectedMaterial->category }}</span>
            </div>
            <h2 class="text-2xl font-bold">{{ $selectedMaterial->name }}</h2>
            <p class="text-blue-200 text-xs mt-1">
                Batas Minimum: {{ number_format($selectedMaterial->min_stock, 2) }} {{ $selectedMaterial->unit }} &bull; Harga Beli: Rp {{ number_format($selectedMaterial->price_per_unit, 0, ',', '.') }} / {{ $selectedMaterial->unit }}
            </p>
        </div>

        <div class="flex items-center gap-6 divide-x divide-white/20">
            <div class="text-right">
                <p class="text-xs text-blue-200">Stok Saat Ini (Sistem)</p>
                <h3 class="text-2xl font-bold text-white mt-0.5">{{ number_format($selectedMaterial->stock, 2) }} {{ $selectedMaterial->unit }}</h3>
            </div>
            <div class="pl-6 text-right">
                <p class="text-xs text-blue-200">Total Nilai Aset</p>
                <h3 class="text-2xl font-bold text-emerald-300 mt-0.5">Rp {{ number_format($selectedMaterial->stock * $selectedMaterial->price_per_unit, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    {{-- Mutation Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <h2 class="text-base font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Kronologis Mutasi Bahan ({{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} &mdash; {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }})
            </h2>
            <span class="text-xs text-gray-500">{{ $mutations->count() }} transaksi tercatat</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                <thead class="bg-gray-50 dark:bg-gray-750 text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">No. Referensi</th>
                        <th class="py-3 px-4">Jenis Mutasi</th>
                        <th class="py-3 px-4 text-right bg-emerald-50/50 dark:bg-emerald-950/20 text-emerald-800 dark:text-emerald-400">Masuk (+)</th>
                        <th class="py-3 px-4 text-right bg-rose-50/50 dark:bg-rose-950/20 text-rose-800 dark:text-rose-400">Keluar (-)</th>
                        <th class="py-3 px-4">Keterangan / Sumber</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($mutations as $m)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition">
                        <td class="py-3 px-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($m->date)->translatedFormat('d M Y H:i') }}
                        </td>
                        <td class="py-3 px-4 font-mono font-medium text-gray-800 dark:text-gray-200 text-xs">
                            {{ $m->reference }}
                        </td>
                        <td class="py-3 px-4">
                            @if($m->source === 'purchase')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/80">
                                    Pembelian Bahan
                                </span>
                            @elseif($m->source === 'usage')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800/80">
                                    Pemakaian Kebun
                                </span>
                            @elseif($m->source === 'opname_in')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-50 dark:bg-teal-950/60 text-teal-700 dark:text-teal-400 border border-teal-200 dark:border-teal-800/80">
                                    Opname Tambah
                                </span>
                            @elseif($m->source === 'opname_out')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800/80">
                                    Opname Koreksi/Susut
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50/20 dark:bg-emerald-950/10">
                            @if($m->in_quantity > 0)
                                +{{ number_format($m->in_quantity, 2) }} {{ $selectedMaterial->unit }}
                            @else
                                <span class="text-gray-300 dark:text-gray-600">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right font-semibold text-rose-600 dark:text-rose-400 bg-rose-50/20 dark:bg-rose-950/10">
                            @if($m->out_quantity > 0)
                                -{{ number_format($m->out_quantity, 2) }} {{ $selectedMaterial->unit }}
                            @else
                                <span class="text-gray-300 dark:text-gray-600">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-xs text-gray-600 dark:text-gray-300">
                            {{ $m->notes }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400">
                            Tidak ada riwayat mutasi untuk bahan ini pada rentang tanggal yang dipilih.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @else
    {{-- Empty State (Belum memilih bahan) --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-12 text-center shadow-sm">
        <div class="w-16 h-16 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
            </svg>
        </div>
        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-1">Silakan Pilih Bahan</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto">
            Gunakan filter di atas untuk memilih bahan yang ingin Anda periksa kartu mutasi dan alur perpindahan stoknya.
        </p>
    </div>
    @endif
</div>
@endsection

