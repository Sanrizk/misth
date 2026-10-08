@extends('layouts.app')
@section('title', 'Supplier')
@section('content')

<div x-data="{
    search: '',
    suppliers: {{ Js::from($suppliers->items()) }},
    links: {{ Js::from($suppliers->toArray()['links']) }},
    showModal: false,
    modalMode: 'add',
    form: { id: '', name: '', phone: '', address: '' },
    
    fetchData(url = '{{ route('suppliers.index') }}') {
        const fetchUrl = url.includes('?') ? `${url}&search=${this.search}` : `${url}?search=${this.search}`;
        fetch(fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            this.suppliers = data.data;
            this.links = data.links;
        });
    },

    init() {
        this.$watch('search', value => {
            this.fetchData('{{ route('suppliers.index') }}');
        });
    },

    openAddModal() {
        this.modalMode = 'add';
        this.form = { id: '', name: '', phone: '', address: '' };
        this.showModal = true;
    },
    openEditModal(supplier) {
        this.modalMode = 'edit';
        this.form = { ...supplier };
        this.showModal = true;
    },
    deleteSupplier(id) {
        if(confirm('Yakin ingin menghapus supplier ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/suppliers/' + id;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name=csrf-token]').content;
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
}">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h2 class="text-lg font-semibold text-gray-700">Daftar Supplier</h2>
        
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <div class="relative flex-1 sm:w-64">
                <input type="text" x-model.debounce.300ms="search" placeholder="Cari supplier..."
                       class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500 transition">
                <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            
            <button @click="openAddModal()" class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition flex items-center gap-2 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span class="hidden sm:inline">Tambah Supplier</span>
                <span class="sm:hidden">Tambah</span>
            </button>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-xl p-3 text-sm text-green-700 flex items-center gap-3 mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-4">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase font-medium">
                    <tr>
                        <th class="py-3 px-4">Nama</th>
                        <th class="py-3 px-4">Telepon</th>
                        <th class="py-3 px-4">Alamat</th>
                        <th class="py-3 px-4 w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700">
                    <template x-for="supplier in suppliers" :key="supplier.id">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 font-semibold text-gray-800" x-text="supplier.name"></td>
                            <td class="py-3 px-4" x-text="supplier.phone || '-'"></td>
                            <td class="py-3 px-4 truncate max-w-xs" x-text="supplier.address || '-'"></td>
                            <td class="py-3 px-4">
                                <div class="flex gap-2">
                                    <button @click="openEditModal(supplier)" class="p-1.5 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                    
                                    <button @click="deleteSupplier(supplier.id)" class="p-1.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    
                    <tr x-show="suppliers.length === 0">
                        <td colspan="4" class="py-8 text-center text-gray-400 text-sm">Tidak ada data ditemukan.</td>
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
        <div x-cloak x-show="showModal" x-transition x-init="$watch('showModal', val => document.body.style.overflow = val ? 'hidden' : '')"
             class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
             @click.self="showModal = false">
            <div class="bg-white rounded-2xl w-full max-w-md max-h-[90vh] overflow-y-auto shadow-xl flex flex-col">
                    <form :action="modalMode === 'add' ? '{{ route('suppliers.store') }}' : '/suppliers/' + form.id" method="POST">
                        @csrf
                        <template x-if="modalMode === 'edit'">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="px-6 py-5 bg-white">
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" x-text="modalMode === 'add' ? 'Tambah Supplier' : 'Edit Supplier'"></h3>
                                <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <input type="text" name="name" x-model="form.name" placeholder="Nama Supplier" required class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                </div>
                                <div>
                                    <input type="text" name="phone" x-model="form.phone" placeholder="Nomor Telepon" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                </div>
                                <div>
                                    <textarea name="address" x-model="form.address" placeholder="Alamat" rows="3" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 py-4 bg-gray-50 rounded-b-2xl sm:flex sm:flex-row-reverse border-t border-gray-100">
                            <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-xl shadow-sm hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto">
                                Simpan
                            </button>
                            <button type="button" @click="showModal = false" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
        </div>
    </template>
</div>

@endsection