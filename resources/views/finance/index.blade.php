@extends('layouts.app')

@section('title', 'Buku Kas & Keuangan')

@section('content')
<div x-data="{
    modalOpen: false,
    modalMode: 'create', // 'create' atau 'edit'
    formAction: '{{ route('finance.store') }}',
    form: {
        id: null,
        transaction_date: '{{ date('Y-m-d') }}',
        type: 'in',
        category: 'Penjualan Langsung',
        amount: '',
        description: ''
    },
    openCreateModal(type = 'in') {
        this.modalMode = 'create';
        this.formAction = '{{ route('finance.store') }}';
        this.form = {
            id: null,
            transaction_date: '{{ date('Y-m-d') }}',
            type: type,
            category: type === 'in' ? 'Penjualan Langsung' : 'Operasional Kebun',
            amount: '',
            description: ''
        };
        this.modalOpen = true;
    },
    openEditModal(cash) {
        if (cash.reference_type !== 'manual') {
            alert('Transaksi otomatis dari sistem (pesanan/pembelian) tidak dapat diedit secara manual di sini.');
            return;
        }
        this.modalMode = 'edit';
        this.formAction = '{{ url('finance') }}/' + cash.id;
        this.form = {
            id: cash.id,
            transaction_date: cash.transaction_date ? cash.transaction_date.substring(0, 10) : '{{ date('Y-m-d') }}',
            type: cash.type,
            category: cash.category,
            amount: cash.amount,
            description: cash.description || ''
        };
        this.modalOpen = true;
    }
}" class="space-y-6">

    {{-- Flash Notifications --}}
    @if(session('success'))
    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 text-sm flex items-center gap-3">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm flex items-center gap-3">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Buku Kas & Keuangan
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Pencatatan kas masuk & keluar kebun secara manual, serta sinkronisasi otomatis dari pesanan & pembelian.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <button @click="openCreateModal('in')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Data Kas
            </button>
        </div>
    </div>

    {{-- Cards Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Kas Masuk --}}
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Pemasukan Periode</p>
                <h3 class="text-xl font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">
                    Rp {{ number_format($totalIn, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-gray-400">Total kas masuk</span>
            </div>
        </div>

        {{-- Total Kas Keluar --}}
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Pengeluaran Periode</p>
                <h3 class="text-xl font-bold text-rose-600 dark:text-rose-400 mt-0.5">
                    Rp {{ number_format($totalOut, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-gray-400">Total kas keluar</span>
            </div>
        </div>

        {{-- Selisih Arus Kas (Net) --}}
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Arus Kas Bersih (Net)</p>
                <h3 class="text-xl font-bold {{ $netBalance >= 0 ? 'text-gray-800 dark:text-gray-100' : 'text-rose-600 dark:text-rose-400' }} mt-0.5">
                    Rp {{ number_format($netBalance, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-gray-400">Selisih in - out periode</span>
            </div>
        </div>

        {{-- Saldo Kumulatif --}}
        <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Saldo Kas Tersedia</p>
                <h3 class="text-xl font-bold {{ $cumulativeBalance >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }} mt-0.5">
                    Rp {{ number_format($cumulativeBalance, 0, ',', '.') }}
                </h3>
                <span class="text-[11px] text-gray-400">Saldo kas riil hingga saat ini</span>
            </div>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm">
        <form method="GET" action="{{ route('finance.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1.5">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1.5">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1.5">Jenis Transaksi</label>
                <select name="type" class="w-full text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                    <option value="">Semua (Masuk & Keluar)</option>
                    <option value="in" {{ $type === 'in' ? 'selected' : '' }}>Hanya Kas Masuk (+)</option>
                    <option value="out" {{ $type === 'out' ? 'selected' : '' }}>Hanya Kas Keluar (-)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1.5">Cari Keterangan / No Kas</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="No kas / catatan..." class="w-full text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Terapkan
                </button>
                <a href="{{ route('finance.index') }}" class="p-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-xl transition" title="Reset Filter">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </a>
            </div>
        </form>
    </div>

    {{-- History Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">
                <thead class="bg-gray-50 dark:bg-gray-750 text-xs uppercase font-semibold text-gray-500 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">No. Kas</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Sumber / Asal</th>
                        <th class="py-3 px-4 text-right bg-emerald-50/50 dark:bg-emerald-950/20 text-emerald-800 dark:text-emerald-400">Masuk (+)</th>
                        <th class="py-3 px-4 text-right bg-rose-50/50 dark:bg-rose-950/20 text-rose-800 dark:text-rose-400">Keluar (-)</th>
                        <th class="py-3 px-4">Keterangan</th>
                        <th class="py-3 px-4">Petugas</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($cashFlows as $cash)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition">
                        <td class="py-3 px-4 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($cash->transaction_date)->translatedFormat('d M Y') }}
                        </td>
                        <td class="py-3 px-4 font-mono font-medium text-gray-800 dark:text-gray-200 text-xs">
                            {{ $cash->cash_number }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                {{ $cash->category }}
                            </span>
                        </td>
                        <td class="py-3 px-4 whitespace-nowrap text-xs">
                            @if($cash->reference_type === 'order')
                                <span class="inline-flex items-center gap-1 text-emerald-700 dark:text-emerald-400 font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    Otomatis Pesanan
                                </span>
                            @elseif($cash->reference_type === 'purchase')
                                <span class="inline-flex items-center gap-1 text-blue-700 dark:text-blue-400 font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Otomatis Pembelian
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-gray-600 dark:text-gray-400 font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Input Manual
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50/30 dark:bg-emerald-950/10">
                            @if($cash->type === 'in')
                                +Rp {{ number_format($cash->amount, 0, ',', '.') }}
                            @else
                                <span class="text-gray-300 dark:text-gray-600">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right font-semibold text-rose-600 dark:text-rose-400 bg-rose-50/30 dark:bg-rose-950/10">
                            @if($cash->type === 'out')
                                -Rp {{ number_format($cash->amount, 0, ',', '.') }}
                            @else
                                <span class="text-gray-300 dark:text-gray-600">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-xs text-gray-600 dark:text-gray-300 max-w-xs truncate">
                            {{ $cash->description ?? '-' }}
                        </td>
                        <td class="py-3 px-4 text-xs text-gray-500 whitespace-nowrap">
                            {{ $cash->user->name ?? '-' }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if($cash->reference_type === 'manual')
                            <div class="flex items-center justify-center gap-1.5">
                                {{-- Tombol Edit --}}
                                <button type="button" 
                                        @click="openEditModal({{ Js::from($cash) }})"
                                        class="p-1.5 bg-yellow-50 hover:bg-yellow-100 text-yellow-600 dark:bg-yellow-950/40 dark:hover:bg-yellow-900/60 dark:text-yellow-400 rounded-lg transition" 
                                        title="Edit Transaksi Kas">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('finance.destroy', $cash->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            @click="$dispatch('confirm', {
                                                title: 'Hapus Catatan Kas?',
                                                message: 'Apakah Anda yakin ingin menghapus catatan kas ini?',
                                                type: 'danger',
                                                onConfirm: () => $el.closest('form').submit()
                                            })"
                                            class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-950/40 dark:hover:bg-red-900/60 dark:text-red-400 rounded-lg transition" 
                                            title="Hapus Transaksi Kas">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            @else
                            <span class="text-[11px] text-gray-400 italic">Terkunci</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="py-12 text-center text-gray-400">
                            Tidak ada transaksi kas dalam rentang tanggal ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($cashFlows->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $cashFlows->links() }}
        </div>
        @endif
    </div>

    {{-- Modal Form Input / Edit Kas Manual --}}
    <template x-teleport="body">
        <div x-cloak x-show="modalOpen" class="fixed inset-0 z-[9999] flex items-center justify-center p-4" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            {{-- Backdrop --}}
            <div x-show="modalOpen" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0" 
                 x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100" 
                 x-transition:leave-end="opacity-0" 
                 @click="modalOpen = false" 
                 class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>

            {{-- Dialog Panel --}}
            <div x-show="modalOpen" 
                 @click.away="modalOpen = false"
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="relative bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all w-full max-w-lg border border-gray-100 dark:border-gray-700 max-h-[90vh] flex flex-col z-10">
                
                <form :action="formAction" method="POST" class="flex flex-col h-full overflow-hidden">
                    @csrf
                    <template x-if="modalMode === 'edit'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    
                    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between shrink-0">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                            <span :class="form.type === 'in' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>
                            <span x-text="modalMode === 'edit' ? 'Edit Transaksi Kas' : (form.type === 'in' ? 'Input Kas Masuk (+)' : 'Input Kas Keluar (-)')"></span>
                        </h3>
                        <button type="button" @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4 text-sm overflow-y-auto flex-1">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Jenis Kas --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Jenis Kas <span class="text-red-500">*</span></label>
                                <select name="type" x-model="form.type" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                    <option value="in">Kas Masuk (+)</option>
                                    <option value="out">Kas Keluar (-)</option>
                                </select>
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Tanggal <span class="text-red-500">*</span></label>
                                <input type="date" name="transaction_date" x-model="form.transaction_date" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Nominal --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Nominal (Rp) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-xs font-semibold text-gray-500">Rp</span>
                                    <input type="number" min="1" step="1" name="amount" x-model="form.amount" required placeholder="0" class="w-full pl-9 pr-3 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                </div>
                            </div>

                            {{-- Kategori --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                                <input type="text" name="category" x-model="form.category" required placeholder="Contoh: Listrik, Air, Gaji..." list="category-suggestions" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                                <datalist id="category-suggestions">
                                    <option value="Penjualan Langsung">
                                    <option value="Listrik Kebun">
                                    <option value="Air & PDAM">
                                    <option value="Gaji / Upah Karyawan">
                                    <option value="Perbaikan Greenhouse">
                                    <option value="Transportasi & Logistik">
                                    <option value="Lain-lain">
                                </datalist>
                            </div>
                        </div>

                        {{-- Keterangan --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Keterangan / Catatan</label>
                            <textarea name="description" x-model="form.description" rows="3" placeholder="Tuliskan keterangan detail transaksi..." class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-750 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-700 shrink-0">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-xl hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit" 
                                :class="form.type === 'in' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-rose-600 hover:bg-rose-700'"
                                class="px-5 py-2 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                            <span x-text="modalMode === 'edit' ? 'Simpan Perubahan' : 'Simpan Transaksi Kas'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
@endsection

