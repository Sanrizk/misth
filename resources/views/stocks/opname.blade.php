@extends('layouts.app')

@section('title', 'Stok Opname Bahan')

@section('content')
<div x-data="{ 
    modalOpen: false, 
    modalMode: 'create', // 'create' atau 'edit'
    formAction: '{{ route('stocks.opname.store') }}',
    form: {
        id: null,
        material_id: '{{ old('material_id', $materialId) }}',
        adjustment_date: '{{ date('Y-m-d') }}',
        type: 'out',
        quantity: '',
        reason: 'Selisih Hitung Opname',
        notes: ''
    },
    materialsData: {{ Js::from($materials->keyBy('id')) }},
    get selectedMaterial() {
        return this.materialsData[this.form.material_id] || null;
    },
    openCreateModal() {
        this.modalMode = 'create';
        this.formAction = '{{ route('stocks.opname.store') }}';
        this.form = {
            id: null,
            material_id: '{{ $materialId ?: ($materials->first()->id ?? '') }}',
            adjustment_date: '{{ date('Y-m-d') }}',
            type: 'out',
            quantity: '',
            reason: 'Selisih Hitung Opname',
            notes: ''
        };
        this.modalOpen = true;
    },
    openEditModal(adj) {
        this.modalMode = 'edit';
        this.formAction = '{{ url('stocks/opname') }}/' + adj.id;
        this.form = {
            id: adj.id,
            material_id: adj.material_id,
            adjustment_date: adj.adjustment_date ? adj.adjustment_date.substring(0, 10) : '{{ date('Y-m-d') }}',
            type: adj.type,
            quantity: adj.quantity,
            reason: adj.reason || 'Selisih Hitung Opname',
            notes: adj.notes || ''
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

    @if($errors->any())
    <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-300 text-sm">
        <p class="font-bold mb-1">Periksa kembali data Anda:</p>
        <ul class="list-disc pl-5 space-y-0.5 text-xs">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400 mb-1">
                <a href="{{ route('stocks.index') }}" class="hover:text-green-600 dark:hover:text-green-400">Stok</a>
                <span>&bull;</span>
                <span>Stok Opname & Koreksi Fisik</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                <svg class="w-7 h-7 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Stok Opname Bahan
            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Pencatatan selisih fisik bahan baku untuk menjaga akurasi pembukuan stok kebun hidroponik.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="openCreateModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Penyesuaian Baru
            </button>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 p-5 shadow-sm">
        <form method="GET" action="{{ route('stocks.opname') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1.5">Filter Bahan</label>
                <select name="material_id" class="w-full text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
                    <option value="">Semua Bahan</option>
                    @foreach($materials as $mat)
                        <option value="{{ $mat->id }}" {{ $materialId == $mat->id ? 'selected' : '' }}>
                            {{ $mat->name }} ({{ $mat->code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1.5">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1.5">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full text-sm bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl shadow-sm transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Filter
                </button>
                <a href="{{ route('stocks.opname') }}" class="p-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 rounded-xl transition" title="Reset Filter">
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
                        <th class="py-3 px-4">No. Penyesuaian</th>
                        <th class="py-3 px-4">Tanggal</th>
                        <th class="py-3 px-4">Bahan</th>
                        <th class="py-3 px-4">Tipe Penyesuaian</th>
                        <th class="py-3 px-4 text-right">Stok Sebelum</th>
                        <th class="py-3 px-4 text-right">Perubahan</th>
                        <th class="py-3 px-4 text-right">Stok Sesudah</th>
                        <th class="py-3 px-4">Alasan</th>
                        <th class="py-3 px-4">Catatan</th>
                        <th class="py-3 px-4">Petugas</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse($adjustments as $adj)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition">
                        <td class="py-3 px-4 font-mono font-medium text-gray-800 dark:text-gray-200 text-xs">{{ $adj->adjustment_number }}</td>
                        <td class="py-3 px-4 text-xs whitespace-nowrap">{{ \Carbon\Carbon::parse($adj->adjustment_date)->translatedFormat('d M Y') }}</td>
                        <td class="py-3 px-4">
                            <span class="font-medium text-gray-800 dark:text-gray-100">{{ $adj->material->name ?? '-' }}</span>
                            <span class="text-xs text-gray-400 block font-mono">{{ $adj->material->code ?? '' }}</span>
                        </td>
                        <td class="py-3 px-4 whitespace-nowrap">
                            @if($adj->type === 'in')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/80">
                                    + Penambahan Fisik
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-800/80">
                                    - Pengurangan / Susut
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-right text-xs text-gray-500">
                            {{ number_format($adj->stock_before, 2) }}
                        </td>
                        <td class="py-3 px-4 text-right font-bold {{ $adj->type === 'in' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                            {{ $adj->type === 'in' ? '+' : '-' }}{{ number_format($adj->quantity, 2) }} {{ $adj->material->unit ?? '' }}
                        </td>
                        <td class="py-3 px-4 text-right font-semibold text-gray-800 dark:text-gray-200 text-xs">
                            {{ number_format($adj->stock_after, 2) }}
                        </td>
                        <td class="py-3 px-4 text-xs font-medium text-gray-700 dark:text-gray-300">
                            {{ $adj->reason ?? '-' }}
                        </td>
                        <td class="py-3 px-4 text-xs text-gray-500 max-w-xs truncate">
                            {{ $adj->notes ?? '-' }}
                        </td>
                        <td class="py-3 px-4 text-xs text-gray-500 whitespace-nowrap">
                            {{ $adj->user->name ?? '-' }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                {{-- Tombol Edit --}}
                                <button type="button" 
                                        @click="openEditModal({{ Js::from($adj) }})"
                                        class="p-1.5 bg-yellow-50 hover:bg-yellow-100 text-yellow-600 dark:bg-yellow-950/40 dark:hover:bg-yellow-900/60 dark:text-yellow-400 rounded-lg transition" 
                                        title="Edit Stok Opname">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('stocks.opname.destroy', $adj->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            @click="$dispatch('confirm', {
                                                title: 'Hapus Catatan Opname?',
                                                message: 'Menghapus catatan ini akan mengembalikan stok bahan ke kondisi sebelum penyesuaian opname dilakukan. Lanjutkan?',
                                                type: 'danger',
                                                onConfirm: () => $el.closest('form').submit()
                                            })"
                                            class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-950/40 dark:hover:bg-red-900/60 dark:text-red-400 rounded-lg transition" 
                                            title="Hapus Stok Opname">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="py-12 text-center text-gray-400">
                            Belum ada catatan stok opname yang cocok dengan filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($adjustments->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
            {{ $adjustments->links() }}
        </div>
        @endif
    </div>

    {{-- Modal Form Input Stok Opname --}}
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
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span x-text="modalMode === 'edit' ? 'Edit Penyesuaian Stok Opname' : 'Input Penyesuaian Stok Opname'"></span>
                        </h3>
                        <button type="button" @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4 text-sm overflow-y-auto flex-1">
                        {{-- Pilih Bahan --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Bahan yang Disesuaikan <span class="text-red-500">*</span></label>
                            <select name="material_id" x-model="form.material_id" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
                                <option value="">-- Pilih Bahan --</option>
                                @foreach($materials as $mat)
                                    <option value="{{ $mat->id }}">{{ $mat->name }} ({{ $mat->code }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Current stock alert info --}}
                        <template x-if="selectedMaterial">
                            <div class="p-3 rounded-xl bg-blue-50/70 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900 flex items-center justify-between text-xs">
                                <span class="text-blue-700 dark:text-blue-300">Stok Sistem Saat Ini:</span>
                                <span class="font-bold text-blue-900 dark:text-blue-200" x-text="Number(selectedMaterial.stock).toFixed(2) + ' ' + selectedMaterial.unit"></span>
                            </div>
                        </template>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Tanggal --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Penyesuaian <span class="text-red-500">*</span></label>
                                <input type="date" name="adjustment_date" x-model="form.adjustment_date" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
                            </div>

                            {{-- Tipe Penyesuaian --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Tipe Penyesuaian <span class="text-red-500">*</span></label>
                                <select name="type" x-model="form.type" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
                                    <option value="out">Pengurangan (-) / Susut / Rusak</option>
                                    <option value="in">Penambahan (+) / Selisih Fisik Lebih</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Kuantitas Perubahan --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Jumlah Selisih Fisik <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="number" step="0.01" min="0.01" name="quantity" x-model="form.quantity" required placeholder="0.00" class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
                                    <span class="absolute right-3 top-2 text-xs text-gray-400" x-text="selectedMaterial ? selectedMaterial.unit : ''"></span>
                                </div>
                            </div>

                            {{-- Alasan/Penyebab --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Alasan / Penyebab <span class="text-red-500">*</span></label>
                                <select name="reason" x-model="form.reason" required class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none">
                                    <option value="Selisih Hitung Opname">Selisih Hitung Opname</option>
                                    <option value="Barang Rusak / Bocor">Barang Rusak / Bocor</option>
                                    <option value="Kadaluarsa">Kadaluarsa</option>
                                    <option value="Penguapan / Susut">Penguapan / Susut Alami</option>
                                    <option value="Bonus / Kelebihan Kirim">Bonus / Kelebihan Kirim</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>

                        {{-- Catatan Lengkap --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Catatan / Keterangan Detail</label>
                            <textarea name="notes" x-model="form.notes" rows="2" placeholder="Contoh: Ditemukan botol nutrisi A retak saat inspeksi mingguan..." class="w-full bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2 text-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-green-500 focus:outline-none"></textarea>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-750 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-700 shrink-0">
                        <button type="button" @click="modalOpen = false" class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-xl hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                            <span x-text="modalMode === 'edit' ? 'Simpan Perubahan' : 'Simpan Penyesuaian'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
@endsection

