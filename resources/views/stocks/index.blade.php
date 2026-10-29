@extends('layouts.app')

@section('title', 'Manajemen Stok')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <svg class="w-7 h-7 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                Pusat Manajemen Stok
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Monitoring ketersediaan bahan baku, mutasi keluar-masuk, dan penyesuaian fisik (stok opname).
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('stocks.opname') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Input Stok Opname
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Bahan --}}
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Macam Bahan</p>
                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mt-0.5">{{ number_format($totalMaterials) }}</h3>
                <span class="text-[11px] text-gray-400 dark:text-gray-500">{{ $activeMaterials }} aktif</span>
            </div>
        </div>

        {{-- Stok Menipis --}}
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-red-50 dark:bg-red-900/40 text-red-600 dark:text-red-400 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Stok Menipis / Habis</p>
                <h3 class="text-xl font-bold {{ $lowStockCount > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-800 dark:text-gray-100' }} mt-0.5">
                    {{ number_format($lowStockCount) }}
                </h3>
                <span class="text-[11px] text-gray-400 dark:text-gray-500">&le; Batas Minimum</span>
            </div>
        </div>

        {{-- Total Valuasi Bahan --}}
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-4 sm:col-span-2">
            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Estimasi Valuasi Nilai Bahan Tersedia</p>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-0.5">Rp {{ number_format($totalValuation, 0, ',', '.') }}</h3>
                <span class="text-[11px] text-gray-400 dark:text-gray-500">Berdasarkan stok saat ini &times; harga beli/unit</span>
            </div>
        </div>
    </div>

    {{-- Main Action Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Card 1: Cek Stok Keseluruhan --}}
        <a href="{{ route('stocks.summary') }}" class="group bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 hover:border-green-500 dark:hover:border-green-500 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 bg-green-50 dark:bg-green-900/40 text-green-600 dark:text-green-400 rounded-xl flex items-center justify-center mb-4 group-hover:scale-105 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 group-hover:text-green-600 dark:group-hover:text-green-400 transition">
                    Cek Stok Keseluruhan
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
                    Lihat rekapitulasi seluruh bahan, batas minimum, estimasi nilai aset, serta total barang masuk & keluar dalam rentang tanggal.
                </p>
            </div>
            <div class="mt-6 flex items-center gap-2 text-sm font-semibold text-green-600 dark:text-green-400">
                Buka Cek Stok Keseluruhan
                <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </div>
        </a>

        {{-- Card 2: Mutasi Stok Per Bahan --}}
        <a href="{{ route('stocks.mutations') }}" class="group bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center mb-4 group-hover:scale-105 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                    Kartu & Mutasi Stok
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
                    Lacak histori kronologis alur bahan: Pembelian masuk, pemakaian perawatan tanaman hidroponik, dan penyesuaian opname dalam rentang tanggal.
                </p>
            </div>
            <div class="mt-6 flex items-center gap-2 text-sm font-semibold text-blue-600 dark:text-blue-400">
                Lihat Kartu Mutasi
                <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </div>
        </a>

        {{-- Card 3: Stok Opname --}}
        <a href="{{ route('stocks.opname') }}" class="group bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-100 dark:border-gray-700 hover:border-amber-500 dark:hover:border-amber-500 shadow-sm hover:shadow-md transition duration-200 flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 rounded-xl flex items-center justify-center mb-4 group-hover:scale-105 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-800 dark:text-gray-100 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition">
                    Stok Opname & Koreksi
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
                    Sinkronisasi fisik bahan di kebun/gudang dengan sistem: catat barang rusak, bocor, susut, kadaluarsa, atau kelebihan fisik secara akuntabel.
                </p>
            </div>
            <div class="mt-6 flex items-center gap-2 text-sm font-semibold text-amber-600 dark:text-amber-400">
                Kelola Stok Opname
                <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </div>
        </a>
    </div>

    {{-- Riwayat Opname Terakhir --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Aktivitas Stok Opname Terakhir
            </h2>
            <a href="{{ route('stocks.opname') }}" class="text-xs font-semibold text-green-600 dark:text-green-400 hover:underline">
                Lihat Semua Riwayat
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                <thead class="bg-gray-50 dark:bg-gray-750 text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="py-3 px-4">No. Penyesuaian</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Bahan</th>
                        <th class="py-3 px-4">Tipe</th>
                        <th class="py-3 px-4 text-right">Kuantitas</th>
                        <th class="py-3 px-4">Alasan</th>
                        <th class="py-3 px-4">Petugas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($recentAdjustments as $adj)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition">
                        <td class="py-3 px-4 font-mono font-medium text-gray-800 dark:text-gray-200">{{ $adj->adjustment_number }}</td>
                        <td class="py-3 px-4">{{ \Carbon\Carbon::parse($adj->adjustment_date)->translatedFormat('d M Y') }}</td>
                        <td class="py-3 px-4 font-medium text-gray-800 dark:text-gray-100">{{ $adj->material->name ?? '-' }}</td>
                        <td class="py-3 px-4">
                            @if($adj->type === 'in')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/80">
                                    + Tambah Fisik
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800/80">
                                    - Pengurangan/Susut
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right font-semibold {{ $adj->type === 'in' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            {{ $adj->type === 'in' ? '+' : '-' }}{{ number_format($adj->quantity, 2) }} {{ $adj->material->unit ?? '' }}
                        </td>
                        <td class="py-3 px-4">{{ $adj->reason ?? '-' }}</td>
                        <td class="py-3 px-4 text-xs text-gray-500">{{ $adj->user->name ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-6 text-center text-gray-400 text-xs">
                            Belum ada riwayat stok opname.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

