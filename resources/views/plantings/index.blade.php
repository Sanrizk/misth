@extends('layouts.app')
@section('title', 'Penanaman')
@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold text-gray-700">Penanaman</h2>
    <a href="{{ route('plantings.create') }}"
       class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
        + Tambah
    </a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-4">
    @forelse($plantings as $planting)
    <div x-data="{
            showPerawatan: false,
            showKualitasAir: false,
            showPanen: false
         }"
         class="bg-white rounded-2xl shadow-sm overflow-hidden hover:-translate-y-1 hover:shadow-md transition duration-200 flex flex-col h-full">

        {{-- Card Header --}}
        <div class="px-4 pt-4 pb-2 flex justify-between items-start">
            <div>
                <h3 class="font-bold text-green-700 text-sm">
                    {{ optional($planting->plantType)->name ?? '-' }}
                </h3>
                <p class="text-xs text-gray-400">{{ $planting->batch_code }}</p>
            </div>
            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                @if($planting->status === 'in_progress') bg-blue-100 text-blue-700
                @elseif($planting->status === 'harvested') bg-green-100 text-green-700
                @else bg-red-100 text-red-700 @endif">
                {{ $planting->status === 'in_progress' ? 'Proses' : ($planting->status === 'harvested' ? 'Panen' : 'Gagal') }}
            </span>
        </div>

        {{-- Info --}}
        <div class="px-4 pb-2 space-y-1">
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Semai</span>
                <span class="font-medium text-gray-700">
                    {{ \Carbon\Carbon::parse($planting->start_date)->format('d M Y') }}
                </span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-400">Est. Panen</span>
                <span class="font-medium text-gray-700">
                    {{ optional($planting->plantType)->estimated_harvest_days ?? '-' }} Hari
                </span>
            </div>
        </div>

        {{-- Progress Bar --}}
        <div class="px-4 pb-3 flex-1">
            <div class="flex justify-between text-xs mb-1">
                <span class="text-gray-400">Progress</span>
                <span class="font-semibold text-green-600">0%</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-2">
                <div class="bg-green-500 h-2 rounded-full" style="width: 0%"></div>
            </div>
        </div>

        <div class="border-t border-gray-100 px-4 py-3 space-y-2">

            {{-- Row 1: Show, Edit, Delete --}}
            <div class="grid grid-cols-3 gap-1">
                <a href="{{ route('plantings.show', $planting->id) }}"
                   class="flex flex-col items-center py-1.5 rounded-lg bg-sky-50 hover:bg-sky-100 text-sky-600 transition text-xs">
                    <svg class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Detail
                </a>
                <a href="{{ route('plantings.edit', $planting->id) }}"
                   class="flex flex-col items-center py-1.5 rounded-lg bg-yellow-50 hover:bg-yellow-100 text-yellow-600 transition text-xs">
                    <svg class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit
                </a>
                <form action="{{ route('plantings.destroy', $planting->id) }}" method="POST" class="w-full">
                    @csrf @method('DELETE')
                    <button onclick="return confirm('Yakin hapus?')"
                            class="w-full flex flex-col items-center py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition text-xs">
                        <svg class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Hapus
                    </button>
                </form>
            </div>

            {{-- Row 2: Perawatan, Kualitas Air, Panen --}}
            <div class="grid grid-cols-3 gap-1">
                <button @click="showPerawatan = true"
                        class="flex flex-col items-center py-1.5 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-600 transition text-xs">
                    <svg class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Perawatan
                </button>
                <button @click="showKualitasAir = true"
                        class="flex flex-col items-center py-1.5 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition text-xs">
                    <svg class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    Kualitas Air
                </button>
                <button @click="showPanen = true"
                        class="flex flex-col items-center py-1.5 rounded-lg bg-green-50 hover:bg-green-100 text-green-600 transition text-xs">
                    <svg class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    Panen
                </button>
            </div>

        </div>

        {{-- MODAL PERAWATAN --}}
        <template x-teleport="body">
        <div x-cloak x-show="showPerawatan" x-transition x-init="$watch('showPerawatan', val => document.body.style.overflow = val ? 'hidden' : '')"
             class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
             @click.self="showPerawatan = false">
            <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-xl">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-700 text-sm">
                        Perawatan — {{ $planting->batch_code }}
                    </h3>
                    <button @click="showPerawatan = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-5">

                    {{-- Form Perawatan --}}
                    <form action="{{ route('maintenance-logs.store') }}" method="POST" class="space-y-3 mb-5">
                        @csrf
                        <input type="hidden" name="planting_id" value="{{ $planting->id }}">

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Aktivitas</label>
                                <input type="datetime-local" name="activity_date"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Tindakan</label>
                                <select name="action_type" id="action_type_{{ $planting->id }}"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Pemberian Nutrisi AB Mix">Pemberian Nutrisi AB Mix</option>
                                    <option value="Penyemprotan Pestisida Nabati">Penyemprotan Pestisida Nabati</option>
                                    <option value="Pengecekan Akar">Pengecekan Akar</option>
                                    <option value="Pemangkasan Daun">Pemangkasan Daun</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Nutrisi PPM</label>
                                <input type="number" name="nutrients_ppm" min="0"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-green-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Catatan</label>
                                <textarea name="notes" rows="1"
                                          class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                            </div>
                        </div>

                        {{-- Bahan Section --}}
                        <div id="bahanSection_{{ $planting->id }}" class="hidden">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Bahan Digunakan</label>
                            <div id="bahanList_{{ $planting->id }}" class="space-y-2"></div>
                        </div>

                        <button type="submit"
                                class="w-full bg-gray-700 hover:bg-gray-800 text-white text-xs font-semibold py-2.5 rounded-xl transition">
                            Simpan Perawatan
                        </button>
                    </form>

                    <hr class="border-gray-100 mb-4">

                    {{-- Riwayat Perawatan --}}
                    <h4 class="text-xs font-semibold text-gray-600 mb-3">Riwayat Perawatan</h4>
                    @forelse($planting->maintenanceLogs->sortByDesc('activity_date') as $log)
                    <div class="bg-gray-50 rounded-xl p-3 mb-2">
                        <div class="flex justify-between items-start">
                            <div class="space-y-0.5">
                                <p class="text-xs font-semibold text-gray-700">{{ $log->action_type }}</p>
                                <p class="text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($log->activity_date)->format('d M Y H:i') }}
                                    · {{ optional($log->user)->name }}
                                </p>
                                @if($log->nutrients_ppm)
                                <p class="text-xs text-blue-500">PPM: {{ $log->nutrients_ppm }}</p>
                                @endif
                                @if($log->notes)
                                <p class="text-xs text-gray-400 italic">{{ $log->notes }}</p>
                                @endif
                                @foreach($log->materialUsages as $usage)
                                <span class="inline-block text-xs bg-white border border-gray-200 rounded-lg px-2 py-0.5 mr-1 mt-1">
                                    {{ optional($usage->material)->name }} {{ $usage->quantity_used }} {{ optional($usage->material)->unit }}
                                </span>
                                @endforeach
                            </div>
                            <form action="{{ route('maintenance-logs.destroy', $log->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Hapus?')"
                                        class="text-red-400 hover:text-red-600 p-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 text-center py-4">Belum ada riwayat perawatan.</p>
                    @endforelse

                </div>
            </div>
        </div>
        </template>

        {{-- MODAL KUALITAS AIR --}}
        <template x-teleport="body">
        <div x-cloak x-show="showKualitasAir" x-transition x-init="$watch('showKualitasAir', val => document.body.style.overflow = val ? 'hidden' : '')"
             class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
             @click.self="showKualitasAir = false">
            <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-xl">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-700 text-sm">
                        Kualitas Air — {{ $planting->batch_code }}
                    </h3>
                    <button @click="showKualitasAir = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-5">

                    {{-- Form Kualitas Air --}}
                    <form action="{{ route('water-quality-logs.store') }}" method="POST" class="space-y-3 mb-5">
                        @csrf
                        <input type="hidden" name="planting_id" value="{{ $planting->id }}">

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Cek</label>
                                <input type="datetime-local" name="checked_at"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">pH Level (0-14)</label>
                                <input type="number" name="ph_level" step="0.1" min="0" max="14"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">TDS PPM</label>
                                <input type="number" name="tds_ppm" min="0"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Suhu Air °C</label>
                                <input type="number" name="water_temp" step="0.1"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="col-span-2">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Catatan</label>
                                <textarea name="notes" rows="1"
                                          class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold py-2.5 rounded-xl transition">
                            Simpan Kualitas Air
                        </button>
                    </form>

                    <hr class="border-gray-100 mb-4">

                    {{-- Riwayat Kualitas Air --}}
                    <h4 class="text-xs font-semibold text-gray-600 mb-3">Riwayat Kualitas Air</h4>
                    @forelse($planting->waterQualityLogs->sortByDesc('checked_at') as $log)
                    <div class="bg-gray-50 rounded-xl p-3 mb-2">
                        <div class="flex justify-between items-start">
                            <div class="space-y-0.5">
                                <div class="flex items-center gap-2">
                                    <p class="text-xs font-semibold text-gray-700">pH: {{ $log->ph_level }}</p>
                                    <span class="text-xs px-1.5 py-0.5 rounded-full
                                        @if($log->ph_level < 6) bg-red-100 text-red-600
                                        @elseif($log->ph_level <= 7) bg-green-100 text-green-600
                                        @else bg-yellow-100 text-yellow-600 @endif">
                                        {{ $log->ph_level < 6 ? 'Asam' : ($log->ph_level <= 7 ? 'Normal' : 'Basa') }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-400">
                                    TDS: {{ $log->tds_ppm }} ppm
                                    @if($log->water_temp) · {{ $log->water_temp }}°C @endif
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($log->checked_at)->format('d M Y H:i') }}
                                </p>
                            </div>
                            <form action="{{ route('water-quality-logs.destroy', $log->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Hapus?')"
                                        class="text-red-400 hover:text-red-600 p-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <p class="text-xs text-gray-400 text-center py-4">Belum ada riwayat kualitas air.</p>
                    @endforelse

                </div>
            </div>
        </div>
        </template>

        {{-- MODAL PANEN --}}
        <template x-teleport="body">
        <div x-cloak x-show="showPanen" x-transition x-init="$watch('showPanen', val => document.body.style.overflow = val ? 'hidden' : '')"
             class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
             @click.self="showPanen = false">
            <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-xl">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-700 text-sm">
                        Panen — {{ $planting->batch_code }}
                    </h3>
                    <button @click="showPanen = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-5">

                    @if($planting->harvest)
                    <div class="bg-green-50 border border-green-100 rounded-xl p-4 mb-4">
                        <p class="text-xs text-green-700 font-semibold mb-3">
                            Dipanen pada {{ \Carbon\Carbon::parse($planting->harvest->harvest_date)->format('d M Y') }}
                        </p>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <p class="text-xs text-gray-400">Jumlah</p>
                                <p class="text-sm font-bold text-gray-700">{{ $planting->harvest->total_yield_quantity }} unit</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Berat</p>
                                <p class="text-sm font-bold text-gray-700">{{ $planting->harvest->total_yield_weight }} kg</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Grade</p>
                                <span class="text-xs px-2 py-0.5 rounded-full font-medium
                                    @if($planting->harvest->quality_grade === 'Grade A') bg-green-100 text-green-700
                                    @elseif($planting->harvest->quality_grade === 'Grade B') bg-yellow-100 text-yellow-700
                                    @else bg-red-100 text-red-700 @endif">
                                    {{ $planting->harvest->quality_grade }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @elseif($planting->status === 'in_progress')
                    <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-3 mb-4">
                        <p class="text-xs text-yellow-700">Menyimpan panen akan otomatis membuat produk di toko dan mengubah status penanaman menjadi harvested.</p>
                    </div>

                    <form action="{{ route('harvests.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="hidden" name="planting_id" value="{{ $planting->id }}">

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Panen</label>
                                <input type="date" name="harvest_date"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Grade</label>
                                <select name="quality_grade"
                                        class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="Grade A">Grade A</option>
                                    <option value="Grade B">Grade B</option>
                                    <option value="Grade C">Grade C</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Jumlah (unit)</label>
                                <input type="number" name="total_yield_quantity" min="1"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Berat (kg)</label>
                                <input type="number" name="total_yield_weight" step="0.01" min="0"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Harga/Unit (Rp)</label>
                                <input type="number" name="price" min="0"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Catatan</label>
                                <textarea name="notes" rows="1"
                                          class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                            </div>
                        </div>

                        <button type="submit"
                                class="w-full bg-green-600 hover:bg-green-700 text-white text-xs font-semibold py-2.5 rounded-xl transition">
                            Eksekusi Panen
                        </button>
                    </form>

                    @else
                    <div class="bg-red-50 border border-red-100 rounded-xl p-4">
                        <p class="text-xs text-red-600">Status penanaman <strong>{{ $planting->status }}</strong>, tidak bisa dipanen.</p>
                    </div>
                    @endif

                </div>
            </div>
        </div>
        </template>

    </div>
    @empty
    <div class="col-span-4 text-center py-16 text-gray-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        <p class="text-sm">Belum ada data penanaman.</p>
        <a href="{{ route('plantings.create') }}" class="text-green-600 text-sm hover:underline mt-1 inline-block">Tambah sekarang</a>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
<div class="flex justify-end mt-6">
    {{ $plantings->links('pagination::tailwind') }}
</div>

@endsection

@section('scripts')
<script>
window.addEventListener("pageshow", function() {
    document.querySelectorAll("[x-data]").forEach(el => {
        if (el._x_dataStack) {
            const data = el._x_dataStack[0];
            if ("showPerawatan" in data) data.showPerawatan = false;
            if ("showKualitasAir" in data) data.showKualitasAir = false;
            if ("showPanen" in data) data.showPanen = false;
            document.body.style.overflow = "";
        }
    });
});
const materials = @json($materials);
const actionMaterialMap = {
    'Pemberian Nutrisi AB Mix': ['AB Mix Part A', 'AB Mix Part B'],
    'Penyemprotan Pestisida Nabati': ['Pestisida Nabati'],
    'Pengecekan Akar': [],
    'Pemangkasan Daun': [],
    'Lainnya': null,
};

document.querySelectorAll('[id^="action_type_"]').forEach(select => {
    const plantingId = select.id.replace('action_type_', '');
    const bahanSection = document.getElementById('bahanSection_' + plantingId);
    const bahanList = document.getElementById('bahanList_' + plantingId);

    select.addEventListener('change', function () {
        const mapped = actionMaterialMap[this.value];
        bahanList.innerHTML = '';

        if (!this.value || (Array.isArray(mapped) && mapped.length === 0)) {
            bahanSection.classList.add('hidden');
            return;
        }

        bahanSection.classList.remove('hidden');

        if (mapped === null) {
            bahanList.innerHTML = buildManualRow();
        } else {
            mapped.forEach(name => {
                const mat = materials.find(m => m.name === name);
                if (mat) bahanList.innerHTML += buildReadonlyRow(mat);
            });
        }
    });
});

function buildReadonlyRow(mat) {
    return `
    <div class="flex gap-2 items-center mt-2">
        <input type="hidden" name="material_ids[]" value="${mat.id}">
        <input type="text" class="flex-1 px-3 py-1.5 border border-gray-200 rounded-lg text-xs bg-gray-50" value="${mat.name} (${mat.unit})" readonly>
        <input type="number" name="quantities[]" class="w-24 px-3 py-1.5 border border-gray-200 rounded-lg text-xs" placeholder="Qty" step="0.01" min="0.01" required>
        <span class="text-xs text-gray-400 whitespace-nowrap">Stok: ${mat.stock}</span>
    </div>`;
}

function buildManualRow() {
    const options = materials.map(m =>
        `<option value="${m.id}">${m.name} (${m.unit})</option>`
    ).join('');
    return `
    <div class="flex gap-2 items-center mt-2">
        <select name="material_ids[]" class="flex-1 px-3 py-1.5 border border-gray-200 rounded-lg text-xs">
            <option value="">-- Pilih Bahan --</option>
            ${options}
        </select>
        <input type="number" name="quantities[]" class="w-24 px-3 py-1.5 border border-gray-200 rounded-lg text-xs" placeholder="Qty" step="0.01" min="0.01">
        <button type="button" onclick="addManualRow(this)"
                class="px-2 py-1.5 bg-green-100 text-green-700 rounded-lg text-xs hover:bg-green-200">+</button>
    </div>`;
}

function addManualRow(btn) {
    const options = materials.map(m =>
        `<option value="${m.id}">${m.name} (${m.unit})</option>`
    ).join('');
    const row = document.createElement('div');
    row.className = 'flex gap-2 items-center mt-2';
    row.innerHTML = `
        <select name="material_ids[]" class="flex-1 px-3 py-1.5 border border-gray-200 rounded-lg text-xs">
            <option value="">-- Pilih Bahan --</option>
            ${options}
        </select>
        <input type="number" name="quantities[]" class="w-24 px-3 py-1.5 border border-gray-200 rounded-lg text-xs" placeholder="Qty" step="0.01" min="0.01">
        <button type="button" onclick="this.closest('div').remove()"
                class="px-2 py-1.5 bg-red-100 text-red-600 rounded-lg text-xs hover:bg-red-200">−</button>`;
    btn.closest('div').parentNode.appendChild(row);
}
</script>
@endsection
