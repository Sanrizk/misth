@extends('layouts.app')
@section('title', 'Bahan')
@section('content')

<div x-data="materialIndex()" class="space-y-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-gray-700">Daftar Bahan & Nutrisi</h2>
        <a href="{{ route('materials.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Bahan
        </a>
    </div>

    @if($materials->contains(fn($m) => $m->is_low_stock))
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-3 text-sm text-yellow-700 flex items-center gap-3 mb-4">
        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        <div>
            <strong>Perhatian!</strong> Ada bahan yang stoknya menipis! Segera lakukan pengadaan.
        </div>
    </div>
    @endif

    {{-- Filters & Search --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-4">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" x-model.debounce.300ms="search" placeholder="Cari kode atau nama bahan..." 
                   class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>
        <select x-model="categoryFilter" @change="fetchData()"
                class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="all">Semua Kategori</option>
            <option value="nutrient">Nutrisi / Pupuk</option>
            <option value="pesticide">Pestisida</option>
            <option value="operational">Operasional</option>
        </select>
        <select x-model="statusFilter" @change="fetchData()"
                class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="all">Semua Status</option>
            <option value="active">Aktif</option>
            <option value="inactive">Nonaktif</option>
        </select>
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden relative">
        {{-- Loading Overlay --}}
        <div x-show="loading" class="absolute inset-0 bg-white/50 backdrop-blur-sm z-10 flex items-center justify-center">
            <svg class="animate-spin h-8 w-8 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase font-medium">
                    <tr>
                        <th class="py-3 px-4">No</th>
                        <th class="py-3 px-4">Kode</th>
                        <th class="py-3 px-4">Nama</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Satuan</th>
                        <th class="py-3 px-4">Stok</th>
                        <th class="py-3 px-4">Min Stok</th>
                        <th class="py-3 px-4">Harga/Unit</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700">
                    <template x-for="(material, index) in materials" :key="material.id">
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4" x-text="index + 1 + (currentPage - 1) * perPage"></td>
                            <td class="py-3 px-4"><span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-mono" x-text="material.code"></span></td>
                            <td class="py-3 px-4 font-semibold" x-text="material.name"></td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium"
                                      :class="{
                                          'bg-blue-100 text-blue-700': material.category === 'nutrient',
                                          'bg-orange-100 text-orange-700': material.category === 'pesticide',
                                          'bg-gray-100 text-gray-700': material.category !== 'nutrient' && material.category !== 'pesticide'
                                      }"
                                      x-text="material.category === 'nutrient' ? 'Nutrisi' : (material.category === 'pesticide' ? 'Pestisida' : 'Operasional')">
                                </span>
                            </td>
                            <td class="py-3 px-4" x-text="material.unit"></td>
                            <td class="py-3 px-4" 
                                :class="{'text-red-600 font-bold': material.stock <= material.min_stock}"
                                x-text="material.stock">
                            </td>
                            <td class="py-3 px-4" x-text="material.min_stock"></td>
                            <td class="py-3 px-4" x-text="formatCurrency(material.price_per_unit)"></td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium"
                                      :class="material.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                      x-text="material.status === 'active' ? 'Aktif' : 'Nonaktif'">
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <button @click="openDetailModal(material)" class="p-1.5 bg-sky-100 text-sky-600 rounded-lg hover:bg-sky-200 transition cursor-pointer" title="Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </button>
                                    <button @click="openEditModal(material)" class="p-1.5 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition cursor-pointer" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    <form :action="`/materials/${material.id}`" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Hapus"
                                                @click.prevent="$dispatch('confirm', { message: 'Yakin ingin menghapus bahan ini?', onConfirm: () => $el.closest('form').submit() })">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <tr x-show="materials.length === 0 && !loading" x-cloak>
                        <td colspan="10" class="py-8 text-center text-gray-400 text-sm">Belum ada data bahan.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Alpine Custom Pagination --}}
        <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between" x-show="links.length > 3" x-cloak>
            <div class="flex flex-wrap gap-1">
                <template x-for="(link, index) in links" :key="index">
                    <button @click.prevent="if(link.url) fetchData(link.url)"
                            x-html="link.label"
                            :disabled="!link.url || link.active"
                            :class="{
                                'bg-green-600 text-white font-medium': link.active,
                                'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200': !link.active && link.url,
                                'bg-gray-50 text-gray-400 border border-gray-100 cursor-not-allowed': !link.url
                            }"
                            class="px-3 py-1.5 min-w-[2rem] text-sm rounded-lg transition-colors">
                    </button>
                </template>
            </div>
        </div>
    </div>

    <template x-teleport="body">
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" x-cloak>
            <div @click.away="closeModal()" class="bg-white rounded-2xl w-full max-w-lg p-6 shadow-xl relative max-h-[90vh] overflow-y-auto"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <button @click="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <h3 class="text-lg font-bold text-gray-800 mb-4" x-text="modalMode === 'edit' ? 'Edit Bahan' : 'Detail Bahan'"></h3>
                
                <template x-if="modalMode === 'edit'">
                    <form :action="`/materials/${form.id}`" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Bahan</label>
                                    <input type="text" name="code" x-model="form.code" required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bahan</label>
                                    <input type="text" name="name" x-model="form.name" required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500 outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                                    <select name="category" x-model="form.category" required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500 outline-none">
                                        <option value="nutrient">Nutrisi</option>
                                        <option value="pesticide">Pestisida</option>
                                        <option value="operational">Operasional</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                                    <input type="text" name="unit" x-model="form.unit" required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500 outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok Saat Ini</label>
                                    <input type="number" step="0.01" name="stock" x-model="form.stock" required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Stok</label>
                                    <input type="number" step="0.01" name="min_stock" x-model="form.min_stock" required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500 outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga per Unit</label>
                                    <input type="number" step="0.01" name="price_per_unit" x-model="form.price_per_unit" required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500 outline-none">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                    <select name="status" x-model="form.status" required class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500 outline-none">
                                        <option value="active">Aktif</option>
                                        <option value="inactive">Nonaktif</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                <textarea name="description" x-model="form.description" rows="2" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500 outline-none"></textarea>
                            </div>
                            <div class="flex justify-end gap-2 mt-6">
                                <button type="button" @click="closeModal()" class="px-4 py-2 text-sm text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 cursor-pointer">Batal</button>
                                <button type="submit" class="px-4 py-2 text-sm text-white bg-green-600 rounded-xl hover:bg-green-700 cursor-pointer">Simpan Perubahan</button>
                            </div>
                        </div>
                    </form>
                </template>
                
                <template x-if="modalMode === 'detail'">
                    <div class="space-y-4 text-sm">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex flex-col">
                                <span class="text-gray-500 font-medium">Kode Bahan</span>
                                <span class="text-gray-800 font-semibold" x-text="form.code"></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-500 font-medium">Nama Bahan</span>
                                <span class="text-gray-800" x-text="form.name"></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-500 font-medium">Kategori</span>
                                <span class="text-gray-800 capitalize" x-text="form.category"></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-500 font-medium">Satuan</span>
                                <span class="text-gray-800" x-text="form.unit"></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-500 font-medium">Stok Saat Ini</span>
                                <span class="text-gray-800" :class="{'text-red-600 font-bold': form.stock <= form.min_stock}" x-text="form.stock + ' ' + form.unit"></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-500 font-medium">Min Stok</span>
                                <span class="text-gray-800" x-text="form.min_stock + ' ' + form.unit"></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-500 font-medium">Harga / Unit</span>
                                <span class="text-gray-800" x-text="formatCurrency(form.price_per_unit)"></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-500 font-medium">Status</span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium w-max"
                                      :class="form.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'"
                                      x-text="form.status === 'active' ? 'Aktif' : 'Nonaktif'">
                                </span>
                            </div>
                            <div class="flex flex-col col-span-2">
                                <span class="text-gray-500 font-medium">Deskripsi</span>
                                <span class="text-gray-800" x-text="form.description || '-'"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </template>

</div>

<script>
function materialIndex() {
    return {
        materials: {{ Js::from($materials->items()) }},
        links: {{ Js::from($materials->toArray()["links"]) }},
        search: '{{ request("search") }}',
        categoryFilter: '{{ request("category", "all") }}',
        statusFilter: '{{ request("status", "all") }}',
        loading: false,
        showModal: false,
        modalMode: 'create',
        form: {
            id: null,
            name: '',
            code: '',
            category: 'nutrient',
            unit: 'Kg',
            stock: 0,
            min_stock: 0,
            price_per_unit: 0,
            status: 'active',
            description: ''
        },
        openEditModal(material) {
            this.modalMode = 'edit';
            this.form = {
                id: material.id,
                name: material.name,
                code: material.code,
                category: material.category,
                unit: material.unit,
                stock: material.stock,
                min_stock: material.min_stock,
                price_per_unit: material.price_per_unit,
                status: material.status,
                description: material.description || ''
            };
            this.showModal = true;
        },
        openDetailModal(material) {
            this.modalMode = 'detail';
            this.form = { ...material };
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
        },

        currentPage: {{ $materials->currentPage() }},
        perPage: {{ $materials->perPage() }},

        init() {
            this.$watch('search', value => {
                this.fetchData();
            });
        },

        fetchData(url = '{{ route("materials.index") }}') {
            this.loading = true;
            
            const params = new URLSearchParams();
            if (this.search) params.append('search', this.search);
            if (this.categoryFilter && this.categoryFilter !== 'all') params.append('category', this.categoryFilter);
            if (this.statusFilter && this.statusFilter !== 'all') params.append('status', this.statusFilter);

            const hasQueryParams = url.includes('?');
            const finalUrl = `${url}${hasQueryParams ? '&' : '?'}${params.toString()}`;

            fetch(finalUrl, {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                this.materials = data.data;
                this.links = data.links;
                this.currentPage = data.current_page;
                this.perPage = data.per_page;
            })
            .finally(() => {
                this.loading = false;
            });
        },
        
        formatCurrency(value) {
            return 'Rp ' + parseInt(value).toLocaleString('id-ID');
        }
    }
}
</script>
@endsection
