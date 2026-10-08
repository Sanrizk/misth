@extends('layouts.app')
@section('title', 'Pembelian Bahan')
@section('content')

<div x-data="{
        search: '',
    statusFilter: '{{ request('status') }}',
    startDate: '{{ request('start_date') }}',
    endDate: '{{ request('end_date') }}',
    purchases: {{ Js::from($purchases->items()) }},
    links: {{ Js::from($purchases->toArray()['links']) }},
    loading: false,
    
    init() {
        this.$watch('search', value => this.fetchData());
        this.$watch('statusFilter', value => this.fetchData());
        this.$watch('startDate', value => this.fetchData());
        this.$watch('endDate', value => this.fetchData());
    },
    
    fetchData(url = '{{ route('purchases.index') }}') {
        this.loading = true;
        const params = new URLSearchParams();
        if (this.search) params.append('search', this.search);
        if (this.statusFilter) params.append('status', this.statusFilter);
        if (this.startDate) params.append('start_date', this.startDate);
        if (this.endDate) params.append('end_date', this.endDate);
        
        const finalUrl = url.includes('?') ? `${url}&${params.toString()}` : `${url}?${params.toString()}`;
        
        fetch(finalUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            this.purchases = data.data;
            this.links = data.links;
            this.loading = false;
        })
        .catch(() => { this.loading = false; });
    },
    showAddModal: false,
    showEditModal: false,
    showStatusModal: false,
    selectedPurchase: null,
    form: {
        supplier_id: '',
        purchase_date: '{{ date('Y-m-d') }}',
        notes: '',
        items: [{ material_id: '', quantity: 1, unit_price: 0 }]
    },
    editForm: {
        id: '',
        supplier_id: '',
        purchase_date: '',
        notes: '',
        items: []
    },
    addItem() {
        this.form.items.push({ material_id: '', quantity: 1, unit_price: 0 });
    },
    removeItem(index) {
        this.form.items.splice(index, 1);
    },
    addEditItem() {
        this.editForm.items.push({ material_id: '', quantity: 1, unit_price: 0 });
    },
    removeEditItem(index) {
        this.editForm.items.splice(index, 1);
    },
    openStatusModal(purchase) {
        this.selectedPurchase = purchase;
        this.showStatusModal = true;
    },
    openEditModal(purchase) {
        fetch('/purchases/' + purchase.id)
            .then(res => res.json())
            .then(data => {
                this.editForm.id = data.id;
                this.editForm.supplier_id = data.supplier_id;
                this.editForm.purchase_date = data.purchase_date;
                this.editForm.notes = data.notes;
                if(data.purchase_items && data.purchase_items.length > 0) {
                    this.editForm.items = data.purchase_items.map(i => ({
                        material_id: i.material_id,
                        quantity: parseFloat(i.quantity),
                        unit_price: parseFloat(i.unit_price)
                    }));
                } else {
                    this.editForm.items = [];
                }
                this.showEditModal = true;
            });
    }
}">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-gray-700">Daftar Pembelian Bahan</h2>
        <button @click="showAddModal = true" class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Pembelian
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-xl p-3 text-sm text-green-700 flex items-center gap-3 mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-sm text-red-700 flex items-center gap-3 mb-4">
        {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-sm text-red-700 mb-4">
        <ul class="list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="flex flex-col sm:flex-row gap-3 mb-4">
        <input type="text" x-model.debounce.300ms="search" placeholder="Cari invoice atau supplier..." class="flex-1 px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        <select x-model="statusFilter" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <option value="">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="confirmed">Confirmed</option>
            <option value="received">Received</option>
            <option value="cancelled">Cancelled</option>
        </select>
        <input type="date" x-model="startDate" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        <input type="date" x-model="endDate" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
    </div>

    <div class="bg-white rounded-2xl shadow-sm overflow-x-auto mb-4">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase font-medium">
                <tr>
                    <th class="py-3 px-4">No. Invoice</th>
                    <th class="py-3 px-4">Tanggal</th>
                    <th class="py-3 px-4">Supplier</th>
                    <th class="py-3 px-4">Total</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-gray-700 relative">
                <template x-for="purchase in purchases" :key="purchase.id">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-xs font-mono text-gray-500" x-text="purchase.invoice_number"></td>
                        <td class="py-3 px-4 text-xs text-gray-500" x-text="new Date(purchase.purchase_date).toLocaleDateString('id-ID', {day:'2-digit', month:'short', year:'numeric'})"></td>
                        <td class="py-3 px-4 font-semibold text-gray-800" x-text="purchase.supplier ? purchase.supplier.name : '-'"></td>
                        <td class="py-3 px-4" x-text="'Rp ' + parseInt(purchase.total_amount).toLocaleString('id-ID')"></td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium"
                                :class="{
                                    'bg-gray-100 text-gray-700': purchase.status === 'draft',
                                    'bg-blue-100 text-blue-700': purchase.status === 'confirmed',
                                    'bg-green-100 text-green-700': purchase.status === 'received',
                                    'bg-red-100 text-red-700': purchase.status === 'cancelled'
                                }"
                                x-text="purchase.status.charAt(0).toUpperCase() + purchase.status.slice(1)">
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex gap-2">
                                <button @click="openStatusModal(purchase)" class="text-xs px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition">Status</button>
                                
                                <template x-if="purchase.status === 'draft'">
                                    <button @click="openEditModal(purchase)" class="p-1.5 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>
                                </template>
                                
                                <template x-if="purchase.status === 'draft'">
                                    <form :action="'/purchases/' + purchase.id" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Hapus" @click.prevent="$dispatch('confirm', { message: 'Yakin hapus pembelian ini?', onConfirm: () => $el.closest('form').submit() })">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </template>
                            </div>
                        </td>
                    </tr>
                </template>
                <tr x-show="purchases.length === 0 && !loading" x-cloak>
                    <td colspan="6" class="py-8 text-center text-gray-400 text-sm">Tidak ada data pembelian.</td>
                </tr>
            </tbody>
        </table>
    </div>

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

    <template x-teleport="body">
        <div>
            <!-- ADD MODAL -->
            <div x-cloak x-show="showAddModal" x-transition x-init="$watch('showAddModal', val => document.body.style.overflow = val ? 'hidden' : '')"
                 class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
                 @click.self="showAddModal = false">
                <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-xl flex flex-col">
                        <form action="{{ route('purchases.store') }}" method="POST">
                            @csrf
                            <div class="px-6 py-5 bg-white">
                                <div class="flex items-center justify-between mb-5">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900">Tambah Pembelian</h3>
                                    <button type="button" @click="showAddModal = false" class="text-gray-400 hover:text-gray-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                    <select name="supplier_id" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                        <option value="">Pilih Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="date" name="purchase_date" x-model="form.purchase_date" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                    
                                    <div class="col-span-1 sm:col-span-2">
                                        <textarea name="notes" placeholder="Catatan (Opsional)" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500" rows="3"></textarea>
                                    </div>
                                </div>

                                <hr class="my-5 border-gray-100">
                                <h4 class="mb-3 text-sm font-medium text-gray-900">Item Pembelian</h4>
                                
                                <template x-for="(item, index) in form.items" :key="index">
                                    <div class="flex flex-col sm:flex-row gap-3 mb-3 items-start sm:items-center">
                                        <div class="flex-1 w-full sm:w-auto">
                                            <select :name="'items['+index+'][material_id]'" x-model="item.material_id" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                                <option value="">Pilih Bahan</option>
                                                @foreach($materials as $material)
                                                    <option value="{{ $material->id }}">{{ $material->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="w-full sm:w-32">
                                            <input type="number" step="0.01" :name="'items['+index+'][quantity]'" x-model="item.quantity" placeholder="Qty" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                        </div>
                                        <div class="w-full sm:w-48">
                                            <input type="number" step="0.01" :name="'items['+index+'][unit_price]'" x-model="item.unit_price" placeholder="Harga Satuan" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                        </div>
                                        <button type="button" @click="removeItem(index)" class="p-2 text-red-500 hover:text-red-700 bg-red-50 rounded-lg hover:bg-red-100" x-show="form.items.length > 1" title="Hapus Item">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </template>
                                
                                <button type="button" @click="addItem()" class="inline-flex items-center gap-2 mt-2 text-sm font-medium text-green-600 hover:text-green-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Tambah Item
                                </button>
                            </div>
                            <div class="px-6 py-4 bg-gray-50 rounded-b-2xl sm:flex sm:flex-row-reverse border-t border-gray-100">
                                <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-xl shadow-sm hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto">
                                    Simpan Pembelian
                                </button>
                                <button type="button" @click="showAddModal = false" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
            </div>

            <!-- EDIT MODAL -->
            <div x-cloak x-show="showEditModal" x-transition x-init="$watch('showEditModal', val => document.body.style.overflow = val ? 'hidden' : '')"
                 class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
                 @click.self="showEditModal = false">
                <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-xl flex flex-col">
                        <form :action="`/purchases/${editForm.id}`" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="px-6 py-5 bg-white">
                                <div class="flex items-center justify-between mb-5">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900">Ubah Pembelian</h3>
                                    <button type="button" @click="showEditModal = false" class="text-gray-400 hover:text-gray-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                    <select name="supplier_id" x-model="editForm.supplier_id" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                        <option value="">Pilih Supplier</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="date" name="purchase_date" x-model="editForm.purchase_date" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                    
                                    <div class="col-span-1 sm:col-span-2">
                                        <textarea name="notes" x-model="editForm.notes" placeholder="Catatan (Opsional)" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500" rows="3"></textarea>
                                    </div>
                                </div>

                                <hr class="my-5 border-gray-100">
                                <h4 class="mb-3 text-sm font-medium text-gray-900">Item Pembelian</h4>
                                
                                <template x-for="(item, index) in editForm.items" :key="index">
                                    <div class="flex flex-col sm:flex-row gap-3 mb-3 items-start sm:items-center">
                                        <div class="flex-1 w-full sm:w-auto">
                                            <select :name="'items['+index+'][material_id]'" x-model="item.material_id" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                                <option value="">Pilih Bahan</option>
                                                @foreach($materials as $material)
                                                    <option value="{{ $material->id }}">{{ $material->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="w-full sm:w-32">
                                            <input type="number" step="0.01" :name="'items['+index+'][quantity]'" x-model="item.quantity" placeholder="Qty" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                        </div>
                                        <div class="w-full sm:w-48">
                                            <input type="number" step="0.01" :name="'items['+index+'][unit_price]'" x-model="item.unit_price" placeholder="Harga Satuan" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                                        </div>
                                        <button type="button" @click="removeEditItem(index)" class="p-2 text-red-500 hover:text-red-700 bg-red-50 rounded-lg hover:bg-red-100" title="Hapus Item">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </template>
                                
                                <button type="button" @click="addEditItem()" class="inline-flex items-center gap-2 mt-2 text-sm font-medium text-green-600 hover:text-green-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    Tambah Item
                                </button>
                            </div>
                            <div class="px-6 py-4 bg-gray-50 rounded-b-2xl sm:flex sm:flex-row-reverse border-t border-gray-100">
                                <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-xl shadow-sm hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto">
                                    Ubah Pembelian
                                </button>
                                <button type="button" @click="showEditModal = false" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
            </div>

            <!-- STATUS MODAL -->
            <div x-cloak x-show="showStatusModal" x-transition x-init="$watch('showStatusModal', val => document.body.style.overflow = val ? 'hidden' : '')"
                 class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
                 @click.self="showStatusModal = false">
                <div class="bg-white rounded-2xl w-full max-w-md max-h-[90vh] overflow-y-auto shadow-xl flex flex-col">
                        <form :action="selectedPurchase ? '/purchases/' + selectedPurchase.id + '/status' : ''" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="px-6 py-5 bg-white">
                                <div class="flex items-center justify-between mb-5">
                                    <h3 class="text-lg font-medium leading-6 text-gray-900">Update Status Pembelian</h3>
                                    <button type="button" @click="showStatusModal = false" class="text-gray-400 hover:text-gray-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                                
                                <div>
                                    <select name="status" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500" :value="selectedPurchase?.status">
                                        <option value="draft">Draft</option>
                                        <option value="confirmed">Confirmed</option>
                                        <option value="received">Received</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="px-6 py-4 bg-gray-50 rounded-b-2xl sm:flex sm:flex-row-reverse border-t border-gray-100">
                                <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-xl shadow-sm hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto">
                                    Update Status
                                </button>
                                <button type="button" @click="showStatusModal = false" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
            </div>
        </div>
    </template>

</div>

@endsection
