@extends('layouts.app')
@section('title', 'Jenis Tanaman')
@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold text-gray-700">Jenis Tanaman</h2>
    <a href="{{ route('plant-types.create') }}"
       class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
        + Tambah
    </a>
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
                            <a href="{{ route('plant-types.edit', $plantType->id) }}"
                               class="text-xs px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition">
                                Edit
                            </a>
                            <form action="{{ route('plant-types.destroy', $plantType->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Yakin hapus?')"
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

@endsection
