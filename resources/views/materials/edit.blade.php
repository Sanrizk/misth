@extends('layouts.app')

@section('titleTab', 'Edit Bahan')
@section('titleDash', 'Edit Bahan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title fw-bold mb-0">Form Edit Bahan</h5>
                    <a href="{{ route('materials.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
                </div>

                <form action="{{ route('materials.update', $material->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kode Bahan</label>
                        <input type="text" class="form-control bg-light" value="{{ $material->code }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Bahan</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $material->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="nutrient" {{ old('category', $material->category) == 'nutrient' ? 'selected' : '' }}>Nutrisi / Pupuk</option>
                                <option value="pesticide" {{ old('category', $material->category) == 'pesticide' ? 'selected' : '' }}>Pestisida</option>
                                <option value="operational" {{ old('category', $material->category) == 'operational' ? 'selected' : '' }}>Operasional</option>
                            </select>
                            @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Satuan</label>
                            <input type="text" name="unit" class="form-control @error('unit') is-invalid @enderror" value="{{ old('unit', $material->unit) }}" required>
                            @error('unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Stok Saat Ini</label>
                            <input type="number" step="0.01" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $material->stock) }}" required>
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Minimum Stok</label>
                            <input type="number" step="0.01" name="min_stock" class="form-control @error('min_stock') is-invalid @enderror" value="{{ old('min_stock', $material->min_stock) }}" required>
                            @error('min_stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Harga per Satuan (Rp)</label>
                            <input type="number" step="0.01" name="price_per_unit" class="form-control @error('price_per_unit') is-invalid @enderror" value="{{ old('price_per_unit', $material->price_per_unit) }}" required>
                            @error('price_per_unit') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="active" {{ old('status', $material->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status', $material->status) == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Deskripsi (Opsional)</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $material->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold">Perbarui Bahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
