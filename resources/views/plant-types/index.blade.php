@extends('layouts.app')
@section('title', 'Jenis Tanaman')
@section('content')

<div x-data="{
    showModal: false,
    modalMode: 'add',
    form: { id: '', name: '', estimated_harvest_days: '', description: '' },
    openAddModal() {
        this.modalMode = 'add';
        this.form = { id: '', name: '', estimated_harvest_days: '', description: '' };
        this.showModal = true;
    },
    openEditModal(plantType) {
        this.modalMode = 'edit';
        this.form = { ...plantType };
        this.showModal = true;
    }
}">

<div class="flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold text-gray-700">Jenis Tanaman</h2>
    <button @click="openAddModal()"
       class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
        + Tambah
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">No</th>
                    <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Nama</th>
                    <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Est. Panen</th>
                    <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Deskripsi</th>
                    <th class="text-left py-3 px-4 text-xs font-medium text-gray-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($plantTypes as $plantType)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 text-gray-500">{{ $loop->iteration }}</td>
                    <td class="py-3 px-4 font-medium text-gray-800">{{ $plantType->name }}</td>
                    <td class="py-3 px-4">
                        <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs">
                            {{ $plantType->estimated_harvest_days }} hari
                        </span>
                    </td>
                    <td class="py-3 px-4 text-gray-500 text-xs">
                        {{ \Illuminate\Support\Str::limit($plantType->description, 50) ?? '-' }}
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex gap-2">
                            <button @click="openEditModal({{ json_encode($plantType) }})"
                               class="text-xs px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition">
                                Edit
                            </button>
                            <form action="{{ route('plant-types.destroy', $plantType->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button @click.prevent="$dispatch('confirm', { message: 'Yakin hapus?', onConfirm: () => $el.closest('form') ? $el.closest('form').submit() : null })"
                                        class="text-xs px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-gray-400 text-xs">Belum ada data.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $plantTypes->links('pagination::tailwind') }}
    </div>
</div>

    <template x-teleport="body">
        <div x-cloak x-show="showModal" x-transition x-init="$watch('showModal', val => document.body.style.overflow = val ? 'hidden' : '')"
             class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
             @click.self="showModal = false">
            <div class="bg-white rounded-2xl w-full max-w-md max-h-[90vh] overflow-y-auto shadow-xl flex flex-col">
                    <form :action="modalMode === 'add' ? '{{ route('plant-types.store') }}' : '/plant-types/' + form.id" method="POST">
                        @csrf
                        <template x-if="modalMode === 'edit'">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="px-6 py-5 bg-white">
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" x-text="modalMode === 'add' ? 'Tambah Jenis Tanaman' : 'Edit Jenis Tanaman'"></h3>
                                <button type="button" @click="showModal = false" class="text-gray-400 hover:text-gray-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <input type="text" name="name" x-model="form.name" placeholder="Nama Tanaman" required class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                </div>
                                <div>
                                    <input type="number" name="estimated_harvest_days" x-model="form.estimated_harvest_days" placeholder="Estimasi Panen (Hari)" required class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                </div>
                                <div>
                                    <textarea name="description" x-model="form.description" placeholder="Deskripsi" rows="3" class="w-full px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
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
