@extends('layouts.app')
@section('title', 'Bahan')
@section('content')

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

<form action="{{ route('materials.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 mb-4">
    <select name="category" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500" onchange="this.form.submit()">
        <option value="all">Semua Kategori</option>
        <option value="nutrient" {{ request('category') == 'nutrient' ? 'selected' : '' }}>Nutrisi / Pupuk</option>
        <option value="pesticide" {{ request('category') == 'pesticide' ? 'selected' : '' }}>Pestisida</option>
        <option value="operational" {{ request('category') == 'operational' ? 'selected' : '' }}>Operasional</option>
    </select>
    <select name="status" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-green-500" onchange="this.form.submit()">
        <option value="all">Semua Status</option>
        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
    </select>
</form>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-4">
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
                @forelse($materials as $material)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4">{{ $loop->iteration + $materials->firstItem() - 1 }}</td>
                    <td class="py-3 px-4"><span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs font-mono">{{ $material->code }}</span></td>
                    <td class="py-3 px-4 font-semibold">{{ $material->name }}</td>
                    <td class="py-3 px-4">
                        @if($material->category === 'nutrient')
                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Nutrisi</span>
                        @elseif($material->category === 'pesticide')
                            <span class="px-2 py-0.5 bg-orange-100 text-orange-700 rounded-full text-xs font-medium">Pestisida</span>
                        @else
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">Operasional</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">{{ $material->unit }}</td>
                    <td class="py-3 px-4 {{ $material->is_low_stock ? 'text-red-600 font-bold' : '' }}">
                        {{ $material->stock }}
                    </td>
                    <td class="py-3 px-4">{{ $material->min_stock }}</td>
                    <td class="py-3 px-4">Rp {{ number_format($material->price_per_unit, 0, ',', '.') }}</td>
                    <td class="py-3 px-4">
                        @if($material->status === 'active')
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-medium">Aktif</span>
                        @else
                            <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-medium">Nonaktif</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex gap-2">

                            <a href="{{ route('materials.edit', $material->id) }}" class="p-1.5 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
                            <form action="{{ route('materials.destroy', $material->id) }}" method="POST" @submit.prevent="$dispatch('confirm', { message: 'Yakin ingin menghapus bahan ini?', onConfirm: () => $el.submit() })">
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
                    <td colspan="10" class="py-8 text-center text-gray-400 text-sm">Belum ada data bahan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $materials->appends(request()->query())->links('pagination::tailwind') }}
    </div>
</div>

@endsection
