@extends('layouts.app')
@section('title', 'Supplier')
@section('content')

<div x-data="{
    showModal: false,
    modalMode: 'add',
    form: { id: '', name: '', phone: '', address: '' },
    openAddModal() {
        this.modalMode = 'add';
        this.form = { id: '', name: '', phone: '', address: '' };
        this.showModal = true;
    },
    openEditModal(supplier) {
        this.modalMode = 'edit';
        this.form = { ...supplier };
        this.showModal = true;
    }
}">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-semibold text-gray-700">Daftar Supplier</h2>
        <button @click="openAddModal()" class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Supplier
        </button>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-xl p-3 text-sm text-green-700 flex items-center gap-3 mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm overflow-x-auto mb-4">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase font-medium">
                <tr>
                    <th class="py-3 px-4">Nama</th>
                    <th class="py-3 px-4">Telepon</th>
                    <th class="py-3 px-4">Alamat</th>
                    <th class="py-3 px-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-gray-700">
                @forelse($suppliers as $supplier)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 font-semibold">{{ $supplier->name }}</td>
                    <td class="py-3 px-4">{{ $supplier->phone }}</td>
                    <td class="py-3 px-4">{{ $supplier->address }}</td>
                    <td class="py-3 px-4">
                        <div class="flex gap-2">
                            <button @click="openEditModal({{ json_encode($supplier) }})" class="p-1.5 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            
                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus supplier ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-8 text-center text-gray-400 text-sm">Belum ada data supplier.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-4 py-3 border-t border-gray-100">
        {{ $suppliers->links('pagination::tailwind') }}
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
