@extends('layouts.app')
@section('title', 'Edit Penanaman')
@section('content')

<div class="flex justify-between items-center mb-6">
    <h2 class="text-lg font-semibold text-gray-700">Edit Penanaman: {{ $planting->batch_code }}</h2>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    <form action="{{ route('plantings.update', $planting->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Tanaman</label>
            <select name="plant_type_id" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500" required>
                <option value="">-- Pilih Jenis Tanaman --</option>
                @foreach($plantTypes as $type)
                    <option value="{{ $type->id }}" {{ $planting->plant_type_id == $type->id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Penanggung Jawab (Petani)</label>
            <select name="user_id" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500" required>
                <option value="">-- Pilih Petani --</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $planting->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Bibit</label>
            <input type="number" name="quantity_seeds" value="{{ $planting->quantity_seeds }}" min="1" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Tanam</label>
            <input type="date" name="start_date" value="{{ $planting->start_date }}" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500" required>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-green-500" required>
                <option value="in_progress" {{ $planting->status == 'in_progress' ? 'selected' : '' }}>Dalam Proses</option>
                <option value="harvested" {{ $planting->status == 'harvested' ? 'selected' : '' }}>Selesai / Panen</option>
                <option value="failed" {{ $planting->status == 'failed' ? 'selected' : '' }}>Gagal</option>
            </select>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-xl text-sm font-medium transition">
                Simpan Perubahan
            </button>
            <a href="{{ route('plantings.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2 rounded-xl text-sm font-medium transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
