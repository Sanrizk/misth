@extends('layouts.app')
@section('title', 'Penanaman')
@section('content')

<div x-data="{ 
    showAddModal: false, 
    addStep: 1, 
    selectedPlantType: '', 
    setPlantType(id) { this.selectedPlantType = id; this.addStep = 2; },
    search: new URLSearchParams(window.location.search).get('search') || '',
    statusFilter: new URLSearchParams(window.location.search).get('status') || '',
    plantings: {{ Js::from($plantings->items()) }},
    links: {{ Js::from($plantings->linkCollection()) }},
    materials: {{ Js::from($materials) }},
    actionMaterialMap: {
        'Pemberian Nutrisi AB Mix': ['AB Mix Part A', 'AB Mix Part B'],
        'Penyemprotan Pestisida Nabati': ['Pestisida Nabati'],
        'Pengecekan Akar': [],
        'Pemangkasan Daun': [],
        'Lainnya': null,
    },
    formatDate(dateStr) {
        if (!dateStr) return '-';
        const d = new Date(dateStr);
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        return d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
    },
    formatDateTime(dateStr) {
        if (!dateStr) return '-';
        const d = new Date(dateStr);
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        return d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear() + ' ' + String(d.getHours()).padStart(2, '0') + ':' + String(d.getMinutes()).padStart(2, '0');
    },
    
    calculateProgress(planting) {
        if (planting.status === 'harvested') return 100;
        
        const start = new Date(planting.start_date).getTime();
        const now = new Date().getTime();
        
        const estDays = planting.plant_type ? planting.plant_type.estimated_harvest_days : 0;
        if (!estDays) return 0;
        
        const daysPassed = (now - start) / (1000 * 60 * 60 * 24);
        let progress = Math.round((daysPassed / estDays) * 100);
        
        if (progress < 0) progress = 0;
        if (progress > 100) progress = 100;
        
        return progress;
    },
    getProgressColorClass(progress) {
        if (progress < 30) return 'text-red-600';
        if (progress < 99) return 'text-yellow-600';
        return 'text-green-600';
    },
    getProgressBgClass(progress) {
        if (progress < 30) return 'bg-red-500';
        if (progress < 99) return 'bg-yellow-500';
        return 'bg-green-500';
    },
    fetchData(url = null) {
        let fetchUrl = new URL(url || window.location.href);
        if (this.search) fetchUrl.searchParams.set('search', this.search);
        else fetchUrl.searchParams.delete('search');
        if (this.statusFilter) fetchUrl.searchParams.set('status', this.statusFilter);
        else fetchUrl.searchParams.delete('status');

        fetch(fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            this.plantings = data.data || data;
            if(data.links) this.links = data.links;
        });
    }
}" x-init="$watch('search', val => { fetchData() }); $watch('statusFilter', val => { fetchData() })">

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <h2 class="text-lg font-semibold text-gray-700">Penanaman</h2>
    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
        <input type="text" x-model.debounce.300ms="search" placeholder="Cari..." class="w-full sm:w-auto px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        <select x-model="statusFilter" class="w-full sm:w-auto px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">Semua Status</option>
            <option value="in_progress">Proses</option>
            <option value="harvested">Panen</option>
            <option value="failed">Gagal</option>
        </select>
        <button @click="showAddModal = true; addStep = 1; selectedPlantType = '';"
           class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition whitespace-nowrap text-center">
            + Tambah
        </button>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-4 gap-4">
    <template x-for="planting in plantings" :key="planting.id">
        <div x-data="{
                showPerawatan: false,
                showKualitasAir: false,
                showPanen: false,
                showEdit: false,
                actionType: '',
                manualRows: [],
                get mappedMaterials() {
                    if (!this.actionType) return [];
                    const mapped = $data.actionMaterialMap[this.actionType];
                    if (mapped === null) return null; // manual
                    return mapped.map(name => $data.materials.find(m => m.name === name)).filter(m => m);
                },
                addManualRow() {
                    this.manualRows.push(Date.now());
                },
                removeManualRow(id) {
                    this.manualRows = this.manualRows.filter(r => r !== id);
                }
             }"
             class="bg-white rounded-2xl shadow-sm overflow-hidden hover:-translate-y-1 hover:shadow-md transition duration-200 flex flex-col h-full">

            {{-- Card Header --}}
            <div class="px-4 pt-4 pb-2 flex justify-between items-start">
                <div>
                    <h3 class="font-bold text-green-700 text-sm" x-text="planting.plant_type ? planting.plant_type.name : '-'">
                    </h3>
                    <p class="text-xs text-gray-400" x-text="planting.batch_code"></p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs font-medium"
                      :class="{
                          'bg-blue-100 text-blue-700': planting.status === 'in_progress',
                          'bg-green-100 text-green-700': planting.status === 'harvested',
                          'bg-red-100 text-red-700': planting.status !== 'in_progress' && planting.status !== 'harvested'
                      }"
                      x-text="planting.status === 'in_progress' ? 'Proses' : (planting.status === 'harvested' ? 'Panen' : 'Gagal')">
                </span>
            </div>

            {{-- Info --}}
            <div class="px-4 pb-2 space-y-1">
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400">Semai</span>
                    <span class="font-medium text-gray-700" x-text="formatDate(planting.start_date)">
                    </span>
                </div>
                <div class="flex justify-between text-xs">
                    <span class="text-gray-400">Est. Panen</span>
                    <span class="font-medium text-gray-700">
                        <span x-text="planting.plant_type ? planting.plant_type.estimated_harvest_days : '-'"></span> Hari
                    </span>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="px-4 pb-3 flex-1" x-data="{ progress: calculateProgress(planting) }">
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-gray-400">Progress</span>
                    <span class="font-semibold" :class="getProgressColorClass(progress)" x-text="progress + '%'"></span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all duration-500" :class="getProgressBgClass(progress)" :style="`width: ${progress}%`"></div>
                </div>
            </div>

            <div class="border-t border-gray-100 px-4 py-3 space-y-2">

                {{-- Row 1: Edit, Delete --}}
                <div class="grid grid-cols-2 gap-1">
                    <button @click="showEdit = true"
                       class="flex flex-col items-center py-1.5 rounded-lg bg-yellow-50 hover:bg-yellow-100 text-yellow-600 transition text-xs">
                        <svg class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit
                    </button>
                    <form :action="`{{ url('plantings') }}/${planting.id}`" method="POST" class="w-full">
                        @csrf @method('DELETE')
                        <button @click.prevent="$dispatch('confirm', { message: 'Yakin hapus?', onConfirm: () => $el.closest('form') ? $el.closest('form').submit() : null })"
                                class="w-full flex flex-col items-center py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition text-xs">
                            <svg class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Hapus
                        </button>
                    </form>
                </div>

                {{-- Row 2: Perawatan, Kualitas Air, Panen --}}
                <div class="grid gap-1" :class="planting.status === 'in_progress' ? 'grid-cols-3' : 'grid-cols-2'">
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
                    <template x-if="planting.status === 'in_progress'">
                        <button @click="showPanen = true"
                                class="flex flex-col items-center py-1.5 rounded-lg bg-green-50 hover:bg-green-100 text-green-600 transition text-xs">
                            <svg class="w-4 h-4 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Panen
                        </button>
                    </template>
                </div>

            </div>

            {{-- MODAL PERAWATAN --}}
            <template x-teleport="body">
            <div x-cloak x-show="showPerawatan" x-transition x-init="$watch('showPerawatan', val => document.body.style.overflow = val ? 'hidden' : '')"
                 class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
                 @click.self="showPerawatan = false">
                <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-xl">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-700 text-sm">
                            Perawatan — <span x-text="planting.batch_code"></span>
                        </h3>
                        <button @click="showPerawatan = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="p-5">

                        {{-- Form Perawatan --}}
                        <form action="{{ route('maintenance-logs.store') }}" method="POST" class="space-y-3 mb-5">
                            @csrf
                            <input type="hidden" name="planting_id" :value="planting.id">

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Aktivitas</label>
                                    <input type="datetime-local" name="activity_date"
                                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Tindakan</label>
                                    <select name="action_type" x-model="actionType"
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
                            <div x-show="actionType && (mappedMaterials !== null ? mappedMaterials.length > 0 : true)">
                                <label class="block text-xs font-medium text-gray-600 mb-1">Bahan Digunakan</label>
                                <div class="space-y-2">
                                    <template x-if="mappedMaterials !== null">
                                        <template x-for="mat in mappedMaterials" :key="mat.id">
                                            <div class="flex gap-2 items-center mt-2">
                                                <input type="hidden" name="material_ids[]" :value="mat.id">
                                                <input type="text" class="flex-1 px-3 py-1.5 border border-gray-200 rounded-lg text-xs bg-gray-50" :value="mat.name + ' (' + mat.unit + ')'" readonly>
                                                <input type="number" name="quantities[]" class="w-24 px-3 py-1.5 border border-gray-200 rounded-lg text-xs" placeholder="Qty" step="0.01" min="0.01" required>
                                                <span class="text-xs text-gray-400 whitespace-nowrap" x-text="'Stok: ' + mat.stock"></span>
                                            </div>
                                        </template>
                                    </template>
                                    <template x-if="mappedMaterials === null">
                                        <div>
                                            <div class="flex gap-2 items-center mt-2">
                                                <select name="material_ids[]" class="flex-1 px-3 py-1.5 border border-gray-200 rounded-lg text-xs">
                                                    <option value="">-- Pilih Bahan --</option>
                                                    <template x-for="m in $data.materials" :key="m.id">
                                                        <option :value="m.id" x-text="m.name + ' (' + m.unit + ')'"></option>
                                                    </template>
                                                </select>
                                                <input type="number" name="quantities[]" class="w-24 px-3 py-1.5 border border-gray-200 rounded-lg text-xs" placeholder="Qty" step="0.01" min="0.01">
                                                <button type="button" @click="addManualRow()"
                                                        class="px-2 py-1.5 bg-green-100 text-green-700 rounded-lg text-xs hover:bg-green-200">+</button>
                                            </div>
                                            <template x-for="rowId in manualRows" :key="rowId">
                                                <div class="flex gap-2 items-center mt-2">
                                                    <select name="material_ids[]" class="flex-1 px-3 py-1.5 border border-gray-200 rounded-lg text-xs">
                                                        <option value="">-- Pilih Bahan --</option>
                                                        <template x-for="m in $data.materials" :key="m.id">
                                                            <option :value="m.id" x-text="m.name + ' (' + m.unit + ')'"></option>
                                                        </template>
                                                    </select>
                                                    <input type="number" name="quantities[]" class="w-24 px-3 py-1.5 border border-gray-200 rounded-lg text-xs" placeholder="Qty" step="0.01" min="0.01">
                                                    <button type="button" @click="removeManualRow(rowId)"
                                                            class="px-2 py-1.5 bg-red-100 text-red-600 rounded-lg text-xs hover:bg-red-200">−</button>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <button type="submit"
                                    class="w-full bg-gray-700 hover:bg-gray-800 text-white text-xs font-semibold py-2.5 rounded-xl transition">
                                Simpan Perawatan
                            </button>
                        </form>

                        <hr class="border-gray-100 mb-4">

                        {{-- Riwayat Perawatan --}}
                        <h4 class="text-xs font-semibold text-gray-600 mb-3">Riwayat Perawatan</h4>
                        <template x-for="log in (planting.maintenance_logs || []).sort((a,b) => new Date(b.activity_date) - new Date(a.activity_date))" :key="log.id">
                            <div class="bg-gray-50 rounded-xl p-3 mb-2">
                                <div class="flex justify-between items-start">
                                    <div class="space-y-0.5">
                                        <p class="text-xs font-semibold text-gray-700" x-text="log.action_type"></p>
                                        <p class="text-xs text-gray-400">
                                            <span x-text="formatDateTime(log.activity_date)"></span>
                                            · <span x-text="log.user ? log.user.name : ''"></span>
                                        </p>
                                        <template x-if="log.nutrients_ppm">
                                            <p class="text-xs text-blue-500" x-text="'PPM: ' + log.nutrients_ppm"></p>
                                        </template>
                                        <template x-if="log.notes">
                                            <p class="text-xs text-gray-400 italic" x-text="log.notes"></p>
                                        </template>
                                        <template x-for="usage in log.material_usages || []" :key="usage.id">
                                            <span class="inline-block text-xs bg-white border border-gray-200 rounded-lg px-2 py-0.5 mr-1 mt-1"
                                                  x-text="(usage.material ? usage.material.name : '') + ' ' + usage.quantity_used + ' ' + (usage.material ? usage.material.unit : '')">
                                            </span>
                                        </template>
                                    </div>
                                    <form :action="`{{ url('maintenance-logs') }}/${log.id}`" method="POST">
                                        @csrf @method('DELETE')
                                        <button @click.prevent="$dispatch('confirm', { message: 'Hapus?', onConfirm: () => $el.closest('form') ? $el.closest('form').submit() : null })"
                                                class="text-red-400 hover:text-red-600 p-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </template>
                        <template x-if="!planting.maintenance_logs || planting.maintenance_logs.length === 0">
                            <p class="text-xs text-gray-400 text-center py-4">Belum ada riwayat perawatan.</p>
                        </template>

                    </div>
                </div>
            </div>
            </template>

            {{-- MODAL KUALITAS AIR --}}
            <template x-teleport="body">
            <div x-cloak x-show="showKualitasAir" x-transition x-init="$watch('showKualitasAir', val => document.body.style.overflow = val ? 'hidden' : '')"
                 class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
                 @click.self="showKualitasAir = false">
                <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-xl">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-700 text-sm">
                            Kualitas Air — <span x-text="planting.batch_code"></span>
                        </h3>
                        <button @click="showKualitasAir = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="p-5">

                        {{-- Form Kualitas Air --}}
                        <form action="{{ route('water-quality-logs.store') }}" method="POST" class="space-y-3 mb-5">
                            @csrf
                            <input type="hidden" name="planting_id" :value="planting.id">

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
                        <template x-for="log in (planting.water_quality_logs || []).sort((a,b) => new Date(b.checked_at) - new Date(a.checked_at))" :key="log.id">
                            <div class="bg-gray-50 rounded-xl p-3 mb-2">
                                <div class="flex justify-between items-start">
                                    <div class="space-y-0.5">
                                        <div class="flex items-center gap-2">
                                            <p class="text-xs font-semibold text-gray-700" x-text="'pH: ' + log.ph_level"></p>
                                            <span class="text-xs px-1.5 py-0.5 rounded-full"
                                                  :class="{
                                                      'bg-red-100 text-red-600': log.ph_level < 6,
                                                      'bg-green-100 text-green-600': log.ph_level >= 6 && log.ph_level <= 7,
                                                      'bg-yellow-100 text-yellow-600': log.ph_level > 7
                                                  }"
                                                  x-text="log.ph_level < 6 ? 'Asam' : (log.ph_level <= 7 ? 'Normal' : 'Basa')">
                                            </span>
                                        </div>
                                        <p class="text-xs text-gray-400">
                                            <span x-text="'TDS: ' + log.tds_ppm + ' ppm'"></span>
                                            <template x-if="log.water_temp">
                                                <span x-text="' · ' + log.water_temp + '°C'"></span>
                                            </template>
                                        </p>
                                        <p class="text-xs text-gray-400" x-text="formatDateTime(log.checked_at)"></p>
                                    </div>
                                    <form :action="`{{ url('water-quality-logs') }}/${log.id}`" method="POST">
                                        @csrf @method('DELETE')
                                        <button @click.prevent="$dispatch('confirm', { message: 'Hapus?', onConfirm: () => $el.closest('form') ? $el.closest('form').submit() : null })"
                                                class="text-red-400 hover:text-red-600 p-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </template>
                        <template x-if="!planting.water_quality_logs || planting.water_quality_logs.length === 0">
                            <p class="text-xs text-gray-400 text-center py-4">Belum ada riwayat kualitas air.</p>
                        </template>

                    </div>
                </div>
            </div>
            </template>

            {{-- MODAL PANEN --}}
            <template x-teleport="body">
            <div x-cloak x-show="showPanen" x-transition x-init="$watch('showPanen', val => document.body.style.overflow = val ? 'hidden' : '')"
                 class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
                 @click.self="showPanen = false">
                <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-xl">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-700 text-sm">
                            Panen — <span x-text="planting.batch_code"></span>
                        </h3>
                        <button @click="showPanen = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="p-5">

                        <template x-if="planting.harvest">
                        <div class="bg-green-50 border border-green-100 rounded-xl p-4 mb-4">
                            <p class="text-xs text-green-700 font-semibold mb-3">
                                Dipanen pada <span x-text="formatDate(planting.harvest.harvest_date)"></span>
                            </p>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs text-gray-400">Jumlah</p>
                                    <p class="text-sm font-bold text-gray-700"><span x-text="planting.harvest.total_yield_quantity"></span> unit</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Berat</p>
                                    <p class="text-sm font-bold text-gray-700"><span x-text="planting.harvest.total_yield_weight"></span> kg</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">Grade</p>
                                    <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                                          :class="{
                                              'bg-green-100 text-green-700': planting.harvest.quality_grade === 'Grade A',
                                              'bg-yellow-100 text-yellow-700': planting.harvest.quality_grade === 'Grade B',
                                              'bg-red-100 text-red-700': planting.harvest.quality_grade !== 'Grade A' && planting.harvest.quality_grade !== 'Grade B'
                                          }" x-text="planting.harvest.quality_grade">
                                    </span>
                                </div>
                            </div>
                        </div>
                        </template>

                        <template x-if="!planting.harvest && planting.status === 'in_progress'">
                        <div>
                            <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-3 mb-4">
                                <p class="text-xs text-yellow-700">Menyimpan panen akan otomatis membuat produk di toko dan mengubah status penanaman menjadi harvested.</p>
                            </div>

                            <form action="{{ route('harvests.store') }}" method="POST" class="space-y-3">
                                @csrf
                                <input type="hidden" name="planting_id" :value="planting.id">

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
                        </div>
                        </template>

                        <template x-if="!planting.harvest && planting.status !== 'in_progress'">
                        <div class="bg-red-50 border border-red-100 rounded-xl p-4">
                            <p class="text-xs text-red-600">Status penanaman <strong x-text="planting.status"></strong>, tidak bisa dipanen.</p>
                        </div>
                        </template>

                    </div>
                </div>
            </div>
            </template>

            {{-- MODAL EDIT --}}
            <template x-teleport="body">
            <div x-cloak x-show="showEdit" x-transition x-init="$watch('showEdit', val => document.body.style.overflow = val ? 'hidden' : '')"
                 class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
                 @click.self="showEdit = false">
                <div class="bg-white rounded-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-xl flex flex-col">
                    <form :action="`{{ url('plantings') }}/${planting.id}`" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
                            <h3 class="font-semibold text-gray-700 text-sm">
                                Edit Penanaman: <span x-text="planting.batch_code"></span>
                            </h3>
                            <button type="button" @click="showEdit = false" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="p-5 space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Jenis Tanaman</label>
                                <select name="plant_type_id" x-model="planting.plant_type_id" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-green-500" required>
                                    <option value="">-- Pilih Jenis Tanaman --</option>
                                    @foreach($plantTypes as $type)
                                        <option value="{{ $type->id }}">
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Penanggung Jawab (Petani)</label>
                                <select name="user_id" x-model="planting.user_id" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-green-500" required>
                                    <option value="">-- Pilih Petani --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Jumlah Bibit</label>
                                <input type="number" name="quantity_seeds" :value="planting.quantity_seeds" min="1" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-green-500" required>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Tanam</label>
                                <input type="date" name="start_date" :value="planting.start_date ? planting.start_date.split(' ')[0] : ''" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-green-500" required>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                                <select name="status" x-model="planting.status" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-green-500" required>
                                    <option value="in_progress">Proses</option>
                                    <option value="harvested">Panen</option>
                                    <option value="failed">Gagal</option>
                                </select>
                            </div>
                        </div>

                        <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex gap-2 justify-end rounded-b-2xl">
                            <button type="button" @click="showEdit = false" class="px-4 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 text-xs font-medium text-white bg-green-600 rounded-xl shadow-sm hover:bg-green-700 transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            </template>

        </div>
    </template>
    
    <template x-if="plantings.length === 0">
        <div class="col-span-4 text-center py-16 text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <p class="text-sm">Belum ada data penanaman.</p>
            <button @click="showAddModal = true; addStep = 1; selectedPlantType = '';" class="text-green-600 text-sm hover:underline mt-1 inline-block">Tambah sekarang</button>
        </div>
    </template>
</div>

{{-- Pagination --}}
<div class="flex justify-end mt-6">
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-md">
                    <template x-for="(link, index) in links" :key="index">
                        <span x-html="link.label === '&amp;laquo; Previous' ? '&laquo;' : (link.label === 'Next &amp;raquo;' ? '&raquo;' : link.label)"
                              @click="if(link.url) fetchData(link.url)"
                              class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium border"
                              :class="{
                                  'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 cursor-pointer': link.url && !link.active,
                                  'bg-gray-100 border-gray-300 text-gray-500 cursor-default': !link.url,
                                  'z-10 bg-green-50 border-green-500 text-green-600': link.active,
                                  'rounded-l-md': index === 0,
                                  'rounded-r-md': index === links.length - 1
                              }">
                        </span>
                    </template>
                </span>
            </div>
        </div>
    </nav>
</div>

    {{-- MODAL TAMBAH PENANAMAN --}}
    <template x-teleport="body">
        <div x-cloak x-show="showAddModal" x-transition x-init="$watch('showAddModal', val => document.body.style.overflow = val ? 'hidden' : '')"
             class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
             @click.self="showAddModal = false">
            <div class="bg-white rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-xl flex flex-col">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 shrink-0">
                    <h3 class="font-semibold text-gray-700 text-sm">
                        Tambah Penanaman
                    </h3>
                    <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="p-5 overflow-y-auto">
                    
                    {{-- Form Steps --}}
                    <form action="{{ route('plantings.store') }}" method="POST">
                        @csrf
                        
                        {{-- Step 1: Plant Type Selection --}}
                        <div x-show="addStep === 1" x-transition>
                            <p class="text-sm text-gray-500 mb-4">Pilih jenis tanaman yang akan ditanam:</p>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                @foreach($plantTypes as $type)
                                <div @click="setPlantType('{{ $type->id }}')" 
                                     class="cursor-pointer border-2 rounded-xl p-4 transition text-center"
                                     :class="selectedPlantType == '{{ $type->id }}' ? 'border-green-500 bg-green-50' : 'border-gray-100 hover:border-green-200 hover:bg-gray-50'">
                                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                    </div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-1">{{ $type->name }}</h4>
                                    <p class="text-xs text-gray-400">{{ $type->estimated_harvest_days }} Hari Panen</p>
                                </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="plant_type_id" :value="selectedPlantType" required>
                        </div>

                        {{-- Step 2: Form Details --}}
                        <div x-show="addStep === 2" x-transition x-cloak>
                            <div class="flex items-center text-sm mb-4">
                                <button type="button" @click="addStep = 1" class="text-gray-400 hover:text-green-600 mr-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                                </button>
                                <span class="text-gray-500 font-medium">Kembali ke pemilihan tanaman</span>
                            </div>

                            <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 mb-5">
                                <p class="text-xs text-blue-700">Kode batch (Batch Code) akan dibuat secara otomatis saat disimpan.</p>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Penanggung Jawab (Petani)</label>
                                    <select name="user_id" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                        <option value="">-- Pilih Petani --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Jumlah Bibit</label>
                                        <input type="number" name="quantity_seeds" min="1" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal Mulai (Semai)</label>
                                        <input type="date" name="start_date" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white text-sm font-semibold py-3 rounded-xl transition">
                                    Simpan Penanaman
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </template>
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
            if ("showEdit" in data) data.showEdit = false;
            document.body.style.overflow = "";
        }
    });
});
</script>
@endsection
