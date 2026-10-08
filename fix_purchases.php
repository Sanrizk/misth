<?php
$file = 'resources/views/purchases/index.blade.php';
$content = file_get_contents($file);

// 1. Fix x-data
$old_xdata = <<<HTML
<div x-data="{
    showAddModal: false,
    showStatusModal: false,
    selectedPurchase: null,
    form: {
        supplier_id: '',
        purchase_date: '{{ date('Y-m-d') }}',
        notes: '',
        items: [{ material_id: '', quantity: 1, unit_price: 0 }]
    },
    addItem() {
        this.form.items.push({ material_id: '', quantity: 1, unit_price: 0 });
    },
    removeItem(index) {
        this.form.items.splice(index, 1);
    },
    openStatusModal(purchase) {
        this.selectedPurchase = purchase;
        this.showStatusModal = true;
    }
}">
HTML;

$new_xdata = <<<HTML
<div x-data="{
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
HTML;

$content = str_replace($old_xdata, $new_xdata, $content);

// 2. Fix the template teleport issue and inject edit modal
$old_template_start = '<template x-teleport="body">';
$new_template_start = <<<HTML
    <template x-teleport="body">
        <div>
HTML;
$content = str_replace($old_template_start, $new_template_start, $content);

$old_template_end = '</template>';
$edit_modal = <<<HTML
        <div x-cloak x-show="showEditModal" x-transition x-init="\$watch('showEditModal', val => document.body.style.overflow = val ? 'hidden' : '')"
             class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
             @click.self="showEditModal = false">
            <div class="bg-white rounded-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto shadow-xl flex flex-col">
                    <form :action="`/purchases/\${editForm.id}`" method="POST">
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
                                    @foreach(\$suppliers as \$supplier)
                                        <option value="{{ \$supplier->id }}">{{ \$supplier->name }}</option>
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
                                            @foreach(\$materials as \$material)
                                                <option value="{{ \$material->id }}">{{ \$material->name }}</option>
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
        </div>
    </template>
HTML;

$content = str_replace($old_template_end, $edit_modal, $content);

// 3. Fix the edit button missing in the table
$old_buttons = <<<HTML
                            @if(\$purchase->status === 'draft')
                            <form action="{{ route('purchases.destroy', \$purchase) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Hapus">
HTML;
$new_buttons = <<<HTML
                            @if(\$purchase->status === 'draft')
                            <button @click="openEditModal({{ json_encode(\$purchase) }})" class="p-1.5 bg-amber-100 text-amber-600 rounded-lg hover:bg-amber-200 transition" title="Ubah">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <form action="{{ route('purchases.destroy', \$purchase) }}" method="POST" @submit.prevent="\$dispatch('confirm', { message: 'Yakin ingin menghapus?', onConfirm: () => \$el.submit() })">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Hapus">
HTML;
$content = str_replace($old_buttons, $new_buttons, $content);

file_put_contents($file, $content);
