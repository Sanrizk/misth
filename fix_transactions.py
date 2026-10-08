import re

with open('resources/views/transactions/index.blade.php', 'r') as f:
    content = f.read()

# 1. Inject openEditModal into x-data
xdata_target = "manualInvoice: '',"
xdata_new = """manualInvoice: '',
        editForm: { id: '', payment_method: '' },
        showEditModal: false,
        openEditModal(trx) {
            this.editForm.id = trx.id;
            this.editForm.payment_method = trx.payment_method;
            this.showEditModal = true;
        },"""

if xdata_target in content:
    content = content.replace(xdata_target, xdata_new)
else:
    print("Warning: manualInvoice not found in x-data")

# 2. Add Edit and Delete buttons
buttons_target = """<td class="py-3 px-4">
                        <a href="{{ route('transactions.show', $trx->id) }}"
                           class="text-xs px-3 py-1 bg-sky-100 text-sky-700 rounded-lg hover:bg-sky-200 transition">
                            Detail
                        </a>
                    </td>"""
buttons_new = """<td class="py-3 px-4">
                        <div class="flex gap-2">
                            <a href="{{ route('transactions.show', $trx->id) }}"
                               class="text-xs px-3 py-1.5 bg-sky-100 text-sky-700 rounded-lg hover:bg-sky-200 transition">
                                Detail
                            </a>
                            <button @click="openEditModal({{ json_encode($trx) }})" class="p-1.5 bg-amber-100 text-amber-600 rounded-lg hover:bg-amber-200 transition" title="Ubah">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <form action="{{ route('transactions.destroy', $trx->id) }}" method="POST" @submit.prevent="$dispatch('confirm', { message: 'Yakin ingin menghapus transaksi ini?', onConfirm: () => $el.submit() })">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>"""

if buttons_target in content:
    content = content.replace(buttons_target, buttons_new)
else:
    print("Warning: Detail button target not found")

# 3. Add modal HTML
style_target = "</style>"
modal_html = """</style>
<template x-teleport="body">
    <div x-cloak x-show="showEditModal" x-transition 
         class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
         @click.self="showEditModal = false">
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-xl flex flex-col">
            <form :action="`/transactions/${editForm.id}`" method="POST">
                @csrf
                @method('PUT')
                <div class="px-6 py-5 bg-white">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Ubah Transaksi</h3>
                        <button type="button" @click="showEditModal = false" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran</label>
                        <select name="payment_method" x-model="editForm.payment_method" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500" required>
                            <option value="cash">Tunai (Cash)</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="qris">QRIS</option>
                        </select>
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 sm:flex sm:flex-row-reverse border-t border-gray-100">
                    <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-xl shadow-sm hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto">
                        Simpan Perubahan
                    </button>
                    <button type="button" @click="showEditModal = false" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
"""
if style_target in content:
    content = content.replace(style_target, modal_html)
else:
    print("Warning: </style> not found")

with open('resources/views/transactions/index.blade.php', 'w') as f:
    f.write(content)
