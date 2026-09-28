@extends('layouts.app')
@section('title', 'Detail Bahan')
@section('content')

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-1">
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-4">
            <div class="flex justify-between items-start mb-4">
                <h5 class="font-bold text-lg text-gray-800">Detail Bahan</h5>
                <a href="{{ route('materials.edit', $material->id) }}" class="text-sm text-yellow-600 hover:text-yellow-700 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit
                </a>
            </div>

            @if($material->is_low_stock)
            <div class="inline-flex items-center gap-1 bg-red-100 text-red-700 rounded-full px-2 py-0.5 text-xs font-medium mb-4">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Stok Menipis
            </div>
            @endif

            <div class="space-y-3 text-sm">
                <div class="flex justify-between border-b border-gray-50 pb-2">
                    <span class="text-gray-500">Kode</span>
                    <span class="font-semibold text-gray-800 font-mono">{{ $material->code }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-50 pb-2">
                    <span class="text-gray-500">Nama</span>
                    <span class="font-semibold text-gray-800">{{ $material->name }}</span>
                </div>
                <div class="flex justify-between border-b border-gray-50 pb-2">
                    <span class="text-gray-500">Kategori</span>
                    <span>
                        @if($material->category === 'nutrient')
                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Nutrisi</span>
                        @elseif($material->category === 'pesticide')
                            <span class="px-2 py-0.5 bg-orange-100 text-orange-700 rounded-full text-xs font-medium">Pestisida</span>
                        @else
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded-full text-xs font-medium">Operasional</span>
                        @endif
                    </span>
                </div>
                <div class="flex justify-between border-b border-gray-50 pb-2">
                    <span class="text-gray-500">Status</span>
                    <span>
                        @if($material->status === 'active')
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-medium">Aktif</span>
                        @else
                            <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-medium">Nonaktif</span>
                        @endif
                    </span>
                </div>
                <div class="flex justify-between pb-2">
                    <span class="text-gray-500">Harga/Unit</span>
                    <span class="font-semibold text-gray-800">Rp {{ number_format($material->price_per_unit, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-4 p-4 bg-gray-50 rounded-xl text-center">
                <h3 class="text-3xl font-bold {{ $material->is_low_stock ? 'text-red-600' : 'text-green-600' }}">
                    {{ $material->stock }} <span class="text-lg text-gray-500 font-normal">{{ $material->unit }}</span>
                </h3>
                <p class="text-xs text-gray-500 mt-1">Stok Tersedia (Min: {{ $material->min_stock }})</p>
            </div>

            @if($material->description)
            <div class="mt-5">
                <h6 class="text-sm font-semibold text-gray-700 mb-1">Deskripsi</h6>
                <p class="text-sm text-gray-500">{{ $material->description }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="md:col-span-2 space-y-4">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h5 class="font-bold text-gray-800 mb-4">Riwayat Pemakaian</h5>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-medium">
                        <tr>
                            <th class="py-3 px-4 rounded-tl-lg">Tanggal</th>
                            <th class="py-3 px-4">Log Perawatan (Batch)</th>
                            <th class="py-3 px-4">Jumlah Dipakai</th>
                            <th class="py-3 px-4">Catatan</th>
                            <th class="py-3 px-4 rounded-tr-lg">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-gray-700">
                        @forelse($material->materialUsages()->latest()->get() as $usage)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $usage->created_at->format('d M Y H:i') }}</td>
                            <td class="py-3 px-4">
                                <a href="{{ route('maintenance-logs.show', $usage->maintenance_log_id) }}" class="text-blue-600 hover:underline">
                                    {{ optional($usage->maintenanceLog)->activity_date }}
                                    <span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded text-xs ml-1">{{ optional(optional($usage->maintenanceLog)->planting)->batch_code }}</span>
                                </a>
                            </td>
                            <td class="py-3 px-4 font-semibold text-red-600">-{{ $usage->quantity_used }} {{ $material->unit }}</td>
                            <td class="py-3 px-4 text-gray-500">{{ $usage->notes ?: '-' }}</td>
                            <td class="py-3 px-4">
                                <form action="{{ route('material-usages.destroy', $usage->id) }}" method="POST" onsubmit="return confirm('Hapus riwayat pemakaian dan kembalikan stok?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Hapus Pemakaian">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400 text-sm">Belum ada riwayat pemakaian.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h5 class="font-bold text-gray-800 mb-4">Catat Pemakaian Manual</h5>
            <form action="{{ route('material-usages.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="material_id" value="{{ $material->id }}">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Log Perawatan</label>
                        <select name="maintenance_log_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('maintenance_log_id') border-red-400 @enderror" required>
                            <option value="" disabled selected>-- Pilih Log Perawatan Aktif --</option>
                            @foreach($maintenanceLogs as $log)
                                <option value="{{ $log->id }}">Log: {{ $log->activity_date }} (Batch: {{ optional($log->planting)->batch_code }})</option>
                            @endforeach
                        </select>
                        @error('maintenance_log_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Dipakai ({{ $material->unit }})</label>
                        <input type="number" step="0.01" name="quantity_used" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('quantity_used') border-red-400 @enderror" min="0.01" max="{{ $material->stock }}" required>
                        @error('quantity_used') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                    <textarea name="notes" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                
                <button type="submit" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl text-sm transition disabled:opacity-50 disabled:cursor-not-allowed" {{ $material->stock <= 0 ? 'disabled' : '' }}>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Catat Pemakaian
                </button>
                @if($material->stock <= 0)
                    <p class="text-xs text-red-600 mt-2">Stok habis, tidak dapat digunakan.</p>
                @endif
            </form>
        </div>
    </div>
</div>

@endsection
